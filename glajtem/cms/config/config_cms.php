<?
defined('_CMSPATH') or header('HTTP/1.1 404 File Not Found');

/**
* konfiguracja ogólna dla CMS'a
*
* 2013-01-03
*
*
*/

define('_CMS_TYTUL', 'CMS BY ALEPROSTE.PL'); 		//-do head cms_end.php

define('_CMS_HEADER', '&copy; 2008-2013 CMS BY PROJEKT.ETVN.PL & ALEPROSTE.PL ver. 2013.01 :: zalogowany-');

define('_CMS_REPLY_TO', 'admin(+)aleproste.pl'); 	//-do head cms_end.php

define('_CMS_OUT_CONTENT', '');							//-do head cms_end.php

define('_CMS_LIMIT', '
<p>Przekroczony limit czasu bezczynności!</p>');

define('_CMS_STOPKA', '
<p>&copy; 2009-2012 CMS designed by <a href="http://projekt.etvn.pl">projekt.etvn.pl</a> &amp; <a href="http://aleproste.pl">aleproste.pl</a></p>');

define('_BRAK_DOSTEMPU', '
<p class=\'out\'>Nie masz uprawnień do zarządzania stroną!</p>');


define('_PATH_ARCH', 'arch/');					//-położenie archiwum serwisu
define('_PATH_ARCH_FOTO', 'arch_foto/');		//-położenie archiwum serwisu

define('_LIMIT_ARCH', 5);							//-limit ilości punktów archiwizacji

$o['edycja'] = '::cms -> edycja'; 				//-nagłówek dla cms/inc/edycja.php  ????????????????????

?>
