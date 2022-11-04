<?

/*
@ 0 skrypt wyświetla miniaturkę wskazanego zdjecia .jpg
* skrypt z ograniczeneim tylko dla Admina
*
* id = nazwaPliku_identyfikatorSesji_rozmiarX_rozmiar_Y
*
*
*/


$image_id = isset($_GET['id']) ? $_GET['id'] : false;


$image_id = explode('-', $image_id);

if(count($image_id) == 2 || count($image_id) == 6 )
{
 session_id($image_id[1]);

 session_start();

 //if($_SESSION['admin']['status'] < 1  && $_SESSION['us_zalog']['stat'] < 1)  exit(0); 							// dostęp tylko dla administratora

 require_once '../hidden/config_def.php';

 $fileName = '../'.$c['tmpPath_foty'] . $image_id[0]; 			// . '.jpg';


 //echo $fileName;

 $img = imagecreatefromjpeg($fileName);

 if(!$img)
 exit('ERROR img');


 $width = imageSX($img);
 $height = imageSY($img);


 if(!$width || !$height)
  exit('Invalid width or height');

 if($image_id[2] && $image_id[3])
 {
  $target_width = $image_id[2];
  $target_height = $image_id[3];
 }
 else
 {
  $target_width = 200;
  $target_height = 200;
 }

 $target_ratio = $target_width / $target_height;

 $img_ratio = $width / $height;

 if ($target_ratio > $img_ratio)
 {
  $new_height = $target_height;
  $new_width = $img_ratio * $target_height;
 }
 else
 {
  $new_height = $target_width / $img_ratio;
  $new_width = $target_width;
 }

 if($new_height > $target_height)
  $new_height = $target_height;

 if($new_width > $target_width)
  $new_height = $target_width;


 if($image_id[4] != 'k')
 {
  $new_img = ImageCreateTrueColor($target_width , $target_width );

  if (!@imagecopyresampled($new_img, $img, ($target_width-$new_width)/2, ($target_height-$new_height)/2, 0, 0, $new_width, $new_height, $width, $height))
  {
   //$this->error .= 'Could not resize image';
   //echo "ERROR: \n" . $this->error;

   echo "ERROR:Could not resize image";
   exit(0);
  }

 }
 else
 {
  $new_img = ImageCreateTrueColor($new_width , $new_height );

  if (!@imagecopyresampled($new_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height))
  {
   //$this->error .= 'Could not resize image';
   //echo "ERROR: \n" . $this->error;

   echo "ERROR:Could not resize image";
   exit(0);
  }
 }



 unset($img, $target_width, $target_height, $new_width, $new_height, $width, $height, $target_ratio);

 //-bez tego pojawiają się błedy, które powodują brak wyświetlania się fotek
 ob_start();
 imagejpeg($new_img);
 $imagevariable .= ob_get_contents();
 ob_end_clean();


 header('Content-type: image/jpeg');
 header('Content-Length: '.strlen($imagevariable));
 echo $imagevariable;

 unset($imagevariable, $new_img);

 exit(0);

}
else
 exit(0);
?>
