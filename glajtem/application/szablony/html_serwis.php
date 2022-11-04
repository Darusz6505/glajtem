<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
* szablon strony dla komunikatów ( ErrorAdmin -> dla Admina i Dla użytkownika) + zarządzanie reklamami
*
* 2013-02-16
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -- 2009-03-30 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/

$strona = '
<div id=\'logo_tlo2\'></div>

<div id=\'strona_serwis\'>
 <div id=\'gora\'>
  <b id=\'lg\'></b><b id=\'pg\'></b>
 </div>

 <div id=\'rotgryf_info\'>
  <img src=\'./application/skin/zaklad.gif\' alt=\'zakład poligraficzny ROTGRYF\' />
 </div>

 <div id=\'logo_tlo\'>
  <img src=\'./application/skin/panorama.jpg\' alt=\'zakład poligraficzny Rotgryf w Świdnicy\' />
  <img id=\'tukan1\' src=\'./application/skin/tukan1.jpg\' alt=\'kompleksowa obsługa poligraficzna\' />
 </div>

 <div id=\'rotgryf_serwis\'>
  <a id=\'start\' href=\'start.html\'><img id=\'logo\' src=\'./application/skin/logo.jpg\' alt=\'Rotgryf Logo\' /></a>

 <div id=\'main_serwis\'>'.$this->polahtml['mein'].'
 </div>

 <div style=\'clear:both; height: 1px;\'>&nbsp;</div>


 <ul id=\'menu2\'>'.S::menu($this->m['m2'], false, 1).'</ul>
 </div>

 <div id=\'dol\'>
  <b id=\'ld\'></b><b id=\'pd\'></b>
 </div>
 <div id=\'stopka\'>'.$stopka.'</div>
</div>';

?>
