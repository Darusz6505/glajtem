<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* szablon dla : glajtem.pl / .eu
*
* 2015-07-24
* 2015-01-06
* 2014-09-21
* 2013-09-09
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -- 2009-03-30 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/

/*
<!--<iframe src="http://streaming.airmax.pl/dzikowiec2/index.m3u8" width=600 height=470></iframe>  -->


<!-- onclick="window.open("http://www.dzikowiec.info/o-dzikowcu/245-kamera-gorna-android.html?tmpl=component&amp;print=1&amp;page=","win2","status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no"); return false;" rel="nofollow" -->
*/



$strona = '
<div id=\'fb_left\' class=\'fb\'>
 <div class=\'fbook\'>
  <div class=\'fb_inside\'>
   <div id="fanpage_fb" rel="403x500"></div>
  </div>
 </div>
</div>

<div id=\'menu\' class=\'menu width\' >
 <ul >'.S::menu($this->m['m1'], 0, 0, 1).'
 </ul>
</div>


<h1 id="cam_start"> Proszę czekać na rozpoczęcie transmisji...</h1>

<iframe src="http://streaming.airmax.pl/dzikowiec2/index.m3u8" width=600 height=470></iframe>

<div id=\'down_start\' class=\'menu width\'>
 <ul >'.S::menu($this->m['m2']).'
 </ul>
</div>

<div id=\'stopka_start\' class=\'width\'>
 <div class=\'footer\'>'.$stopka.'
 </div>
 <div id=\'smartfon\'></div>
</div>

';


?>