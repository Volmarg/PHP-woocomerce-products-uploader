<?php

    include_once 'Model/curler.php';
    include_once 'Model/extractors/'.$_GET['page'].'/extractor.php';

    $curl=new curler();
    $extract=new extractor();
    if($_GET['page']=='fastservice.pl'){

      $link='http://www.fastservice.pl/';
    }elseif($_GET['page']=='kart-map.pl'){

      $link='http://kart-map.pl/pl/64-oferta';
    }elseif($_GET['page']=='metalprodukt.net'){

      $link='http://www.metalprodukt.net/';
    }

    # Data from form
    $productsData=$extract->getContent($link);

?>
