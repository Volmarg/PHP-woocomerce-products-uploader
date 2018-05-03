<?php

  class curler{

    function get($url){

      $curl=curl_init();

      curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $curl_timeout);
      curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

      curl_setopt($curl, CURLINFO_HEADER_OUT, true);
      curl_setopt($curl, CURLOPT_URL, trim($url));

      #Optimization part
      curl_setopt($curl, CURLOPT_ENCODING, '');//set gzip, deflate or keep empty for server to detect and set supported encoding.

      #Connection timeout and Crashing
      curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
      curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);       


      #Store in var fix
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

      #Https fixes
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);

      #Keep Session
      curl_setopt($curl, CURLOPT_COOKIESESSION, true);
      curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookie-name');  //could be empty, but cause problems on some hosts
      curl_setopt($curl, CURLOPT_COOKIEFILE, '/var/www/ip4.x/file/tmp');  //could be empty, but cause problems on some hosts

      #Store content of page in $content;
      $content=curl_exec($curl);

      #Add basehref
      $base='<base href="'.$url.'"/>';

      $content=str_replace('<head>','<head>'.$base,$content);
      $content=str_replace('<head >','<head >'.$base,$content);

      curl_close($curl);

      return $content;
    }


  }


?>
