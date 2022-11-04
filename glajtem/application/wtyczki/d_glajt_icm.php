<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* wtyczka dedykowana dla glajt.pl : widok satelitarny z sat24.com
*
* 2013-09-13
*
* autorem skryptu jest
* glajtem.pl Dariusz Golczewski -------- 2009-11-11 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/

$r = new Glajtem;

$r->pogodaIcm();
$wtyk = $r->wynik();

/*
$r->sat24();
$wtyk .= $r->wynik(); */

unset($r);

?>