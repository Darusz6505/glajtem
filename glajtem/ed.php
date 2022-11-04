<?
/*
* skrypt wyświetla znak małpy w adresie e-mail jako grafikę
*
* przykład: preg_replace('/@/', '<img src="./ed.php" alt=\'(ed)\'>', $email)
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2011-12-27 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/

switch($_GET['ed'])
{
 case '1': $f = fopen('./skin/malpa.gif', 'r'); break;

 case '2': $f = fopen('./skin/malpa.png', 'r'); break;

 default: $f = fopen('./skin/malpa.jpg', 'r');
}

while(!feof($f)) $dane .= fread($f,1024);
fclose($f);

unset($f);

header('Content-type: image/jpg');

echo base64_decode(chunk_split(base64_encode($dane)));

?>
