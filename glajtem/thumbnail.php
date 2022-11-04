<?

/**
*
* wyświetlanie miniatur zdjęć skalowanych w locie :: v.2.1
*
* 2015-05-17 : inspekcja i drobne poprawki
* 2013-04-12 : poprawki ustawienia statusu admina
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2013-04-12 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/

/*
*
* wyświetla zdjecia bezpośrednio po załadowaniu
* UWAGA!
* nie może być żadnych komunikatów za pomocą echo !!! bo heder()
* nie może też być żadnych komunikatów kompilatora czyli musi być :: error_reporting(E_ALL & ~E_NOTICE);
*

* Uwaga !! testowanie za pomocą pliku w logs/ xxxxx.txt

*/

error_reporting(E_ALL & ~E_NOTICE);

function errorlog($t, $name = './logs/thumbnail_error')
{

 if($t)
 {
  foreach($t as $wart)
   $tt .= "\n\r".$wart;
 }
 $t = $tt;
 unset($tt);

 $z = '';

 if(is_array($t)) $t = implode("\n::", $t);

 $t = $z."\n\r".date("Y-m-d H:i:s", time()).' | '.$t."\n\r";

 $h = fopen($name.'.txt', 'w');

 fputs($h, $t);

 fclose($h);

 unset($h, $t, $z);
}

$error = array();

$id = isset($_GET['id']) ? $_GET['id'] : false;

$error[] = __FILE__.'-> line: '.__LINE__.' = id = '.$id;

//$te = substr($id, -4);

if(substr($id, -4) === '.jpg')		//- dla prostego przypisania nazwy pliku to jeśli jest to jpg
 $fileName = $id;
else							//- w przeciwnym razie dekodowanie i analiza parametru id
{

 list($fileName, $sesion_id, $x, $y, $pk, $pt, $pat) = explode(':', $id);

 $error[] = '$sesion_id = '.$sesion_id;

 session_id($sesion_id);
 session_start();

 if(!isset($_SESSION['admin']['status'])) $_SESSION['admin']['status'] = false;

 $error[] = 'admin status = '.$_SESSION['admin']['status'];


 if($_SESSION['admin']['status'] < 9) 								//- ograniczenie tylko dla administratora
 {
  $error[] = ' - Nie masz uprawnień do wykonania tej akcji!';

  errorlog($error);
  exit('Nie masz uprawnień do wykonania tej akcji!');
 }
}

$error[] = ' $fileName = '.$fileName;

if($fileName)
{

 $ex = explode('/', $fileName);
 $error[] = ' $ex = '.$ex[0].' -> '.$ex[1].' -> '.count($ex);

 $target_width = 200;
 $target_height = 200;

 $path = false;

 require_once $pp.'./hidden/start.php';
 require_once $pp.'./hidden/config_def'._EX;							//-koniecznie tu, przed sprawdzeniem sid[2]

 $target_width = $c['thubs_X'];
 $target_height = $c['thubs_Y'];

 if(!$pat)
  $path = $pp.$c['tmpPath_foty'];
 else
  $path = $pp.$c['fotyPath'];

 unset($c);

 if(isset($x)) $target_width = $x;
 if(isset($y)) $target_height = $y;

 if(count($ex) > 1)
 {
  $path = $ex[0].'/';

  $fileName = $ex[1];

  $target_width = 100;
  $target_height = 100;

  $pk = 'k';
 }

 $fileName = $path.$fileName;

 $error[] = 'fileName = '.$fileName;

 if(!file_exists($fileName))
 {
  $error[] = 'Nie można utworzyć uchwytu dla pliku : ' . $fileName;
 }


 $img = imagecreatefromjpeg($fileName);

 if(!$img) $error[] = 'Nie można utworzyć uchwytu dla pliku : ' . $fileName;

 $width = imageSX($img);
 $height = imageSY($img);

 if(!$width || !$height)
 {
  $error[] = 'Nieprawidłowe rozmiary wysokość lub szerokość!';
 }

 // Build the thumbnail

 $target_ratio = $target_width / $target_height;

 $img_ratio = $width / $height;

 if($target_ratio > $img_ratio)
 {
  $new_height = $target_height;
  $new_width = $img_ratio * $target_height;
 }
 else
 {
  $new_height = $target_width / $img_ratio;
  $new_width = $target_width;
 }

 if ($new_height > $target_height) $new_height = $target_height;

 if ($new_width > $target_width) $new_height = $target_width;



 if($pk != 'k')
 {

  //$error[] = 'Nie K';

  $new_img = ImageCreateTrueColor($target_width, $target_height);

  if(!@imagefilledrectangle($new_img, 0, 0, $target_width-1, $target_height-1, 0))
  {
   $error[] = 'Could not fill new image!';
  }

  if(!@imagecopyresampled($new_img, $img, ($target_width-$new_width)/2, ($target_height-$new_height)/2, 0, 0, $new_width, $new_height, $width, $height))
  {
   $error[] = 'Nie można przeskalować pliku!';
  }

 }
 else
 {

  //$error[] = 'Dla k ';

  $new_img = ImageCreateTrueColor($new_width , $new_height );

  if(!@imagecopyresampled($new_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height))
  {
   $error[] = 'Nie można przeskalować pliku!';
  }

 }

 unset($img, $target_width, $new_width, $target_height, $new_height, $width, $height, $target_ratio);

 //ob_end_clean();

 ob_start();
 imagejpeg($new_img);
 $imagevariable = ob_get_contents();
 ob_end_clean();

 imagedestroy($new_img);										// zwolnienie pamięci
 unset($new_img);													// usunięcie zmiennych


 header('Content-type: image/jpeg');
 header('Content-Length: '.strlen($imagevariable));
 echo $imagevariable;

 unset($imagevariable, $new_img);

 if($error) errorlog($error);
 exit(0);

}
else
{

 $error[] = 'NO IMAGE ID';

 if($error) errorlog($error);
 exit(0);
}

?>