<?php
header("Access-Control-Allow-Origin: *");

$responce = array();

if (isset($_POST["username"])) {
    $link = $_POST["username"]; 
    $curl = curl_init();
    
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://www.instagram.com/'.$link.'/?__a=1',
        CURLOPT_HTTPHEADER => [
            'Cookie: ig_did=6673FDE0-4797-4BFE-941A-8023C5FC5AE7; mid=Xx3MKgALAAHPzEfJptIrYxsk4piR; csrftoken=7b096zhtnkk8XSsLYfxdJZDMOLwq8E8F; ds_user_id=36863456778; sessionid=36863456778%3AtZ9MzQPwajLyB2%3A6; rur=FRC; urlgen="{\"2409:4041:e9b:fa1:9dba:7329:35b8:7bbc\": 55836}:1jzlSZ:ZXqcSy1lUGYogC98lDy8lyVOhwg"',
        ],
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Linux; Android 6.0.1; Moto G (4)) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Mobile Safari/537.36',
        CURLOPT_HEADER => false
    ]);

    $resp = curl_exec($curl);
    curl_close($curl);

    $someArray = json_decode($resp, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        $image = $someArray["graphql"]["user"]["profile_pic_url_hd"];

        $responce['code'] = "200";
        $responce['status'] = "valid";
        $responce['message'] = "HD DP Viewer";
        $responce['media_url'] = $image;

    } else {
        $responce['status'] = "401";
        $responce['message'] = "Something goes wrong";
    }
} else {
    $responce['code'] = "401";
    $responce['status'] = "invalid";
    $responce['message'] = "Invalid username";
}

header('Content-Type: application/json');
echo json_encode($responce, JSON_UNESCAPED_SLASHES);
?>
