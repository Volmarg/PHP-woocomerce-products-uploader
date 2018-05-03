<?php
  #includes of libs
  include_once '../Model/databaseConnection.php';
  include_once '../Model/fileDownload.php';
  include_once '../Model/lib/pack.php';

#Vars from js
  $allProducts=$_POST['products'];

  class dbUpdates{

      public function addProduct($ID,$title,$desc,..........,$dlugosc,$waga,$kategoria,$smallInfo,$imageID,$imageID2){
      $this->addPost($ID,$title,$desc,$smallInfo);
      $this->addPostMeta($ID,$cena,$wysokosc,$szerokosc,$dlugosc,$waga,$imageID,$imageID2,$numer);
      $this->addTaxonomy($ID,$kategoria);
    }

      public function addPost($ID_,$title_,$desc_,$mallInfo_){
        $db=new askDatabase();

        #Small description
          if($mallInfo_==false || $mallInfo_==undefined || $mallInfo_==null){
            $mallInfo_='&nbsp;';
          }

        #Constant values for SQL
          $post_author=1;
          $post_date='2017-11-11 12:15:56';
		   .
		   .
		   .
		   .

        #Generated values
          $ID=$ID_;
          $guid='http://sklep.volmarg.pl/?post_type=product&#038;p='.$ID;

        #Scrapper values
          $post_content=trim($desc_);            #Product description
          $post_title=trim($title_);             #Product title
          $post_excerpt=trim($mallInfo_);         #Basket text
          $post_name=createUrl($post_title,$ID_); #product SLUG url

          $sql="
            INSERT INTO `wp_posts`
            (
              `ID`,
              `post_author`,
              `post_date`,
              `post_date_gmt`,
              `post_content`,
              `post_title`,
              `post_excerpt`,
              `post_status`,
				 .
				 .
				 .
              `post_parent`,
              `guid`,
              `menu_order`,
              `post_type`,
              `comment_count`,

              `post_password`,
              `to_ping`,
              `pinged`,
              `post_content_filtered`,
              `post_mime_type`
            )
            VALUES
            (
                  '$ID',
              '$post_author',
              '$post_date',
              '$post_date_gmt',
                  '$post_content',
                  '$post_title',
                  '$post_excerpt',
              '$post_status',
              '$comment_status',
              '$ping_status',
              '$post_name',
              '$post_modified',
              '$post_modified_gmt',
              '$post_parent',
                  '$guid',
              '$menu_order',
              '$post_type',
              '$comment_count',
              '',
              '',
              '',
              '',
              ''
            );";

          $imageID=$db->modifyDataInDatabase($sql);
          return $imageID;
      }

      public function addPostMeta($ID,$cena_,$wysokosc,$szerokosc,$dlugosc,$waga,$obrazek,$galeria,$numer_){
        $db=new askDatabase();

        $serializedNumber=$this->addProductNumber($numer_);

        $arrayOfInserts=array(
          "
          INSERT INTO `wp_postmeta`(`post_id`,`meta_key`,`meta_value`)      VALUES('$ID','_product_attributes','$serializedNumber');
          ",
          "
			 .
			 .
			 .
          "
          INSERT INTO `wp_postmeta`(`post_id`,`meta_key`,`meta_value`)      VALUES('$ID','_yoast_wpseo_primary_product_cat','49');
          ",
          "
          INSERT INTO `wp_postmeta`(`post_id`,`meta_key`,`meta_value`)      VALUES('$ID','_yoast_wpseo_content_score','30');
          "
        );

        foreach($arrayOfInserts as $sql){
            $db->modifyDataInDatabase($sql);
        }

      }

      public function addTaxonomy($ID,$category){
        $db=new askDatabase();

		.
		.
		.
		.
		
        #constants
        $productType='21';

        $arrayOfInserts=array(
          "
          INSERT INTO `wp_term_relationships` (`object_id`,`term_taxonomy_id`,`term_order`)      VALUES ('$ID','$category','0');
          ",
          "
          INSERT INTO `wp_term_relationships` (`object_id`,`term_taxonomy_id`,`term_order`)      VALUES ('$ID','$productType','0');
          ");

          foreach($arrayOfInserts as $sql){
              $db->modifyDataInDatabase($sql);
          }

      }

      public function addProductNumber($ID_){
        $db=new askDatabase();

        $jsn='{
         "nr-artykulu": {
           "name": "Nr artykułu:",
           "value": "'.$ID_.'",
           "position": 0,
           "is_visible": 1,
           "is_variation": 0,
           "is_taxonomy": 0
         },
         "pa_kolory": {
           "name": "pa_kolory",
           "value": "",
           "position": 2,
           "is_visible": 0,
           "is_variation": 1,
           "is_taxonomy": 1
         }
       }';


        $serialized=serialization($json);
        return $serialized;
      }

  }

  class fetching extends dbUpdates{

    public function getData($allProducts){
      $db=new askDatabase();
      $img=new images();

        #Iterate over all products
          foreach($allProducts as $id=>$singleProduct){
            $productAr=json_decode(stripslashes($singleProduct));

        #download images
          $imageID=$img->getImage($productAr->{'download1'},$productAr->{'fileName1'});

          #if only one image is being downloaded
          if($productAr->{'download2'}!=null && $productAr->{'download2'}!=false && $productAr->{'download2'}!=''){
              $imageID2=$img->getImage($productAr->{'download2'},$productAr->{'fileName2'});
          };


        #GenerateID for new pics update
          $sql="SELECT max(`ID`) FROM `wp_posts`";
          $id=$db->getDataFromDatabase($sql);
          $thisId=$id[0][0][0];
          $postId=$thisId+1;

          #Start adding products
            $this->addProduct(
            $postId,
            trim(strip_tags($productAr->{'title'})),
            $productAr->{'desc'},
            $productAr->{'cena'},
				.
				.
				.

          );
        }
    }

  }

  class ajax extends fetching{
  }

  $ax=new ajax();
  $ax->getData($allProducts);

?>
