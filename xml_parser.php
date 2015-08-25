<?php 

require_once './UniversalProductParser.php';
require_once './UniversalShopParser.php';

require_once './dal.php';




$allProductsJson = file_get_contents('all_products_from_client.json');
$allProducts = json_decode($allProductsJson, true);

$allImagesJson = file_get_contents('image_to_path.json');
$allImages      = json_decode($allImagesJson, true);

$count = 0;
foreach ($allProducts as &$singleProduct)
{
    $count++;
    $singleProduct['ImagePath'] = '';
    foreach ($allImages as $singleImage)
    {
        if ($singleImage['ItemCode'] == $singleProduct['ItemCode'])
        {
            $singleProduct['ImagePath'] = $singleImage['ImagePath'];
            break;
        }
    }
}

$allProductsUpdatedJson = json_encode($allProducts);
echo $allProductsUpdatedJson;
//
//$dal = DAL::GetInstance();
//$downloadedImages = $dal->GetDownloadedImages();
//
//$imageTopath = array();
//$imageCount = 0;
//foreach ($downloadedImages as $singleImage)
//{
//    $productCode = $singleImage['ProductCode'];
//    $imageCount++;
//    
//    $folder     = '../Images/Images_png';
//    $fileName   = 'product_' . $productCode . '.png';
//    $imageFile = findfile($folder,'/' . $fileName . '.*$/');
//    if (count($imageFile) == 1)
//    {
//        $imageTopath[] = array('ItemCode'   => $productCode,
//                               'ImagePath'  => 'https://s3.amazonaws.com/compare.prices/product_images_png/product_' . $productCode . '.png');
//    }
//    else
//    {
//        $folder     = '../Images/Images_jpg';
//        $fileName   = 'product_' . $productCode . '.jpg';        
//        $imageFile = findfile($folder,'/' . $fileName . '.*$/');    
//    
//        if (count($imageFile) == 1)
//        {
//            $imageTopath[] = array('ItemCode'   => $productCode,
//                                   'ImagePath'  => 'https://s3.amazonaws.com/compare.prices/product_images_jpg/product_' . $productCode . '.jpg');
//        }
//        else
//        {
//            echo "Cannot find image for product $productCode";
//        }
//    }
//    
////    if (($imageCount % 50) == 0)
////    {
////        echo "scanned $imageCount images </br>";
////    }
//}

//    $imageTopathJson = json_encode($imageTopath, JSON_UNESCAPED_SLASHES);
//    echo $imageTopathJson;
//$universalParser = new UniversalProductParser();
//
//$productsList = $universalParser->Parse("../SuperSalPrices", "PriceFull7290027600007-", 'SuperSal');

//$dal = DAL::GetInstance();
//$notDownloadedImages = $dal->GetNotDownloadedImages();
//
//$productCount = 0;
//foreach ($notDownloadedImages as $singleProduct)
//{
//    // Mega
////    $sourceFileName = "http://www.mega.co.il/resources/" . $singleProduct['ProductCode'] . ".jpg";
////    $destFileName   = "Images/product_" . $singleProduct['ProductCode'] . ".jpg";
//    
//    // Super Sal
////    $sourceFileName = "http://www.shufersal.co.il/_layouts/images/Shufersal/Images/Products_Large/z_" . $singleProduct['ProductCode'] . ".PNG";
////    $destFileName   = "Images/product_" . $singleProduct['ProductCode'] . ".png";
//   
//    $sourceFileName = "http://www.rami-levy.co.il/files/products/big/" . $singleProduct['ProductCode'] . ".jpg";
//    $destFileName   = "Images/product_" . $singleProduct['ProductCode'] . ".jpg";
//    
//    $result = @file_get_contents($sourceFileName);
//    $downloadStatus = '';
//    if (!$result)
//    {
//        $downloadStatus = 'DOWNLOAD_ERROR';
//        $dal->ChangeProductImageStatus($singleProduct['ProductCode'], $downloadStatus);
//    }
//    else
//    {
//        $downloadStatus = 'DOWNLOADED';
//        file_put_contents($destFileName, $result);    
//    }
//    
//    $dal->ChangeProductImageStatus($singleProduct['ProductCode'], $downloadStatus);
//     
//    $productCount++;
//    if (($productCount % 50) == 0) {
//        echo "downloaded images $productCount";
//    }
//}

//$universalParser = new UniversalProductParser();
//
//$yanotProductsList = $universalParser->Parse("../YanotBitan", "PriceFull7290725900003-", 'YanotBitan');
//print_r($yanotProductsList);
//
//$intersectInfo = array('Mega'       => array("../MegaPrices", "PriceFull7290055700007-"));
//                       //'SuperSal'   => array("../SuperSalPrices", "PriceFull7290027600007-"),
//                       //'AM_PM'      => array("../AM_PM", "PriceFull7290492000005-"));
//$universalParser->IntersectProductsFromStores($intersectInfo);

//$universalShopParser  = new UniversalShopParser();
//
//$shopsInfo = array(//'Mega'       => array("../MegaPrices", "StoresFull7290055700007-0000-201506010104"),
//                   'SuperSal'   => array("../SuperSalPrices", "Stores7290027600007-000-201507020201"));
//                   //'AM_PM'      => array("../AM_PM", "Stores7290492000005-201507020700.xml"));
//$universalShopParser->Parse($shopsInfo);
?> 
