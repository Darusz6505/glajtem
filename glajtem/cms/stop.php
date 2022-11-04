<?
// UNIWERSALNA STRONA AWARYJNA -- 2010-12-03 -> 2013-02-21 -> 2013-02-22

// autorem skryptu jest
// projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2010-03-14 --- UTF-8
// skrypt nie jest darmowy!
// legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora


echo '<?xml version=\'1.0\' encoding=\'ISO-8859-2\'?>
<!DOCTYPE html PUBLIC \'-//W3C//DTD XHTML 1.0 Strict//EN\' \'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\'>
<html xmlns=\'http://www.w3.org/1999/xhtml\' lang=\'pl\' xml:lang=\'pl\'>
<head>
 <meta http-equiv=\'content-type\' content=\'text/html; charset=UTF-8\' />
 <title>STOP :: '.$_SERVER['HTTP_HOST'].'</title>

 <script type="text/javascript" src="../js/jq.js"></script>
 <script type="text/javascript">var $j = jQuery.noConflict(); </script>

 <meta http-equiv=\'Reply-to" content=\'admin@aleproste.pl\' />
 <meta http-equiv=\'Content-Language\' content=\'pl\' />
 <meta name=\'Author\' content="strony i serwisy www, projekt.etvn.pl i aleproste.pl" />
 <meta name=\'Robots\' content=\'none\' />
 <link type=\'text/css\' rel=\'stylesheet\' href=\'styl/stop.css\' />
 <link rel=\'shortcut icon\' href=\'favicon.ico\' />
</head>
<body>
<p class=\'komunikat\'>Serwis - '.$_SERVER['HTTP_HOST'].' - czasowo niedostępny<br />Zapraszamy z chwilę!</p>

<img src=\'skin/stop.gif\' alt=\'serwis czasowo niedostępny\'>

<a id=\'stop_test\' href=\'../index.php\'>sprawdź dostępność serwisu</a>
<p id=\'stop_stopka\'><a href=\'http://projekt.etvn.pl\'>projekt.etvn.pl</a> &amp <a href=\'http://aleproste.pl\'>aleproste.pl</a></p>
<div id=\'reklamad\'></div>
</body>
</html>
<script type="text/javascript" src="../js/jq.reklama_stop.js"></script>';

exit('THE END');
?>
