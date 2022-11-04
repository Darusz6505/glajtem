<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* wtyczka dedykowana: dla glajtem.pl -> KWT Szybowcowe
*
* 2021-01-09 : modyfikacje do wersji PHP 7.xx
*
* 2013-09-10 -> 2019-02-07
*
*
* autorem skryptu jest
* aleproste.pl Dariusz Golczewski -------- 2009-11-11 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/


class Testy2
{
 private $w = '';				//-wynik działania klasy

 private $akcja = '';
 private $opcja = '';
 private $tab = '';

 private $foto_path = './testy/';

 private $nr_testu = 0;

 private $testy = array();

 private $jo;
 private $jx = false;
 private $tryw = 0; //-znacznik pytania trywialnego do odfiltrowania, przez okrycie w bazie pytań


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

  if(!isset($this->opcja) || !$this->opcja)
  {

   $this->opcja = '1';
	$_SESSION['test']['jo'] = false;

  }
  else
   if($_SESSION['test']['jo'])
	 $this->jx = true;

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

  if(!$this->opcja) $this->opcja = 1;

  $opcja = explode('+', $this->opcja);

  if(!is_numeric($opcja[0]))
  {
	 $falsh = true;

	 $this->nr_testu = 1;

	 if($opcja[0] == 'anuLRrats')
	  $_SESSION['test']['jo'] = true;

	 $opcja[0] = 1;
	 $opcja[1] = 0; //-nr odpowedzi : jeśli 'X' - odfiltrować
	 $opcja[2] = 0; //-nr pytania

  }
  else
  {

   if(!$opcja[1])
   {
    //unlink($_SESSION['test']);

    $_SESSION['test'] = array();
    $_SESSION['test']['test'] =  $opcja[0]; //-nr wybranego testu
	 $_SESSION['test']['jo'] = $this->jx;

    $opcja[1] = 0;

	 if($opcja[2]) unset($opcja[2]);

	 $falsh = true;
	}

	if($_SESSION['test']['test'] != $opcja[0])
	{
	 $_SESSION['test'] = array();
	 $_SESSION['test']['lista'] = array(0);
	 $_SESSION['test']['listaf'] = array(0);
	 $_SESSION['test']['test'] = $opcja[0];
	 //$_SESSION['test']['jo'] = false;
	}

   if($_SESSION['test']['opcja'] != $this->opcja)
	 $_SESSION['test']['opcja'] = $this->opcja;
	else
	 $nolos =  true;

	$this->nr_testu = $opcja[0];

	if(!is_numeric($opcja[1])) $opcja[1] = 0;

	if($opcja[2] && !is_numeric($opcja[2])) $opcja[2] = 0;

	if(!$opcja[1] || !$opcja[2]) $falsh = true;
  }


  $wyb[$this->nr_testu] = ' class=\'wyb\'';

