<?php 
  $curl = curl_init();
  $link = 'https://www.instagram.com/p/CCOCs6jAFn4/?__a=1';
  curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $link,
    CURLOPT_USERAGENT => 'IIG curl request'
  ]);

  $resp = curl_exec($curl);

  echo $resp;
?>
