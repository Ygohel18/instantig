<?php
header("Access-Control-Allow-Origin: *");

$responce = array();

if (isset($_GET["posturl"])) {
    $link = $_GET["posturl"]; 

    $jzon = file_get_contents($link."?__a=1"); 
    $someArray = json_decode($jzon, true);

    print_r($someArray);

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
		
		/*

    if (json_last_error() === JSON_ERROR_NONE) {
       
        
    } else {
        $responce['status'] = "401";
        $responce['message'] = "Private account";
    }
	*/
} else {
    $responce['status'] = "401";
    $responce['message'] = "Post url not found";
}

header('Content-Type: application/json');
echo json_encode($responce, JSON_UNESCAPED_SLASHES);
?>