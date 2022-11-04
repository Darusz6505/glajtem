<?
defined('_APATH')or header('HTTP/1.1 404 File Not Found');


/**
*
* wtyczka uniwersalna-> publikacja tematu i zawartości
*
* 2013-05-29
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2011-02-05 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*/

$r = new Publikacje($l['nazw'], $l['stro'], C::get('tab_fotb'));

//$r->noBackLink = true;			//-wyłancza odnośnik powrotu
//$r->noLoadFiles = true;  		//-wyłancza link dodawania plików = zdjęć :: tylko dla metody "publikacja"

$r->noInfo   = true;

$r->prefTumbs = 'g';					//-prefix dla miniatur ilustracyjnych bloki treści

$r->tytul = 'h3';
$r->publikacja();

$wtyk = $r->wynik();

unset($r);
?>
