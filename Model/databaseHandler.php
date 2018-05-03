<?php
  include_once 'databaseConnection.php';

  class dataHandle{

    #For checking if record exists already
    function doesExists($category,$name,$movie){
      $db=new askDatabase();

      $sql="SELECT `id` FROM `videos` WHERE
      `tytul`='$name' AND `cat`='$category';
      ";

      $id=$db->getDataFromDatabase($sql);
      $idk=$id[0][0][0];

      if(is_numeric($idk)){
        $status='true';
      }else{
        $status='false';
      }

      return $status;
    }



  }

?>
