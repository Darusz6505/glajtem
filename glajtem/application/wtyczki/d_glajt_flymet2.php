<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* wtyczka dedykowana dla glajt.pl : pogoda z flymet ala Faflik
*
* 2015-07-22
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2009-11-11 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora

*
*/

 C::add('jsEndEnd', '
    <script type="text/javascript" src="./application/js/flymet2.js"></script>');

$r = new Glajtem;

$r->flymet2();
$wtyk = $r->wynik();

unset($r);

?>