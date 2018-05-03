<?php
include_once 'fileDownload.php';

  class elements{

    public function getCategoryName($content){
      $match='<div  id="CM_tytul">(.*)</div>';
      preg_match('#'.$match.'#Usmi',$content,$matched);
      $name=strip_tags($matched[1]);
      return $name;
    }

    public function getDescription($content){
      $match='                        	<div  class="produkt-opis" id="CM_produkt_opis">(.*)</div>';
      preg_match('#'.$match.'#Usmi',$content,$matched);
      $description=strip_tags($matched[1]);
      return $description;

    }

    protected function getImage($content){
        #$match='<div  id="CM_produkt_zdjecia"><P align=center><IMG style="(.*)" src="(.*)"(.*)></P></div>';
        $match='<div class="kolumna-tresc">(.*)<IMG style="(.*)" src="(.*)"(.*)>(.*)</div><!--\.kolumna-tresc-->';
      preg_match('#'.$match.'#Usmi',$content,$matched);
      $imgUrl='http://www.metalprodukt.net/'.str_replace('./','',$matched[3]);
      return $imgUrl;
    }

    protected function getTitle($content){

      $match='<div  class="(.*)" id="CM_produkt_nazwa">(.*)</div>';
      preg_match('#'.$match.'#Usmi',$content,$matched);
      $title=strip_tags($matched[2]);
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
      #Curling lib
      $curl=new curler();

      #Match Main menu
      $match='<ul class="splash-menu">(.*)</ul>';
      preg_match('#'.$match.'#Usmi', $content, $matched);
      $menuWrapper=$matched[0];

      #get Links for main cats
      $match='href="(.*)"([^>]*)>([^>]*)</a>';
      preg_match_all('#'.$match.'#Usmi', $menuWrapper, $matched);
      $menuLinks=$matched[1]; #Url
      $menuNames=$matched[3]; #Name in menu

      #get Links for cats with products foreach main category
      $allCatsLinks=array();
      foreach($menuLinks as $link){
        #full UL wrapper
        $match='<ul class="kategoria-menu([^\"]*)">(.*)</ul>';
        $content=$curl->get('http://www.metalprodukt.net'.$link);
        preg_match('#'.$match.'#Usmi', $content, $matched);
        $menuWrapper=$matched[2];

        #just the links
        $match='<li><a  href="(.*)"(.*)>(.*)</a></li>';
        preg_match_all('#'.$match.'#Usmi',$menuWrapper,$matched);
        $catsLinks=$matched[1];
        array_push($allCatsLinks,$catsLinks);
      }
      $menu=$allCatsLinks;
      #var_dump($allCatsLinks);
      return $menu;
    }

    public function getProductsLinks($catsLinks){
      #Curling lib
      $curl=new curler();
      $allProductsLinks=array();
      #iterate over all categories types in main menu
      foreach($catsLinks as $cat){

        #now iterate over the categories with products
        foreach($cat as $catLinks){
          $content=$curl->get('http://www.metalprodukt.net'.$catLinks);

    
          #match products rows
          $match='<div class="miniatury-produktow clearfix">(.*)</div><!--\.miniatury-produktow-->';
          preg_match('#'.$match.'#Usmi',$content,$matched);
          $prodRows=$matched[1];

            #match productsLinks
            #$match='<a href="([^\"]*)"(.*)>(.*)<img(.*)/></a>';
            $match='<a href="([^\"]*)" target="_self" title="([^\"]*)"><img src="([^\"]*)" width="([^\"]*)" height="([^\"]*)" alt="([^\"]*)" title="([^\"]*)" border="([^\"]*)" /></a>';
            preg_match_all('#'.$match.'#Usmi',$prodRows,$matched);
            $links=$matched[1];

            /*
            echo '<br/>---Produkty w tej kategorii---<br/>';
                  var_dump($allProductsLinks);
            echo '<br/>------------------<br/>';
            */

            array_push($allProductsLinks,$links);

        }

      }
      return $allProductsLinks;
    }


  }

  class extractor extends helpers{

    protected function iterator($productLinks){
      #Curling lib
      $curl=new curler();
      $tableBuilder=array();
        #Title [0]
        #Desc  [1]
        #Image [2]

        foreach($productLinks as $id=>$arrayOfLinks){
          #var_dump($productLinks);
          foreach($arrayOfLinks as $id=>$singleLink){
              $content=$curl->get('http://www.metalprodukt.net'.$singleLink);

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
      $catsLinks=$this->getMenuLinks($content);
      $productLinks=$this->getProductsLinks($catsLinks);
      $productsData=$this->iterator($productLinks);

      return $productsData;

      }

    }


?>
