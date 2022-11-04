<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* wtyczka dedykowana dla glajtem.pl -> testy egzaminacyjne
*
* 2018-02-14
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2009-11-11 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*
*/

$r = new Testy2('tab_test2');

$r->test();

$wtyk = $r->wynik();

unset($r);

?>