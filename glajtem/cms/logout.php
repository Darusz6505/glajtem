<?
defined('_CMSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* wylogowanie z CMS'a
* 2013-02-22 : asynchronicznie ładowana rekalma + poprawki kodu
*
* 2012-12-30 : poprawki dla reklam @
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2010-12-25 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*/


 $_SESSION = array();																	//-Uwaga: to usunie sesję, nie tylko dane sesji
 $_COOKIE = array();

 //-Jeśli pożądane jest zabicie sesji, usuń także ciasteczko sesyjne

 $time = time()-42000;

 if(isset($_COOKIE[session_name()])) setcookie(session_name(), '', $time);

 setcookie(_CIA_ADM, '', $time);													//-usunięcie ciacha dla admin
 setcookie(_CIA_VIP, '', $time);													//-usunięcie ciacha dla vipa

 session_destroy(); 																	//-na koniec zniszcz sesję

 unset($time);


 echo '<?xml version=\'1.0\' encoding=\'ISO-8859-2\'?>
<!DOCTYPE html PUBLIC \'-//W3C//DTD XHTML 1.0 Strict//EN\' \'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\'>
<html xmlns=\'http://www.w3.org/1999/xhtml\' lang=\'pl\' xml:lang=\'pl\'>
<head>
 <meta http-equiv=\'content-type\' content=\'text/html; charset=UTF-8\' />
 <title>'._CMS_TYTUL.'</title>
 <script type="text/javascript" src="./js/jq.js"></script>
 <script type="text/javascript">var $j = jQuery.noConflict(); </script>

 <meta http-equiv=\'Reply-to" content=\''._CMS_REPLY_TO.'\' />
 <meta http-equiv=\'Content-Language\' content=\'pl\' />
 <meta name=\'Author\' content="'._CMS_OUT_CONTENT.'" />
 <meta name=\'Robots\' content=\'none\' />
 <link type=\'text/css\' rel=\'stylesheet\' href=\'cms/styl/login.css\' />
 <link rel=\'shortcut icon\' href=\'favicon.ico\' />
</head>
<body>
 <p class=\'out\'>Zostałeś wylogowany z serwisu : <b>'.$_SERVER['HTTP_HOST'].'</b><br />You\'ve been logged out.<br />Sie sind abgemeldet.</p>
 <a class=\'aaut\' href=\'_Admin\'>zaloguj się ponownie do CMS<br />Log in again to CMS<br />Bitte loggen Sie sich erneut zu CMS</a> |
 <a class=\'aaut\' href=\'start.html\'>przejdź do strony startowej serwisu<br/>go to start www<br />Gehe zur Startseite</a>'._CMS_STOPKA.'
<div id=\'reklamad\'></div>
</body>
</html>
<script type="text/javascript" src="./js/jq.reklama_logout.js"></script>';

exit('THE END');
?>
