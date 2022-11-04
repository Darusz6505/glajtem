<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
* klasa odczytywania statystyk z xcportalu
* dla wybranych startowisk
*
* 2021-03-08 - poprawki dla PHP 7...
* 2018-08-15
*
* autorem skryptu jest
* aleproste.pl Dariusz Golczewski -------- 2010-11-11 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/

class Naloty
{
 private $w = '';
 private $test = 1;					//-przełącznik dla komunikatów testowych


 private $jo = false;

 private $error = array();
 private $trace = array();

 private $por = array();

 private $adres = 'http://xcportal.pl';
 //href="/flights-table/2018-07-15

 private $tab_start = array(
    "Klin (Andrzejówka)",
    "Cerna Hora",
    "Kozakov",
    "Dzikowiec",
    "Srebrna Góra",
    "Mała Kopa",
    "Mieroszów",
    "Grzmiąca",
	 "Wolowa Góra",
	 "Rudnik",
	 "Czermna",
	 "Rog (Chełmsko Śląskie)",
	 "Dolni Morava",
	 "Lysa hora",
	 "Kamenec",
	 "Żmij"
);


 function __construct($id)
 {

  if(!$id)
  {

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
   require_once _CONPATH.'config_sql_dlploty'._EX;



   $this->jo = $c['jo'] = $c['ja'] = 1; //- wskaźnik Admina

   C::loadConfig($c);

   $this->mmysql = new Db;



   $this->zap_loty($id);

   //exit("<br />STOP");


   $this->statystyka(0);
  }
  else
  {
	$this->jo = C::get('jo');

  }

 }

 /**
 *
 * głowna funkcja programu
 *
 *
 */

 public function nalot($id)
 {

  $this->zap_loty($id);

  $this->statystyka($id);

  if($id && $this->w)
   return $this->w;
 }

 /**
 *
 *
 *
 */

 private function statystyka($id)
 {

  if(!$id)
  {
   echo "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'
	xml:lang='PL'
	lang='PL'>
<head>
<meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\" CHARSET=\"utf-8\" />

<style type=\"text/css\">

table#xc_nalot {display: inline-block; vertical-align: top; clear: right; margin: 0 0 1em; padding: 0;  border-collapse: collapse; background: #09F; background: RGBa(0, 150, 200, .75);}

table#xc_nalot td{
	text-align: center;
	border: 1px solid #CFC;
	margin: 0;
	padding: 0 .5em;
	color: #FF0;
	line-height: 300%;
}

table#xc_nalot td.nalot {text-align: right;}


</style>

</head>
<body>";

  $user = 15641;
  $adr = 'http://xcportal.pl/user/' . $user;
 }
 else
 {
  $this->w = "
  <style type=\"text/css\">

table#xc_nalot {display: inline-block; vertical-align: top; clear: right; margin: 0 0 1em; padding: 0;  border-collapse: collapse; background: #09F; background: RGBa(0, 150, 200, .75);}

table#xc_nalot td{
	text-align: center;
	border: 1px solid #CFC;
	margin: 0;
	padding: 0 .5em;
	color: #FF0;
	line-height: 300%;
}

table#xc_nalot td.nalot {text-align: right;}


</style>";

  $adr = $id;

 }

