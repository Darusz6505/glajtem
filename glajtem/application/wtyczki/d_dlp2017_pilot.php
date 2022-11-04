<?
defined('_APATH')or header('HTTP/1.1 404 File Not Found');


/**
*
* wtyczka dedykowana dla glajtem.pl
* liczba lotów wybranego pilota
*
* 2017-10-02
* 2017-01-21
*
* autorem skryptu jest
* aleproste.pl Dariusz Golczewski -------- 2011-02-05 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*/


$r = new Dlp2017();

$r->dlp_pilot();

list($wtyk, $rok, $id) = $r->wynik();

unset($r);

if($id)
{
 $r = new Naloty(1);

 $wtyk .= $r->nalot($id);
}
?>