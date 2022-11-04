<?
defined('_CMSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* menu dla VIP'a :: v.1.1
*
* 2013-02-05
* 2012-02-01 :: dodano menu dodatkowe zmiany w klasie Admin.php
*
*
*
*/

   $m = array
	(
	 'wyloguj.cmsl'    =>	'LOGOUT;wylogowanie ze strony administratora;0;0;1',
	 'doc.cmsl'		    =>	'DOC;dokumentacja techniczna do CMS-a;0;0;1',
	 'stop.php'        =>	'ERROR PAGE;Fehlerseite<br />podgląd strony na wypadek awarji;0;0;0',
	 'cms.cmsl'        =>	'TABELE;Menu Content Management<br />zarządzanie tabelami;0;0;0',
	 'mysql.cmsl'      =>	'DATABASE;Datenbank<br />zarządzanie bazą danych, operacje na tabelach;0;0;0',
	 'sew,mysql.cmsl'  =>	'SAVE TABLES;Tabellen speichren<br />archiwizacja bazy danych, zapisanie tabel do plików;0;0;1',
	 'low,mysql.cmsl'  =>	'LOAD TABLES;Belastungstabellen<br />odtworzenie bazy danych z archiwum;0;0;1',
	 'od,mysql.cmsl'   =>	'REFRESH TABLE;Tabele sktualisieren<br />odświeżenie tabeli systemowej;0;0;0',
	 $l[0]             =>	'FRONT SIDE;Benutzersicht<br />przejcie do serwisu w widoku użytkownika;0;0;0'
	);

	//tylko menu podstawowe, menu dodatkowe w application/cms

?>