  //-jeśli poprawnie
  if($this->opcja && !$falsh && $opcja[2] && $this->nr_testu > 0)
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
		  $falsh = true;												//-znacznik że odpowiedziano źle
		  $_SESSION['test']['falsh']++;  						//-licznik błędnych odpowiedzi
		  $_SESSION['test']['listaf'][] = $tb['testy_id']; //-lista nr pytań na które odpowiedziano źle
		 }
		 else
		 {
		  $_SESSION['test']['ok']++;

		  if(!$nolos) array_push($_SESSION['test']['lista'], $tb['testy_id']);


		  	if($opcja[3] == 'X')
	      {

	       $tab = 'UPDATE '.$this->tab.' SET testy_tryw = \'1\'
	 			WHERE testy_nr = '.$opcja[0].' AND testy_id = '.$opcja[2];

	      if($tab = Db::myQuery($tab))
	      {
	       //array_push($_SESSION['test']['lista'], $opcja[2]);
	       $opcja = array();
	       $opcja[0] = $_SESSION['test']['test'];

	      }
	     }
		 }

		 $_SESSION['test']['count']++;

		 $nolos = false;
		}
	  }

    unset($opid);
  }

 //-wyświetlenie liczników ilości pytań
  if($opcja[0])
  {

   $tab = 'SELECT count(*) as licz FROM '.$this->tab.' WHERE testy_nr = '.$opcja[0].' AND testy_tryw = \'0\'';
   if($tab = DB::myQuery($tab))
	 if($tb = mysqli_fetch_assoc($tab))
	  $il_pyt = $tb['licz'];

	$tab = 'SELECT count(*) as licz FROM '.$this->tab.' WHERE testy_nr = '.$opcja[0].' AND testy_tryw = \'1\'';
   if($tab = DB::myQuery($tab))
	 if($tb = mysqli_fetch_assoc($tab))
	  $il_pyt .= '/'.$tb['licz'];
  }
  else
	$il_pyt = 'x/x';


	//-powrót do puli pytań na które udzielono złej odpowiedzi

	if($_SESSION['test']['count'] > 15 && count($_SESSION['test']['listaf']) > 0 && !$falsh)
	{
	 $tab_diff = array();

	 $el_pow = array_shift($_SESSION['test']['listaf']);

    $tab_diff = array_diff($_SESSION['test']['lista'], array($el_pow));

	 $_SESSION['test']['lista'] = $tab_diff;

	 unset($tab_diff,$el_pow);

	}


  if($falsh && $opcja[2]) //-jeśli odpowiedź błędna
  {
	$tab = 'SELECT * FROM '.$this->tab.' WHERE testy_nr = '.$this->nr_testu.$idd.' AND testy_id = '.$opcja[2];
  }
  else
  {
   if(count($_SESSION['test']['lista']) == 0) $_SESSION['test']['lista'] = array(0);

   $war = implode(',',$_SESSION['test']['lista']);

	if($_SESSION['test']['jo']) $tryw = ' AND testy_tryw = \'0\''; //$this->jo ||

   $tab = 'SELECT * FROM '.$this->tab.' WHERE testy_nr = '.$this->nr_testu.$tryw.' AND testy_id NOT IN('.$war.') ORDER BY rand() LIMIT 1';

	unlink($war, $tryw);
  }

  if($tab = DB::myQuery($tab))
   if($tb = mysqli_fetch_assoc($tab))
	{

	 $name = $tb['testy_name'];

	 $tb['testy_pyt'] = preg_replace("# :#", "&nbsp;:", $tb['testy_pyt']);

	 $test[] = array($tb['testy_pyt'], 'pyta');

	 if($tb['testy_odp1']) $t[1] = array($tb['testy_odp1'],'odp', '1');
	 if($tb['testy_odp2']) $t[2] = array($tb['testy_odp2'],'odp', '2');
	 if($tb['testy_odp3']) $t[3] = array($tb['testy_odp3'],'odp', '3');
	 if($tb['testy_odp4']) $t[4] = array($tb['testy_odp4'],'odp', '4');

	 if($t[$opcja[1]] && $falsh) $t[$opcja[1]] = array($tb['testy_odp'.$opcja[1]],'odp falsh');

	 shuffle($t);

	 $test = array_merge($test, $t);

	 unlink($t);


	 if($tb['testy_fot0'] && file_exists($this->foto_path.'g_'.$tb['testy_fot0']))
	  $fo = $this->foto_path.'g_'.$tb['testy_fot0'];


    $this->html($test, $tb['testy_id'], $fo, $name, $il_pyt);

  	}
	else
	{
	 $this->w .= '
	 <div id=\'wyniki\' >
	  <h2>Koniec testu.</h2>
	 </div>
	 <a href=\''.$opcja[0].'+'.$this->akcja.'.html\' >RESET</a>';
/*
    if($this->jo)
    {

	  $akcja = $this->nr_testu.'+8+id+'.$this->akcja;

	  $this->w .= '<a class=\'test_nota\' href=\''.S::linkCode(array($this->tab,0,'formu','testy_nr.'.$this->nr_testu, $akcja, '#id')).'.htmlc\' >DODAJ</a>';

    } */
	}

	/*
   if(!$_SESSION['test']['falsh']) $_SESSION['test']['falsh'] = 0;
   if(!$_SESSION['test']['count']) $_SESSION['test']['count'] = 0;
   if(!$_SESSION['test']['ok'])    $_SESSION['test']['ok'] = 0;
   */

  $this->w .= '
  	 <div id=\'wyniki\' >
	  <b class=\'count\'>licz : <i>'.$_SESSION['test']['count'].'</i></b>
	  <b class=\'ok\'>ok : <i>'.$_SESSION['test']['ok'].'</i></b>
	  <b class=\'zle\'>źle : <i>'.$_SESSION['test']['falsh'].'</i></b>
	  <b class=\'wynik\'><i>'.number_format(($_SESSION['test']['ok']*100/$_SESSION['test']['count']), 0, ',', ' ').'%</i></b>
	 </div>';


  $this->w .= '
    <p>Wybierz dział:</p>
	 <ul id=\'testy\'>
	  <li'.$wyb[1].'><a href=\'1+'.$this->akcja.'.html\' title=\'Ogólna wiedza\'>0</a></li>
	  <li'.$wyb[2].'><a href=\'2+'.$this->akcja.'.html\' title=\'Osiągi i planowanie\'>1</a></li>
	  <li'.$wyb[3].'><a href=\'3+'.$this->akcja.'.html\' title=\'Meteorologia\'>2</a></li>
	  <li'.$wyb[4].'><a href=\'4+'.$this->akcja.'.html\' title=\'Człowiek ...\'>3</a></li>
	  <li'.$wyb[5].'><a href=\'5+'.$this->akcja.'.html\' title=\'Procedury operacujne\'>4</a></li>
	  <li'.$wyb[6].'><a href=\'6+'.$this->akcja.'.html\' title=\'Nawigacja\'>5</a></li>
	  <li'.$wyb[7].'><a href=\'7+'.$this->akcja.'.html\' title=\'Prawo\'>6</a></li>
	  <li'.$wyb[8].'><a href=\'8+'.$this->akcja.'.html\' title=\'Zasady lotu\'>7</a></li>
	  <li'.$wyb[9].'><a href=\'9+'.$this->akcja.'.html\' title=\'Łączność\'>8</a></li>
	  <li'.$wyb[10].'><a href=\'10+'.$this->akcja.'.html\' title=\'Łączność nowa\'>9</a></li>
	 </ul>';

	 /*
  $this->w .= '
  <div>
	<a class=\'test_nota\' href=\'./testy+wiedzy+teoretycznej+instrukcja+i+informacje+66512667+czytaj.html\' title=\'zapoznaj się z instrukcją i dodatkowymi informacjami na temat testu\'>Instrukcja i opis testów.</a>
  </div>'; */

  if($this->jo)
  {
	$akcja = $this->nr_testu.'+5+'.$tb['testy_id'].'+'.$this->akcja;

  	$this->w .= '<a class=\'test_nota\' href=\''.S::linkCode(array($this->tab, $tb['testy_id'], 'edycja','', $akcja)).'.htmlc\'
	title=\''.$tb['testy_id'].'\'>EDIT</a>';

	$this->w .= '<a class=\'test_nota\' href=\''.S::linkCode(array($this->tab,0,'formu','testy_nr.'.$this->nr_testu, $akcja, '#id')).'.htmlc\' >DODAJ</a>';

	  $this->w .= '
    <p>Skasuj trywialne:</p>
	 <ul id=\'testy\'>
	  <li'.$wyb[1].'><a href=\'1+del+'.$this->akcja.'.html\' title=\'Ogólna wiedza\'>0</a></li>
	  <li'.$wyb[2].'><a href=\'2+del+'.$this->akcja.'.html\' title=\'Osiągi i planowanie\'>1</a></li>
	  <li'.$wyb[3].'><a href=\'3+del+'.$this->akcja.'.html\' title=\'Meteorologia\'>2</a></li>
	  <li'.$wyb[4].'><a href=\'4+del+'.$this->akcja.'.html\' title=\'Człowiek ...\'>3</a></li>
	  <li'.$wyb[5].'><a href=\'5+del+'.$this->akcja.'.html\' title=\'Procedury operacujne\'>4</a></li>
	  <li'.$wyb[6].'><a href=\'6+del+'.$this->akcja.'.html\' title=\'Nawigacja\'>5</a></li>
	  <li'.$wyb[7].'><a href=\'7+del+'.$this->akcja.'.html\' title=\'Prawo\'>6</a></li>
	  <li'.$wyb[8].'><a href=\'8+del+'.$this->akcja.'.html\' title=\'Zasady lotu\'>7</a></li>
	  <li'.$wyb[9].'><a href=\'9+del+'.$this->akcja.'.html\' title=\'Łączność\'>8</a></li>
	  <li'.$wyb[10].'><a href=\'10+'.$this->akcja.'.html\' title=\'Łączność nowa\'>9</a></li>
	 </ul>';

  }
 }

 /**
 *
 *
 *
 */

 private function html($test, $id, $fo, $name, $il_pyt)
 {

  $i = 0;
  $ff = 0;

  foreach($test as $war)
  {
   if($war[1] == 'odp falsh') $ff = 1;

   if($war[1]) $war[1] = ' class =\''.$war[1].'\'';

	$akcja = $this->nr_testu.'+'.$war[2].'+'.$id.'+'.$this->akcja.'.html';


	if($war[2])
	{
    $w[] = '<li'.$war[1].'><a href=\''.$akcja.'\'><p>'.$war[0].'</p></a></li>';
	 $f[] = "<a class='tryw' href='".$this->nr_testu."+".$war[2]."+".$id."+X+".$this->akcja.".html'>".$i."</a>";
	}
	else
	{
	 $w[] = '<li'.$war[1].'><p>'.$war[0].'</p></li>';
	}

	$i++;
  }

  unset($akcja);


  if($this->jo || $_SESSION['test']['jo'])
  {

   if($ff == 0)
    $r = $f[0].$f[1].$f[2].$f[3];

   $r = '<li class =\'pyta\'>'.$r.'</li>
	<li>no=>'.implode(', ',$_SESSION['test']['listaf']).'<br />ok=>'.implode(', ', $_SESSION['test']['lista']).'</li>';
  }

  unset($f, $ff, $i);

  /*
  if($this->akcja != 'szybowce-testy')
   $uwaga = '<h2>Uwaga! (2017-03-19) Testy są już nie aktualne!</h2>';
  else
   $uwaga = ''; */

  $this->w .= '
  <h2>'.$name.' ('.$il_pyt.')</h2>
  <p> Wybierz prawidłową odpowiedź.</p>
  '.$uwaga.'
  <ul id=\'test\'>
   '.implode($w, '
  ').$r.'</ul>';

  unset($uwaga);

  if($fo) $this->w .= '
	<div class=\'ilustr\'>
	 <img src=\''.$fo.'\'>
	</div>';


 }

 /**
 *
 *
 *
 */

 public function wynik()
 {
   C::add('adcss', '
	<link rel="stylesheet" href="./application/glajtem_testy_20190213.css" type="text/css" media="screen" />');
	//-css dlo formatowania zdjęć

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