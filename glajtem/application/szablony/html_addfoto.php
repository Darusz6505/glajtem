<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
* szablon dla ładowania zdjęć : dla glajtem.pl
*
* 2013-09-21
*
* menu zalogowanego user'a :: 2012-12-04
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -- 2009-03-30 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/

/*

if($_SESSION['us_zalog']) $menu_user = '
<ul id=\'menu_user\'>'.S::menu($this->m['m3']).'</ul>';

*/

//

$strona = '
<div id=\'menu\' class=\'menu width\' >
 <ul >'.S::menu(array('start.html' => 'start')).'
 </ul>
</div>

 <div id=\'addfoto\'>
  '.$this->polahtml['mein'].'
 </div>

<div id=\'stopka\'>
 <div class=\'footer\'>'.$stopka.'
 </div>
</div>';

?>
