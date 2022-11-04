<?
defined('_APATH')or header('HTTP/1.1 404 File Not Found');


/**
*
* 2016-03-13 -> menu z katalogów we wskazanym katalogu głównym
*
* 2016-01-03
* 2015-12-10
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2011-02-05 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*/


$r = new Igcpm($l['nazw'], $l['stro']);


$r->igc_testm();

$wtyk = $r->wynik();

unset($r);

?>