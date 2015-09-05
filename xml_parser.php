<?php 

require_once './UniversalProductParser.php';
require_once './UniversalShopParser.php';

require_once './dal_images.php';


//$allProductsJson = file_get_contents('all_products_from_client.json');
//$allProducts = json_decode($allProductsJson, true);
//
//$allImagesJson = file_get_contents('image_to_path.json');
//$allImages      = json_decode($allImagesJson, true);
//
//$count = 0;
//foreach ($allProducts as &$singleProduct)
//{
//    $count++;
//    $singleProduct['ImagePath'] = 'img/no_product_img.jpg';
//    foreach ($allImages as $singleImage)
//    {
//        if ($singleImage['ItemCode'] == $singleProduct['IC'])
//        {
//            $singleProduct['ImagePath'] = $singleImage['ImagePath'];
//            break;
//        }
//    }
//}
//
//$allProductsUpdatedJson = json_encode($allProducts);
//echo $allProductsUpdatedJson;

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

ini_set('memory_limit','512M');
NeedToMapImages();
//CheckDownloadedImagesVsAllProducts();
//AddImagesToAllProducts();
//CreateJsonOfDownloadedImages();
//CheckImagesDownloadedProperly

//
////$allProductsJson = file_get_contents('all_products_from_client.json');
////$allProductsArray  = json_decode($allProductsJson, true);
//
////$dal->InitProductPicturesCollection($allProductsArray);
//
//$notDownloadedImages = $dal->GetNotDownloadedImages();
//
//echo "num of not downloaded images = " . count($notDownloadedImages);
////foreach ($notDownloadedImages as $singleProduct)
////{
////    $pngImagePath = '../Images/Images_png/product_' . $singleProduct['ProductCode'] . '.png';
////    $jpgImagePath = '../Images/Images_jpg/product_' . $singleProduct['ProductCode'] . '.jpg';
////    
////    // test if image exists
////    if (file_exists($pngImagePath) || file_exists($jpgImagePath)) {
////        $downloadStatus = 'DOWNLOADED';
////        $dal->ChangeProductImageStatus($singleProduct['ProductCode'], $downloadStatus);        
////    }
////}
//
//
//$productCount = 0;
//foreach ($notDownloadedImages as $singleProduct)
//{
//    // Super Sal
//    $sourceFileName = "http://www.shufersal.co.il/_layouts/images/Shufersal/Images/Products_Large/z_" . $singleProduct['ProductCode'] . ".PNG";
//    $destFileName   = "../Images/product_" . $singleProduct['ProductCode'] . ".png";
//
//    // Mega
////    $sourceFileName = "http://www.mega.co.il/resources/" . $singleProduct['ProductCode'] . ".jpg";
////    $destFileName   = "../Images/product_" . $singleProduct['ProductCode'] . ".jpg";
//  
////    $sourceFileName = "http://www.rami-levy.co.il/files/products/big/" . $singleProduct['ProductCode'] . ".jpg";
////    $destFileName   = "../Images/product_" . $singleProduct['ProductCode'] . ".jpg";
//
//    $result = @file_get_contents($sourceFileName);
//    $downloadStatus = '';
//    if (!$result)
//    {
//        $downloadStatus = 'DOWNLOAD_ERROR';
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
//    if (($productCount % 100) == 0) {
//        echo "downloaded images $productCount";
//    }
//    
//    if ($productCount == 20000) {
//        echo "Scanned 20000 images </br>";
//        return;
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

function CreateJsonOfDownloadedImages()
{
    $downloadedImages = array();

    $jpgFolder     = '../Images/Images_jpg';
    $jpgImagesFile = findfile($jpgFolder,'/product_.*$/');   
    
    $pngFolder      = '../Images/Images_png';
    $pngImagesFile  = findfile($pngFolder,'/product_.*$/');
    foreach ($pngImagesFile as $singleImage)
    {
        $splittedFileName   = explode("/", $singleImage);
        $almostProductCode  = explode('_', $splittedFileName[3]);
        $productCodeArray   = explode('.', $almostProductCode[1]);
        $productCode        = $productCodeArray[0];
        $downloadedImages[$productCode] = 'png';

    }
    
    foreach ($jpgImagesFile as $singleImage)
    {
        $splittedFileName   = explode("/", $singleImage);
        $almostProductCode  = explode('_', $splittedFileName[3]);
        $productCodeArray   = explode('.', $almostProductCode[1]);
        $productCode        = $productCodeArray[0];
        $downloadedImages[$productCode] = 'jpg';
    }
    
    echo "num of downloaded images = " . count($downloadedImages);
    
    echo json_encode($downloadedImages);
}

