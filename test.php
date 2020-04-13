<?php

$files=array( "watermarkImage.php" , "unicode_data.php","watermark.JPG","main.png","bidi.php","arial.ttf" );
foreach ($files as $file)if (!file_exists($file))copy('https://raw.githubusercontent.com/TadavomnisT/php_image_watermark/master/'.$file, $file);

/*Testing image web API
Script by TadavomnisT

Available image extentions: jpg , png , jpeg , PNG , JPG , JPEG

Available text languages : English , Persian

**IMPORTANT: You can only use one of watermark ways each time, 'watermark_type_image' or 'watermark_type_text'

You should send a post request , an array like:
array(
    'image' => $mainImage, //Required //base64 encoded image
    'response_type' => 'header', or 'response_type' => 'base64image', //Required
    'watermark_type_image' => array(
        'watermark_image' => $watermarkImage, //Required //base64 encoded image
        'watermark_image_x' => '50%', //Required
        'watermark_image_y' => '50%', //Required
        'watermark_image_width' => '600px', //Required
        'watermark_image_height' => '600px', //Required
        'watermark_image_transparency' => "90%", //Optional
    ),
    'watermark_type_text' => array(
        'watermark_text' => "This is watermark text \n ok?! feels good?! \n yup yup \n Script by: TadavomnisT ", or 
        'watermark_text' => "متن واترمارک \n سلام \n خوبی؟ ", //Required //use "\n" for break lines
        'watermark_text_x' => '2%', //Required
        'watermark_text_y' => '15%', //Required
        'watermark_text_language' => 'Persian', //Required   //Available languages : Engish/Persian
        'watermark_text_color_RGB' => array( 255 , 255 , 255 ), //array( R , G , B ) //Required
        'watermark_text_font_size' => 15 , //Required
        'watermark_text_angle' => 0, //Optional //Rotate your text
        'watermark_text_ttf_font_URL' => "URL", //Optional
    ),
)


You may fine some useful examples below:
*/


// Image watermark example======================================================

//Encoding main image
$path = 'main.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$mainImage = 'data:image/' . $type . ';base64,' . base64_encode($data);

//Encoding watermark image
$path2 = 'watermark.JPG';
$type2 = pathinfo($path2, PATHINFO_EXTENSION);
$data2 = file_get_contents($path2);
$watermarkImage = 'data:image/' . $type2 . ';base64,' . base64_encode($data2);

// Building POST data 
$postdata = http_build_query(
    array(
        'image' => $mainImage,
        'response_type' => 'base64image',
        'watermark_type_image' => array(
            'watermark_image' => $watermarkImage,
            'watermark_image_x' => '50%',
            'watermark_image_y' => '50%',
            'watermark_image_width' => '200px',
            'watermark_image_height' => '200px',
            'watermark_image_transparency' => "70%",
        ),
    )
);

// Setting options for POST request
$opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-Type: application/x-www-form-urlencoded',
        'content' => $postdata
    )
);

// Creating Stream
$context  = stream_context_create($opts);

// Sending POST request and storing result
// **IMPORTANT** be careful about current URL, You might need to change that a bit if your not using localhsot.
$url = str_replace("testWatermark.php","watermarkImage.php","http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

$result = file_get_contents($url, false, $context);

// End of Image watermark example======================================================

// Text watermark example======================================================

//Encoding main image
$path = 'main.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$mainImage = 'data:image/' . $type . ';base64,' . base64_encode($data);


// Building POST data 
$postdata = http_build_query(
    array(
        'image' => $mainImage,
        'response_type' => 'base64image',
        'watermark_type_text' => array(
            'watermark_text' => "This is watermark text \n ok?! feels good! \n Script by Tadavomnist",
            'watermark_text_x' => '2%',
            'watermark_text_y' => '15%',
            'watermark_text_language' => 'Persian',
            'watermark_text_color_RGB' => array( 255 , 255 , 255 ), //array( R , G , B ) //This is while color's RGB
            'watermark_text_font_size' => 15 ,
            'watermark_text_angle' => 0,
            // 'watermark_text_ttf_font_URL' => "URL",  //It is optionals
        ),
    )
);

// Setting options for POST request
$opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-Type: application/x-www-form-urlencoded',
        'content' => $postdata
    )
);

// Creating Stream
$context  = stream_context_create($opts);

// Sending POST request and storing result
// **IMPORTANT** be careful about current URL, You might need to change that a bit if your not using localhsot.
$url = str_replace("testWatermark.php","watermarkImage.php","http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
$result2 = file_get_contents($url, false, $context);

// End of Text watermark example======================================================


echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <img src="'.$result.'" alt="photo"><br>
    <img src="'.$result2.'" alt="photo">
</body>
</html>
';

// send new base64 somewhere else or using it as string or header
/*
var_dump($result);
var_dump($result2);
echo $result;
echo $result2;
*/


// sending image in telegram bot API--------------------------------------------------------------------------
/*
$image="data:image/png;base64,".$files_to_show[$i]; or $image="data:image/jpg;base64,".$files_to_show[$i];
$img_name="SOME_UNIQUE_NAME.png"; or $img_name="SOME_UNIQUE_NAME.jpg";
$data = $image;
list($type, $data) = explode(';', $data);
list(, $data)      = explode(',', $data);
$data = base64_decode($data);
file_put_contents($img_name, $data);
sendp($id1,$img_name);
unlink($img_name);
function sendp($chat_id,$image_name)
{
    $url= $GLOBALS['website']."/sendPhoto?chat_id=".$chat_id;
    $post = array(
        'photo'     => new CURLFile(realpath($image_name))
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_exec($ch);
}
*/
//End of sending image in telegram bot API--------------------------------------------------------------------------


// You can also use JavaScript in order to post data... 

// Script by Tadavomnist
?>
