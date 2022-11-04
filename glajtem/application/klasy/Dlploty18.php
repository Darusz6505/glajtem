<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
* klasa atomatycznego pobierania lotów z portalu xcportal
* dla wybranych startowisk
*
* 2018-08-11
* 2018-08-07, 2019-07-03 -> Hodkovice
*
* autorem skryptu jest
* aleproste.pl Dariusz Golczewski -------- 2010-11-11 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/

class Dlploty18
{
 private $test = 1;					//-przełącznik dla komunikatów testowych
 private $strona = 0;

 private $mmysql = ''; 				//-uchwyt do połaczenia MySQL
 private $w = '';

 private $akcja = '';
 private $opcja = '';

 private $tabp = '';
 private $tabl = '';

 private $jo = false;

 private $tab_loty = '';
 private $tab_pilot = '';

 private $error = array();
 private $trace = array();

 private $loty = array();
 private $blokada = ''; 			//-ogólna blokada loty przez ustawinie naruszenie stref

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
	 "Żmij",
	 "Hodkovice"
);


 function __construct()
 {
   if($_SERVER['HTTP_X_FORWARDED_FOR'])											//-prawdziwe ip - bez funkcji ippraw()
   $c['ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
  else
   $c['ip'] = $_SERVER['REMOTE_ADDR'];


  if(!preg_match('^127\.', $c['ip']) || $c['set_remote'])
   $c['db_local'] = false;
  else
   $c['db_local'] = true;

  unset($c['set_remote']);

  if(!preg_match('^127\.', $c['ip']))
   $c['localhost'] = false;
  else
   $c['localhost'] = true;


  require_once _CONPATH.'config_def_min'._EX; //-zrobić config_min_def,  z minimalną potrzebną ilością ustawień
  require_once _CONPATH.'config_sql_dlploty'._EX;

  /*
  if($c['db_local'])
   echo '<br /><code>db_local = True</code><br />';
  else
	echo '<br /><code>db_local = False</code><br />';

  exit('<br />STOP - 106'); */

  $this->jo = $c['jo'] = $c['ja'] = 1; //- wskaźnik Admina

  C::loadConfig($c);

  $this->mmysql = new Db;

  if($this->year == 2018)
  {
	$this->tab_loty = C::get('tab_dpl18');
   $this->tab_pilot = C::get('tab_piloci19');
  }
  else
  {
	$this->tab_loty = C::get('tab_dpl18');
   $this->tab_pilot = C::get('tab_piloci20');
  }

  //}
  //$this->akcja = C::Get('akcja');
  //$this->opcja = C::Get('opcja');
  //Test::trace(__METHOD__ .' parametry  -> ', $parametry);

  $this->loty();
 }

 /**
 *
 * głowna funkcja programu
 *
 *
 */

 private function loty()
 {
 /** UWAGA !!! przy wdrożeniu skryptu
  //
  //-dla uporządkowania tabeli DPL 2018
  //-kasaować loty z roku przed 2018
  $this->kasuj_loty();
  exit('<br />STOP Kasuj loty przed 2018');

  //-Uporządkować tabelę pilotów, mogą zosstać tylko Ci którzy posiadają profil na xcportal'u
  $this->piloci();
  exit('<br />STOP piloci bez xcportalu');

  */
  //echo '<br /><code>Działanie dla Crona -> jest w klasie Dlploty18!</code>';


  $data_por = explode("-", date('Y-m-d', time()));


  if(isset($_GET['itss78ffH$tr']))
  {
   if(!$data = C::odbDane($_GET['itss78ffH$tr']))
	{
	 $this->error[] =  'brak odebranej daty, line-> '.__LINE__;
	}
   else
   {
	 if(substr($data, -1) == 'x')
	 {
	  $this->strona = 1; //-wejście ze strony włącza komunikaty
	  $this->trace[] = 'Wejście ze strony -> '.$data;
	 }
	 else
	  $this->trace[] = 'Wejście z Crona -> '.$data;

    $data = substr($data, 0, 10);

	 $data_por2 = explode("-", date('Y-m-d', strtotime($data)));

    $datax = array(0, 0, 0);

    if(!is_array($datax = explode("-", $data)))
	  $this->error[] =  'zły format daty yyyy-mm-dd -1, line-> '.__LINE__;
    elseif(count($datax) <> 3)
	  $this->error[] =  'zły format daty yyyy-mm-dd -2, line-> '.__LINE__;
    elseif($datax[0] > $data_por[0] || $datax[0] < $data_por[0] || strlen($datax[0]) <> 4) //-rok tylko obecny
	  $this->error[] =  'zły rok, line-> '.__LINE__;
    elseif($datax[1] < 1 || $datax[1] > $data_por[1]) //-miesiąc
	  $this->error[] =  'zły miesiąc, line-> '.__LINE__;
    elseif($datax[2] < 1 || $datax[2] > $data_por2[2]) //-dzień
	  $this->error[] =  'zły dzień, line-> '.__LINE__;
	 elseif($datax[1] > 9 || $datax[1] < 3)
	  {
	   $this->trace[] = 'data poza zakresem, line-> '.__LINE__;
      $this->error[] = 'data poza zakresem, line-> '.__LINE__;
	  }

	  /*
	  elseif($datax[2] < 1 || $datax[2] > $data_por[2]) //-dzień
	  $this->error[] =  'zły dzień, line-> '.__LINE__;
	  $data_por2[0] = rok
	  $data_por2[1] = miesiąc

	  $data_por2[2] = dzień


		 */

    //-blokada w roku 2020 : epidemia

	 if($data_por2[2]>0 && $data_por2[1]>2 && $data_por2[1]<7 && $data_por2[0] == 2020)
	 {
	  $this->blokada = 1;
	  $this->trace[] = 'data w okresie blokady, line-> '.__LINE__;

	 }
	 else
	  $this->blokada = 0;


	}
  }
  else
  {
   $this->trace[] = 'brak przesłanej daty, line-> '.__LINE__;
   $this->error[] = 'brak przesłanej daty, line-> '.__LINE__;
  }




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
	 echo '<br /><code>Wejście ze strony (dodawanie lotów przez link z kodem jako parametr).</code><br />';

  if($this->error)
  {
	if($this->strona)
	 echo '<br /><code>Błąd danych wejściowych. Exit! '.__LINE__.'</code><br />';
	return(0);
  }

  if($this->strona)
  	echo '<br /><code>'.$data.'</code><br />';
  /*
   echo '<br /><code>'.$data.'</code><br />';
   exit('STOP');
  */

   $root = './igc/';		 // katalog główny
   $fname = 'dlp_';  	 // przedrostek nazwy katalogi

   //$loty = array();

	$tab_w = array();

   //$data = '2018-07-02'; //-ustawiona wyżej
	// 2018-05-25_Pilot Imię_wynik [km or pkt].igc = format pliku z lotem

   $path = $root . $fname . $data; // nazwa katalogu w plikami igc z danego dnia

   if($this->test) echo '<br /><code>'.$path.'</code><br />';

   //$adr = "http://xcportal.pl/flights-table/2018-05-31";

   $adr = $this->adres.'/flights-table/'.$data;

	if($this->test) echo '<br /><code>'.$adr.'</code><br />';

   $kod1 = file_get_contents($adr);

   $kod1 = htmlentities($kod1);

   $kod1 = explode("tbody", $kod1);

	//$this->trace[] = 'Zawartość strony = '.strlen($kod1[1]).' '.__LINE__;

	//echo '<br /><code>'.$kod[1].'</code><br />';

   $kod1[1] = preg_replace("#&lt;/tr&gt;#", "", $kod1[1]);
   $kod1[1] = preg_replace("#&lt;/td&gt;#", "", $kod1[1]);
   $kod1[1] = preg_replace("#&lt;time#", "&lt;td", $kod1[1]);

   $kod = explode("&lt;tr", $kod1[1]);
   unset($kod1);

   $lp = count($kod);

   //$this->show($kod3, 1, count($kod3));
	//exit('<br />STOP');

	//-utworzenie tablicy lotów z wybranych startowisk, ze strony dziennych lotów xcportal
   for ($i = 1; $i <= $lp; $i++)
   {
    $tmp = explode("&lt;td", $kod[$i]);

    $xx = $this->loty_dlp($tmp);

	 //if($xx) $this->show($xx, 0, 3); //-test bazy wstępnej

    if($xx) $tab_w[] = $xx; //-tabela z ograniczoną ilością danych ze strony wykazu dziennych lotów
   }
	//exit('<br />STOP');

	//-utworzenie pełnej tablicy lotów, po przetworzeniu stron dla poszczególnych lotów
   foreach ($tab_w as $k => $v)
   {
    $lot = $this->onefly($v, $data);

	 if(is_array($lot)) $this->loty[] = $lot;

	 unset($lot);
   }
   unset($k, $v);

   $tx = count($this->loty);
	$new = 0;

	$this->trace[] = 'Licznik tablicy lotów = '.$tx;

	if($tx > 0)
	{

    foreach($this->loty as $k => $v)
    {
     if($this->test)
		$this->show($v, 0, 10);

     $id = $this->zap_pilota($v);

	  if($id)
	  {
		if($this->zap_lot($id, $v))
		 $new++;

	  }
	  else
	   $this->trace[] = 'Brak ID pilota !! -> '.__LINE__;

     if($this->test) echo "<br />";
    }

	 // do testów wyłączony !!! zapis igc // save_igc($path, $loty, $data);
	 //-zapis wszystkich plików igc dla lotów z danego dnia

	 if($new)
	  $this->save_igc($path, $this->loty, $data); //-poprawić wewnątrz funkcji zmienną loty na $this->loty
	 else
	  $this->trace[] = 'Brak nowych lotów w dniu '.$data;

	}
	else
	{
	 $this->trace[] = 'Brak lotów w dniu '.$data;

	 if($this->test) echo '<code style=\'color: red;\'>Brak lotów w dniu '.$data.'</code>';
   }

 if($this->test) echo '
  </body>
  </html>
 ';
 }



 /** !!!!!!!!!
 * sprawdza czy jest w bazie lot i jeśli nie ma dodoaje lot do bazy
 * zwraca 1 jeśli ok., 0 - jeśli błąd
 *
 */

 private function zap_lot($d, $v)
 {
  if(!$d)
  {
   $this->error[] =  'Brak id pilota do zapisu w tabeli lotów!, line-> '.__LINE__;
	return 0;
  }

  if(count($v) < 8)
  {
	$this->error[] =  'Brak lub nie kompletna tabela danych do zapisu lotów!, line-> '.__LINE__;
	return 0;
  }


  //-przeszukujemy po traku - co jest oczywiste

  $tab = 'SELECT * FROM '.$this->tab_loty.'
		 	 WHERE dpl_track=\''.$v[3].'\' AND dpl_pilot=\''.$d.'\' AND dpl_data = \''.$v[10].'\'';


  if($tab = Db::myQuery($tab))
   if($ta = mysqli_fetch_assoc($tab))
	{
    //-lot już istniej !!!
	 if($this->test)
	 {
	  echo '<code class=\'bred\'>Lot już istnieje</code><br />';
	  echo '<code class=\'bred\'>'.$ta['dpl_track'].'</code><br />';
	  echo '<code class=\'bred\'>'.$ta['dpl_pilot'].'</code><br />';
	  echo '<code class=\'bred\'>'.$ta['dpl_data'].'</code><br />';
	  echo '<code class=\'bred\'>'.$ta['dpl_stref'].'</code><br />';
	 }
    //-jeśi jest już taki track
	 return 0;
	}
	else
	{
	 //-dodanie lotu do bazy
	 //if($this->test) echo '<code class=\'bgreen\'>Dodanie lotu do bazy.</code><br />';


	 $tab = 'INSERT INTO '.$this->tab_loty.' SET dpl_pilot = \''.$d.'\', dpl_stref = \''.$this->blokada.'\', dpl_data = \''.$v[10].'\', dpl_km = \''.$v[6].'\', dpl_fai = \''.$v[7].'\', dpl_track = \''.$v[3].'\'';


	 //if($this->test) echo '<code class=\'bred\'>'.$tab.'</code><br />';


	 if($tab = Db::myQuery($tab))
	 {

	  //$id = mysqli_insert_id(); //po zmianie na 7.00 potrzebny jest parametr połączenia z bazą

	  if($id = C::get('my_id'))
	  {
	   $this->trace[] = 'Dodano lot z dnia '.$v[10].'->'.$v[6].' '.$v[7].'->'.$v[3].'->'.$v[1];

	   if($this->test)
		 echo '<code class=\'bgreen\'>'.$tab.'-> Dodano lot z dnia '.$v[10].'->'.$v[6].' '.$v[7].'->'.$v[3].'->'.$v[1].'</code><br />';

		return $id;
	  }
	  else
	  {
	   $this->trace[] = 'NIE Dodano lotu z dnia '.$v[10].'->'.$v[6].' '.$v[7].'->'.$v[3].'->'.$v[1].':'.$id;
		return 0;
	  }

	 }
	 else
	 {
	  $this->error[] =  'Problem z dodaniem lotu, line-> '.__LINE__;
     $this->trace[] =  'Problem z dodaniem lotu, line-> '.__LINE__;
	 }
	}

 }

 /**
 * sprawdza czy jest w bazie pilot i jeśli nie ma dodaje pilota do bazy
 * zwraca nr id pilota
 *
 */

 private function zap_pilota($d)
 {
  if(count($d) < 8)
  {
   $this->error[] =  'Brak danych do zapisu w tabeli pilotów!, line-> '.__LINE__;
	return 0;
  }

  //szukamy tylko pilotów, którzy mają profil w xcportalu !!!!

	/*
	$pol = 'SELECT * FROM '.C::get('tab_pola').'
		 			WHERE loka=\''.$l['nazw'].'\' '.$war.'
					ORDER BY stat DESC'; */


  $tab = 'SELECT * FROM '.$this->tab_pilot.' WHERE pil_xc=\''.$d[0].'\'';

  if($tab = Db::myQuery($tab))
   if($ta = mysqli_fetch_assoc($tab))
	{
    if($this->test)
	 {
	  echo '<br /><code class=\'bgreen\'>'.$ta['pil_name'].'</code><br />';
	  echo '<code class=\'bgreen\'>'.$ta['pil_xc'].'</code><br /><br />';
	 }

	  //if($this->test) echo '<code class=\'bgreen\'>'.$ta['pil_id'].'</code><br /><br />';

	  //$this->trace[] = 'Pilot '.$ta['pil_name'].' odnaleziony w bazie';
	  return($ta['pil_id']);
	}
	else
	{
    $this->trace[] = 'Brak pilota '.$ta['pil_name'].' w bazie!';

	 $pil_name = explode(' ', $d[1]);
	 $pil_name = $pil_name[1].' '.$pil_name[0].'*';

	 $tab = 'INSERT INTO '.$this->tab_pilot.' SET
	 	pil_glajt = \'O\', pil_name = \''.$pil_name.'\', pil_opis = \''.$d[2].'\', pil_xc = \''.$d[0].'\'';

	 //if($this->test) echo '<code class=\'bred\'>'.$tab.'</code><br />';


	 if($tab = Db::myQuery($tab))
	 {
	  $id = mysqli_insert_id();

	  if($id)
	  {
	   if($this->test)
		 echo '<br /><code class=\'bgreen\'>Dodano pilota: '.$pil_name.'</code><br />';

	   $this->trace[] = 'Dodano pilota '.$pil_name.' w bazie!';
		return($id);
	  }
	  else
	   {
		 $this->error[] = 'Brak ID pilota: '.$pil_name.', line-> '.__LINE__;
		 $this->trace[] = 'Brak ID pilota: '.$pil_name.', line-> '.__LINE__;
		}
	 }
	 else
	 {
	  $this->error[] = 'Błąd dodania pilota do bazy!, line-> '.__LINE__;
	  $this->trace[] = 'Błąd dodania pilota do bazy!, line-> '.__LINE__;
	 }

	}

   /*
	//-nie ma pilota w bazie -> dodajemy do bazy
	//-dla nowego pilota -> $this->id = mysql_insert_id();
	//-INSERT INTO $this->t SET $qu
	//- $tab = mysql_query("INSERT INTO $tabela SET nazwa='$naz' $defd");
	$query[] = 'tr_dapu = \''.$time.'\'';
	$query[] = 'tr_dado = \''.$time.'\'';
	$query[] = 'tr_blok = 1';

	$query = implode(', ',$query); */

 }

 /**
 *
 * zapis traków igc
 * wszystkich w pętli
 *
 */

 private function save_igc($path, $lot = array(), $data)
 {
  /*
	sprawdzenie czy istnieje folder dlp_$data
   jeśli nie to założenie folderu
	zapis wszystkich plików igc

   jeśli istniał folder dlp_$data
	sprawdzenie czy pliki już istnieją
	jeśli któregoś brak to zapisujemy
  */

  $test = $this->test;  //-lokalny wskaźnik testu, tylko dla tej funkcji

  if(!file_exists($path))
	if(!mkdir($path, 0777))
	{
	 $this->error[] =  'Nie można utworzyć katalogu! '.$path.', line-> '.__LINE__;
	 return 0;
	}

	//-zapisuje wszystkie pliki

	if($test) echo '<br /><code>Katalog '.$path.' ok!</code>';

	foreach($lot as $k => $v)
	{

	 if($test) echo '<br /><code>'.$v[5].'</code>';

	 //$pilot_name = korekta_pl($v[1]);
	 //$v[1] = $pilot_name;

	 $pilot = explode(" ", $v[9]);
	 $v[1] = substr($pilot[0], 0, 1).strtolower(substr($pilot[0], 1)).' '.substr($pilot[1], 0, 1);

	 $plik_name = $data.'_'.$v[1].'_'.$v[6].' '.$v[7]; //'.igc';
	 $plik_name = preg_replace('#\.#', ',', $plik_name).'.igc';

	 if($test) echo '<br /><code>psth = '.$path.' => plik = '.$plik_name.'</code>';

	 if(!file_exists($path.'/'.$plik_name))
	 {

		$plik_igc = file_get_contents($v[8], true);
		$plik_test = strlen($plik_igc);

	   $fp = fopen($path.'/'.$plik_name, 'wb');

      if($fp && $plik_test > 0)
		{
		 if(fwrite($fp, $plik_igc))
		 {
        fclose($fp);

		  if($this->test) echo '<br /><code class=\'green\'>Zapisany = '.$path.'/'.$plik_name.'</code>';

		  //show($v, 0, 10); //-wyświetla dane jednego lotu  po zapisaniu pliku
		  // 2018-08-03 tutaj ? => zap_do_bazy($v); //-zapis lotu do bazy

		 }
		 else
		 {
		  $this->error[] =  'Problem z zapisem pliku! = '.$path.'/'.$plik_name.', line-> '.__LINE__;

        if($test)
		  {
		   echo '<br /><code>uchwyt fp = '.$fp.'</code>';
		   echo '<br /><code>rozmiar pliku = '.$plik_test.'</code>';
		   echo '<br /><code>zawartość pliku = '.$plik_igc.'</code>';
		   echo '<br /><code>link do pliku = '.$v[8].'</code>';
		  }

		  fclose($fp);
		  unlink($path.'/'.$plik_name);
		 }
		}
		else
		{
		 $this->error[] =  'Nie można utworzyć pliku! = '.$path.'/'.$plik_name.', line-> '.__LINE__;

		 if($test)
		 {
		  echo '<br /><code>uchwyt fp = '.$fp.'</code>';
		  echo '<br /><code>rozmiar pliku = '.$plik_test.'</code>';
		  echo '<br /><code>zawartość pliku = '.$plik_igc.'</code>';
		  echo '<br /><code>link do pliku = '.$v[8].'</code>';
		 }

		 fclose($fp);
		 unlink($path.'/'.$plik_name);
		}

	  }
	  else
	   if($this->test)
		 echo '<br /><code class=\'blue\'>Plik '.$path.'/'.$plik_name.' już istnieje!</code>';


		unset($plik_name, $pilot_name, $plik_test, $plik_igc, $fp);
	 }

  if($this->test) echo '<br />';
 }

 /**
 *
 * dane dotyczące jednego lotu
 * dekodowane ze strony lotu wybranego lotu
 *
 */

 private function onefly($tab, $data)
 {

  $adr1 = $this->adres . $tab[3];
  //$adr1 = 'http://xcportal.pl' . $tab_w[0][3];

  $kod = file_get_contents($adr1);
  $kod = htmlentities($kod);

  $kod = explode("view-content", $kod); //  view-content - znaleść lepszy znacznik

  //echo '<br /><code>' . $kod[1] . '</code>';
  //echo '<br /><code>' . $kod[2] . '</code>';

  $kod[1] =  $kod[1].$kod[2];
	//- jeśli są komentarze - jeśli są opisy pilota układ strony się zmienia i wpływa na położenie danych

  $kod10 = explode("&lt;/div&gt;", $kod[1]);

  $odlxc = array($kod10[12], $kod10[14], $kod10[16]);

  $odl = explode('&gt;', $odlxc[0]);
  $odl = preg_replace("#km#", "", $odl);

  $xc[0] = $odl[2]*1.5;						//-rójkąt FAI	 *1.5

  $odl = explode('&gt;', $odlxc[1]);
  $odl = preg_replace("#km#", "", $odl);
  $xc[1] = $odl[2]*1.3;						//-trójkąt płaski *1.3

  $odl = explode('&gt;', $odlxc[2]);
  $odl = preg_replace("#km#", "", $odl);
  $xc[2] = $odl[2]*1;						//-odległość po prostej

  $lot[6] = round(max($xc), 2); 			//-zaokrąglenie do x.xx

  if($lot[6] < 15) return 0;			//-ograniczenie do 15 pkt.

  if($lot[6] > $xc[2])
   $lot[7] = 'pkt';
  else
   $lot[7] = 'km';

  //-strefy

  $strefy = explode("field-name-field-airspace-violation-status", $kod[1]);	//-status naruszenia strefy
  $strefy = explode("&lt;/div&gt;", $strefy[1]);
  //echo '<br /><code>' . $strefy[1] . '</code>';
  $lot[5] = $this->convertChars($strefy[1]);

  //-skrzydło

  $glajt = $kod10[0];
  $glajt = preg_replace("#&lt;/span&gt;#", "", $glajt);
  $glajt = explode("producent_logo_medium/public/", $glajt);

  $kod11 = explode("&gt;", $glajt[1]);
  $kod12 = explode(".", $kod11[0]);

  $lot[2] = trim($kod12[0].$kod11[1]);													//-skrzydło

  //-pilot

  $pilot = $kod10[3];
  $pilot = explode("href=", $pilot);
  $pilot = explode("&gt;", $pilot[1]);

  $lot[0] = $this->adres.preg_replace("#&quot;#", "", $pilot[0]); 		 	//-link do profilu pilota
  $lot[1] = $this->convertChars(preg_replace("#&lt;/a#", "", $pilot[1]));  //-Imię i nazwisko pilota
  $lot[9] = $this->korekta_pl($lot[1]);

  $lot[3] = $this->adres.$tab[3]; 													   // link do lotu
  $lot[4] = $tab[0];

  //-link igc

  $link = explode("field-name-field-flight-track-file", $kod[1]);				//-link do igc
  $link = explode("&lt;/div&gt;", $link[1]);

  $igc = explode("href=", $link[0]);
  $igc = explode("type", $igc[1]);
  $lot[8] = trim(preg_replace("#&quot;#", "", $igc[0]));				 			//-link igc

  $lot[10] = $data;																			//-data lotu

  unset($xc, $odlxc, $kod, $kod10, $kod11, $kod12, $link, $strefy, $odl);

  //show($lot, 0, 10);

  $ten_sam = 0;
  //!! ale trzeba wybrać ten lepszy

  foreach($this->loty as $key => $val)
  {
   if($tmp_pilot = array_intersect($val, array($lot[0])))
	{
	 $ten_sam = $key;
	 //$ten_sam_val =  $val[6];

	 //echo '<br /><code>Ten sam pilot = '.$tmp_pilot[0].' => '.$key.'</code>';
	}
  }

  if($ten_sam)
  {
	if($this->loty[$ten_sam][6] > $lot[6]) //-jeśli wcześniej był juź lot tego pilota z lepszym wynikiem
	 $lot = 0;
	else
	 unset($this->loty[$ten_sam]); //-jeśli był z gorszym to go kasujemy
  }

  unset($ten_sam);

  return $lot;
 }

 /*
 * wybranie lotów z wybranych startowisk
 * ze strony lotów dla wybranego dnia
 *
 */

 private function loty_dlp($kod4)
 {

  $w = array();

  //miejsce startu
  $temp = explode("/&gt;", $kod4[4]);

  //echo '<br /><code>1->'.trim($kod4[4]).' </code>';

  //$temp[1] = $this->convertChars(trim($temp[1]));

  //$temp[1] = trim($temp[1]);

  //-test znaków narodowych
  //echo '<br /><code>0->'.$temp[0].' </code>';
  //echo '<br /><code>1->'.trim($temp[1]).' </code>';

  //$this->poznaku($temp[1]);

  //$tempx = array();

  /*
  if(in_array($temp[1], $this->tab_start))
	echo '<br /><code>jest->'.$temp[1].'</code>';
  else
  {
	echo '<br /><code>tt->'.$this->tab_start[0].'</code>';
	$this->poznaku($this->tab_start[0]);
	echo '<br /><code>ss->'.$temp[1].'</code>';
	$this->poznaku($temp[1]);
	echo '<br /><code>ss->'.$this->convertChars(trim($temp[1])).'</code>';
	$this->poznaku($this->convertChars(trim($temp[1])));

  }	 */

  $temp[1] = $this->convertChars(trim($temp[1]));

  //echo '<br /><code>count->'.count($tempx).' </code>';

  if($tempx = array_intersect($temp, $this->tab_start))
  {
	//echo '<br /><code>x->'.$tempx[1].' </code>';

   $w[0] = $tempx[1];
  }
  else
  {
   return;
   $w[0] = '*' . $temp[1];
  }


  //wynik
  $temp = explode("&gt;", $kod4[3]);
  $temp = explode("&lt;span", $temp[1]);
  $temp[0] = trim($temp[0]);

  if($temp[0] < 10) return 0;

  $w[1] = $temp[0];

  //pilot
  $temp = explode("/&gt;", $kod4[2]);
  $temp = explode("&lt;", $temp[1]);
  $temp[0] = trim($temp[0]);

  //-test znaków narodowych
  //echo '<br /><code>'.$temp[0].' </code>';
  //$this->poznaku($temp[0]);

  $w[2] = $this->convertChars($temp[0]);

  //link do strony lotu
  $temp = explode("&lt;a", $kod4[9]);
  $temp = explode("&gt", $temp[1]);
  $temp = explode("&quot;", $temp[0]);
  $temp[1] = trim($temp[1]);

  $w[3] = $temp[1];

  return $w; //-przepisanie wyników do tablicy
 }

 /**
 *
 * konwerter polskich znaków na literę x 3 np. Ł => LLL
 * do naw plików igc
 *
 */

 private function korekta_pl($t)
 {

  $na = array(
	'aaa', 'AAA', 'ccc', 'CCC', 'eee', 'EEE', 'lll', 'LLL', 'nnn', 'NNN', 'ooo', 'OOO', 'sss', 'SSS', 'zzz', 'ZZZ', 'xxx', 'XXX');

  $to = array('/ą/', '/Ą/', '/ć/', '/Ć/', '/ę/', '/Ę/', '/ł/', '/Ł/', '/ń/', '/Ń/', '/ó/', '/Ó/', '/ś/', '/Ś/', '/ż/', '/Ż/', '/ź/', '/Ź/');

  $t = preg_replace($to, $na, $t);

  unset($to, $na);

  return $t;
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
  $char = str_replace('&oacute;', 'ó', $char);
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

 /*
 *
 * testowa-wyświetla elementy tablicy od i do j
 *
 */

 private function show($w, $i, $j)
 {

  echo '<br />';

  for ($c = $i; $c <= $j; $c++)
   echo '
  	<br /><code>'.$c.'=> ' . $w[$c] . ' <=</code>';
 }

 /**
 * funkcja porządkowa - wdrożeniowa
 * wyświetla bazę wszystkich pilotów
 * z informają o profilu na XCportal'u
 * do porządkowania bazy przy wdrożeniu
 */

 private function piloci()
 {

    $tab = 'SELECT * FROM '.$this->tab_pilot; /*.'
		 			WHERE pil_xc=\'\''; */

  if($tab = Db::myQuery($tab))
   while($ta = mysqli_fetch_assoc($tab))
	{
	 if(!$ta['pil_xc'])
	  echo '<code>Bez konta na xcportal:'.$ta['pil_name'].'</code><br />';
	 else
	 {
	  $server = explode('//', $ta['pil_xc']);
	  $server = explode('/', $server[1]);

	  if($server[0] <> 'xcportal.pl')
	   echo '<code>Bez konta na xcportal:'.$ta['pil_name'].'</code><br />';
	 }
	}
  else
   echo '<code>Brak pilotów bez konta w xcportal</code><br />';

 }

 /**
 * funkcja porządkowa-wdrożeniowa
 * kasująca loty z lat wcześniejszych od 2018
 * do porządkowania bazy przy wdrożeniu
 *
 */

 private function kasuj_loty()
 {

 	 $tax = 'DELETE FROM '.$this->tab_loty.' WHERE dpl_data < \'2018-01-01\'';

	 if(!DB::MyQuery($tax))
	  echo '<code style=\'color: bred;\'>Nie udało się skasować lotów z przed 2018 r.</code><br />';
 }

 /**
 *
 * zapisuje błędy do pliku błędów dla tego skryptu
 *
 */

 private function Error($file_name = './logs/dlploty21_error')
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
	  echo '<br /><code>Error:</code><br />
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

 private function traceShow($t, $name = './logs/dlploty21_trace', $ext = '.html')
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