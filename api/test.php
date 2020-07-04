<?php 
  $curl = curl_init();
  $link = 'https://www.instagram.com/p/CCOCs6jAFn4/?__a=1';
  curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $link,
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36',
    CURLOPT_HEADER => false,
    CURLOPT_HTTP_VERSION => 84,
    CURLOPT_COOKIE => 'csrftoken=EMI26nJLlrhavUwJKW50mWxM2ETzaK3y;ds_user_id=5698133721;mid=XuCEBQALAAGpwPd_k6kSnxtPfe6D;sessionid=5698133721%3AaNQiLMvWGHrzDc%3A22'

  ]);

  $resp = curl_exec($curl);

  echo $resp;
?>
