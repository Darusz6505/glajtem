<?
defined('_APATH')or header('HTTP/1.1 404 File Not Found');


/**
*
* wtyczka dedykowana -> generuje plik z noszeniami, a w trybie admina dodatkowo wyświetla loty
*
* 2016-03-13
* 2016-01-04
* 2015-12-10
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2011-02-05 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*/


$r = new Igcp($l['nazw'], $l['stro']);


$r->igc_noszenia();

$wtyk = $r->wynik();

unset($r);
?>