<?php

class mcurl{

    public function getMulti($links){

      // Your URL array that hold links to files
      $urls = $links; // array of URLs

      // cURL multi-handle
      $mh = curl_multi_init();

      // This will hold cURLS requests for each file
      $requests = array();
      $options = array(
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_AUTOREFERER    => true,
          CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',
          CURLOPT_HEADER         => false,
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_RETURNTRANSFER => true
      );

      //Corresponding filestream array for each file
      $fstreams = array();

      $folder = 'content/';
      if (!file_exists($folder)){ mkdir($folder, 0777, true); }

      foreach ($urls as $key => $url)
      {

          // Add initialized cURL object to array
          $requests[$key] = curl_init($url);

          // Set cURL object options
          curl_setopt_array($requests[$key], $options);

          // Add cURL object to multi-handle
          curl_multi_add_handle($mh, $requests[$key]);
      }

      // Do while all request have been completed
      do {
         curl_multi_exec($mh, $active);
      } while ($active > 0);

      // Collect all data here and clean up
      foreach ($requests as $key => $request) {

          $returned[$key] = curl_multi_getcontent($request); // Use this if you're not downloading into file, also remove CURLOPT_FILE option and fstreams array
          curl_multi_remove_handle($mh, $request); //assuming we're being responsible about our resource management
          curl_close($request);                    //being responsible again.  THIS MUST GO AFTER curl_multi_getcontent();
          fclose($fstreams[$key]);
      }

      curl_multi_close($mh);
      return $returned;
  }
}

?>
