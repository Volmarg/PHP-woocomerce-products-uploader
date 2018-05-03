<?php
include_once 'fileDownload.php';

  class elements{

    public function getCategoryName(){
      $match='<div id="lay-product-description">(.*)</div>';
      preg_match('#'.$match.'#Usmi',$content,$matched);
      $description=strip_tags($matched[1]);
        echo $description;

      return $name;
    }

    public function getDescription($content){
      $match='<div id="lay-product-description">(.*)</div>';
      preg_match('#'.$match.'#Usmi',$content,$matched);
      $description=strip_tags($matched[1]);
        #echo $description;
      return $description;

    }

    protected function getImage($content){
      $match='<img id="image-big" src="(.*)"/>';
      preg_match('#'.$match.'#Usmi',$content,$matched);
      $imgUrl=$matched[1];
      #echo '<img src="http://www.fastservice.pl/'.$imgUrl.'"/>';
      return $imgUrl;
    }

    protected function getScheme($content){
      #get the div with gallery
      $matchGallery='<div id="lay-image-thumbs" class="images-right">(.*)<div class="clear"><\/div>';
      preg_match_all('#'.$matchGallery.'#Usmi',$content,$matched);
      $galleryContent=$matched[1];

      $allImages=array();
      foreach($galleryContent as $section){
          #now get all images
          $match='<a class="small-image-overlay product-system-gallery" title="(.*)" href="(.*)" rel="gallery-([0-9]*)">';
          preg_match_all('#'.$match.'#Usmi',$section,$matched);
          $imgUrl=$matched[2];
          array_push($allImages,$imgUrl);

      }

      #now get one last link
      $ile=count($allImages[0]);
      $last=$allImages[0][$ile-1];

      //if($ile)

      return $last;
    }


    protected function getTitle($content){

      $match='<div id="product-name">(.*)</div>';
      preg_match('#'.$match.'#Usmi',$content,$matched);
      $title=strip_tags($matched[1]);
        #echo $title;
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

    public function getMenuLinks($content){

      #Match menu
      $match='<ul class="menu" id="product-categories-menu"><li  id="product-category-id-1">(.*)</li></ul>';
      preg_match('#'.$match.'#Usmi', $content, $matched);
      $menuWrapper=$matched[1];

      #get Links
      $match='href="(.*)"><span>([^>]*)</span></a>';
      preg_match_all('#'.$match.'#Usmi', $menuWrapper, $matched);
      $menuLinks=$matched[1];
      $menuNames=$matched[2];

      $menu=array($menuLinks, $menuNames);

      return $menu;
    }

  }

  class extractor extends helpers{

    protected function iterator($menuLinks){
      #Curling lib
      $curl=new curler();
      $content=$curl->get($link);

      $tableBuilder=array();
        #Title [0]
        #Desc  [1]
        #Image [2]

        foreach($menuLinks[0] as $id=>$categoryLink){

          #print category name
          #echo '<b>Category: </b>'.$menuLinks[1][$id];
          #echo '<br/>';

          #get content of page from that link
          $categoryContent=$curl->get($categoryLink);

          #Now from that content extract all products sections
          $productsSections=$this->getProductsBlock($categoryContent);

          #now iterate over all products in section corespoding this one category
          foreach($productsSections as $linksArray){

            foreach($linksArray as $singleLink){
              $prodContent=$curl->get($singleLink);
              #echo $categoryContent;

              #getTitle
              $title=$this->getTitle($prodContent);

              #getDescription
              $description=$this->getDescription($prodContent);
              #getImage
              $image=$this->getImage($prodContent);

              #getScheme
              $secondImage=$this->getScheme($prodContent);

              $dataPack=array($title,$description,$image,$secondImage,$singleLink);
              array_push($tableBuilder,$dataPack);
            }

          }

        }

        return $tableBuilder;
    }

    function getContent($link){

      #Curling lib
      $curl=new curler();
      $content=$curl->get($link);


      #getMenuLinks
      $menuLinks=$this->getMenuLinks($content);
      $productsData=$this->iterator($menuLinks);

      return $productsData;
      }

    }


?>
