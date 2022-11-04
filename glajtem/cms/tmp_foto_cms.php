<?

/**
*
* dodano obsługę plików gif 2012-02-07
*
*
* skrypt wyświetla miniaturkę wskazanego zdjecia jpg i gif
* skrypt z ograniczeneim tylko dla Admina
*
* id = nazwaPliku_identyfikatorSesji_rozmiarX_rozmiar_Y
*
*
*/

$image_id = isset($_GET['id']) ? $_GET['id'] : false;


if($image_id) $image_id = explode('-', $image_id);

if(count($image_id) == 2 || count($image_id) == 4)
{
 session_id($image_id[1]);

 session_start();

 if($_SESSION['admin']['status'] < 1  && $_SESSION['us_zalog']['stat'] < 1)
 {
  echo('dostęp tylko dla Admina!');
  exit(0); // dostęp tylko dla administratora

 }


 require_once '../hidden/config_def.php';

 $fileName = '../'.$c['fotyPath'] . $image_id[0]; // . '.jpg';

 //echo $fileName;

 $typ = substr($image_id[0], -3);

 switch($typ)
 {
  case 'gif':
	$img = imagecreatefromgif($fileName);

  break;


  default:
   $img = imagecreatefromjpeg($fileName);
 }

 if(!$img) exit('ERROR img');


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
  $target_width = $c['thubs_X'];
  $target_height = $c['thubs_Y'];
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

 $new_img = ImageCreateTrueColor($target_width , $target_width );

 if (!@imagecopyresampled($new_img, $img, ($target_width-$new_width)/2, ($target_height-$new_height)/2, 0, 0, $new_width, $new_height, $width, $height))
 {
  //$this->error .= 'Could not resize image';
  //echo "ERROR: \n" . $this->error;

  echo "ERROR:Could not resize image";
  exit(0);
 }

 unset($img, $target_width, $target_height, $new_width, $new_height, $width, $height, $target_ratio);

 //-bez tego pojawiają się błedy, któro powodują brak wyświetlania się fotek
 ob_start();
 imagejpeg($new_img);
 $imagevariable .= ob_get_contents();
 ob_end_clean();


 switch($typ)
 {
  case 'gif':
	header('Content-type: image/gif');
  break;

  default:
   header('Content-type: image/jpeg');
 }

 unset($t);

 header('Content-Length: '.strlen($imagevariable));
 echo $imagevariable;

 unset($imagevariable, $new_img);

 exit(0);

}
else
 exit(0);
?>