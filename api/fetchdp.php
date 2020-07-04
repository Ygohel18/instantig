<?php
header("Access-Control-Allow-Origin: *"); //this will allow any page to send GET AJAX request
$user = $_GET["u"]; //taking "USERNAME" from query "u" from current url as variable, e.g https://CURRENTURL.com/THISFILE.php?u=USERNAME
$jzon = file_get_contents("https://www.instagram.com/".$user."/?__a=1"); //getting source code of the user profile instagram page
$someArray = json_decode($jzon, true);
$image = $someArray["graphql"]["user"]["profile_pic_url_hd"];
setrawcookie("image", $image, 0, "/");
$responce = array();
$responce['status'] = "200";
$responce['message'] = "Profile Picture";
$responce['image'] = $image;

//header('Content-Type: application/json');
// echo json_encode($responce, JSON_UNESCAPED_SLASHES);
?>
<html>
<style>
    body {
        margin: 0;
    }

    img {
        width: 100%;
    }

    a {
        display: block;
    }
</style>
<body>
<a href="<?php echo $image;?>" download="<?php echo $user;?>" target="_blank"><img src="<?php echo $image;?>"/></a>
</body>
</html>
