<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
* wczytanie bazy borucha do moich testów
* 2019-02-06
*
* autorem skryptu jest
* aleproste.pl Dariusz Golczewski -------- 2010-11-11 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/

class Boruch
{

 private $nr = 0; //-nr testu
 private $text = ''; //-tutuł testu + liczba pytań

 private $plik = './pliki/boruch/test';

 private $zapis = 1;

 ################

 private $test = 1;					//-przełącznik dla komunikatów testowych
 private $strona = 0;

 private $mmysql = ''; 				//-uchwyt do połaczenia MySQL
 private $w = '';

 private $akcja = '';
 private $opcja = '';

 private $tab = ''; 					//-tabela pytań

 private $jo = false;

 private $tab_testy = '';			//-tabela testu


 private $error = array();
 private $trace = array();

 private $loty = array();


 function __construct()
 {

  $kod = 'fsdfr7rr2fasd'; //-główny kod

  // http://x.glajtem.pl/boruch.html?kikejrbd78df=fsdfr7rr2fasd

  //echo '<code>'.substr($kod, 0, 3).'</code>';

 if(isset($_GET['kikejrbd78df']))
 {
  if(substr($_GET['kikejrbd78df'], 0, 13) != $kod)
   die('Brak uprawnien!');
  else
  {
   $te = substr($_GET['kikejrbd78df'], 13);
	//die('NR testu = '.$te);


	if($te)
	{
	 if($te > 0 && $te < 11)
	  $this->plik .= $te.'.php';
	 else
	  die('NR testu poza zakresem!');
	}
	else
	 die('NR testu - brak !');
  }
 }
 else
 {
  die('Nrak uprawnień!');
 }

   if($_SERVER['HTTP_X_FORWARDED_FOR'])											//-prawdziwe ip - bez funkcji ippraw()
   $c['ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
  else
   $c['ip'] = $_SERVER['REMOTE_ADDR'];


  if(!eregi('^127\.', $c['ip']) || $c['set_remote'])
   $c['db_local'] = false;
  else
   $c['db_local'] = true;

  unset($c['set_remote']);

  if(!eregi('^127\.', $c['ip']))
   $c['localhost'] = false;
  else
   $c['localhost'] = true;


  require_once _CONPATH.'config_def_min'._EX; //-zrobić config_min_def,  z minimalną potrzebną ilością ustawień
  require_once _CONPATH.'config_sql_boruch'._EX;

  /*
  if($c['db_local'])
   echo '<br /><code>db_local = True</code><br />';
  else
	echo '<br /><code>db_local = False</code><br />';

  exit('<br />STOP - 106'); */

  $this->jo = $c['jo'] = $c['ja'] = 1; //- wskaźnik Admina

  C::loadConfig($c);

  $this->mmysql = new Db;

  $this->tab_testy = C::get('tab_test2');

  $this->testy();
 }

  /**
 *
 * głowna funkcja programu
 *
 *
 */

 private function testy()
 {

	$this->strona = 1; //-wejście ze strony włącza komunikaty
	$this->trace[] = 'Wejście ze strony -> '.$data;

   if($this->test || $this->strona) echo "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'
	xml:lang='PL'
	lang='PL'>
<head>
<meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\" />

<link rel=\"stylesheet\" href=\"./application/dlploty.css\" type=\"text/css\" media=\"screen\" />

</head>
<body>
";


  if($this->strona)
	 echo '<br /><code>Wejście ze strony.</code><br />';

  require_once $this->plik;

  $this->nr = $nr;
  $this->text = $text;

  echo '<code>'.$this->text.'</code><br />';
  echo '<code>Nr testu = '.$this->nr.'</code><br /><br />';

  $kod = htmlentities($kod1);

  //echo '<code>'.$kod.'</code>';
  //$ko = explode("&lt;aside", $kod);
  //$kod = $ko[0];

  //$kod = preg_replace("#PL([0-9]{3})#", "^^$1", $kod);

  $kod = preg_replace("#\nPL#", "PL", $kod);

  $kod = preg_replace("#PL([0-9]{3})#", "$1", $kod);

  $kod = preg_replace("#qtext&quot;&gt;#", "^^", $kod);

  $kod = explode("^^", $kod); //- NIE ZAWSZE !!!


  //qtext"> qtext&quot;&gt;

  echo '<code>Liczba pytań = '.count($kod).'</code><br /><br />';

  //array_shift($kod);

  //$kod = explode("formulation", $kod);

  $i = 1;

  foreach($kod as $v)
  {

   $c = explode("gradingdetails", $v);
	$testv = $v = $c[0];
	unset($c);


	//echo '<code>'.$this->convertChars($v).'</code><br /><br />';

	$v = preg_replace("#\r|\n#", " ", $v);
	$v = preg_replace("# {2,}#", " ", $v);

   //$v = preg_replace("#clearfix&quot;&gt;&lt;h4 class=&quot;accesshide&quot;&gt;#", "", $v);

   $v = preg_replace("#&lt;br /&gt;#", "", $v); // <br />

   $v = preg_replace("#&lt;a (.)+?(&lt;/a&gt;)+?#", "", $v); //A całe
   $v = preg_replace("#&lt;p&gt;#", "", $v); //P całe

//$v = preg_replace("#&lt;p (.)+?(&lt;/p&gt;)+?#", "", $v); //P całe
//  $kod = preg_replace("#&lt;/strong&gt;#", "", $kod);
//  $kod = preg_replace("#&lt;/label&gt;#", "", $kod);
//  $kod = preg_replace("#&lt;/h4&gt;#", "", $kod);
//  $kod = preg_replace("#&lt;/div&gt;#", "", $kod);
//  $kod = preg_replace("#&lt;/form&gt;#", "", $kod);
//  //$kod = preg_replace("#&lt;/sup&gt;#", "", $kod);
//  $kod = preg_replace("#&lt;/td&gt;#", "", $kod);
//  $kod = preg_replace("#&lt;/tr&gt;#", "", $kod);
//  $kod = preg_replace("#&lt;/table&gt;#", "", $kod);
//  $kod = preg_replace("#&lt;/tbody&gt;#", "", $kod);
//  $kod = preg_replace("#&lt;/span&gt;#", "", $kod);


  $v = preg_replace("#&lt;/(strong|label|h4|div|form|sup|td|tr|table|tbody|span|h3|a|p)&gt;#", "", $v);

  //echo '<code>'.$kod.'</code>';


  $v = preg_replace("#&quot;answer&quot;&gt;#", "||", $v);	//PYTANIA
  $v = preg_replace("#&quot;rightanswer&quot;&gt;#", "||", $v); //-Odp [$1]-odp	ODPOWIEDZI

  $v = preg_replace("#&amp;quot;#", "&#8242;", $v);
  $v = preg_replace("#&amp;\#8221;#", "&#8242;", $v);
  $v = preg_replace("#&amp;\#8222;#", "&#8242;", $v);

  $v = preg_replace("#&amp;\#8211;#", "-", $v);
  $v = preg_replace("#&amp;\#8216;#", "&#8242", $v);
  $v = preg_replace("#&amp;\#8217;#", "&#8242", $v);
  $v = preg_replace("#&amp;\#8217;#", "&#8242", $v);
  $v = preg_replace("#&amp;\#8220;#", "&#8242", $v);
  $v = preg_replace("#&amp;\#8230;#", "", $v);
  $v = preg_replace("#&amp;\#8250;#", "", $v);

  $v = preg_replace("#&amp;upsilon#", "&#965", $v); //
  $v = preg_replace("#&amp;omega;#", "&#969;", $v); //
  $v = preg_replace("#&amp;Delta;#", " &#916;", $v); //
  $v = preg_replace("#&amp;delta;#", " &#948;", $v); //
  $v = preg_replace("#&amp;lambda;#", " &#955;", $v); //
  $v = preg_replace("#&amp;phi;#", " &#966;", $v); //
  $v = preg_replace("#&amp;rho;#", " &#961;", $v); //
  $v = preg_replace("#&amp;fnof;#", " &#402;", $v); //
  $v = preg_replace("#&amp;Aring;#", " &#197;", $v); //
  $v = preg_replace("#&amp;Theta;#", "&#952;", $v); //

  $v = preg_replace("#m\?#", "m?", $v);
  $v = preg_replace("#&lt;(sub|/sub)&gt;#", "", $v);
  $v = preg_replace("#&lt;sup&gt;0#", "&#176;", $v);
  $v = preg_replace("#&amp;\#177;#", "&#177;", $v); //&#956;
  $v = preg_replace("#&amp;\#956;#", "&#956;", $v);

  $v = preg_replace("#&lt;sup&gt;2#", "2", $v);
  $v = preg_replace("#sup &amp;gt;#", "", $v);
  $v = preg_replace("#&lt;sup&gt;#", "", $v);
  $v = preg_replace("#&amp;lt;/#", "", $v);
  $v = preg_replace("#&amp;lt; /#", "", $v);


  $v = preg_replace("#&amp;\#176;#", "&#176;", $v); //-stopień

  $v = preg_replace("#&amp;alpha;#", "&#945;", $v);
  $v = preg_replace("#&amp;amp;alpha;#", " &#945;", $v);
  $v = preg_replace("#&amp;\#945;#", " &#945;", $v);
  $v = preg_replace("#&amp;\#937;#", "&#937;", $v);
  $v = preg_replace("#&amp;amp#", "&amp", $v);

  $v = preg_replace("#&amp;\#963;#", "&#963;", $v);
  $v = preg_replace("#&amp;\#961;#", "&#961;", $v);

  $v = preg_replace("#&amp;nbsp;#", "", $v);



  $v = preg_replace("#&amp;gt;#", " > ", $v);
  $v = preg_replace("#&amp;lt;#", " < ", $v);

  $v = preg_replace("#sup&amp;gt;#", "", $v);

  /*
  $v = preg_replace("#&lt;strong&gt;#", "", $v);
  $v = preg_replace("#sup &amp;gt;#", "", $v);
  $v = preg_replace("#&lt;span(.)+?&gt;#", "", $v); &#963;

  $v = preg_replace("#&amp;lt;/sup&amp;gt;#", "", $v);
  $v = preg_replace("#&lt;tr(.)+?&gt;#", "", $v); //TR
  $v = preg_replace("#&lt;tbody(.)+?&gt;#", "", $v); //TBODY
  $v = preg_replace("#&lt;td(.)+?&gt;#", "", $v); //TD
  $v = preg_replace("#&lt;table(.)+?&gt;#", "", $v); //TABLE

  //$v = preg_replace("#(Poprawna odpowie|Poprawnaodpowie)(.)+?:#", "||$odp", $v); //-Odp [$1]-odp
  $v = preg_replace("#Prawa Pa(.)+?kip#", "Prawa Pałki p", $v);
  $v = preg_replace("#&amp;quot;#", "^", $v);
  $v = preg_replace("#gradingdetails(.)+#", "", $v);
  */

  $v = preg_replace("#im-feedback(.)+#", "", $v);	//potrzebne do 2

   //echo '<code>'.$this->convertChars($v).'</code><br /><br />';

   $t = explode('||', $v);

	if($i == 10005)
	{
	 echo '<code>'.$this->convertChars($testv).'</code><br /><br />';

	 echo '<code>'.$this->convertChars($v).'</code><br /><br />';
    $tk = 1;
	}


	//Pytanie

   //$t[0] = preg_replace("#&lt;div(.)+?&gt;#", "", $t[0]); //DIV
	$t[0] = preg_replace("#(Wybierz jedn|Wybierzjedn)(.)+?:#", "", $t[0]);
	$t[0] = preg_replace("#&lt;div(.)+#", "", $t[0]); //DIV
   //$t[0] = preg_replace("#Tre(.)+?pytania#", "", $t[0]);
	//$t[0] = preg_replace("#&quot;#", "", $t[0]);
	$t[0] = preg_replace("#&quot;#", "", $t[0]);
   //echo '<code>'.$this->convertChars($t[0]).'</code><br />';

   $this->tab[$i][0] = 'PL-'.$this->convertChars(trim($t[0]));


	//Odpowiedzi
   //$t[1] = preg_replace("#&lt;div(.)+?&gt;#", "", $t[1]); //DIV
	//$t[1] = substr($t[1],2);
	//$t[1] = preg_replace("#&quot;#", "", $t[1]);
	//$t[1] = preg_replace("#&lt;img(.)+?(\|\|)#", "||", $t[1]); //IMG


	$tsc = explode("specificfeedback", $t[1]);
	$t[1] = preg_replace("#&lt;img(.)+?&gt;#", "", $tsc[0]);
	unset($tsc);
	$t[1] = preg_replace("#&lt;input(.)+?&gt;#", "", $t[1]); //INPUT
	$t[1] = preg_replace("#&lt;label(.)+?&gt;#", "$$", $t[1]);
	$t[1] = preg_replace("#&lt;div(.)+?&gt;#", "", $t[1]); //DIV
	$t[1] = preg_replace("#&lt;div(.)+?&quot;#", "", $t[1]); //DIV
	$t[1] = preg_replace("#&lt;h4(.)+#", "", $t[1]); //DIV

   $t[1] = preg_replace("#&quot;#", "", $t[1]);

   $tt = explode("$$", $t[1]);
   //array_shift($tt);
   foreach($tt as $vv)
	{


	 $ttt[] = trim($this->convertChars(substr($vv, 2)));

	}
   $t[1] = $this->tab[$i][1] = $ttt;
   unset($ttt);


	//-Odpowiedzi prawidłowe
   //$t[2] = preg_replace("#&lt;div(.)+?&gt;#", "", $t[2]); //DIV
	//$t[2] = substr($t[2],2);
	//$t[2] = preg_replace("#&quot;#", "", $t[2]);
   $t[2] = preg_replace("#(Poprawna odpowie|Poprawnaodpowie)(.)+?:#", "", $t[2]); //-Odp [$1]-odp
	$t[2] = preg_replace("#&lt;div(.)+?&gt;#", "", $t[2]); //DIV
   $t[2] = preg_replace("#&lt;div(.)+?&quot;#", "", $t[2]); //DIV"
	$t[2] = preg_replace("#Poprawnie#", "", $t[2]); //DIV"
	$t[2] = preg_replace("#&quot;#", "", $t[2]);
	$t[2] = preg_replace("#&lt;input(.)+?&gt;#", "", $t[2]); //INPUT potrzebne do 2
	$t[2] = preg_replace("#&lt;img(.)+?&gt;#", "", $t[2]); //IMG potrzebne do 4
	$t[2] = preg_replace("#&lt;h3(.)+#", "", $t[2]); //H3 potrzebne do 4

	$t[2] = preg_replace("#Prawid(.)+?:#", "", $t[2]); //-jeśli jest więcek niż jedna odpowiedź

	//$t[2] = explode("||", $t[2]);

	//-proteza - jeśli są 2
	/*
	if($t[2][1])
	 $this->tab[$i][2] = this->convertChars(trim($t[2][1]))); //, this->convertChars(trim($t[2][1])));
	else
    $this->tab[$i][2] = $this->convertChars(trim($t[2][0])); */

   $this->tab[$i][2] = $this->convertChars(trim($t[2]));


   if($this->tab[$i][1][1] == $this->tab[$i][2]) $this->tab[$i][3] = 1;
	if($this->tab[$i][1][2] == $this->tab[$i][2]) $this->tab[$i][3] = 2;
	if($this->tab[$i][1][3] == $this->tab[$i][2]) $this->tab[$i][3] = 3;
	if($this->tab[$i][1][4] == $this->tab[$i][2]) $this->tab[$i][3] = 4;

	if($this->tab[$i][3] == 0) $this->tab[$i][0] = 'X-'.$this->tab[$i][0].' = '.$this->tab[$i][2];



	if($tk)
	{
   echo '<code>Pytanie '.$i.': '.$this->tab[$i][0].'</code>
	<br /><br />';


	$k = 0;
	foreach ($t[1] as $vv)
	{
	 echo '<code>'.$vv.'</code>
	 <br />';//' - '.$t[1][$k].'-'.$k.
	 $k++;
	}


	echo '<br />
	<code>odp. '.$this->tab[$i][2].'</code>
	<br /><br />';

	echo '<code>pra. '.$this->tab[$i][3].'</code><br />
	<br /><br />';
	}

	//if($i == 47) $this->poznaku($this->tab[$i][4]);


	$i++;
  }



  $i = 1;

  foreach($this->tab as $t)
  {

   if(trim($t[0]) == "") break('<br /><br />STOP!!!');
	echo '<code>Pytanie '.$i.': '.$this->convertChars($t[0]).'</code>
	<br /><br />';


	$k = 0;
	foreach ($t[1] as $vv)
	{
	 echo '<code>'.$k.'-> '.$vv.'</code>
	 <br />';//' - '.$t[1][$k].'-'.$k.
	 $k++;
	}

	if(trim($t[2]) == "") break('<br /><br />STOP!!!');
	echo '<br /><code>odp.-> '.($t[2]).'</code>
	<br /><br />';

	if(trim($t[3]) == "") break('<br /><br />STOP!!!');
	echo '<code>praw.-> '.($t[3]).'</code>
	<br /><br />';


   $i++;
  }

	if($this->zapis)
	{
	 $j = count($this->tab);

	 for($k=0; $k <= $j; $k++)
     if($this->tab[$k][0])
		$this->zap_test($this->tab[$k], $k);
   }


	$this->text = $this->text .'('. count($this->tab) .')';
 }

 /**
 * zapisuje element testy do bazy
 *
 *
 */

 private function zap_test($t, $k)
 {
 /* do testów
    $r = array();

	 if($t[3] == 1) $r[1] = 1;
	 if($t[3] == 2) $r[2] = 1;
	 if($t[3] == 3) $r[3] = 1;
	 if($t[3] == 4) $r[4] = 1;

 	 $tab = 'INSERT INTO '.$this->tab_testy.' SET
	 			testy_nr = \''.$this->nr.'\',
				testy_tryw = \'0\',
				testy_name = \''.$this->text.'\',
	 			testy_pyt = \''.$t[0].'\',
				testy_odp1 = \''.$t[1][1].'\',
				testy_odp11 = \''.$r[1].'\',
				testy_odp2 = \''.$t[1][2].'\',
				testy_odp21 = \''.$r[2].'\',
				testy_odp3 = \''.$t[1][3].'\',
				testy_odp31 = \''.$r[3].'\',
				testy_odp4 = \''.$t[1][4].'\',
				testy_odp41 = \''.$r[4].'\'';


    if($this->test) echo '<code class=\'bred\'>'.$tab.'</code><br />';

	 exit; */

 /*
  if(count($this->tab)==0)
  {
   $this->error[] =  'Tabela testu jest pusta, line-> '.__LINE__;
	return 0;
  } */

  	/*
  $tan[C::get('tab_test2')] = "(
	testy_id    INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,[10]
	testy_stat  TINYINT UNSIGNED NOT NULL DEFAULT '0',			[Pozycja na liście^S;X;0;30^H;priorytet bloku treści w publikacji]
	testy_blok  VARCHAR(1) NOT NULL DEFAULT '',					[Rekord widoczny tylko dla admina^W]
	testy_nr	   TINYINT UNSIGNED NOT NULL DEFAULT '0',			[nr testu^L;5^C;2]
	testy_pyt   TEXT,														[pytanie*^L;40^T;300]
	testy_odp1  TEXT,														[odpow.1^L;30^T;300]
	testy_odp11 VARCHAR(1)  NOT NULL DEFAULT '',					[odpow.1 ok^W]
	testy_odp2	TEXT,														[odpow.2^L;30^T;300]
	testy_odp21 VARCHAR(1)  NOT NULL DEFAULT '',					[odpow.2 ok^W]
	testy_odp3	TEXT,														[odpow.3^L;30^T;300]
	testy_odp31 VARCHAR(1)  NOT NULL DEFAULT '',					[odpow.3 ok^W]
	testy_odp4	TEXT,														[odpow.4^L;30^T;300]
	testy_odp41 VARCHAR(1)  NOT NULL DEFAULT '',					[odpow.4 ok^W]
	testy_fot0  VARCHAR(40) NOT NULL DEFAULT ''					[Zdjęcie^ext;jpg;jpeg;gif;png^path;testy^th;m_,170,170,90;g_,420,300,90;L_,900,600,90,121]) ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci"; */


  //-przeszukujemy

  $tab = 'SELECT * FROM '.$this->tab_testy.'
		 	 WHERE testy_pyt=\''.$t[0].'\' AND testy_nr=\''.$n.'\'';


  if($tab = Db::myQuery($tab))
   if($ta = mysql_fetch_assoc($tab))
	{
    //-pytanie już jest!!
	 if($this->test)
	 {
	  echo '<code class=\'bred\'>Element testu już isnieje</code><br />';
	  echo '<code class=\'bred\'>'.$ta['testy_nr'].'</code><br />';
	  echo '<code class=\'bred\'>'.$ta['testy_pyt'].'</code><br />';

	 }
    //-jeśi jest już takie pytanie w teście
	 return 0;
	}
	else
	{
	 //-dodanie pyanie do bazy
	 if($this->test) echo '<code class=\'bgreen\'>Dodanie pyanie do bazy.</code><br />';

	 $r = array();

	 if($t[3] == 1) $r[1] = 1;
	 if($t[3] == 2) $r[2] = 1;
	 if($t[3] == 3) $r[3] = 1;
	 if($t[3] == 4) $r[4] = 1;


	 $tab = 'INSERT INTO '.$this->tab_testy.' SET
	 			testy_nr = \''.$this->nr.'\',
				testy_tryw = \'0\',
				testy_name = \''.$this->text.'\',
	 			testy_pyt = \''.$t[0].'\',
				testy_odp1 = \''.$t[1][1].'\',
				testy_odp11 = \''.$r[1].'\',
				testy_odp2 = \''.$t[1][2].'\',
				testy_odp21 = \''.$r[2].'\',
				testy_odp3 = \''.$t[1][3].'\',
				testy_odp31 = \''.$r[3].'\',
				testy_odp4 = \''.$t[1][4].'\',
				testy_odp41 = \''.$r[4].'\'';


    if($this->test) echo '<code class=\'bred\'>'.$tab.'</code><br />';


	 if($tab = Db::myQuery($tab))
	 {
	  $id = mysql_insert_id();

	  if($id)
	  {
	   $this->trace[] = 'Dodano element testu '.$k.'=>'.$this->nr.'-'.$this->text.'->'.$t[0];

	   if($this->test)
		 echo '<code class=\'bgreen\'>'.$tab.'-> Dodano element testu '.$k.'=>'.$this->nr.'-'.$this->text.'->'.$t[0].'</code><br />';

		return $id;
	  }
	  else
		return 0;


	 }
	 else
	  $this->error[] =  'Problem z dodaniem elementu testu, line-> '.__LINE__;

	}

 }


 /**
 *
 * konwersja znaków narodowych ze strony xcportal'u
 *
 */

 private function convertChars($char)
 {
  // ą,ć,ę,ł,ń,ó,ś,ż,ź,

  $char = str_replace('&Auml;'.chr(133), 'ą', $char);
  $char = str_replace('&Auml;'.chr(132), 'Ą', $char); //?

  $char = str_replace('&Auml;'.chr(135), 'ć', $char); //??
  $char = str_replace('&Auml;'.chr(134), 'Ć', $char);

  $char = str_replace('&Auml;'.chr(153), 'ę', $char);
  $char = str_replace('&Auml;'.chr(152), 'Ę', $char); //?

  $char = str_replace('&Aring;'.chr(130), 'ł', $char);
  $char = str_replace('&Aring;'.chr(129), 'Ł', $char);

  $char = str_replace('&Aring;'.chr(132), 'ń', $char);
  $char = str_replace('&Aring;'.chr(131), 'Ń', $char); //?

  $char = str_replace('&Atilde;&sup3;', 'ó', $char);
  $char = str_replace('&Atilde;'.chr(147), 'Ó', $char);

  $char = str_replace('&Aring;'.chr(155), 'ś', $char);
  $char = str_replace('&Aring;'.chr(154), 'Ś', $char);

  $char = str_replace('&Aring;&ordm;', 'ź', $char);
  $char = str_replace('&Aring;&sup1;', 'Ź', $char);

  $char = str_replace('&Aring;&frac14;', 'ż', $char);
  $char = str_replace('&Aring;&raquo;', 'Ż', $char);

  $char = str_replace('&Atilde;&shy;', 'i', $char);	 // czeskie i z kreską Dolni Morawa

  return $char;
 }

 /*
 *
 * testowa - odczyt po znaku polskich znaków z kodu strony
 * pozwala odczytać kod znaków wyświetlanych jako piktogramy
 *
 */

 private function poznaku($string)
 {
  $s = str_split($string);

  foreach($s as $index => $char)
  {
   echo $char.' = '.ord($char).'|';
  }
 }



 /**
 *
 * zapisuje błędy do pliku błędów dla tego skryptu
 *
 */

 private function Error($file_name = './logs/boruch_error')
 {
  if($this->error)
  {
   $this->trace[] = 'ERROR SKRYPT';

	$this->error = implode("\r\n::", $this->error);

   $this->error = "\r\n->".date("d-m-Y h:i:s", time()).'-> Errors:' . $this->error;

	echo '<code>'.$this->error.'</code>';

	if(!file_exists($file_name . '.php'))
	 $this->error = '<? exit() ?>' . "\r\n" . $this->error;

   $h = fopen($file_name . '.php', 'a');

   fputs($h, $this->error);
   fclose($h);
   unset($h);

	if($this->test)
	  echo '<br />Error:<br />
		<code>'.$this->error.'</code>';
  }
  return;
 }

 /**
 *
 * funkcja - zapisuje do pliku ustawione znaczniki kontrolne, tylko  w trybie testowym
 * nie jest to historia tylko wynik ostatniego działania
 *
 */

 private function traceShow($t, $name = './logs/boruch', $ext = '.html')
 {
    //UWAGA! może by było lepiej gdyby Error miałą taką samą strukturę jak traceShow ?


  if(!file_exists($file_name . $ext)) $z = "
	 <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'
	xml:lang='PL'
	lang='PL'>
<head>
<meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\" />
<link rel=\"stylesheet\" href=\"./application/dlploty.css\" type=\"text/css\" media=\"screen\" />
</head>
<body>";

    if(is_array($t)) $t = implode(" <br />&nbsp;&nbsp;->", $t);

    $t = $z.' <br /><code>'."->".date("Y-m-d H:i:s", time()).' : '.$t.'</code>';

    $h = fopen($name.$ext, 'a');

    fputs($h, $t);

    fclose($h);

	 if($this->test)
	  echo '<br /><code>Trace show:</code><br />
		<code>'.$t.'</code>';

    unset($h, $t, $z);
 }

 /**
 *
 *
 */

 function __destruct()
 {
  //-kolejność obu skryptów jest ważna
  $this->Error();
  $this->traceShow($this->trace);

  unset($this->mmysql);
  unset($this->w);

  //echo Test::testShow();
 }

}
?>