//$this->w .= '<br /><code>ID='.$id.'</code>';

  $loty = array();



  $kod = file_get_contents($adr);

  $kod = htmlentities($kod);

  $kod = explode("tbody", $kod);

  $kod11 = explode("views-field-name", $kod[0]);
  $kod11 = explode("&lt;/span", $kod11[1]);
  $kod11 = explode("&gt;", $kod11[0]);

	//echo '<br /><code>'.$kod11[2].'</code><br />';

	//views-field-name
	//echo '<br /><code>'.$kod[1].'</code><br />';

  $kod21 = explode("&lt;tr", $kod[1]);

	//echo '<br /><code>'.$kod21[1].'</code><br />';

  $lp = count($kod21);



  for($i = 1; $i <= $lp; $i++)
  {

   $tmp = explode("&lt;td", $kod21[$i]);

   $lp2 = count($tmp);

   for($j = 1; $j < $lp2; $j++)
   {
    $tmp[$j] = preg_replace("#&lt;/td&gt;#", '', $tmp[$j]);
	 $tmp[$j] = preg_replace("#&lt;/time&gt;#", '', $tmp[$j]);

	//echo '<br /><code>'.$tmp[$j].'=</code>';
	 $tmp[$j] = explode("&gt;", $tmp[$j]);

	//echo '<br /><code>'.$tmp[$j][1].'||</code>';
   }

   $dane[0] = $tmp[2][2]; //-data lotu
   $dane[1] = $tmp[3][1]; //-odległość lub punkty ??
   $dane[2] = $tmp[4][1]; //-czas lotu
   $dane[3] = $tmp[5][3]; //-czas startu

   $tmp2 = explode("&lt;", $tmp[5][2]);
   //echo '<br /><code>'.$tmp2[1].'</code>';

   $dane[4] = $this->convertChars(trim($tmp2[0])); //-miejsce startu

   $tmp3 = explode("&quot;", $tmp2[1]);

   //poznaku($tmp3[1]);

   $dane[5] = $tmp3[1]; //-dokładny czas startu


   $dane[6] = trim($tmp[6][2]); //-czas lądowania

   $tmp2 = explode("&lt;", $tmp[6][1]);
   $dane[7] = $this->convertChars(trim($tmp2[0])); //-miejsce lądowania

   $tmp3 = explode("&quot;", $tmp2[1]);
   $dane[8] = $tmp3[1]; //-dokładny czas lądowania

   $dane[9] = trim($tmp[7][2]); //-skrzydło

   $tmp2 = explode("&quot;", $tmp[8][1]);
   $dane[10] = $tmp2[1]; //-odnośnik do strony z lotem

   $loty[$i] = $dane;

   unset($dane, $tmp2, $tmp3);

  }

  $glajt = $start_place = array();

  $j = 0;

  foreach($loty as $k => $v)
  {

   $text .= '<br />';
   $text .= '<br /><code>Nr lotu : '.++$j.'</code>';

   for($n = 0; $n < 11; $n++)
    $text .= '<br /><code>'.$n.': '.$v[$n].'</code>'; //-wyświetla wszystkie loty

   $year = substr($v[0], 0, 4);

   //exit($v[0].'->'.$year);
   if($year)
    if(array_key_exists($year, $rok))
    {
     $rok[$year][0] = $rok[$year][0] + $this->fors($v[2]);
     $rok[$year][1]++;
    }
    else
    {
     $rok[$year][0] =  $this->fors($v[2]);
     $rok[$year][1] = 1;
    }


 	 if(array_key_exists($v[9], $glajt))  //-jeśli skrzydło jest w tablicy
 	 {
  	  $glajt[$v[9]][0] = $glajt[$v[9]][0] + $this->fors($v[2]);  //-dodajemy nalot dla skrzydła
  	  $glajt[$v[9]][1]++;
 	 }
 	 else
 	 {
  	  $glajt[$v[9]][0] = $this->fors($v[2]);	//-jeśli skrzydło pierwszy raz to czas pierwszego lotu
  	  $glajt[$v[9]][1] = 1;
 	 }


    $sum_nalot = $sum_nalot + $this->fors($v[2]); //-sumaryczny nalot pilota

 	 if($v[4])
  	  if(array_key_exists($v[4], $start_place))
  	  {
      $start_place[$v[4]][0]++;  //-ilość startów
   	$start_place[$v[4]][1] = $start_place[$v[4]][1] + $this->fors($v[2]); //-nalot z tego miejsca startu
  	  }
  	  else
  	  {
   	$start_place[$v[4]][0]++;
   	$start_place[$v[4]][1] = $this->fors($v[2]);
  	  }

  }

  //###############
    $test = '';

  if($this->jo)
	$test = '<td><code>TEST</code></td><td><code>odl.dlp</code></td>';

  $tabela = '
   <tr>
    <td><code> Data <code></td><td ><code> Start </code></td><td><code> XC </code></td><td><code> Trak </code></td>'.$test.'
   </tr>';


  foreach($loty as $k => $v)
  {
   $data = explode('-', $v[0]);

	if($data[0] == '2018' && $v[1] > 9.99)
	{
	 if(array_intersect(array($v[4]), $this->tab_start))
	 {


	 $spr = '';

	 if($this->jo)
	 {
	  if($this->por['http://xcportal.pl'.$v[10]])
	  {
	   $espr = '<a class=\'ed_dlp\' href=\''.S::linkCode(array($this->tabl, $this->por['http://xcportal.pl'.$v[10]][5], 'edycja','', $akcja)).'.htmlc\' title=\'Edytuj lot\'>&nbsp;EL</a>';

	   if($this->por['http://xcportal.pl'.$v[10]][3])
		 $spr = '<td>anulowany'.$espr.'</td>';
		else
	    $spr = '<td>jest'.$espr.'</td>';

		$spr.= '<td>'.$this->por['http://xcportal.pl'.$v[10]][1].'</td>';
	  }
	  else
	   $spr = '<td> <a class=\'ed_dlp\' href=\'dlploty.php?itss78ffH$tr='.$v[0].'\' title=\'Dodaj lot\' target=\'_blank\'>DODAJ</a> </td>';
    }


	 $tabela .= '
    <tr>
     <td><code>'.$v[0].'</code></td><td><code>'.$v[4].'</code></td><td><code>'.$v[1].'</code></td>
	  <td><code><a href="http://xcportal.pl'.$v[10].'" target=\'_blank\'>Trak</a></code></td>'.$spr.'
    </tr>';
    }
	}
  }

  if(!$id)
	echo '
    <br />
    <table id=\'xc_nalot\'>'.$tabela.'
    </table>';
  else
   $this->w .= '
    <br />
    <table id=\'xc_nalot\'>'.$tabela.'
    </table>';

	$this->w .= '
	 <div class=\'info\'>
	 <a class=\'more\' href=\'open+2018+dolnoslaska-liga-paralotniowa.html#'.$this->idd.'\' title=\'wróć do zestawienia\'>POWRÓT</a>
	</div>';


  unset($tabela);

  //##########

  $tabela = '
   <tr>
    <td><code>Liczba lotów</code></td><td><code>'.$lp.'</code></td>
   </tr>';

  $tabela .= '
 <tr>
  <td><code>Sezon<code></td><td ><code>lotów</code></td><td><code>nalot [h m s]</code></td>
 </tr>';

  foreach($rok as $k => $v)
  {
   $tabela .= '
 <tr>
  <td><code>'.$k.'</code></td><td><code>'.$v[1].'</code></td><td class=\'nalot\'><code>'.$this->forhms($v[0]).'</code></td>
 </tr>';
  }

  if(!$id)
	echo '
	 <br />
	 <table id=\'xc_nalot\'>'.$tabela.'
	 </table>';
  else
   $this->w .= '
	 <br />
	 <table id=\'xc_nalot\'>'.$tabela.'
	 </table>';

  unset($tabela);

  //-sortowanie i budowa wyniku

  //-nagłóek dla skrzydła
  $tabela .= '
   <tr>
    <td><code>Skrzydło</code></td><td><code>lotów</code></td><td><code>nalot [h m s]</code></td>
   </tr>';

  ksort($glajt);

  foreach($glajt as $k => $v)
  {

   if(!$k) $k = 'Brak danych';

   //echo '<br /><code>'.$k.' : '.$v.' => '.forhms($v).'</code>';


   $tabela .= '
   <tr>
    <td><code>'.$k.' </code></td><td><code>'.$v[1].'</code></td><td class=\'nalot\'><code>'.$this->forhms($v[0]).'</code></td>
   </tr>';

  }


  $tabela .= '
   <tr>
    <td ><code>RAZEM =</code></td><td><code>'.$this->forhms($sum_nalot).'</code></td>
   </tr>';


	if(!$id)
	 echo '
	  <table id=\'xc_nalot\'>'.$tabela.'
	  </table>';
	else
	 $this->w .= '
	  <table id=\'xc_nalot\'>'.$tabela.'
	  </table>';



   unset($tabela);



  $tabela = '
   <tr>
    <td><code>Miejsce<code></td><td ><code>lotów</code></td><td><code>nalot [h m s]</code></td>
   </tr>';

  ksort($start_place);

  foreach($start_place as $k => $v)
  {
   $sort[$k] = $v[0];
   $place[$k] = $k;
	$nalot[$k] = $v[1];
  }

  array_multisort($sort, SORT_DESC, $nalot, SORT_DESC, $place, SORT_ASC, $start_place);
  //array_multisort($volume, SORT_DESC, $edition, SORT_ASC, $data);


  foreach($start_place as $k => $v)
  {

   if(!$k) $k = 'Brak danych';

  //echo '<br /><code>'.$k.' : '.$v.' => '.forhms($v).'</code>';

   $tabela .= '
   <tr>
    <td><code>'.$k.'</code></td><td><code>'.$v[0].'</code></td><td class=\'nalot\'><code>'.$this->forhms($v[1]).'</code></td>
   </tr>';

  }


  if(!$id)
	echo '
    <br />
    <table id=\'xc_nalot\'>'.$tabela.'
    </table>';
  else
   $this->w .= '
    <br />
    <table id=\'xc_nalot\'>'.$tabela.'
    </table>';


  unset($tabela);

  //sortowanie według nalotu z danego startowiska	2018-09-14

  $tabela = '
   <tr>
    <td><code>Nalot<code></td><td ><code>Lotów</code></td><td><code>Miejsce</code></td><td><code>Średnio lot</code></td>
   </tr>';


  //ksort($start_place);

  foreach($start_place as $k => $v)
  {
   $sort[$k] = $v[1];
   $place[$k] = $k;
  }

  array_multisort($sort, SORT_DESC, $place, SORT_ASC, $start_place);
  //array_multisort($volume, SORT_DESC, $edition, SORT_ASC, $data);

  foreach($start_place as $k => $v)
  {

   if(!$k) $k = 'Brak danych';

  //echo '<br /><code>'.$k.' : '.$v.' => '.forhms($v).'</code>';

   $tabela .= '
   <tr>
    <td><code>'.$this->forhms($v[1]).'</code></td><td><code>'.$v[0].'</code></td><td><code>'.$k.'</code></td>
	 <td class=\'nalot\'><code>'.$this->forhms($v[1]/$v[0]).'</code></td>
   </tr>';

  }

  if(!$id)
	echo '
    <br />
    <table id=\'xc_nalot\'>'.$tabela.'
    </table>';
  else
   $this->w .= '
    <br />
    <table id=\'xc_nalot\'>'.$tabela.'
    </table>';


  unset($tabela);


  if(!$id)
   echo $text;

 }

