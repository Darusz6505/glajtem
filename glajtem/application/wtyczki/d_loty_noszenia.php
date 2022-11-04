<?
defined('_APATH')or header('HTTP/1.1 404 File Not Found');


/**
*
* wtyczka dedykowana -> wyświetla noszenia na podstawie pliko kominy.js
*
* 2016-03-13
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2011-02-05 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*/


/*
$r = new Igcp($l['nazw'], $l['stro']);
$r->igc_test();
$wtyk = $r->wynik();
unset($r); */


C::add('javascript_down', '
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<script src="//maps.googleapis.com/maps/api/js?v=3.exp&signed_in=false"></script>
   <script type="text/javascript" src="js/map.js"></script>
	<script type="text/javascript" src="noszenia/kominy.js"></script>');


$wtyk = '
<div id="map" style=""></div>';

?>