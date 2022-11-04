<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* wtyczka dedykowana dla glajtem.pl : obsługa plików IGC
*
* 2014-05-29
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2014-05-29 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/

$r = new GlajtemLoty;


$r->loty();

$r->track();

$wtyk = $r->wynik();


unset($r);

?>
