<?
defined('_CONPATH') or header('HTTP/1.1 404 File Not Found');

/**
* test
* 2018-08-07
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2010-11-11 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/

define('_1MB', 1048576); 									//-dla plików
define('_ZM_KOD', 'vyfhd43-glajtem-34dsd4e');		//-kod dla serwisu

define('_DEBUG', _ZM_KOD);									//-włącza testy

define('_ZONE', 'Europe/Warsaw');						//-strefa czasowa



$c['set_ja'] = 1;
$c['set_jo'] = 1;

$c['ip_vip'] =  '';					 		//-> tylko do testów !!
$c['ip_adm'] =  '';					 		//-> tylko do testów !!
$c['ip_test'] = '1';					 		//-> tylko do testów !! lokalnie wystarczy dowolna wartość, w sieci musi być ip

$c['doz_tab'] = array('glajtem_03_piloci', 'glajtem_04_loty_dpl');



$c['set_remote'] = 0;										//-wart=1 wymusza podpięcie lokalnego serwisu na zewnętrzną bazę MySQL

$c['adres_strony'] = 'glajtem.pl';


date_default_timezone_set(_ZONE);

$c['datetime_teraz'] = date('Y-m-d H:i:s', time());


define('_KOMUNIKAT', 'komunikat');
?>