<?php
header("Access-Control-Allow-Origin: *");

$responce = array();

if (isset($_POST["posturl"])) {
    $link = $_POST["posturl"]; 
    $curl = curl_init();
    
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $link.'?__a=1',
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
        $type = $someArray["graphql"]["shortcode_media"]["__typename"];
        $username = $someArray["graphql"]["shortcode_media"]["owner"]["username"];
        $shortcode = $someArray["graphql"]["shortcode_media"]["shortcode"];
        $displayurl = $someArray["graphql"]["shortcode_media"]["display_url"];
        $imagearray = $someArray["graphql"]["shortcode_media"]["display_resources"][2];
        $caption = $someArray["graphql"]["shortcode_media"]["edge_media_to_caption"]["edges"][0]["node"]["text"];
        // setrawcookie("image", $image, 0, "/");

        $responce['status'] = "200";
        $responce['message'] = "Media";
        $responce['type'] = $type;
        $responce['display_url'] = $displayurl;
        $responce['image_width'] = $imagearray["config_width"];
        $responce['image_height'] = $imagearray["config_height"];
        $responce['image_url'] = $imagearray["src"];
        $responce['username'] = $username;
        $responce['shortcode'] = $shortcode;
        $responce['caption'] = $caption;

        if($type == "GraphVideo") {
            $videourl = $someArray["graphql"]["shortcode_media"]["video_url"];
            $responce['image_url'] = $videourl;
        }

        if($type == "GraphSidecar") {
            $responce['sidecar'] = array();
            $responce['sidecar_type'] = array();
            $sidecar = $someArray["graphql"]["shortcode_media"]["edge_sidecar_to_children"]["edges"];

            for($i=0;$i<count($sidecar);$i++) {

                array_push($responce["sidecar_type"], $sidecar[$i]["node"]["__typename"]);

                if ($sidecar[$i]["node"]["__typename"] == "GraphVideo") {
                    array_push($responce["sidecar"], $sidecar[$i]["node"]["video_url"]);
                } else {
                    array_push($responce["sidecar"], $sidecar[$i]["node"]["display_url"]);
                }       
            }
        }
        
    } else {
        $responce['status'] = "401";
        $responce['message'] = "Private account";
    }
} else {
    $responce['status'] = "401";
    $responce['message'] = "Post url not found";
}

header('Content-Type: application/json');
echo json_encode($responce, JSON_UNESCAPED_SLASHES);
?>
