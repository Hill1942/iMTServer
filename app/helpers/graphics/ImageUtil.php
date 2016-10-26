<?php
/**
 * Created by PhpStorm.
 * User: kaidi
 * Date: 2/10/15
 * Time: 7:23 PM
 */

namespace helpers\graphics;


class ImageUtil {
    public static function isometricScale($path, $scale, $width, $height) {
        $image = imagecreatefromjpeg($path);
        list($img_w, $img_h) = getimagesize($path);
        
        if ($scale != 0) {
            $new_scale = $scale;
        } else if (isset($width) && $width != 0) {
            $new_scale = $width / $img_w;
        } else if (isset($height) && $height != 0) {
            $new_scale = $height / $img_h;
        } else {
            $new_scale = 1;
        }

        $new_width  = $img_w  * $new_scale;
        $new_height = $img_h  * $new_scale;

        $new_image  = imagecreatetruecolor($new_width, $new_height);
        
        imagecopyresampled($new_image, $image, 0, 0, 0, 0, 
            $new_width, $new_height, $img_w, $img_h);

        return $new_image;
    }
}