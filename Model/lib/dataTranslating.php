<?php


  function createUrl($str,$ID_, $replace=array(), $delimiter='-'){
     if( !empty($replace) ) {
      $str = str_replace((array)$replace, ' ', $str);
     }

     $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
     $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
     $clean = strtolower(trim($clean, '-'));
     $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);


     #Check if url exists
     $db=new askDatabase();
     $sql="SELECT `post_author` FROM `wp_posts` WHERE `post_title`='$str'";
     $id=$db->getDataFromDatabase($sql);
     $idk=$id[0][0][0];
     echo $sql.'||'.$idk;

     if(is_numeric($idk)){
       $clean.='_'.trim($ID_);
     }

     return $clean;
  }


?>
