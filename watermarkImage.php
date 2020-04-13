<?php
// Script by TadavomnisT
if (isset($_POST) && count($_POST)>0) {
    if (isset($_POST["image"])) {
        $is_sup=false;
        $array_of_sup=array("data:image/png;base64,","data:image/jpg;base64,","data:image/jpeg;base64,","data:image/PNG;base64,","data:image/JPG;base64,","data:image/JPEG;base64,");
        foreach ($array_of_sup as $sup) if (strpos(substr($_POST["image"], 0, 25),$sup) !== false) {
            $is_sup = true; $master_image_type=$sup;
        }
        if ($is_sup) {
            if (isset($_POST["response_type"])) {
                if ($_POST["response_type"]=="header" || $_POST["response_type"]=="base64image") {
                    if (isset($_POST["watermark_type_image"])) {
                        if (isset($_POST["watermark_type_image"]["watermark_image"])) {
                            $is_sup=false;
                            foreach ($array_of_sup as $sup) if (strpos(substr($_POST["watermark_type_image"]["watermark_image"], 0, 25),$sup) !== false) {
                                $is_sup = true; $watermark_image_type=$sup;
                            }
                            if ($is_sup) {
                                if (isset($_POST["watermark_type_image"]["watermark_image_x"])) {
                                    $x = intval(str_replace("%","",$_POST["watermark_type_image"]["watermark_image_x"]));
                                    if ($x>=0 && $x<=100) {
                                        if (isset($_POST["watermark_type_image"]["watermark_image_y"])) {
                                            $y = intval(str_replace("%","",$_POST["watermark_type_image"]["watermark_image_y"]));
                                            if ($y>=0 && $y<=100) {
                                                if (isset($_POST["watermark_type_image"]["watermark_image_width"])) {
                                                    $watermark_width = intval(str_replace("px","",$_POST["watermark_type_image"]["watermark_image_width"]));
                                                    if ($watermark_width>0) {
                                                        if (isset($_POST["watermark_type_image"]["watermark_image_height"])) {
                                                            $watermark_height = intval(str_replace("px","",$_POST["watermark_type_image"]["watermark_image_height"]));
                                                            if ($watermark_height>0) {
                                                                if (isset($_POST["watermark_type_image"]["watermark_image_transparency"])) {
                                                                    $watermark_image_transparency = intval(str_replace("%","",$_POST["watermark_type_image"]["watermark_image_transparency"]));
                                                                    if ($watermark_image_transparency<0 && $watermark_image_transparency>100) {
                                                                        echo "<b>ERROR</b>: Invalid watermark_image_transparency.(Must be in percent)\n";
                                                                        die;
                                                                    }
                                                                }
                                                                else $watermark_image_transparency = 60;
                                                                $base64 = $_POST["image"];
                                                                $base64 = base64_decode(str_replace($master_image_type,"",$base64));
                                                                $image_infos = getimagesizefromstring($base64);
                                                                if($image_infos["mime"] == "image/jpeg") $image = imagecreatefromjpeg($_POST["image"]);
                                                                elseif ($image_infos["mime"] == "image/png") $image = imagecreatefrompng($_POST["image"]);
                                                                else die("<b>ERROR</b>: Unknown Error occured");

                                                                $base64 = $_POST["watermark_type_image"]["watermark_image"];
                                                                $base64 = base64_decode(str_replace($watermark_image_type,"",$base64));
                                                                $watermark_image_infos = getimagesizefromstring($base64);
                                                                if($watermark_image_infos["mime"] == "image/jpeg") $watermark_image = imagecreatefromjpeg($_POST["watermark_type_image"]["watermark_image"]);
                                                                elseif ($watermark_image_infos["mime"] == "image/png") $watermark_image = imagecreatefrompng($_POST["watermark_type_image"]["watermark_image"]);
                                                                else die("<b>ERROR</b>: Unknown Error occured");
                                                                $watermark_image=imagescale($watermark_image,$watermark_width,$watermark_height);
                                                                imagecopymerge($image, $watermark_image, $x, $y, 0, 0, imagesx($watermark_image), imagesy($watermark_image), $watermark_image_transparency);
                                                                if ($_POST["response_type"] == "header") {
                                                                    header('content:image/jpeg');
                                                                    if($image_infos["mime"] == "image/jpeg") {header('content:image/jpeg'); imagejpeg($image);}
                                                                    elseif($image_infos["mime"] == "image/png") {header('content:image/png'); imagepng($image);}
                                                                    else die("<b>ERROR</b>: Unknown Error occured");
                                                                }else {
                                                                    ob_start();
                                                                    if($image_infos["mime"] == "image/jpeg") imagejpeg($image);
                                                                    elseif($image_infos["mime"] == "image/png") imagepng($image);
                                                                    else die("<b>ERROR</b>: Unknown Error occured");
                                                                    $contents = ob_get_contents();
                                                                    ob_end_clean();
                                                                    echo "data:".$image_infos["mime"].";base64,".base64_encode($contents);
                                                                }
                                                                imagedestroy($image);
                                                                imagedestroy($watermark_image);
                                                            }else echo "<b>ERROR</b>: Invalid watermark_image_height.\n";
                                                        }else echo "<b>ERROR</b>: No watermark_image_height sent.\n";
                                                    }else echo "<b>ERROR</b>: Invalid watermark_image_width.\n";
                                                }else echo "<b>ERROR</b>: No watermark_image_width sent.\n";
                                            }else echo "<b>ERROR</b>: Invalid watermark_image_y.(Must be in percent)\n";
                                        }else echo "<b>ERROR</b>: No watermark_image_y sent.\n";
                                    }else echo "<b>ERROR</b>: Invalid watermark_image_x.(Must be in percent)\n";
                                }else echo "<b>ERROR</b>: No watermark_image_x sent.\n";
                            }else echo "<b>ERROR</b>: Invalid base64 encode for watermark_image.\n";
                        }else echo "<b>ERROR</b>: No watermark_image sent.\n";
                    }elseif (isset($_POST["watermark_type_text"])) {
                        if (isset($_POST["watermark_type_text"]["watermark_text"])) {
                            if (isset($_POST["watermark_type_text"]["watermark_text_x"])) {
                                $x = intval(str_replace("%","",$_POST["watermark_type_text"]["watermark_text_x"]));
                                if ($x>=0 && $x<=100) {
                                    if (isset($_POST["watermark_type_text"]["watermark_text_y"])) {
                                        $y = intval(str_replace("%","",$_POST["watermark_type_text"]["watermark_text_y"]));
                                        if ($y>=0 && $y<=100) {
                                            if (isset($_POST["watermark_type_text"]["watermark_text_language"])) {
                                                if ($_POST["watermark_type_text"]["watermark_text_language"] =="English" || $_POST["watermark_type_text"]["watermark_text_language"] =="Persian") {
                                                    if (isset($_POST["watermark_type_text"]["watermark_text_color_RGB"])) {
                                                        if (
                                                            count($_POST["watermark_type_text"]["watermark_text_color_RGB"])==3 &&
                                                            isset($_POST["watermark_type_text"]["watermark_text_color_RGB"][0]) &&
                                                            isset($_POST["watermark_type_text"]["watermark_text_color_RGB"][1]) &&
                                                            isset($_POST["watermark_type_text"]["watermark_text_color_RGB"][2]) &&
                                                            intval($_POST["watermark_type_text"]["watermark_text_color_RGB"][0])<=255 &&
                                                            intval($_POST["watermark_type_text"]["watermark_text_color_RGB"][1])<=255 &&
                                                            intval($_POST["watermark_type_text"]["watermark_text_color_RGB"][2])<=255 &&
                                                            intval($_POST["watermark_type_text"]["watermark_text_color_RGB"][0])>=0 &&
                                                            intval($_POST["watermark_type_text"]["watermark_text_color_RGB"][1])>=0 &&
                                                            intval($_POST["watermark_type_text"]["watermark_text_color_RGB"][2])>=0 
                                                        ) {
                                                            if (isset($_POST["watermark_type_text"]["watermark_text_font_size"])) {
                                                                if (intval($_POST["watermark_type_text"]["watermark_text_font_size"])>0) {
                                                                    if (isset($_POST["watermark_type_text"]["watermark_text_ttf_font_URL"])) $font = $_POST["watermark_type_text"]["watermark_text_ttf_font_URL"] or die("<b>ERROR</b>: Invalid font URL.");
                                                                    else $font = realpath(getcwd()."/arial.ttf");
                                                                    if (isset($_POST["watermark_type_text"]["watermark_text_angle"])) $angle = intval($_POST["watermark_type_text"]["watermark_text_angle"]);
                                                                    else $angle = 0;
                                                                    $text = $_POST["watermark_type_text"]["watermark_text"];
                                                                    if($_POST["watermark_type_text"]["watermark_text_language"] == "Persian") persian_log2vis($text);
                                                                    $arr_tx = explode("\n", $text);
                                                                    for ($i = 0; $i <count($arr_tx) ; $i++) { 
                                                                        $arr_tx[$i]=rtrim($arr_tx[$i]);
                                                                        $arr_tx[$i]=ltrim($arr_tx[$i]);
                                                                    }
                                                                    $base64 = $_POST["image"];
                                                                    $base64 = base64_decode(str_replace($master_image_type,"",$base64));
                                                                    $image_infos = getimagesizefromstring($base64);
                                                                    if($image_infos["mime"] == "image/jpeg") $image = imagecreatefromjpeg($_POST["image"]);
                                                                    elseif ($image_infos["mime"] == "image/png") $image = imagecreatefrompng($_POST["image"]);
                                                                    else die("<b>ERROR</b>: Unknown Error occured");
                                                                    $text_color = imagecolorallocate($image,intval($_POST["watermark_type_text"]["watermark_text_color_RGB"][0]),intval($_POST["watermark_type_text"]["watermark_text_color_RGB"][1]),intval($_POST["watermark_type_text"]["watermark_text_color_RGB"][2]));
                                                                    $font_size = intval($_POST["watermark_type_text"]["watermark_text_font_size"]);
                                                                    for ($i=0; $i <count($arr_tx) ; $i++) { 
                                                                        imagettftext($image,$font_size,$angle,(imagesx($image)*$x/100),(((imagesy($image)*$y)+($i*$font_size*130))/100),$text_color,$font,$arr_tx[$i]);
                                                                    }
                                                                    if ($_POST["response_type"] == "header") {
                                                                        header('content:image/jpeg');
                                                                        if($image_infos["mime"] == "image/jpeg") {header('content:image/jpeg'); imagejpeg($image);}
                                                                        elseif($image_infos["mime"] == "image/png") {header('content:image/png'); imagepng($image);}
                                                                        else die("<b>ERROR</b>: Unknown Error occured");
                                                                    }else {
                                                                        ob_start();
                                                                        if($image_infos["mime"] == "image/jpeg") imagejpeg($image);
                                                                        elseif($image_infos["mime"] == "image/png") imagepng($image);
                                                                        else die("<b>ERROR</b>: Unknown Error occured");
                                                                        $contents = ob_get_contents();
                                                                        ob_end_clean();
                                                                        echo "data:".$image_infos["mime"].";base64,".base64_encode($contents);
                                                                    }
                                                                    imagedestroy($image);
                                                                }else echo "<b>ERROR</b>: Invalid watermark_text_font_size.\n";
                                                            }else echo "<b>ERROR</b>: No watermark_text_font_size sent.\n";
                                                        }else echo "<b>ERROR</b>: Invalid watermark_text_color_RGB.(Must be like array( 0_to_255 , 0_to_255 , 0_to_255 ))\n";
                                                    }else echo "<b>ERROR</b>: No watermark_text_color_RGB sent.\n";
                                                }else echo "<b>ERROR</b>: Invalid watermark_text_language.(Must be 'English' or 'Persian')\n";
                                            }else echo "<b>ERROR</b>: No watermark_text_language sent.\n";
                                        }else echo "<b>ERROR</b>: Invalid watermark_text_y.(Must be in percent)\n";
                                    }else echo "<b>ERROR</b>: No watermark_text_y sent.\n";
                                }else echo "<b>ERROR</b>: Invalid watermark_text_x.(Must be in percent)\n";
                            }else echo "<b>ERROR</b>: No watermark_text_x sent.\n";
                        }else echo "<b>ERROR</b>: No watermark_text sent.\n";
                    }else echo "<b>ERROR</b>: No watermark_type sent.\n";
                }else echo "<b>ERROR</b>: Invalid response_type.\n";
            }else echo "<b>ERROR</b>: NO response_type sent.\n";
        }else echo "<b>ERROR</b>: Invalid base64 encode for image.\n";
    }else echo "<b>ERROR</b>: No image sent.\n";
}else echo "<b>ERROR</b>: No post request sent.\n";
// =========================functions==============================
function persian_log2vis(&$str)
{
    include_once('bidi.php');
    $bidi = new bidi();
    $text = explode("\n", $str);
    $str = array();
    foreach($text as $line){
        $chars = $bidi->utf8Bidi($bidi->UTF8StringToArray($line), 'AL');
        $line = '';
        foreach($chars as $char){
            $line .= $bidi->unichr($char);
        }
        $str[] = $line;
    }
    $str = implode("\n", $str);
}
// =============================================================
// Script by TadavomnisT
?>
