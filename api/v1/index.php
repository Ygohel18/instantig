<?php 

header("Access-Control-Allow-Origin: *");

$responce = array();
$data = array();
$flag = FALSE;
$type = "TYPE";

function errorResponce() {
    $responce['code'] = 401;
    $responce['status'] = "invalid";
    $responce['message'] = "Something goes wrong";
    return json_encode($responce, JSON_UNESCAPED_SLASHES);
}

function successResponce() {
    $responce['code'] = 200;
    $responce['status'] = "valid";
    $responce['message'] = "Server connected";
    return json_encode($responce, JSON_UNESCAPED_SLASHES);
}

function profilePictureResponce($data) {
    $responce['code'] = "200";
    $responce['status'] = "valid";
    $responce['message'] = $data['message'];
    $responce['media_url'] = $data['media_url'];
    return json_encode($responce, JSON_UNESCAPED_SLASHES);
}

function mediaResponce($data) {
    $responce['code'] = $data['code'] = "200";
    $responce['status'] = $data['status'] = "valid";
    $responce['message'] = $data['message'] = "Download media";
    $responce['type'] = $data['type'] = $type;
    $responce['displau_url'] = $data['display_url'] = $displayurl;
    $responce['image_width'] = $data['image_width'] = $imagearray["config_width"];
    $responce['image_height'] = $data['image_height'] = $imagearray["config_height"];
    $responce['media_url'] = $data['media_url'] = $imagearray["src"];
    $responce['username'] = $data['username'] = $username;
    $responce['shortcode'] = $data['shortcode'] = $shortcode;
    $responce['caption'] = $data['caption'] = $caption;

    if($responce['type'] == "GraphSidecar") {
        $responce['sidecar'] = $data['sidecar'];
        $responce['sidecar_type'] = $data['sidecar_type'];
    }

    return json_encode($responce, JSON_UNESCAPED_SLASHES);
}

function isValidApiRequest($token, $key) {
    $master_token = "809OHKZ73JOVVRFV";
    $master_key = "42wSQ4Ef5tAFrUXiCy0Kh7Nz902inViKas55HzME3r5UzjUqXeRvX0WiDcjeo3iD";

    if ($master_token == $token && $master_key == $key) {
        return true;
    } else return false;
}

if (isset($_POST['api_token']) && isset($_POST['api_key'])) {

    $request_api_token = $_POST['api_token'];
    $request_api_key = $_POST['api_key'];

    $api_status = isValidApiRequest($request_api_token, $request_api_key);

    if ($api_status) {
        if( isset($_POST['api_request']) ) {
            $user_api_request = $_POST['api_request'];
            $type = $user_api_request;

            $master_ig_session = 'ig_did=6673FDE0-4797-4BFE-941A-8023C5FC5AE7; mid=Xx3MKgALAAHPzEfJptIrYxsk4piR; csrftoken=7b096zhtnkk8XSsLYfxdJZDMOLwq8E8F; ds_user_id=36863456778; sessionid=36863456778%3AtZ9MzQPwajLyB2%3A6; rur=FRC; urlgen="{\"2409:4041:e9b:fa1:9dba:7329:35b8:7bbc\": 55836}:1jzlSZ:ZXqcSy1lUGYogC98lDy8lyVOhwg"';
            $master_user_agent = 'Mozilla/5.0 (Linux; Android 6.0.1; Moto G (4)) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Mobile Safari/537.36';
            
            if ( $user_api_request == "curl_dp") {
                if (isset($_POST["username"])) {
                    $link = $_POST["username"]; 
                    $curl = curl_init();
                    
                    curl_setopt_array($curl, [
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL => 'https://www.instagram.com/'.$link.'/?__a=1',
                        CURLOPT_HTTPHEADER => [
                            $master_ig_session
                        ],
                        CURLOPT_SSL_VERIFYHOST => 0,
                        CURLOPT_FOLLOWLOCATION => false,
                        CURLOPT_USERAGENT => $master_user_agent,
                        CURLOPT_HEADER => false
                    ]);
                
                    $resp = curl_exec($curl);
                    curl_close($curl);

                    $someArray = json_decode($resp, true);
                
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $image = $someArray["graphql"]["user"]["profile_pic_url_hd"];
                        $data['media_url'] = $image;
                        $data['message'] = "HD DP Viewer";
                        $flag = TRUE;
                    }
                }
            }

            if ( $user_api_request == "curl_media") {
                if (isset($_POST["url"])) {
                    $link = $_POST["url"]; 
                    $curl = curl_init();
                    
                    curl_setopt_array($curl, [
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL => $link.'?__a=1',
                        CURLOPT_HTTPHEADER => [
                            $master_ig_session
                        ],
                        CURLOPT_SSL_VERIFYHOST => 0,
                        CURLOPT_FOLLOWLOCATION => false,
                        CURLOPT_USERAGENT => $master_user_agent,
                        CURLOPT_HEADER => false
                    ]);
                
                    $resp = curl_exec($curl);
                    curl_close($curl);
  
                    $someArray = json_decode($resp, true);
                
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $type = $someArray["graphql"]["shortcode_media"]["__typename"];
                        $username = $someArray["graphql"]["shortcode_media"]["owner"]["username"];
                        $shortcode = $someArray["graphql"]["shortcode_media"]["shortcode"];
                        $displayurl = $someArray["graphql"]["shortcode_media"]["display_url"];
                        $imagearray = $someArray["graphql"]["shortcode_media"]["display_resources"][2];
                        $caption = $someArray["graphql"]["shortcode_media"]["edge_media_to_caption"]["edges"][0]["node"]["text"];
                
                        $data['code'] = "200";
                        $data['status'] = "valid";
                        $data['message'] = "Download media";
                        $data['type'] = $type;
                        $data['display_url'] = $displayurl;
                        $data['image_width'] = $imagearray["config_width"];
                        $data['image_height'] = $imagearray["config_height"];
                        $data['media_url'] = $imagearray["src"];
                        $data['username'] = $username;
                        $data['shortcode'] = $shortcode;
                        $data['caption'] = $caption;
                
                        if($type == "GraphVideo") {
                            $videourl = $someArray["graphql"]["shortcode_media"]["video_url"];
                            $data['media_url'] = $videourl;
                        }
                
                        if($type == "GraphSidecar") {
                            $data['sidecar'] = array();
                            $data['sidecar_type'] = array();
                            $sidecar = $someArray["graphql"]["shortcode_media"]["edge_sidecar_to_children"]["edges"];
                
                            for($i=0;$i<count($sidecar);$i++) {
                
                                array_push($data["sidecar_type"], $sidecar[$i]["node"]["__typename"]);
                
                                if ($sidecar[$i]["node"]["__typename"] == "GraphVideo") {
                                    array_push($data["sidecar"], $sidecar[$i]["node"]["video_url"]);
                                } else {
                                    array_push($data["sidecar"], $sidecar[$i]["node"]["display_url"]);
                                }       
                            }
                        }

                        $flag = TRUE;
                        
                    } else {
                        $data['code'] = "401";
                        $data['status'] = "invalid";
                        $data['message'] = "Private account";
                    }
                } else {
                    $data['code'] = "401";
                    $data['status'] = "invalid";
                    $data['message'] = "Media url not found";
                }
            }
        }
    }
}

header('Content-Type: application/json');

if($flag) {
    if ( $type == "curl_dp" ) {
        echo profilePictureResponce($data);
    }

    if ( $type == "curl_media" ) {
        echo mediaResponce($data);
    }
} else {
    echo errorResponce();
}

?>