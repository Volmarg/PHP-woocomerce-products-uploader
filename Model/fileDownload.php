<?php
include_once 'databaseConnection.php';
include_once 'curler.php';

class imageDownload{

  private function addToDatabase($filename_,$extension){
    #object
    $db=new askDatabase();

    #crate ID based on highest ID in table
      $sql="SELECT max(`ID`) FROM `wp_posts`";
      $id=$db->getDataFromDatabase($sql);
      $thisId=$id[0][0][0];
      $ID=$thisId+1;

    #constant vars
    $post_author='1';
    $post_date='1991-01-19 18:00:47';
    $post_date_gmt='1991-01-19 18:00:47';
		 .
		 .
		 .
		 .
    $menu_order='0';
    $post_type='attachment';
    $comment_count='0';

    #scrapper vars
    $post_name=$filename_;      #filename
    $post_title=$filename_;     #filename

    $file=$filename_.'.'.$extension;
    $wp_postmetaPath='1991/01/'.$file;
    $filepath='/wp-content/uploads/1991/01/'.$file;
    $guid='http://sklep'.'.'.$_SERVER['SERVER_NAME'].$filepath;

    #do rozwazenia pod generowanie
    $post_mime_type='image/jpeg';

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
			.
			.
			.
			.
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
		.
		.
		.
		.
      '$comment_count',
      '$post_password',
      '$to_ping',
      '$pinged',
      '$post_content_filtered',
      '$post_mime_type'
    );";

    #send as wp_post
    $db->modifyDataInDatabase($sql);

    #send as wp_postmeta attached file
    $sql="INSERT INTO `wp_postmeta`(`post_id`,`meta_key`,`meta_value`)          VALUES('$ID','_wp_attached_file','$wp_postmetaPath')";
    $db->modifyDataInDatabase($sql);

    #send as wp postmeta _wp_attachment_metadata

    $serialized=$this->serial_($wp_postmetaPath,$file);
    $sql="INSERT INTO `wp_postmeta`(`post_id`,`meta_key`,`meta_value`)          VALUES('$ID','_wp_attachment_metadata','$serialized')";
    $db->modifyDataInDatabase($sql);

    return $ID;
  }

  public function getImage($link,$filename){
    $curls=new curler();

    #Check if name was defined else generate random one
    if($filename=='' || $filename==null || $filename==false){
      #if exists will generate name as long as its taken
      do{
        $filename=$this->generateRandomString();
        $filename=$this->createUrl($filename);
      }while(file_exists($filename));
    }
    #extract file extension
    preg_match('#(.*)\.([a-z]*)#',$link,$matched);
    $extension=$matched[2];

    #Set save place
    $save_path="../../wp-content/uploads/1991/01/".$filename.".".$extension;

    #get the file
    $fileData=$curls->get($link);
    $result=file_put_contents($save_path, $fileData);


        #echo $save_path.'{{}}';

    $this->setMemoryLimit($save_path);

    $imageID=$this->addToDatabase($filename,$extension);
    return $imageID;
  }

  private function setMemoryLimit($filename){
   set_time_limit(50);
   $maxMemoryUsage = 258;
   $width  = 0;
   $height = 0;
   $size   = ini_get('memory_limit');

   list($width, $height) = getimagesize($filename);
   $size = $size + floor(($width * $height * 4 * 1.5 + 1048576) / 1048576);

   if ($size > $maxMemoryUsage) $size = $maxMemoryUsage;

   ini_set('memory_limit',$size.'M');
	}

  private function generateRandomString($length = 10) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
    }

  private function serial_($filepath,$filename){
    $jsn='{
    	\"width\": 390,
    	\"height\": 364,
    	\"file\": \"'.$filepath.'\",
    	\"sizes\": {
    		\"thumbnail\": {
    			\"file\": \"'.$filename.'\",
    			\"width\": 150,
    			\"height\": 150,
    			\"mime-type\": \"image/jpeg\"
    		},
    		\"medium\": {
    			\"file\": \"'.$filename.'\",
    			\"width\": 300,
    			\"height\": 280,
    			\"mime-type\": \"image/jpeg\"
    		},
    		\"shop_thumbnail\": {
    			\"file\": \"'.$filename.'\",
    			\"width\": 180,
    			\"height\": 180,
    			\"mime-type\": \"image/jpeg\"
    		},
    		\"shop_catalog\": {
    			\"file\": \"'.$filename.'\",
    			\"width\": 300,
    			\"height\": 300,
    			\"mime-type\": \"image/jpeg\"
    		}
    	},
    	\"image_meta\": {
    		\"aperture\": \"0\",
				.
				.
				.
				.
    		\"title\": \"\",
    		\"orientation\": \"0\",
    		\"keywords\": []
    	}
    }';

    $stripped=stripslashes($jsn);
    $json=json_decode($stripped);
    $serialized=serialize(json_decode(json_encode($json), true));
    return $serialized;
  }

  private function maybe_serialize_( $data ) {
    	        if ( is_array( $data ) || is_object( $data ) )
    	                return serialize( $data );

    	        // Double serialization is required for backward compatibility.
    	        // See https://core.trac.wordpress.org/ticket/12930
    	        // Also the world will end. See WP 3.6.1.
    	        if ( $this->is_serialized_( $data, false ) )
    	                return serialize( $data );

    	        return $data;
    }

  private function is_serialized_( $data, $strict = true ) {
    	        // if it isn't a string, it isn't serialized.
    	        if ( ! is_string( $data ) ) {
    	                return false;
    	        }
    	        $data = trim( $data );
    	        if ( 'N;' == $data ) {
    	                return true;
    	        }
    	        if ( strlen( $data ) < 4 ) {
    	                return false;
    	        }
    	        if ( ':' !== $data[1] ) {
    	                return false;
    	        }
    	        if ( $strict ) {
    	                $lastc = substr( $data, -1 );
    	                if ( ';' !== $lastc && '}' !== $lastc ) {
    	                        return false;
    	                }
    	        } else {
    	                $semicolon = strpos( $data, ';' );
    	                $brace     = strpos( $data, '}' );
    	                // Either ; or } must exist.
    	                if ( false === $semicolon && false === $brace )
    	                        return false;
    	                // But neither must be in the first X characters.
    	                if ( false !== $semicolon && $semicolon < 3 )
    	                        return false;
    	                if ( false !== $brace && $brace < 4 )
    	                        return false;
    	        }
    	        $token = $data[0];
    	        switch ( $token ) {
    	                case 's' :
    	                        if ( $strict ) {
    	                                if ( '"' !== substr( $data, -2, 1 ) ) {
    	                                        return false;
    	                                }
    	                        } elseif ( false === strpos( $data, '"' ) ) {
    	                                return false;
    	                        }
    	                        // or else fall through
    	                case 'a' :
    	                case 'O' :
    	                        return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
    	                case 'b' :
    	                case 'i' :
    	                case 'd' :
    	                        $end = $strict ? '$' : '';
    	                        return (bool) preg_match( "/^{$token}:[0-9.E-]+;$end/", $data );
    	        }
    	        return false;
    	}

  private function createUrl($str, $replace=array(), $delimiter='-'){
            setlocale(LC_ALL, 'en_US.UTF8');
             if( !empty($replace) ) {
              $str = str_replace((array)$replace, ' ', $str);
             }

             $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
             $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
             $clean = strtolower(trim($clean, '-'));
             $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

             return $clean;
  }

}

class images extends imageDownload{


}



?>
