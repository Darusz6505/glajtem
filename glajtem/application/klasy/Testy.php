<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* wtyczka dedykowana: dla glajtem.pl -> testy egzaminacyjne
*
* 2021-01-09 : modyfikacje do wersji PHP 7.xx
*
* 2013-09-10
*
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2009-11-11 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/


class Testy
{
 private $w = '';
 										//-wynik działania klasy
 private $akcja = '';
 private $opcja = '';

 private $tab = '';

 private $foto_path = './testy/';

 private $nr_testu = 0;

 private $jo;



 /**
 *
 *
 */

 function __construct($tabt = false)
 {
  //$this->jo = C::get('jo');																	//- wskaźnik Admina

  $this->jo = C::get('ja');  																	//-2015-03-14 proteza praw dostępu

  if(!$tabt)
   $this->tab = C::get('tab_testy');
  else
	$this->tab = C::get($tabt);

  $this->akcja = C::Get('akcja');

  $this->opcja = C::Get('opcja');
 }

 /**
 *
 *
 *
 */

 public function test()
 {

  if(!$_SESSION['test']['lista'] || !is_array($_SESSION['test']['lista']))
	$_SESSION['test']['lista'] = array('0');

  if($this->opcja)
  {

	$opcja = explode('+', $this->opcja);

	if(!is_numeric($opcja[0]))
	{
	 $falsh = true;

	 $this->nr_testu = 0;

	 $opcja[1] = 0;
	 $opcja[2] = 0;
	}
	else
	{

    if(!$opcja[1])
	 {
     unlink($_SESSION['test']);

	  $_SESSION['test'] = array();

	  $_SESSION['test']['test'] =  $opcja[0];

     $opcja[1] = $opcja[2] = 0;

	  $falsh = true;
	 }

	 if($_SESSION['test']['test'] != $opcja[0])
	 {
	  $_SESSION['test'] = array();

	  $_SESSION['test']['lista'] = array(0);

	  $_SESSION['test']['test'] = $opcja[0];
	 }

    if($_SESSION['test']['opcja'] != $this->opcja)
	 {
	  $_SESSION['test']['opcja'] = $this->opcja;

	 }
	 else
	  $nolos =  true;

	 $this->nr_testu = $opcja[0];

	 if(!is_numeric($opcja[1])) $opcja[1] = 0;
	 if(!is_numeric($opcja[2])) $opcja[2] = 0;

	 if(!$opcja[1] || !$opcja[2]) $falsh = true;

	}
  }


  $wyb[$this->nr_testu] = ' class=\'wyb\'';

  if($this->opcja && !$falsh && $opcja[2])
  {
	 $opid = ' AND testy_id = '.$opcja[2];

	 $tab = 'SELECT * FROM '.$this->tab.' WHERE testy_nr = '.$this->nr_testu.$opid;

    if($tab = DB::myQuery($tab))
     if($tb = mysqli_fetch_assoc($tab))
	  {

	   if(!$nolos)
	   {
	    if(!$tb['testy_odp'.$opcja[1].'1'])
		 {
		  $falsh = true;
		  $_SESSION['test']['falsh']++;

		 }
		 else
		 {
		  $_SESSION['test']['ok']++;

		  if(!$nolos) array_push($_SESSION['test']['lista'], $tb['testy_id']);

		 }

		 $_SESSION['test']['count']++;

		 $nolos = false;
		}
	  }

    unset($opid);
  }

  if($opcja[2]) $idd = ' AND testy_id = '.$opcja[2];

  if($falsh)
  {
	$tab = 'SELECT * FROM '.$this->tab.' WHERE testy_nr = '.$this->nr_testu.$idd.' LIMIT 1';
  }
  else
  {

   $war = implode(',',$_SESSION['test']['lista']);

   $tab = 'SELECT * FROM '.$this->tab.' WHERE testy_nr = '.$this->nr_testu.' AND testy_id NOT IN('.$war.') ORDER BY rand() LIMIT 1';

	unlink($war);
  }

  if($tab = DB::myQuery($tab))
   if($tb = mysqli_fetch_assoc($tab))
	{

	 $test[] = array($tb['testy_pyt'], 'pyta');

	 if($tb['testy_odp1']) $t[1] = array($tb['testy_odp1'],'odp', '1');
	 if($tb['testy_odp2']) $t[2] = array($tb['testy_odp2'],'odp', '2');
	 if($tb['testy_odp3']) $t[3] = array($tb['testy_odp3'],'odp', '3');
	 if($tb['testy_odp4']) $t[4] = array($tb['testy_odp4'],'odp', '4');

	 if($t[$opcja[1]] && $falsh) $t[$opcja[1]] = array($tb['testy_odp'.$opcja[1]],'odp falsh');

	 shuffle ($t);

	 $test = array_merge($test, $t);

	 unlink($t);


	 if($tb['testy_fot0'] && file_exists($this->foto_path.'g_'.$tb['testy_fot0']))
	  $fo = $this->foto_path.'g_'.$tb['testy_fot0'];

    $this->html($test, $tb['testy_id'], $fo);

  	}
	else
	{
	 $this->w .= '
	 <div id=\'wyniki\' >
	  <h2>Koniec testu.</h2>
	 </div>
	 <a href=\''.$opcja[0].'+'.$this->akcja.'.html\' >RESET</a>';

  if($this->jo)
  {

	$akcja = $this->nr_testu.'+8+id+'.$this->akcja;

	$this->w .= '<a class=\'test_nota\' href=\''.S::linkCode(array($this->tab,0,'formu','testy_nr.'.$this->nr_testu, $akcja, '#id')).'.htmlc\' >DODAJ</a>';

  }

	}

  if(!$_SESSION['test']['falsh']) $_SESSION['test']['falsh'] = 0;
  if(!$_SESSION['test']['count']) $_SESSION['test']['count'] = 0;
  if(!$_SESSION['test']['ok']) $_SESSION['test']['ok'] = 0;

  $this->w .= '
  	 <div id=\'wyniki\' >
	  <b class=\'count\'>count : <i>'.$_SESSION['test']['count'].'</i></b>
	  <b class=\'ok\'>ok : <i>'.$_SESSION['test']['ok'].'</i></b>
	  <b class=\'zle\'>źle : <i>'.$_SESSION['test']['falsh'].'</i></b>
	  <b class=\'wynik\'>wyn. : <i>'.number_format(($_SESSION['test']['ok']*100/$_SESSION['test']['count']), 0, ',', ' ').'%</i></b>
	 </div>';

  $this->w .= '
	 <ul id=\'testy\'>
	  <li'.$wyb[0].'><a href=\'0+'.$this->akcja.'.html\'>Test 1</a></li>
	  <li'.$wyb[1].'><a href=\'1+'.$this->akcja.'.html\'>Test 2</a></li>
	  <li'.$wyb[2].'><a href=\'2+'.$this->akcja.'.html\'>Test 3</a></li>
	  <li'.$wyb[3].'><a href=\'3+'.$this->akcja.'.html\'>Test 4</a></li>
	 </ul>';

  $this->w .= '
	<a class=\'test_nota\' href=\'./testy+wiedzy+teoretycznej+instrukcja+i+informacje+66512667+czytaj.html\' title=\'zapoznaj się z instrukcją i dodatkowymi informacjami na temat testu\'>Instrukcja i opis testów.</a>';
 }

