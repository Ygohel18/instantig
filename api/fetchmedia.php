<?php
header("Access-Control-Allow-Origin: *");

$responce = array();

if (isset($_GET["posturl"])) {
    $link = $_GET["posturl"]; 
    $curl = curl_init();
    
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $link.'?__a=1',
        CURLOPT_HTTPHEADER => [
            'Cookie: ig_did=E24C5C9D-8F14-4CF1-97F2-44E17142A296; mid=XuCEBQALAAGpwPd_k6kSnxtPfe6D; shbid=17634; datr=b-LhXgsHC3KiBWZw-a7U0PJ7; shbts=1593630989.5520513; csrftoken=EMI26nJLlrhavUwJKW50mWxM2ETzaK3y; ds_user_id=5698133721; sessionid=5698133721%3AaNQiLMvWGHrzDc%3A22; rur=PRN; urlgen="{\"2405:205:c84a:4db:a8f1:6f6d:1232:bc56\": 55836\054 \"2405:205:c84a:4db:94ae:69c0:d4a:ce92\": 55836}:1jrn04:jWExUEgCr37-xbCFImYteSmCwiY"',
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
        $displayurl = $someArray["graphql"]["shortcode_media"]["display_url"];
        $imagearray = $someArray["graphql"]["shortcode_media"]["display_resources"][2];
        // setrawcookie("image", $image, 0, "/");

        $responce['status'] = "200";
        $responce['message'] = "Media";
        $responce['type'] = $type;
        $responce['display_url'] = $displayurl;
        $responce['image_width'] = $imagearray["config_width"];
        $responce['image_height'] = $imagearray["config_height"];
        $responce['image_url'] = $imagearray["src"];

        if($type == "GraphSidecar") {
            $responce['sidecar'] = array();
            $sidecar = $someArray["graphql"]["shortcode_media"]["edge_sidecar_to_children"]["edges"];

            for($i=0;$i<count($sidecar);$i++) {
                if($sidecar[$i]["node"]["__typename"] == "GraphImage") {
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
