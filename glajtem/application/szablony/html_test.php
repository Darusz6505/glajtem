<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* szablon dla : glajtem.pl / .eu
* 2013-09-09
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -- 2009-03-30 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/


$strona = '
'.$this->polahtml['mein'].'

<div id=\'down_start\' class=\'menu width\'>
 <ul >'.S::menu($this->m['m1']).'
 </ul>
 <ul >'.S::menu($this->m['m2']).'
 </ul>
</div>

<div id=\'stopka\' class=\'width\'>
 <div class=\'footer\'>'.$stopka.'
 </div>
 <div id=\'smartfon\'></div>
</div>';

?>
