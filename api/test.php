<?php 
  $curl = curl_init();
  $link = 'https://www.instagram.com/p/CCOCs6jAFn4/?__a=1';
  curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $link,
    CURLOPT_HTTPHEADER => [
        'Cookie: _ga=GA1.1.1858824391.1587317770; hblid=BiC4bmXs9ruxVjST3m39N0Ja4bAdAab6; _ga_PQ59P2BKSP=GS1.1.1593718474.48.1.1593719199.0'
    ],
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Linux; Android 6.0.1; Moto G (4)) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Mobile Safari/537.36',
    CURLOPT_HEADER => false
  ]);

  $resp = curl_exec($curl);

  echo $resp;
?>
