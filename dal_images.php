<?php

define('DB_HOST_MAIN', 'localhost');
define('DB_NAME_MAIN', 'ComparePrices_App');

    
define('products_colection_for_images', 'products_colection_for_images');

class DAL
{    
    
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////                                                            ////////////////////////// 
//////////////////////////                  INTERNAL DAL FUNCTIONS                    ////////////////////////// 
//////////////////////////                                                            ////////////////////////// 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
    
    private $_mongo;
    private $_dbh;
    private static $_dalInstance;

    protected function __construct()
    {
        try
        {
            $dbhost = DB_HOST_MAIN;
            // Connect to database
            // Throws MongoCursorException if the "w" option is set and the write fails.
            $this->_mongo = new Mongo("mongodb://$dbhost", array('w' => 1));                
            $this->_dbh = $this->_mongo->selectDB(DB_NAME_MAIN);
        }
        catch (Exception $e)
        {
            throw new Exception('Connection failed: ' . $e->getMessage(), INTERNAL_ERROR);
        }
    }
    
    // TODO: check what to do with this, whether we need to close connection
    public function __destruct()
    {
        // http://stackoverflow.com/questions/10174096/what-happens-when-connections-to-mongodb-are-not-closed
        // $this->_mongo->close();
    }
    
    public static function GetInstance()
    {
        if (!self::$_dalInstance)
        {
            self::$_dalInstance = new DAL();
        }
        return self::$_dalInstance;
    }
    
    protected function Update($collectionName, $criteria, $operation, $options = null)
    {
        $collection = $this->_dbh->selectCollection($collectionName);
        if (empty($operation))
        {
            throw new Exception('DAL: Update function was called with null $operation', INTERNAL_ERROR);
        }
        
        if (is_null($options))
        {
            $collection->update($criteria, $operation);
        }
        else
        {
            $collection->update($criteria, $operation, $options);
        }
    }
    
    protected function Find($collectionName, $criteria, $return)
    {
        $return['_id'] = false;
        $collection = $this->_dbh->selectCollection($collectionName);
        $result = $collection->find($criteria, $return);
        return $result;
    }
    
    protected function FindOne($collectionName, $criteria, $return)
    {
        $return['_id'] = false;
        $collection = $this->_dbh->selectCollection($collectionName);
        $result = $collection->findOne($criteria, $return);
        return $result;
    }
    
    protected function Insert($collectionName, $data)
    {
        $collection = $this->_dbh->selectCollection($collectionName);
        $collection->insert($data);
    }
    
    public function InitProductPicturesCollection($productsList)
    {
        foreach ($productsList as $singleProduct)
        {
            $this->Insert(products_colection_for_images, array('ProductCode'    => (string)$singleProduct['IC'],
                                                               'ImageStatus'    => 'NOT_DOWNLOADED'));
        }
    }

    public function GetAllProducts() {
        $criteria = array();
        $return   = array('ImageStatus' => true, 'ProductCode' => true);
        
        $notDownloadedImageIterator = $this->Find(products_colection_for_images, $criteria, $return);
        
        return iterator_to_array($notDownloadedImageIterator);        
    }
    
    public function GetNotDownloadedImages()
    {
        $criteria = array('ImageStatus' => 'NOT_DOWNLOADED');
        $return   = array('ProductCode' => true);
        
        $notDownloadedImageIterator = $this->Find(products_colection_for_images, $criteria, $return);
        
        return iterator_to_array($notDownloadedImageIterator);
    }

    public function GetDownloadedImages()
    {
        $criteria = array('ImageStatus' => 'DOWNLOADED');
        $return   = array('ProductCode' => true);
        
        $notDownloadedImageIterator = $this->Find(products_colection_for_images, $criteria, $return);
        
        return iterator_to_array($notDownloadedImageIterator);        
    }
    
    public function ChangeProductImageStatus($productCode, $downloadStatus)
    {
        $criteria   = array('ProductCode' => (string)$productCode);
        $operation  = array('$set' => array('ImageStatus' => (string)$downloadStatus));
        
        $this->Update(products_colection_for_images, $criteria, $operation);
    }
        
    public function UpdateProductImageStatus($itemCode, $imageStatus) 
    {
        $criteria  = array('ProductCode' => (string)$itemCode);
        $operation = array('$set'        => array('ImageStatus' => $imageStatus));
        
        $this->Update(products_colection_for_images, $criteria, $operation);
    }
    
    public function InitDatabase()
    {
        $productsColectionForImagesMongoCollection = new MongoCollection($this->_dbh, products_colection_for_images);
        $productsColectionForImagesMongoCollection->ensureIndex('ProductCode', array('unique' => true));
    }
}

?>