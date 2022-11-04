<?
/**
@ skrypt startowy serwisu dla CMS'a - v.1.0
*
* 2011-10-13
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2009-03-30 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
* 1. ustalenie ścieżek dostepu do katalogów
* 2. wywołanie klasy startowej
*/

session_start();

require_once 'hidden/start.php';
Test::tracer(__FILE__, __METHOD__, __FUNCTION__, __LINE__, 'trace');

if(!$_SESSION['admin_zalog'])																	//-jeśli jest zalogowany to do cms.php
{

 $r = new LogAdmin();
 unset($r);
}
else //-zmienione 2013-04-16
{
 $r = new CmsStart();
 unset($r);
}

//-zmienione 2013-04-16
// header('location: cms'._EX);																	//-jeśli zalogowany do do cms'a

exit('END');
?>

