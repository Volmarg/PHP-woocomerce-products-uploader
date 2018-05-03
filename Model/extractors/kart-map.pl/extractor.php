<?php
include_once 'fileDownload.php';
include_once 'Model/mCurler.php';

  class elements{

    public function getCategoryName($content){
      $match='<div  id="CM_tytul">(.*)</div>';
      preg_match('#'.$match.'#Usmi',$content,$matched);
      $name=strip_tags($matched[1]);
      return $name;
    }

    public function getDescription($content){
      $match='<h3 class="toggle">Więcej informacji<span class="icon-toggle"></span></h3>([^>]*)<div class="toggle_content">(.*)</div>';
      preg_match('#'.$match.'#Usmi',$content,$matched);
      $description=strip_tags($matched[2]);
      return $description;
    }

    protected function getImage($content){
      $match='<img id="bigpic" alt="(.*)" title="(.*)" src="(.*)" />';
      preg_match('#'.$match.'#Usmi',$content,$matched);
      $imgUrl=$matched[3];
      return $imgUrl;
    }

    protected function getTitle($content){

      $match='<h1>(.*)</h1>';
      preg_match('#'.$match.'#Usmi',$content,$matched);
      $title=strip_tags($matched[1]);
      return $title;
    }


  }

  class helpers extends elements{
    public function getProductsBlock($content){

      #vars
      $allProductsLinks= array();

      #matching all line of products
      if(strstr($content,'<a class="product-arrow-right next">')){ #for 1 version of matching
        $productLineMatch='<div class="product-line items">(.*)<a class="product-arrow-right next">';
      }else{
        $productLineMatch='<div class="product-line items">(.*)<a class="product-arrow-right next disabled">'; #for 2nd version
      }

      preg_match_all("#".$productLineMatch."#Usmi",$content,$matched);
      $productsSections=$matched[0];

      foreach($productsSections as $productSection){
        #getting links for all subpages for specific products
        $matchOneLink='<a class="plil([0-9]+)" href="(.*)">';
        preg_match_all('#'.$matchOneLink.'#Usmi',$productSection,$matched);

      #  var_dump($matched[2]);
        array_push($allProductsLinks,$matched[2]);
      }

      return $allProductsLinks;

    }

    public function getPhotoBlock($content){

      return $photoBlock;

    }

    public function getDescriptionBlock($content){

      return $descriptionBlock;

    }

    public function getCategoriesLinks($content){

      #Match all categories wrapper
      $match='<ul class="sf-menu clearfix">(.*)</li><li><a href="/pl/promocje">Promocje</a>';
      preg_match('#'.$match.'#Usmi',$content,$matched);
      $catsWrapper=$matched[1];

      #Match all categories links
      $match='<li ><a href="(.*)">(.*)</a>';
      preg_match_all('#'.$match.'#Usmi',$catsWrapper,$matched);
      $catsLinks=$matched[1];

      return $catsLinks;
    }

    public function getProductsLinks($catsLinks){

      #Curling lib
      $curl=new curler();
      $mCurl=new mcurl();
      $allProductsLinks=array();


      $catsData=$mCurl->getMulti($catsLinks);
      foreach($catsData as $content){

        #getProdSection
        $match='<ul id="product_list" class="grid row">(.*)</ul>';
        preg_match('#'.$match.'#si',$content,$matched);
        $productsWrapper=$matched[1];

        #getProdLinks
        $match='<a class="button" href="(.*)" title="Wyświetl">Wyświetl</a>';
        preg_match_all('#'.$match.'#Usmi',$productsWrapper,$matched);
        $productsLinks=$matched[1];
        array_push($allProductsLinks,$productsLinks);
      }

        return $allProductsLinks;
    }

  }

  class extractor extends helpers{

    protected function iterator($productLinks){
      #Curling lib
      $curl=new curler();
      $mCurl=new mcurl();

      $tableBuilder=array();
        foreach($productLinks as $id=>$arrayOfLinks){
          $catsData=$mCurl->getMulti($arrayOfLinks);

          foreach($catsData as $id=>$content){

              #getImage
              $image=$this->getImage($content);

              #getTitle
              $title=$this->getTitle($content);

              #getDescription
              $description=$this->getDescription($content);

              $dataPack=array($title,$description,$image,$singleLink);
              array_push($tableBuilder,$dataPack);

          }


        }
        return $tableBuilder;
    }

    function getContent($link){

      #Curling lib
      $curl=new curler();
      $content=$curl->get($link);

      #getMenuLinks
      $catsLinks=$this->getCategoriesLinks($content);
      $productsLinks=$this->getProductsLinks($catsLinks);
      $productsData=$this->iterator($productsLinks);

      return $productsData;

      }

    }


?>
