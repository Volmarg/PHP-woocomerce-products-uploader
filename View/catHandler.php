<?php

include_once 'plugins/grabber/Model/catGrabber.php';

class catHandler{

  public function printCats(){
    $cats=new categories();
    $allCats=$cats->getCats();
    foreach($allCats as $catId){
      echo '<option value="'.$catId[0].'">'.$catId[1].'</option>';
    }
  }
}



?>
