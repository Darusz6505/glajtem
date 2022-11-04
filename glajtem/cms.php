<?
/**
*
* skrypt startowy serwisu dla CMS'a - v.1.1
*
* 2011-05-04 -> 2011-10-11 -> 2013-02-221
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2009-03-30 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
* 1. ustalenie ścieżek dostepu do katalogów
* 2. wywołanie klasy startowej
*
*/

 require_once 'hidden/start.php';

 Test::trace();

 $r = new CmsStart();

 unset($r);
?>

