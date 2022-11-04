<?
defined('_CMSPATH') or header('location: http://'.$_SERVER['HTTP_HOST']);

/*
@ wtyczka -> wywołanie klasy głównej klasy CMS'a
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -- 2008-09-12-- UTF-8
* skrypt nie jest darmowy!!
* aby legalnie wykorzystywać skrypt należy posiadać wykupioną licencję lub sgodę autora
*
*/

Test::tracer(__FILE__, __METHOD__, __FUNCTION__, __LINE__, 'trace');

$r = new Cms();

list($fx, $fy, $fz) = $r->wynik();

unset($r);
?>
