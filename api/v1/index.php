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
    $responce = $data;
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

            $master_ig_session = 'Cookie: ig_did=A03A5753-4EC9-415D-A778-1B3925B31B09; mid=XwqlXQALAAFJfrVx5-YFYwN6q-uH; csrftoken=dhGmbuTSSxnZGZRCk7PtyRdAu7gjojUl; ds_user_id=8173227811; sessionid=8173227811%3AGa3hy7FFSMwSzt%3A15; shbid=17634; shbts=1594533239.137384; rur=PRN; urlgen="{\"2409:4041:2e96:97d:41ea:c89e:8f03:e3b9\": 55836}:1juUwN:i_ct7JlDKyPf_4BXHKfwPO4NKsc"';
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
                $flag = TRUE;
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
    } else if ( $type == "curl_media" ) {
        echo mediaResponce($data);
    } else {
        echo successResponce();
    }
} else {
    echo errorResponce();
}

?>