 /**
 *
 *
 *
 */

 private function html($test, $id, $fo)
 {

  foreach($test as $war)
  {
   if($war[1]) $war[1] = ' class =\''.$war[1].'\'';

	$akcja = $this->nr_testu.'+'.$war[2].'+'.$id.'+'.$this->akcja.'.html';

	if($war[2])
    $w[] = '<li'.$war[1].'><a href=\''.$akcja.'\'><p>'.$war[0].'</p></a></li>';
	else
	 $w[] = '<li'.$war[1].'><p>'.$war[0].'</p></li>';

	$akcja = '';
  }

  if($this->akcja != 'szybowce-testy')
   $uwaga = '<h2>Uwaga! (2017-03-19) Testy są już nie aktualne!</h2>';
  else
   $uwaga = '';

  $this->w .= '
  <h2> Wybierz odpowiedź:</h2>
  '.$uwaga.'
  <ul id=\'test\'>
   '.implode($w, '
  ').'
  </ul>';

  unset($uwaga);

  if($fo) $this->w .= '
	<div class=\'ilustr\'>
	 <img src=\''.$fo.'\'>
	</div>';

  if($this->jo)
  {
	$akcja = $this->nr_testu.'+8+'.$id.'+'.$this->akcja;

  	$this->w .= '<a class=\'test_nota\' href=\''.S::linkCode(array($this->tab, $id, 'edycja','', $akcja)).'.htmlc\' >EDIT</a>';

	$akcja = $this->nr_testu.'+8+id+'.$this->akcja;
	$this->w .= '<a class=\'test_nota\' href=\''.S::linkCode(array($this->tab,0,'formu','testy_nr.'.$this->nr_testu, $akcja, '#id')).'.htmlc\' >DODAJ</a>';

  }

 }


 /**
 *
 *
 *
 */

 public function wynik()
 {
   C::add('adcss', '
	<link rel="stylesheet" href="./application/glajtem_testy_20170319.css" type="text/css" media="screen" />');			//-css dlo formatowania zdjęć

 	return '<div id=\'testowy\'>'.$this->w.'
	</div>';
 }

 /**
 *
 *
 */

 function __destruct()
 {
  unset($this->w);
 }

}
?>