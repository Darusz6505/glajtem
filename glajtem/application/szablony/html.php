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
* glajtem.pl Dariusz Golczewski -- 2009-03-30 --- UTF-8
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

 <a id=\'cam_dzik\' href="start_cam.html">Cam Dzikowiec Android</a>
*/

$strona = '
<div id=\'menu\' class=\'menu width\' >
 <ul >'.S::menu($this->m['m1'], 0, 0, 1).'
 </ul>
</div>

<div id=\'rekl_head\'>'.$rekl_head.'</div>
</div>

<div id=\'start_side\' class=\'width\'>
  <div id=\'extra\'>
    <a href="http://www.paralotnie-sudety.pl/" title=\'Stowarzyszenie Paralotniowe Sudety\'>Stowarzyszenie Paralotniowe Sudety</a>
    <a href="http://www.kkparalotniowy.pl/" title=\'Karkonoski Klub Paralotniowy\'>Karkonoski Klub Paralotniowy</a>
    <a href="http://glidertracker.de/#lat=50.480&lon=17.256&z=10&id=" title=\'Glidertracker.de\'>Loty szybowcowe on-line</a>
    <a href="https://glideandseek.com/?viewport=50.55053,17.75116,10" title=\'glideandseek.com\'>glideandseek</a>
  </div>
</div>

<div id=\'down_start\' class=\'menu width\'>
 <ul >'.S::menu($this->m['m2']).'
 </ul>
</div>

<div id=\'stopka_start\' class=\'width\'>
 <div class=\'footer\'>'.$stopka.'&nbsp;|&nbsp;'.phpversion().'&nbsp;|&nbsp;
 </div>
 <div id=\'smartfon\'></div>
</div>

';

/*
 <a href="dolnoslaska-liga-paralotniowa.html" title=\'Dolnośląska Liga Paralotniowa\'>Dolnośląska Liga Paralotniowa 2021</a>

 <a href="http://puchar-dzikowca.glajtem.pl/" title=\'Puchar Dzikowca\'>Puchar Dzikowca 2018</a>

 <iframe src=\'http://www.livetrack24.com/user/faflik/status\' width=150 height=20 scrolling=\'no\' frameborder=\'0\' target=\'_blank\'></iframe>
 <iframe src=\'http://www.livetrack24.com/user/mecik/status\' width=150 height=20 scrolling=\'no\' frameborder=\'0\' target=\'_blank\'></iframe>
 <iframe src=\'http://www.livetrack24.com/user/cienias/status\' width=150 height=20 scrolling=\'no\' frameborder=\'0\' target=\'_blank\'></iframe>
 <iframe src=\'http://www.livetrack24.com/user/kwscore/status\' width=150 height=20 scrolling=\'no\' frameborder=\'0\' target=\'_blank\'></iframe>
 <iframe src=\'http://www.livetrack24.com/user/grzeschicago/status\' width=150 height=20 scrolling=\'no\' frameborder=\'0\' target=\'_blank\'></iframe>
 <iframe src=\'http://www.livetrack24.com/user/jarekbalicki/status\' width=150 height=20 scrolling=\'no\' frameborder=\'0\' target=\'_blank\'></iframe>
 <iframe src=\'http://www.livetrack24.com/user/sabina2008/status\' width=150 height=20 scrolling=\'no\' frameborder=\'0\' target=\'_blank\'></iframe>
 <iframe src=\'http://www.livetrack24.com/user/benedykt/status\' width=150 height=20 scrolling=\'no\' frameborder=\'0\' target=\'_blank\'></iframe>
 <iframe src=\'http://www.livetrack24.com/user/mateusz/status\' width=150 height=20 scrolling=\'no\' frameborder=\'0\' target=\'_blank\'></iframe>

 <iframe src=\'http://www.livetrack24.com/user/hancu_2008/status\' width=150 height=20 scrolling=\'no\' frameborder=\'0\' target=\'_blank\'></iframe>
 <iframe src=\'http://www.livetrack24.com/user/FrodoBaggins/status\' width=150 height=20 scrolling=\'no\' frameborder=\'0\' target=\'_blank\' ></iframe>

  <!--
    <p>LOT PO REKORD - BRAZYLIA 2015</p>
    <a target="_blank" href="http://share.findmespot.com/shared/faces/viewspots.jsp?glId=03J1ic8JunciKkEdJod6hQJU4XugAvNSy" title="Grzegorz Chicago">Chicago</a>
    <a target="_blank" href="http://share.findmespot.com/shared/faces/viewspots.jsp?glId=0ccQIFeqkPxSMZOF4sv7t8ASCq1TNBPqp" title="Adam Grzech">Adam</a>
    <a target="_blank" href="http://share.findmespot.com/shared/faces/viewspots.jsp?glId=0oG0fNnevUXS37oTpfgkTB3crdJRFEkH6" title="Grzegorz Szafranski">Szafranek</a>
    <a target="_blank" href="http://share.findmespot.com/shared/faces/viewspots.jsp?glId=0Sa66SGYRJ7a1aa4qY2C7Cx4K9Teyax4q" title="Krzysztof Makowski">Makoś</a>
    <a target="_blank" href="http://www.quixadaaventura.com.br/#!mapa-de-localizao/c2va" title="link ogólny do SPOT\'a">Ogólnie</a>
    -->
 */
?>