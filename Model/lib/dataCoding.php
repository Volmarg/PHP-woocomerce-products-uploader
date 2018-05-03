<?

  function serialization($jsn){
    $stripped=stripslashes($jsn);
    $json=json_decode($stripped);
    $serialized=serialize(json_decode(json_encode($json), true));
    return $serialized;
  }

?>
