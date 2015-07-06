<?php 

require_once './UniversalProductParser.php';
require_once './UniversalShopParser.php';

//$universalParser = new UniversalProductParser();
//
////$yanotProductsList = $universalParser->Parse("../YanotBitan", "PriceFull7290725900003-", 'YanotBitan');
////print_r($yanotProductsList);
//
//$intersectInfo = array('Mega'       => array("../MegaPrices", "PriceFull7290055700007-"));
//                       //'SuperSal'   => array("../SuperSalPrices", "PriceFull7290027600007-"),
//                       //'AM_PM'      => array("../AM_PM", "PriceFull7290492000005-"));
//$universalParser->IntersectProductsFromStores($intersectInfo);

$universalShopParser  = new UniversalShopParser();

$shopsInfo = array('Mega'       => array("../MegaPrices", "StoresFull7290055700007-0000-201506010104"),
                   // 'SuperSal'   => array("../SuperSalPrices", "Stores7290027600007-000-201507020201"),
                   'AM_PM'      => array("../AM_PM", "Stores7290492000005-201507020700.xml"));
$universalShopParser->Parse($shopsInfo);
?> 