/*
*
* przeliczenie na sekundy
*
*/

private function fors($x)
{
 $t = explode(':', $x);

 return ($t[0]*3600) + ($t[1]*60) + $t[2];
}

/*
*
* przeliczenie z sekund na H m s
*
*/

private function forhms($x)
{
 $h = floor($x / 3600);

 $x = $x - ($h * 3600);

 $m = floor($x / 60);

 $s = round($x - ($m * 60),0);


 if($m && $m < 10 && $h)
  $m = '0'.$m.'m';
 elseif($m)
  $m = $m.'m';
 elseif($h)
  $m = '00m';
 else
  $m ='';


 if($s && $s < 10)
  $s = '0'.$s.'s';
 elseif($s)
  $s = $s.'s';
 elseif($m)
  $s = '00s';
 else
  $s = '';

 if($h)
  $h = $h.'h';
 else
  $h = '';


 return $h.' '.$m.' '.$s;

}

/*
*
*
*
*/

private function convertChars($char) {
  // ą,ć,ę,ł,ń,ó,ś,ż,ź,

  $char = str_replace('&Auml;'.chr(133), 'ą', $char);
  $char = str_replace('&Auml;'.chr(132), 'Ą', $char); //?

  //$char = str_replace('&Auml;'.chr(133), 'ć', $char);
  //$char = str_replace('&Auml;'.chr(133), 'Ć', $char);

  $char = str_replace('&Auml;'.chr(153), 'ę', $char);
  $char = str_replace('&Auml;'.chr(152), 'Ę', $char); //?

  $char = str_replace('&Aring;'.chr(130), 'ł', $char);
  $char = str_replace('&Aring;'.chr(129), 'Ł', $char);

  $char = str_replace('&Aring;'.chr(132), 'ń', $char);
  $char = str_replace('&Aring;'.chr(131), 'Ń', $char); //?

  $char = str_replace('&Atilde;&sup3;', 'ó', $char);
  //$char = str_replace('&Atilde;&sup3;', 'Ó', $char);

  $char = str_replace('&Aring;'.chr(155), 'ś', $char);
  $char = str_replace('&Aring;'.chr(154), 'Ś', $char);

  $char = str_replace('&Aring;&ordm;', 'ź', $char);
  //$char = str_replace('&Aring;&ordm;', 'Ź', $char);

  $char = str_replace('&Aring;&frac14;', 'ż', $char);
  $char = str_replace('&Aring;&raquo;', 'Ż', $char);

  $char = str_replace('&Atilde;&shy;', 'i', $char);	 // czeskie i z kreską Dolni Morawa

  return $char;
 }

  /**
 *
 *
 *
 */

 private function zap_loty($id)
 {

  if(!$id)
  {
   $user = 15641;
   $adr = 'http://xcportal.pl/user/' . $user;
  }
  else
   $adr = $id;


	$this->tabl = C::get('tab_dpl18');
	$this->tabp = C::get('tab_piloci18');

	$tab = 'SELECT * FROM '.$this->tabp.' WHERE pil_xc = \''.$adr.'\'';

	//echo($tab."<br />");

	if($tab = DB::myQuery($tab))
    if($ta = mysqli_fetch_assoc($tab))
	 {
	  if($this->idd = $ta['pil_id'])
	  {
		$tab = 'SELECT * FROM '.$this->tabl.' WHERE dpl_data > \'2018-02-28\' AND dpl_data < \'2018-10-01\' AND dpl_pilot = '.$this->idd;

		if($tab = DB::myQuery($tab))
		 while($ta = mysqli_fetch_assoc($tab))
		 {
		  $this->por[$ta['dpl_track']] = array($ta['dpl_data'], $ta['dpl_km'], $ta['dpl_fai'], $ta['dpl_stref'], $ta['dpl_pilot'], $ta['dpl_id']);
		 }
	  }
	 }


   if(!$id)
   {
	 foreach($this->por as $k => $v)
	 {
	  $text.= '<code>'.$k.'</code><br />';
	  foreach($v as $k1 => $v1)
	  {
	   $text.= '<code>&nbsp;&nbsp;'.$v1.'</code><br />';
	  }
	 }
    echo $text;
	}

 }


 /**
 *
 * zapisuje błędy do pliku błędów dla tego skryptu
 *
 */

 private function Error($file_name = './logs/dlp_naloty_error')
 {
  if($this->error)
  {
   $this->trace[] = 'ERROR SKRYPT';

	$this->error = implode("\r\n::", $this->error);

   $this->error = "\r\n->".date("d-m-Y h:i:s", time()).'-> Errors:' . $this->error;

	if($this->test && !$this->w)
	 echo '<code>'.$this->error.'</code>';

	if(!file_exists($file_name . '.php'))
	 $this->error = '<? exit() ?>' . "\r\n" . $this->error;

   $h = fopen($file_name . '.php', 'a');

   fputs($h, $this->error);
   fclose($h);
   unset($h);

	if($this->test && !$this->w)
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

 private function traceShow($t, $name = './logs/dlp_naloty_trace')
 {
    //UWAGA! może by było lepiej gdyby Error miałą taką samą strukturę jak traceShow ?


  if(!file_exists($file_name . '.html')) $z = "
	 <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'
	xml:lang='PL'
	lang='PL'>
<head>
<meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\" />
<link rel=\"stylesheet\" href=\"./application/dlploty.css\" type=\"text/css\" media=\"screen\" />
</head>
<body>";

    if(is_array($t)) $t = implode("\r\n->", $t);

    $t = $z.'<br /><code>'."\r\n->".date("Y-m-d H:i:s", time()).' : '.$t.'</code>';

    $h = fopen($name.'.html', 'a');

    fputs($h, $t);

    fclose($h);

	 if($this->test && !$this->w)
	  echo '<br />Trace show:<br />
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

 }

}
?>