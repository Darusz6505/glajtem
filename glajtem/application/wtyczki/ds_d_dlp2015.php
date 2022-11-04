<?
defined('_APATH')or header('HTTP/1.1 404 File Not Found');


/**
*
* wtyczka dedykowana
*
* 2015-03-01
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2011-02-05 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*/



$r = new Dlp2015();

$r->dlp_month();

$wtyk = $r->wynik();

unset($r);
?>
