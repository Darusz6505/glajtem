<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* szablon dla : glajtem.pl / .eu
*
* 2018-09-13
* 2018-01-29
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
if(!C::get('localhost'))
{
    define('SEOPILOT_USER', '38251b7b9ec538207b358cd124265154');
    require_once($_SERVER['DOCUMENT_ROOT'].'/'.SEOPILOT_USER.'/SeoPilotClient.php');
    $seopilot = new SeoPilotClient();
    //echo $seopilot->build_links();
	 //$seopilot = new SeoPilotClient(array( 'is_test' => true ));


 $rekl_head = $seopilot->build_links();
}
else
{
 $rekl_head = 'localhost';
} */

$rekl_head = '';

/*
if($rekl_head == '' or !$rekl_head)
{
 $afilo = file_get_contents('http://aleproste.pl/afilo.php?afilo='.$_SERVER['HTTP_HOST'].'_750x100');

 if($afilo != '')
  $rekl_head =  $afilo;
 else
  $rekl_head = '';

 unset($afilo);
} */

/*
<div id=\'fb_left\' class=\'fb\'>
 <div class=\'fbook\'>
  <div class=\'fb_inside\'>
   <div id="fanpage_fb" rel="403x500"></div>
  </div>
 </div>
</div>
*/

$strona = '
<div id=\'menu\' class=\'menu width\' >
 <ul >'.S::menu($this->m['m3'], 0, 0, 1).S::menu($this->m['m4']).'
 </ul>
</div>

<div id=\'rekl_head\'>'.$rekl_head.'</div>
</div>

<a id=\'cam_dzik\' href="start_cam.html">Cam Dzikowiec Android</a>

<div id=\'start_side\' class=\'width\'>


</div>

<!--
<div id=\'down_start\' class=\'menu width\'>
 <ul >'.S::menu($this->m['m4']).'
 </ul>
</div>  -->

<div id=\'stopka_start\' class=\'width\'>
 <div class=\'footer\'>'.$stopka.'
 </div>
 <div id=\'smartfon\'></div>
</div>

';
?>