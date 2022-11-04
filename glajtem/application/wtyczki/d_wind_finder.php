<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* wtyczka dedykowana dla glajt.pl : pogoda z ParaglidingMap.com
*
* 2013-09-10
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2009-11-11 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora

*
*/

$r = new Glajtem;

$r->pogoda_wf2();

$wtyk = $r->wynik();

unset($r);

?>
