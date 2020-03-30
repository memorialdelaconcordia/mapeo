
<?php
function createThumbsFromArray( $pathToImages, $pathToThumbs, $thumbWidth, $filename) {

    $fname=$filename;
    $info = pathinfo($pathToImages . $fname);
    // continue only if this is a JPEG image    
    $img=imageCreateFromAny("{$pathToImages}{$fname}");
    $width = imagesx( $img );
    $height = imagesy( $img );
    if($width>=$thumbWidth){
      $new_width = $thumbWidth;
      $new_height = floor( $height * ( $thumbWidth / $width ) );
    }
    else{
      $new_width=$width;
      $new_height=$height;
    }
    $tmp_img = imagecreatetruecolor( $new_width, $new_height );
    imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
    if ( strtolower($info['extension']) == 'jpg' || strtolower($info['extension']) == 'jpeg' ){
      imagejpeg( $tmp_img, "{$pathToThumbs}{$fname}" );
    }
    elseif (strtolower($info['extension']) == 'png') {
      imagepng( $tmp_img, "{$pathToThumbs}{$fname}" );
    }
    imagedestroy($tmp_img);
    imagedestroy($img);
}

function imageCreateFromAny($filepath) {
  $type = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize()
  $allowedTypes = array(
    1,  // [] gif
    2,  // [] jpg
    3,  // [] png
    6   // [] bmp
  );
  if (!in_array($type, $allowedTypes)) {
    return false;
  }
  switch ($type) {
    case 1 :
    $im = imageCreateFromGif($filepath);
    break;
      case 2 :
        $im = imageCreateFromJpeg($filepath);
        break;
      case 3 :
        $im = imageCreateFromPng($filepath);
        break;
      case 6 :
        $im = imageCreateFromBmp($filepath);
        break;
  }   
  return $im; 
} 
?>