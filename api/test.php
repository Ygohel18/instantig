<?php 
  $curl = curl_init();
  $link = 'https://www.instagram.com/p/CCOCs6jAFn4/?__a=1&csrf=EMI26nJLlrhavUwJKW50mWxM2ETzaK3y';
  curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $link,
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_USERAGENT => 'Instagram 15.206.133.54 Android (23/6.0.1; 320dpi; 720x1280; samsung; SM-A310F; a3xelte; samsungexynos7580; en_EN; 5698133721)',
    CURLOPT_HEADER => false,
    CURLOPT_HTTP_VERSION => 84

  ]);

  $resp = curl_exec($curl);

  echo $resp;
?>
