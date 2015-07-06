<?php 
      
require_once './Services.php';

// Yanot bitan need to check that it's supported and works properly - Yanot some problems with UTF-8
class UniversalShopParser
{
    private function GetShopsListBasedOnShop($shopName, $xml)
    {
        switch ($shopName)
        {
            case 'Mega':
                return $xml->Store;
                break;


            case 'SuperSal':
                return $xml->Items->Item;
                break;


            case 'YanotBitan':
                return $xml->Items->Item;
                break;


            case 'AM_PM':
                return $xml->SubChains->SubChain->Stores->Store;
                break;


            default:
                break;
        }
    }
    
    private function ParseSingleShopEntry($shopName, $singleShop)
    {
        switch ($shopName)
        {
            case 'Mega':
                return array('StoreId'      => (string)$singleShop->StoreId,
                             'ChainName'    => (string)$singleShop->ChainName,
                             'SubChainName' => (string)$singleShop->SubChainName,
                             'Address'      => (string)$singleShop->Address,
                             'City'         => (string)$singleShop->City);
                break;


            case 'SuperSal':
                return $xml->Items->Item;
                break;


            case 'YanotBitan':
                return $xml->Items->Item;
                break;


            case 'AM_PM':
                return array('StoreId'      => (string)$singleShop->StoreId,
                             'ChainName'    => (string)$singleShop->StoreName,
                             'SubChainName' => (string)$singleShop->StoreName,
                             'Address'      => (string)$singleShop->Address == 'unknown' ? '' : (string)$singleShop->Address,
                             'City'         => (string)$singleShop->City == 'unknown' ? '' : (string)$singleShop->City);
                break;


            default:
                break;
        }        
        
    }
    public function Parse($intersectInfo)
    {
        $parsedShopsInfo = array();
        $numOfShops = 0;        
        foreach ($intersectInfo as $shopName => $shopsInfo)
        {
            $folder     = $shopsInfo[0];
            $fileName   = $shopsInfo[1];
    
            $xmlfiles = findfile($folder,'/' . $fileName . '.*$/');

            foreach ($xmlfiles as $singleXMLFile)
            {
                $xml = simplexml_load_file($singleXMLFile); 

                $shopsList = $this->GetShopsListBasedOnShop($shopName, $xml);
                
                foreach ($shopsList as $singleShop)
                {
                    $parsedSingleShopInfo = $this->ParseSingleShopEntry($shopName, $singleShop);
                    // check that all fields are set
                    if ($parsedSingleShopInfo['StoreId'] == "" || $parsedSingleShopInfo['ChainName'] == "" ||
                        $parsedSingleShopInfo['SubChainName'] == "" || $parsedSingleShopInfo['Address'] == "" || 
                        $parsedSingleShopInfo['City'] == "" ||
                        is_null($parsedSingleShopInfo['StoreId']) || is_null($parsedSingleShopInfo['ChainName']) ||
                        is_null($parsedSingleShopInfo['SubChainName']) || is_null($parsedSingleShopInfo['Address']) || 
                        is_null($parsedSingleShopInfo['City']))
                    {
                        continue;
                    }
                    $parsedShopsInfo[] = $parsedSingleShopInfo;
                    
                    $numOfShops++;
                }
            }
        }
        
                echo "numOf shops = $numOfShops </br>";
                print_r($parsedShopsInfo);
                return;
        
        
                
                
                
                return;
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