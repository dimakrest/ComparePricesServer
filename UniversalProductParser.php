<?php 
      
require_once './Services.php';

// Yanot bitan need to check that it's supported and works properly - Yanot some problems with UTF-8
class UniversalProductParser
{
    private function GetItemsListBasedOnShop($shopName, $xml)
    {
        switch ($shopName)
        {
            case 'Mega':
                return $xml->Item;
                break;


            case 'SuperSal':
                return $xml->Items->Item;
                break;


            case 'YanotBitan':
                return $xml->Items->Item;
                break;


            case 'AM_PM':
                return $xml->Items->Item;
                break;


            default:
                break;
        }
    }
    
    public function Parse($folder, $fileName, $shopName)
    {
        $xmlfiles = findfile($folder,'/' . $fileName . '.*$/');

        $parsedItemsList                = array();
        $isItemAlreadyExists            = array();
        $numOfProductsWithSameIDAndDiff = 0;
        $numOfIdenticalProducts         = 0;
        
        $numOfItems = 0;
        foreach ($xmlfiles as $singleXMLFile)
        {
            $xml = simplexml_load_file($singleXMLFile); 
            
            $itemsList = $this->GetItemsListBasedOnShop($shopName, $xml);
           
            foreach ($itemsList as $singleItem)
            {
                $itemCode = (string)$singleItem->ItemCode;
                if (isset($isItemAlreadyExists[$itemCode]))
                {
                    continue;
                }
//                if (isset($parsedItemsList[$itemCode]))
//                {
//                    $this->CompareItems($singleItem, $parsedItemsList[$itemCode]) ? $numOfIdenticalProducts++ : $numOfProductsWithSameIDAndDiff++;
////                    if (!$this->CompareItems($singleItem, $parsedItemsList[$itemCode]))
////                    {
////                        print_r($singleItem);
////                        print_r($parsedItemsList[$itemCode]);
////                    }
//                }
                $isItemAlreadyExists[$itemCode] = true;
                
                $itemToInsert = array('Chainid'             => (string)$singleItem->Chainid,
                                      'SubChainid'          => (string)$singleItem->SubChainid,
                                      'Storeid'             => (string)$singleItem->Storeid,
                                      'PriceUpdateDate'     => (string)$singleItem->PriceUpdateDate,
                                      'PriceUpdateTime'     => (string)$singleItem->PriceUpdateTime,
                                      'ItemCode'            => (string)$singleItem->ItemCode,
                                      'ItemName'            => (string)$singleItem->ItemName,
                                      'Quantity'            => (string)$singleItem->Quantity,
                                      'UnitOfMeasure'       => (string)$singleItem->UnitOfMeasure,
                                      'QtyInPackage'        => (string)$singleItem->QtyInPackage,
                                      'ItemPrice'           => (string)$singleItem->ItemPrice,
                                      'UnitOfMeasurePrice'  =>(string)$singleItem->UnitOfMeasurePrice);
    
                $parsedItemsList[] = $itemToInsert;
                $numOfItems++;
                if ($numOfItems > 100)
                {
                    return $parsedItemsList;
                }
            }
        }

//        echo "num of products with same id and some difference = $numOfProductsWithSameIDAndDiff \n";
//        echo "num of identical items = $numOfIdenticalProducts \n";        
//        echo "total number of items in parsed items list " . count($parsedItemsList) . "\n";
        
        return $parsedItemsList;
    }
    
    public function CompareItems($item1, $item2)
    {
        if (count($item1) != count($item2))
        {
            return false;
        }
        
        foreach ($item1 as $key => $value)
        {
            $stringValue = (string)$value;
            if ($stringValue != (string)$item2->{$key})
            {
                return false;
            }
        }
        
        return true;
    }
    
    public function IntersectProductsFromStores($intersectInfo)
    {
        define('MAX_NUM_OF_ITEMS', 50);
        
        $productsInShop = array();
        foreach ($intersectInfo as $shopName => $shopsInfo)
        {
            $folder     = $shopsInfo[0];
            $fileName   = $shopsInfo[1];
    
            $xmlfiles = findfile($folder,'/' . $fileName . '.*$/');

            $itemCodeAndName = array();
            $numOfItems = 0;
            foreach ($xmlfiles as $singleXMLFile)
            {
                $xml = simplexml_load_file($singleXMLFile); 

                $itemsList = $this->GetItemsListBasedOnShop($shopName, $xml);
                
                print_r($itemsList);
                return;
                foreach ($itemsList as $singleItem)
                {
                    $itemCode = (string)$singleItem->ItemCode;
                    if (isset($itemCodeAndName[$itemCode]))
                    {
                        continue;
                    }
                    $itemCodeAndName[$itemCode] = array('ItemName'  => (string)$singleItem->ItemName,
                                                        'ItemPrice' => (string)$singleItem->ItemPrice);
                    $numOfItems++;
                    
                    if ($numOfItems >= MAX_NUM_OF_ITEMS)
                    {
                        break;
                    }
                }
                if ($numOfItems >= MAX_NUM_OF_ITEMS)
                {
                    break;
                }                
            }
            $productsInShop[$shopName] = $itemCodeAndName;
        }
        
        print_r($productsInShop);
        $allShopNames = array_keys($productsInShop);
        $numOfStores = count($allShopNames);
        $productsIntersectionDictionary = $productsInShop[$allShopNames[0]];
        
        for ($i=1; $i < $numOfStores; $i++)
        {
            $productsIntersectionDictionary = array_intersect_key($productsIntersectionDictionary, $productsInShop[$allShopNames[$i]]);
        }
        
        $productsIntersection = array();
        foreach ($productsIntersectionDictionary as $key => $value)
        {
            $productsIntersection[] = array('ItemName' => $value['ItemName'], 'ItemCode' => $key);
        }

        // output the intersection json
        $allProductsFile = 'all_products.json';
        $lh = fopen($allProductsFile, 'w') or die("can't open file $allProductsFile");
        $productsIntersectionJson = json_encode($productsIntersection);
        fwrite($lh, $productsIntersectionJson);
        fclose($lh);
        
        
        foreach ($productsInShop as $shopName => $singleShopProducts)
        {
            $intersectedProductsInShop = array();
            foreach ($productsIntersectionDictionary as $key => $value)
            {
                $intersectedProductsInShop[] = array('ItemCode'     => (string)$key, 
                                                     'ItemPrice'    => $singleShopProducts[$key]['ItemPrice'],
                                                     'ItemName'     => $singleShopProducts[$key]['ItemName']);
            }
            // ouput per shop list of prices
            $shopProductsFile = strtolower($shopName) . '_products.json';
            $lh = fopen($shopProductsFile, 'w') or die("can't open file $shopProductsFile");
            $intersectedProductsInShopJson = json_encode($intersectedProductsInShop);
            fwrite($lh, $intersectedProductsInShopJson);
            fclose($lh);
        }
    }
}

?> 