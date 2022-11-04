<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* wtyczka dedykowana dla glajtem.pl -> instrukcje szybowców
*
* 2019-03-10
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2009-11-11 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*
*/

$r = new Testy2('tab_test3');

$r->test();

$wtyk = $r->wynik();

unset($r);

?>