function CheckImagesDownloadedProperly()
{
     $locallyDownloadedImagesJson = file_get_contents('locally_downloaded_imajes.json');
    $locallyDownloadedImages     = json_decode($locallyDownloadedImagesJson, true);
    
    $dal = DAL::GetInstance();
    $downloadImagesMarkedInDB = $dal->GetDownloadedImages();
    
    $numOfDownloadedImagesMarkedInDB    = count($downloadImagesMarkedInDB);
    
    $numOfImagesScanned = 0;
    $imageNotFoundInDB               = array();
    $imageFoundInDBStatNotDownloaded = array();
    foreach ($locallyDownloadedImages as $itemCode => $imageType) {
        $itemFound = false;
        for ($i=0; $i < $numOfDownloadedImagesMarkedInDB; $i++) {
            if ($itemCode == $downloadImagesMarkedInDB[$i]['ProductCode']) {
                $itemFound = true;
                break;
            }
        }
        if (!$itemFound) {
            $imageNotFoundInDB[] = $itemCode;
        }
        $numOfImagesScanned++;
        if (($numOfImagesScanned % 500) == 0) {
            echo "Scanned $numOfImagesScanned";
        }
    }
    
    $result = array('imageNotFoundInDB'                 => $imageNotFoundInDB,
                    'imageFoundInDBStatNotDownloaded'   => $imageFoundInDBStatNotDownloaded);
    echo json_encode($result);   
}

function AddImagesToAllProducts() 
{
    $allProductsJson = file_get_contents('all_products_from_client.json');
    $allProducts = json_decode($allProductsJson, true);

    $locallyDownloadedImagesJson = file_get_contents('locally_downloaded_imajes.json');
    $locallyDownloadedImages = json_decode($locallyDownloadedImagesJson, true);
//    IT:
//0 - no_image
//1- jpg
//2 - png
    // init all
    foreach ($allProducts as &$singleProduct)
    {
        $itemCode = $singleProduct['IC'];
        if (isset($locallyDownloadedImages[$itemCode])) {
            $singleProduct['IT'] = ($locallyDownloadedImages[$itemCode] == 'png') ? '2' : '1';
        } else {
            $singleProduct['IT'] = '0';
        }
    }

    $allProductsUpdatedJson = json_encode($allProducts);
    echo $allProductsUpdatedJson;    
}

function CheckDownloadedImagesVsAllProducts() 
{
    $allProductsJson = file_get_contents('all_products_from_client.json');
    $allProducts = json_decode($allProductsJson, true);

    $locallyDownloadedImagesJson = file_get_contents('locally_downloaded_imajes.json');
    $locallyDownloadedImages = json_decode($locallyDownloadedImagesJson, true);

    $missingItems = array();
    $productCount = 0;
    foreach ($locallyDownloadedImages as $itemCode => $imageType) {
        $productCount++;
        $itemFound = false;
        foreach ($allProducts as $singleProduct) {
            if ($itemCode == $singleProduct['IC']) {
                $itemFound = true;
                break;
            }
        }
        if (!$itemFound) {
            $missingItems[] = $itemCode;
        }
        if ($productCount % 1000 == 0) {
            echo "Scanned $productCount ";
        }
    }

    $missingItemsJson = json_encode($missingItems);
    echo $missingItemsJson;
}

function NeedToMapImages()
{
    $idMapReserveJson   = file_get_contents('id_map_reverse.json');
    $idMapReserve       = json_decode($idMapReserveJson, true);
    
    $locallyDownloadedImagesJson    = file_get_contents('locally_downloaded_images.json');
    $locallyDownloadedImages        = json_decode($locallyDownloadedImagesJson, true);
    
//    echo "num of idMapReverse objects = " . count($idMapReserve);
//    echo "num if localy downloaded objects = " . count($locallyDownloadedImages);
    $itemCodesToConvert = array();
    foreach ($idMapReserve as $shortItemCode => $longItemCodes)
    {
        $singleLongItemCode = $longItemCodes[0];
        if (isset($locallyDownloadedImages[$shortItemCode])) {
            $itemCodesToConvert[$shortItemCode] = $singleLongItemCode;
        }
    }
//    echo "num of images needed conversion = " . count($itemCodesToConvert);
    echo json_encode($itemCodesToConvert);
}
?> 