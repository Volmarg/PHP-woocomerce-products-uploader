<?php
include_once 'Model/mCurler.php';

$mCurl=new mcurl();

$allProductsLinks=array();

$htmlSources=$mCurl->getMulti($catsLinks);

var_dump($htmlSources);






?>
