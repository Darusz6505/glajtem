<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* szablon dla : glajtem.pl - noszenia
*
* 2015-12-10
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
}
*/

if($rekl_head == '' or !$rekl_head)
{
 $afilo = file_get_contents('http://aleproste.pl/afilo.php?afilo='.$_SERVER['HTTP_HOST'].'_750x100');

 if($afilo != '')
  $rekl_head =  $afilo;
 else
  $rekl_head = '';

 unset($afilo);
}


$strona = '
<div id=\'menu\' class=\'menu width\'>
 <ul >'.S::menu($this->m['m1']).'
 </ul>
</div>
<div id=\'rekl_head\'>'.$rekl_head.'</div>

'.$this->polahtml['mein'].'

<div id=\'down_start\' class=\'menu width\'>
 <ul >'.S::menu($this->m['m2']).'
 </ul>
</div>

<div id=\'stopka\' class=\'width\'>
 <div class=\'footer\'>'.$stopka.'
 </div>
 <div id=\'smartfon\'></div>
</div>';

?>