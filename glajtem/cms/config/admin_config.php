<?
defined('_CMSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* menu dla Admina
*
* 2013-02-22 -> menu dodatkowe dla danej aplikacji  ??
*
*
*
*/

 	 $m = array(
 		'wyloguj.cmsl'  =>'LOGOUT;wylogowanie ze strony administratora;0;0;1',
		'cms.cmsl'      =>'TABELE;Menu Content Management<br />zarządzanie tabelami;0;0;0',
		$l[0]           =>'FRONT SIDE;Benutzersicht<br />przejcie do serwisu w widoku użytkownika;0;0;0'
		);

		/*
		$m = array(
 		'wyloguj.cmsl'  =>'LOGOUT;wylogowanie ze strony administratora;0;0;1',
		'help.cmsl'		 =>'HELP;instrukcje do CMS-a;0;0;1',
		'stop.php'      =>'ERROR PAGE;Fehlerseite<br />podgląd strony na wypadek awarji;0;0;0',
		'cms.cmsl'      =>'TABELE;Menu Content Management<br />zarządzanie tabelami;0;0;0',
		'sew,mysql.cmsl'=>'SAVE TABLES;Tabellen speichren<br />archiwizacja bazy danych, zapisanie tabel do plików;0;0;1',
		'low,mysql.cmsl'=>'LOAD TABLES;Belastungstabellen<br />odtworzenie bazy danych z archiwum;0;0;1',
		'od,mysql.cmsl' =>'REFRESH TABLE;Tabele aktualisieren<br />odświeżenie tabeli systemowej;0;0;0',
		$l[0]           =>'FRONT SIDE;Benutzersicht<br />przejcie do serwisu w widoku użytkownika;0;0;0'
		); */


	if(file_exists('./application/cms/cms_add_menu'._EX)) include_once './application/cms/cms_add_menu'._EX;

?>