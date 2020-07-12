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
            'Cookie: ig_did=A03A5753-4EC9-415D-A778-1B3925B31B09; mid=XwqlXQALAAFJfrVx5-YFYwN6q-uH; csrftoken=dhGmbuTSSxnZGZRCk7PtyRdAu7gjojUl; ds_user_id=8173227811; sessionid=8173227811%3AGa3hy7FFSMwSzt%3A15; shbid=17634; shbts=1594533239.137384; rur=PRN; urlgen="{\"2409:4041:2e96:97d:41ea:c89e:8f03:e3b9\": 55836}:1juUwN:i_ct7JlDKyPf_4BXHKfwPO4NKsc"',
        ],
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Linux; Android 6.0.1; Moto G (4)) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Mobile Safari/537.36',
        CURLOPT_HEADER => false
    ]);

    $resp = curl_exec($curl);
    curl_close($curl);

    // $jzon = file_get_contents($link."?__a=1"); 
    $someArray = json_decode($resp, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        $image = $someArray["graphql"]["user"]["profile_pic_url_hd"];
        // setrawcookie("image", $image, 0, "/");

        $responce['status'] = "200";
        $responce['message'] = "Profile picture";
        $responce['image'] = $image;

    } else {
        $responce['status'] = "401";
        $responce['message'] = "Something goes wrong";
    }
} else {
    $responce['status'] = "401";
    $responce['message'] = "Invalid username";
}

header('Content-Type: application/json');
echo json_encode($responce, JSON_UNESCAPED_SLASHES);
?>
