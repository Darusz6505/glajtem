<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* wtyczka dedykowana: dla glajtem.pl -> 2017, 2018
*
* 2021-01-09 : modyfikacje do wersji PHP 7.xx
*
* 2020-02-29 - aktualizacja na nowy sezon 2020
* 2018-07-31 - dodatkowe dane w statystykach
*
*
* 2016-03-04
* 2016-01-24
* 2013-09-10
*
*
* autorem skryptu jest
* aleproste.pl Dariusz Golczewski -------- 2009-11-11 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/


class Dlp2017
{
 private $w = '';

 private $akcja = '';
 private $opcja = '';

 private $tabp = '';
 private $tabl = '';

 private $jo = false;

 private $month = array('', '', '', 'marzec', 'kwiecień', 'maj', 'czerwiec', 'lipiec', 'sierpień', 'wrzesień', 'październik','zakończona','');
 private $years = array('2015','2016','2017','2018', '2019', '2020', '2021');

 private $start_end = array('2016' => array(1,3,30,9),
  						  	 		 '2017' => array(1,3,30,9),
									 '2018' => array(1,3,30,9),
									 '2019' => array(1,3,30,9),
									 '2020' => array(1,7,30,9),
									 '2021' => array(1,3,30,9)
									 );

 private $nrMonthNow = '';
 private $nrMonthNow2 = '';
 private $nrMonthNow3 = '';

 private $nrMonth = '';
 private $klasa = '';

 private $ended = '';					//-koniec rywalizacji + 1 dzień.
 private $endmonth = 9;

 private $year = '';						//-rok ??
 private $rokNow = '';

 private $dniPrzed = 5;					//-dodatkowe puste kolumny
 private $dodWierPo = 17;

 private $mmonth = '';					//-element linku miesiąc
 private $klase = '';					//-element linku klasa

 private $wrok = '';						//-wybrany rok

 private $pil = FALSE;					//-znacznik wyboru strony -> pilot
 private $pilot = 0;						//-identyfikator pilota
 private $xx = '';						//-link do xcportalu dla profilu pilota

 private $stat = array('licz_dni_lot' => 0,'licz_lot' => 0, 'licz_pil' => 0, 'rekord' => array(), 'km' => 0,
 							 'dni_mie' => 0, 'lot_mie' => 0, 'pil_mie' => 0, 'rek_mie' => array(), 'km_mie' => 0);


 function __construct()
 {
  $this->jo = C::get('jo');																	//- wskaźnik Admina

  $this->akcja = C::Get('akcja');


  list($rokNow, $this->nrMonthNow) = explode('-', date("Y-m", strtotime(C::get('datetime_teraz'))));

  //list($rokNow, $this->nrMonthNow) = array('2019', '1'); //do testów

  $this->rokNow = $rokNow; // - zmienna zdublowana, należałoby poprawić

  $this->opcja = C::Get('opcja');

  $opcc = explode('+', $this->opcja);

  $this->pilot = 	$opcc[0];

  //if(!$this->opcja) $this->opcja = $this->nrMonthNow.'+open+'.$rokNow;


  if(!$opccd[0] = array_intersect($opcc, array(3,4,5,6,7,8,9,10)))
   $opccd[0] = $this->nrMonthNow * 1;
  else
   $opccd[0] = $this->adr($opccd[0]); // - pierwsza wartość w tablicy

  if(!$opccd[1] = array_intersect($opcc, array('open', 'sport', 'fun', 'plus')))
	$opccd[1] = 'razem';
  else
   $opccd[1] = $this->adr($opccd[1]);


  if(!$opccd[2] = array_intersect($opcc, $this->years))
   $opccd[2] = $rokNow;
  else
   $opccd[2] = $this->adr($opccd[2]);


  $this->opcja = implode('+', $opccd);

  $this->wrok = $opccd[2];

  //if($opcc[2] < $rokNow) $this->nrMonthNow = '11';


  $this->ended = $opccd[2].'-09-30';
  $this->year = $opccd[2];

  //$this->year < $this->rokNow


  $this->nrMonth = $this->mmonth = $opccd[0];

  $this->klase = $this->klasa = $opccd[1];


  $parametry = array($this->nrMonth, $this->klase, $this->year); //????

  unset($opcc, $opccd);


  $this->nrMonthNow2 = $this->nrMonthNow = $this->nrMonthNow * 1;


  if(strtotime(C::get('datetime_teraz')) > strtotime($this->ended))
   $this->ended = true;
  else
   $this->ended = false;



  if($this->nrMonth)
   $this->nrMonthNow3 = $this->nrMonth;
  else
	$this->nrMonthNow3 = $this->nrMonthNow2;

  if($this->year < $rokNow)
	$this->nrMonthNow = 11;


  if($this->year < $this->rokNow && ($this->nrMonth < 3 || $this->nrMonth > 9))
   		$this->nrMonth = 9;



	//Test::trace(__METHOD__ .' nr miesiaca  -> ', $this->nrMonthNow);

	if($this->year < 2018)
	{
	 $this->tabl = C::get('tab_dpl');
	 $this->tabp = C::get('tab_piloci');
	}
	elseif($this->year == 2018)
	{
	 $this->tabl = C::get('tab_dpl18');
	 $this->tabp = C::get('tab_piloci18');
	}
	elseif($this->year == 2019)
	{
	 $this->tabl = C::get('tab_dpl18');
	 $this->tabp = C::get('tab_piloci19');
	}
	else
	{
	 $this->tabl = C::get('tab_dpl18');
	 $this->tabp = C::get('tab_piloci20');
	}

	//Test::trace(__METHOD__ .' parametry  -> ', $parametry);

	unset($parametry);
 }

 /**
 *
 * funkcja zwracając pierwszą nie pustą wartość w tablicy
 *
 */

 private function adr($t)
 {
  foreach($t as $wa)
  {
   if($wa)
	 return($wa);
  }

  return 'null';
 }

 /**
 *
 * wywołanie publiczne
 *
 */

 public function dlp_month()
 {
  $this->pil = FALSE;

  $this->dlp_p_moth();
 }

 /**
 *
 * własciwa klasa prywatna
 *
 *
 */

 public function dlp_pilot()
 {
  $this->pil = TRUE;

  $this->nrMonth = $this->mmonth = '';

  $this->dlp_p_moth('pilot');
 }

 /**
 *
 * właściwa klasa prywatna
 *
 */

 private function dlp_p_moth($par = false)
 {
  $war2 = '';

  $t = $sum_pkt = array();

  $warp = '';
  if($this->wrok > 2017 && !$this->jo) $warp = ' WHERE pil_blok = 0';

  $tab = 'SELECT * FROM '.$this->tabp.$warp;
  //Test::trace(__METHOD__ .' tab1 ->', $tab);

	 if($this->nrMonth)
	 {
	  $war2 = ' AND dpl_data < \''. date("Y-m-d", mktime(0, 0, 0, ($this->nrMonth + 1), 1, $this->year)).'\'';
 	  $war2 .= ' AND dpl_data >= \''. date("Y-m-d", mktime(0, 0, 0, $this->start_end[$this->year][1], 1, $this->year)).'\'';
	 }
	 else
	 {
	  $war2 = ' AND dpl_data < \''. date("Y-m-d", mktime(0, 0, 0, ($this->nrMonthNow + 1), 1, $this->year)).'\'';
 	  $war2 .= ' AND dpl_data >= \''. date("Y-m-d", mktime(0, 0, 0, $this->start_end[$this->year][1], 1, $this->year)).'\'';
	 }

	 //if($this->wrok > 2017) $war2 .= ' AND dpl_stref = 0';

    if($tab = DB::myQuery($tab))
     while($ta = mysqli_fetch_assoc($tab))
	  {

		$tb2 = 'SELECT * FROM '.$this->tabl.' WHERE dpl_pilot = '.$ta['pil_id'].$war2; //-tabela lotów
		//Test::trace(__METHOD__ .' tab2 ->', $tb2);

		if($tb2 = DB::myQuery($tb2))
       while($tb = mysqli_fetch_assoc($tb2))
	    {

        if($tb['dpl_stref'] && $this->wrok > 2017) $tb['dpl_km'] = 0;

		  $t[$ta['pil_name']][$tb['dpl_data']] =  array('x', $tb['dpl_km'], $tb['dpl_track']);

		  $pil['k'.$tb['dpl_pilot']]	= $ta['pil_name'];										//-tablica do wyłonienia zwycięzców

		  $pilot[$ta['pil_name']] = array($ta['pil_xc'], $ta['pil_glajt'], $ta['pil_xcc'], $ta['pil_id'], $ta['pil_blok']);

		  $sum_pkt[$ta['pil_name']] = 0; 															//-dla nadania wartości - eliminuje ostrzeżenia kompilatora

		 }

		unset($tb, $tb2);
	  }
	unset($war2);



  if(!$t) return;				//-nie ma tabeli lotów => wyjście

  $tt = $this->day($t);		//-tabela dni w których było latanie
  sort($tt);

  $this->stat['licz_dni_lot'] = count($tt); //liczba dni lotnych w sezonie

  $rek = $this->rekord($t);

  $this->stat['rekord'] = reset($rek);

  //Test::trace(__METHOD__ .' rekord = ', $rek);

	/* po sortowaniu
	 $t =
	 [0] => 2015-03-06
    [1] => 2015-03-07
    [2] => 2015-03-08
   */

	// pętla jest po wszystkich lotach w danym dniu, a przy filtrowaniu, lista pilotów jest ograniczona do danej klasy
	//Test::trace(__METHOD__ .' TABB', $tt);

	/*
	if($this->wrok > 2017)
	 $warx = ' AND dpl_stref = 0';
	else
	 $warx = '';  */

	 foreach($tt as $v) 											//$v = data lotu
	 {

		$tb2 = "SELECT * FROM ".$this->tabl." WHERE dpl_data = '".$v."'"; //.$warx;

		if($tb2 = DB::myQuery($tb2))
       while($tb = mysqli_fetch_assoc($tb2))
		 {
        if($this->wrok > 2017 && $tb['dpl_stref']) $tb['dpl_km'] = 0; // && !$this->jo

		  if(isset($pil['k'.$tb['dpl_pilot']]))					//-ograniczenie tylko do pilotów którzy latali danego dnia
		  {
		   if($this->klasa == 'plus')
			{

			 if($tb['dpl_km'] > 50)
			 {
			  $m['k'.$tb['dpl_pilot']] = $tb['dpl_km'];
		     $mid['k'.$tb['dpl_pilot']] = $tb['dpl_id'];		//-tabela identyfikatorów w bazie - identyfikator lotu
			 }

			}
			else
			{
		    $m['k'.$tb['dpl_pilot']] = $tb['dpl_km'];
		    $mid['k'.$tb['dpl_pilot']] = $tb['dpl_id'];			//-tabela identyfikatorów w bazie - identyfikator lotu
			}
		  }
		 }

	   array_multisort($m, SORT_DESC); 							  //-tablica lotów w danym dniu, na pierwszym miejscu zwycięzca

		//Test::trace(__METHOD__ .' day'.$D[1], $m);

	   $mm = each($m);
	   $max_dyst = $zw[$pil[$mm['key']]][$v] = $mm[1];		  //-tablica zwycięzców w danym dniu to jest pilot max

		$mm = each($m);
		if($max_dyst == $mm[1])
		 $zw[$pil[$mm['key']]][$v] = $mm[1]; 					  // - 2 gi egzekwo -> zrobić ewentualnie 3-go :)

		//-dodatkowe punkty dla pilota jeśli było więcej lotów w danym dniu

		$licz_pilot_dnia = count($m);

		$ttt[$v] = $licz_pilot_dnia;

		if($this->klasa == 'plus')
		{
		 $st_pkt = 0;
		}
		else
		{
		 $st_pkt = 100;
		 if($licz_pilot_dnia>1) $st_pkt = 200;
		 if($licz_pilot_dnia>2) $st_pkt = 300;
		 if($licz_pilot_dnia>3) $st_pkt = 400;
		}
		//400 x 55,6 / 155,6 + 55,6 = 198,53 pkt

		//Test::trace(__METHOD__ .' m '.$v, $m);

		foreach($m as $k2 => $v2)
		{
        if($this->year > 2016)
		   $ob_pkt[$pil[$k2]][$v] = round($v2,2);
		  else
		   $ob_pkt[$pil[$k2]][$v] = round(((($st_pkt*$v2)/$max_dyst) + $v2),0);


		 $id[$pil[$k2]][$v] = $mid[$k2];

       $sum_pkt[$pil[$k2]] = $sum_pkt[$pil[$k2]] + $ob_pkt[$pil[$k2]][$v];  //-sumowanie punktów dla danego pilota

		 $this->stat['licz_lot']++;
		}

	  array_multisort($sum_pkt, SORT_DESC);

	  //Test::trace(__METHOD__ .' sum_pkt po ', $sum_pkt);

	  unset($m, $max);
    }


  $this->stat['licz_pil'] = count($sum_pkt);

  $winers = $this->winers($t, $zw, $pilot, $ob_pkt, $sum_pkt, $id);

  if(!$par)
   $this->tabela($t, $zw, $pilot, $ob_pkt, $sum_pkt, $id, $winers);
  else
   $this->pilot($t, $zw, $pilot, $ob_pkt, $sum_pkt, $ttt, $winers);
 }

 /**
 * wyłonienie zwycięzców w poprzednich miesiącach i aktualnym
 *
 *
 */

 //$this->tabela($t, $zw, $pilot, $ob_pkt, $sum_pkt, $id, $winers);
 private function winers($t, $zw, $pilot, $obl, $sum_pkt, $id)
 {

  $tt = $this->day($t);				//- generuje tablicę kolumn (dni) które zawierają dane

  //Test::trace(__METHOD__ .' sum_pkt', $sum_pkt);

  foreach($sum_pkt as $k => $v)	//- pętla po klasyfikacji, pilotach którzy zgłosili swoje loty
  {

   for($i=3; $i<11; $i++)   		//- pętla po miesiącach, ustalająca wartości początkowe tabeli
	 $sumy_w_mie[$k][$i] = '';

	unset($i);
											//-utworzenie tabeli sum dla pilotów w danych miesiącach
	foreach($tt as $day)   			// pętla po dniach lotnych,
   {

	 $nr_mie = $this->date_to_nr_mie($day);

	 if(isset($obl[$k][$day])) $sumy_w_mie[$k][$nr_mie] += $obl[$k][$day];

   }
  }

  unset($tt, $sum_pkt, $obl);

  //Test::trace(__METHOD__ .' sumy_w_mie ', $sumy_w_mie);

  for($i=3; $i<11; $i++)					//- pętla po miesiącach ( ograniczyć do aktulnego miesiąca lub wybranego ) !!!!!!
  {
	$p = array();

	foreach($sumy_w_mie as $k => $v)		//- utworzenie tablicy z komuny dla danego miesiąca
    $p[$k] = $sumy_w_mie[$k][$i];

	array_multisort($p, SORT_DESC);		//- ustalenei kolejności w danym miesiącu

	$tmp = 0;
	$pl = 1;

	foreach($p as $k => $v) // - ustalenie miejsca, z uwzględnieniem miejsc równorzędnych
	{

	 if($v)
	 {
	  if(!$tmp)
	  {
	   $tmp = $v;
	   $p[$k] = array($pl, $v);
	  }
	  else
	  {

	   if($tmp > $v)
	   {
	    $pl++;
	    $p[$k] = array($pl, $v);
	    $tmp = $v;
	   }
	   else
	    $p[$k] = array($pl, $v);

	  }
	 }
   }

   foreach($p as $k => $v)				//-przepisanie tablicy do postaci pierwotnej
    $winers[$k][$i] = $p[$k];

  }

	unset($i, $k, $v, $p, $tmp, $pl);

	return $winers;
 }

 /**
 * tworzenie tabelki
 *
 */

 private function tabela($t, $zw, $pilot, $obl, $sum_pkt, $id, $winers)
 {       //$this->tabela($t, $zw, $pilot, $ob_pkt, $sum_pkt, $id);

   $sumatmp = array();
	$nmm = array();

   if($this->klasa)											// warunek ograniczenia do danej klasy
	{

	 $s = strtoupper(substr($this->klasa, 0, 1));


	 //-osobne klasy open, fan, sport
	 if($this->wrok > '2019')
	 {

	  switch($s)
	  {

	   case 'O': $war_klasy = array('O');
	   break;

	   case 'S': $war_klasy = array('S');
		break;

	   case 'F': $war_klasy = array('F');
	   break;

	   default :	$war_klasy = array('O', 'S', 'F', 'P');

	  }

	 }
	 else
	 {
	  switch($s)
	  {

	   case 'O': $war_klasy = array('O', 'S', 'F');
	   break;

	   case 'S': $war_klasy = array('S', 'F');
		break;

	   case 'F': $war_klasy = array('F');
	   break;

	   default :	$war_klasy = array('O', 'S', 'F', 'P');

	  }
    }
	 //Test::trace(__METHOD__ .' warunek', $tab);

    unset($s);
   }
	else
	 $war_klasy = array('O', 'S', 'F', 'P', ' ');

  $klas = $sum_pkt;

  $tt = $this->day($t);																// generuje tablicę kolumn (dni) które zawierają dane

  $lp = 1;																				// liczba porządkowa

  $w = '';

  //$this->stat['licz_pil'] = count($klas);

  foreach($klas as $k => $v)														// pętla po klasyfikacji, pilotach którzy zgłosili swoje loty
  {
   //$k pilot
	//$v suma km

   if(array_intersect($pilot[$k], $war_klasy)) // && $v > 0 // 2020-04-02
	{

    $licz_kol = $liczDni = $liczMie = $liczPustMie = $sumatmpakt = $lzw = $islot = $rek_mie = 0;

	 $p = '';

	 foreach($tt as $day)   															// pętla po dniach lotnych
    {

     $licz_kol++;

	  if(isset($zw[$k][$day]))
	  {
	   $czw = ' class=\'winer\'';
	   $lzw++;
	  }
	  else
	   $czw = '';


	  if(isset($t[$k][$day])) $dane = true; 										//-znacznik, że są jakieś dane w tabeli

	  if($this->jo && isset($obl[$k][$day]))
	  {
	   $akcja = $this->akcja;

	   $link_ed_lot = '<a class=\'ed_dlp\' href=\''.S::linkCode(array($this->tabl, $id[$k][$day], 'edycja','', $akcja)).'.htmlc\' title=\'Edytuj lot\'>EL</a>';
	  }
	  else
	   $link_ed_lot = '';


	  if(isset($t[$k][$day]))
	   $pp = $t[$k][$day];
	  else
	   $pp = array('', '', '');

	  $this->stat['km'] += round($pp[1],0);

	  if(!$nm = $this->sumaMonth($day))	//-dla aktualnego miesiąca wyświetla loty, a dla poprzednich zlicza punkty
	  {

	   if(!$pp[2])
	   {
	    if($pp[1])
		 {
		  if($this->year > 2016)
		  {
		   $pt = '<td'.$czw.'><p>'.$pp[1].'</p>'.$link_ed_lot.'</td>';
		  }
		  else
		  {
	      $pt = '<td'.$czw.'><p>'.$obl[$k][$day].'</br>'.$pp[1].'</p>'.$link_ed_lot.'</td>';
		  }

        $this->stat['lot_mie']++; //statystyka miesięczna
		  $islot++;	//-znacznik, że pilot wykonał lot

		  $this->stat['km_mie'] += round($pp[1],0);

		  if($this->stat['rek_mie'][0] < $pp[1])
		  {
		   $this->stat['rek_mie'][0] = $pp[1];
			$this->stat['rek_mie'][1] = $day;
			$this->stat['rek_mie'][2] = $k;
			$this->stat['rek_mie'][3] = $pp[2];
		  }

		 }
	    else
	     $pt = '<td>-</td>';
	   }
	   else
		{
		 if($this->year > 2016)
		  $pt = '<td'.$czw.'><a href=\''.$pp[2].'\' target=\'_blanc\' title=\'Zobacz lot na XC\'><p>'.$obl[$k][$day].'</p></a>'.$link_ed_lot.'</td>';
		 else
	     $pt = '<td'.$czw.'><a href=\''.$pp[2].'\' target=\'_blanc\' title=\'Zobacz lot na XC\'><p>'.$obl[$k][$day].'</br>'.$pp[1].'</p></a>'.$link_ed_lot.'</td>';


		$islot++;	//-znacznik, że pilot wykonał lot

		if($this->klasa == 'plus')
		{
		 if($pp[1] <= 50)
		 {
		  $pt = '<td>-</td>';
		  $px = 1;
		 }
		}


		if(!$px){
		 $this->stat['lot_mie']++; //statystyka miesięczna

		 $this->stat['km_mie'] += round($pp[1],0);

		 if($this->stat['rek_mie'][0] < $pp[1])
		 {
		  $this->stat['rek_mie'][0] = $pp[1];
		  $this->stat['rek_mie'][1] = $day;
		  $this->stat['rek_mie'][2] = $k;
		  $this->stat['rek_mie'][3] = $pp[2];
		 }
      }



		}
	   $liczDni ++;

		$p .= $pt;

		unset($pt, $px);

	   if(isset($obl[$k][$day])) $sumatmpakt += $obl[$k][$day];
     }
	  else
	  {
	   if(isset($obl[$k][$day]))
	   {
		 if(isset($sumatmp[$nm]))
		  $sumatmp[$nm] += $obl[$k][$day]; 		 //-sumowanie punktów dla poprzednich miesięcy
		 else
		  $sumatmp[$nm] = $obl[$k][$day];
	   }
	  }
	  unset($czw, $pp, $ob);
	 }



	 if(!$this->nrMonth || $this->nrMonth == $this->nrMonthNow || $this->jo)
	  if($liczDni < $this->dniPrzed)
	   if(!$this->ended)
	    $p = $p . $this->kols($this->dniPrzed-$liczDni-$liczMie);  		//-dodatkowe kolumny jeśli mało dni lotnych


	 $sumatmp = $this->dodPusteMie($sumatmp, false);							//-uzupełnienie tablicy o dodatkowe puste miesiące


	 if($sumatmp)
	 {
	  foreach($sumatmp as $kk => $wwart)							  				//-i sumy poprzedniego
	  {
	   if(isset($winers[$k][$kk][0]))
	    $p .= '<td class=\'dlp_summ\'><p><b>'.$winers[$k][$kk][0].'</b><br />'.$wwart.'</p></td>';
	   else
	    if($wwart)
		  $p .= '<td class=\'dlp_summ\'>'.$wwart.'</td>';
		 else
		  $p .= '<td class=\'dlp_summ\'> </td>';

	   $liczMie++;
	  }

	  unset($nm);

	  $sumatmp = array();
	 }

	if($this->nrMonth && $this->nrMonth <= $this->endmonth)
   {
	 if(isset($winers[$k][$this->nrMonthNow3][0]))
	 {
	  //if(!$this->ended)
     $p = '<td class=\'dlp_summ\'><p><b>'.$winers[$k][$this->nrMonthNow3][0].'</b><br />'.$sumatmpakt.'</p></td>'.$p;
	  //-suma dla aktualnego miesiąca
	 }
	 else
	 {
	  if($sumatmpakt)
	   $p = '<td class=\'dlp_summ\'>'.$sumatmpakt.'</td>'.$p;
	  else
	   $p = '<td class=\'dlp_summ\'> </td>'.$p;
	  }
	 }

	 //-wskazanie pilotów, którzy nie mają profilu na xcportal.pl
	 if($this->year < 2018)
	  $nazwa = '<p>'.preg_replace('/\|/', '</br>/', $k).'</p>';
	 else
	 {
	  if(!$pilot[$k][0])
	   $nazwa = '<p style=\'color: #FF0; font-weight: bold;\'>*'.preg_replace('/\|/', '</br>/', $k).'</p>';
	  else
	  {

	   $serw = explode('//', $pilot[$k][0]);
	   $serw = explode('/', $serw[1]);

	   if($serw[0] <> 'xcportal.pl')
	   {
	    $nazwa = '<p style=\'color: #0F0; font-weight: bold;\'>*'.preg_replace('/\|/', '</br>/', $k).'</p>';
	   }
	   else
	    $nazwa = '<p>'.preg_replace('/\|/', '</br>/', $k).'</p>';
	  }
	  unset($serw);
    }


	//$nazwa = '<p>'.$k.'</p>';

	if(!$pilot[$k][0])
	 $name = $nazwa;
	else
	 $name = '<a href=\''.$pilot[$k][0].'\' target=\'_blanc\' title=\'Zobacz konto pilota na XC\'>'.$nazwa.'</a>';


	if($lzw == 0 || !$lzw) $lzw = '';

	if($lp < 4)
	 $kl_wiersz = ' class=\'pudlo\'';
	else
	 $kl_wiersz = '';

	if($this->jo)
	{
	 $akcja = $this->akcja;
	 $link_ed_pilot = '<a class=\'ed_dlp\' href=\''.S::linkCode(array($this->tabp, $pilot[$k][3], 'edycja','', $akcja)).'.htmlc\' title=\'Edytuj Pilota\'>EP</a>';
	}
	else
	 $link_ed_pilot = '';


	$row = '
	  <td id=\''.$pilot[$k][3].'\'>&nbsp;'.($lp++).'&nbsp;</td><td class=\'pil_name\'>'.$name.$link_ed_pilot.'</td><td>&nbsp;'.$pilot[$k][1].'&nbsp;</td><td class=\'suma\'><a href=\''.$pilot[$k][3].'+'.$this->nrMonth.'+'.$this->klase.'+'.$this->wrok.'+pilot.html\' title=\'zobacz listę lotów pilota zakwalifikowanych do DLP\'>'.$sum_pkt[$k].'</a></td><td>'.$lzw.'</td>'.$p;


	if($pilot[$k][4]) $kl_wiersz = ' bgcolor=#999 ';


	 if($sum_pkt[$k] > 0)
	  $w .= '
	 <tr '.$kl_wiersz.'  >'.$row.'</tr>';


    //if($islot) $this->stat['pil_mie']++;        //-jeśli pilot wykonał przynajmniej jeden lot

	 if($sumatmpakt) $this->stat['pil_mie']++;    //-jeśli pilot wykonał przynajmniej jeden lot

	 //if($sum_pkt[$k]) $this->stat['pil_mie']++;    //-jeśli pilot wykonał przynajmniej jeden lot

	 unset($p, $licz, $lzw, $name, $nazwa, $kl_wiersz, $islot);
   }
  }

  $this->stat['licz_pil'] = $lp-1;

  if($w) 							  					//-jeśli są wiersze z danymi to tworzony jest nagłówek tabeli
  {
   $hed = '';


   foreach($tt as $wart) 							//-dni aktualnego miesiąca
   {

	 if(!$nm = $this->sumaMonth($wart))
	 {
	  $hed .= '<td>'.substr($wart,5).'</td>';

	  $this->stat['dni_mie']++;
	 }
	 else
	 {
	  $nmm[$nm] = $this->nrToMonth($nm);
    }

   }

	$nmm = $this->dodPusteMie($nmm);														//-uzupełnienie tablicy o dodatkowe puste miesiące

	if(!$this->nrMonth || $this->nrMonth == $this->nrMonthNow || $this->jo)
    if($liczDni < $this->dniPrzed)
	  if(!$this->ended)
	   $hed =  $hed . $this->kols($this->dniPrzed-$liczDni);  					//-dodatkowe kolumny jeśli mało dni lotnych

	foreach($nmm as $wart)																	//-i suma poprzedniego !!! 2018-12-27
	  $hed .= '<td>'.$wart.'</td>';

	unset($wart, $nmm, $nrmm);


	if($this->nrMonth) $this->nrMonthNow2 = $this->nrMonth;						// dodatkowa suma dla aktualnego miesiąca


	if($nnmonth = $this->nrToMonth($this->nrMonthNow2))
	{
	 $hed = '<td>'.$nnmonth.'</td>'.$hed;
	 unset($nnmonth);
	}


   $hed = '<tr class=\'tab_hed\'>
	 <td></td><td></td><td>KL</td><td>punkty</td><td title=\'Liczba zwycięstw w okresie zestawienia\'>LZ</td>'.$hed.'
	 </tr>';


	if($lp < $this->dodWierPo)
	 if(!$this->nrMonth || $this->nrMonth == $this->nrMonthNow || $this->jo)
	  $w .= $this->rows($this->dodWierPo - $lp, ($this->dniPrzed + $liczMie+1));

   //overflow: hidden; border: 1px solid #FFF;

   if($this->year >= 2014)
	{
    if($this->dniPrzed-$liczDni > 0) $dni_plus = $this->dniPrzed-$liczDni;

	 $style1 = ' style=\'width:'.(130+310+(($liczDni+$liczMie+$dni_plus)*64)).'px;\'';

    $this->w .= '
	<div id=\'smart_window\'>
	 <div id=\'spon\' '.$style1.'>
	  '.$this->sponsor().'
	  <table id=\'dlp_tab\' >
      '.$hed.'
      '.$w.'
     </table>
	 </div>
	</div>';

   }
	else
	{

	 $this->w .= '
	<div id=\'smart_window\'>
	<table id=\'dlp_tab\' >
    '.$hed.'
    '.$w.'
   </table>
	</div>';

	}
  }
  else
  {
	$this->w .= '
	<h3>Brak sklasyfikowanych przelotów.</h3>';
  }

  if(!$this->w)
  {
   $this->w .= '
	<h3>Brak sklasyfikowanych przelotów.</h3>';
  }
  else
  {
   $this->opis();
	$this->livetrack();
  }

 }

 /**
 *
 * funkcja wyświetla listę lotów danego pilota, sklasyfikowanych w DLPxxxx
 *
 */

 private function pilot($t, $zw, $pilot, $ob_pkt, $sum_pkt, $ttt, $winers)
 {
  //exit('pilot nr = ' . $this->pilot);
  //$n = explode('+', $this->opcja[0]);
  //$n = $n[0];

  $n = $this->pilot;

  $lz = 0;

  if($n)
  {
   $p = array();

   Test::trace(__METHOD__ .' PILOT', $n);

	//Test::trace(__METHOD__ .' TAB-PILOT', $pilot);

   foreach($pilot as $k => $v)
   {
    $p[$v[3]] = array($v[0], $v[1], $v[2], $k);
   }

	$pilot = $p;
	unset($p);

	ksort($t[$pilot[$n][3]]);

   //Test::trace(__METHOD__ .' PILOT2', $p);

	//Test::trace(__METHOD__ .' PILOTx', $pilot[$n][0]);
	//Test::trace(__METHOD__ .' PILOT', $pilot[$n][3]);

   foreach($t[$pilot[$n][3]] as $k => $v)
	{
	 if($zw[$pilot[$n][3]][$k])
	 {
	  $czw = ' class=\'winer\'';
	  $lz++;
	 }
	 else
	  $czw = '';

	 $p .= '
	 <tr>
	  <td>'.++$lp.'</td>
	  <td'.$czw.'>'.$k.'</td><td>'.$v[1].' km</td>
	  <td>'.$ob_pkt[$pilot[$n][3]][$k].'</td>
	  <td>'.$ttt[$k].'</td>
	  <td><a href=\''.$v[2].'\' target=\'_blanc\' title=\'zobacz track\'>track</a></td>
	 </tr>';

	 // $suma = $suma + $ob_pkt[$pilot[$n][3]][$k];
	}

	$hed = '<td>L.p.</td><td>data lotu</td><td>długość lotu</td><td>przyznane punkty</td><td title=\'liczba sklasyfikowanych pilotów danego dnia\'>l.lotów</td><td>track</td>';


	foreach($sum_pkt as $k => $v)
	{
	 $sum_pkt[$k] = array($v, ++$poz);
	}

	$this->w .= '

   <h2>'.$pilot[$n][3].'&nbsp;&nbsp;<b>suma punktów : </b>'.$sum_pkt[$pilot[$n][3]][0].'&nbsp;&nbsp;<b>liczba zwycięstw : </b>'.$lz.'&nbsp;&nbsp;<b>pozycja : </b>'.$sum_pkt[$pilot[$n][3]][1].'</h2>

   <div id=\'smart_window\'>
	 <table id=\'dlp_tab\'>
     '.$hed.'
     '.$p.'
    </table>'.$suma.'
	</div>';

	//-tutal link powrotu

   $this->w .= '
	<div class=\'info\'>
	 <a class=\'more\' href=\''.$this->nrMonthNow3.'+'.$this->klase.'+'.$this->wrok.'+dolnoslaska-liga-paralotniowa.html#'.$n.'\' title=\'wróć do zestawienia\'>POWRÓT</a>
	</div>';

	//<code>'.$this->nrMonthNow3.'->'.$this->klase.'->'.$this->wrok.'->'.$pilot[$n][0].'</code>';


	$this->xx = $pilot[$n][0];
  }
  else
  {
	$this->w .= '
   <h2>Brak pilota!</h2>';
  }

 }

 /**
 *
 * dodatkowe puste miesiące - bez przelotów
 *
 */

 private function dodPusteMie($nmm, $p = true)
 {

 	if($this->nrMonth)
	 $j = $this->nrMonth;
	else
	 $j = $this->nrMonthNow;

	//exit('<p>'.$j.'-'.$this->start_end[$this->wrok][3].'</p>');

	if(($m = $this->start_end[$this->wrok][3]) <= $j)
	 $j =  $m +1;

   for($i = 3; $i < $j; $i++)
	{
	 if($p)
    {
	  if(!isset($nmm[$i])) $nmm[$i] = $this->nrToMonth($i);
	 }
	 else
	 {
	  if(!isset($nmm[$i])) $nmm[$i] = 0;
	 }
	}

	unset($i, $j);

   krsort($nmm);

   return $nmm;
 }

 /**
 *
 * funkcja scala kolumny dla dni z poprzedniego miesiąca
 * dla aktualnego miesiąca zwraca false
 * dla poprzedniego miesiąca zwraca nr miesiąca
 *
 */

 private function sumaMonth($w) // $w -> Y-m-d
 {
 	 //$this->nrMonthNow
	 //$this->w .= '<p>'.$w.'</p>';

	 if($this->nrMonth)
	  $nrmm = $this->nrMonth;
	 else
	  $nrmm = $this->nrMonthNow;

	 $nm = explode('-', $w);

	 $nm = $nm[1] * 1;

 	 if($nm == $nrmm)
	  return false;
	 else
	  return $nm;
 }

 /**
 *
 * zamienia datę na nr miesiąca
 *
 *
 */

 private function date_to_nr_mie($day)
 {

 	 $nm = explode('-', $day);

	 $nm = $nm[1] * 1;

    return $nm;
 }

 /**
 * funkcja generuje tablicę kolumn (dni), które zawierają dane
 *
 *
 */

 private function day($t)
 {

  if($this->klasa == 'plus')
  {
   foreach($t as $k => $v)
     foreach($v as $k2 => $v2)
	   if($v2[1] > 50)
		 $tt[$k2] = $k2;
  }
  else
  {

   foreach($t as $k => $v)
    foreach($v as $k2 => $v2)
	  $tt[$k2] = $k2;
  }

  array_multisort($tt, SORT_DESC);
  unset($k, $k2, $v, $v2, $s);

  return $tt;
 }

 /**
 *
 * funkcja wybiera rekordowy lot
 *
 */

 private function rekord($t)
 {
  foreach($t as $k => $v)
   foreach($v as $k2 => $v2)
    $rek[$v2[1]] = array($v2[1], $k2, $k, $v2[2]);

  array_multisort($rek, SORT_DESC);

  return $rek;
 }

 /**
 *
 * dodatkowe puste kolumny, na końcu tabelki
 *
 */

 private function kols($n)
 {
  if($n < 1) return;

  $row = '';

  for($i = 0; $i < $n; $i++)
   $row .= '
	<td>&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;</td>';

  return $row;
 }

 /**
 *
 * dodatkowe puste wiersze
 *
 */

 private function rows($n, $p)
 {
  if($n < 1) return;

  $row = '';

  for($i = 0; $i < $n; $i++)
   $row .= '
	<tr>
	 <td> - </td><td> - </td><td> - </td><td> - </td><td> - </td>'.$this->kols($p).'
	<tr>';

  return $row;
 }

 /**
 *
 * menu dla miesięcy
 *
 */

 private function menu()
 {

  $t = $kl = '';

  if($this->klasa)
   $kl = '+'.$this->klasa;

  if($this->wrok)								// wybrany rok
   $kl = $kl.'+'.$this->wrok;


  for($i = $this->endmonth; $i > 2; $i--)
  {
   if($i == $this->nrMonth)
	 $class = 'class=\'active\' ';
	else
	 $class = '';

	if($i <= $this->nrMonthNow)
    $t .= '
	<li>
	 <a '.$class.'href=\''.$i.$kl.'+'.$this->akcja.'.html\' title=\'zobacz wyniki na koniec miesiąca '.$this->month[$i].'\'>'.$this->month[$i].'</a>
	</li>';
	else
	 $t .= '
	<li>
	 <spin >'.$this->month[$i].'</spin>
	</li>';

  }

  if($this->nrMonth)
   $mon = $this->nrMonth.'+';
  else
   $mon = '';

  $class1 = $class2 = $class3 = $class4 = '';

  if($this->klasa == 'open')
  {
   $class1 = 'class=\'active\' ';
  }
  elseif($this->klasa == 'sport')
  {
   $class2 = 'class=\'active\' ';
  }
  elseif($this->klasa == 'fun')
  {
   $class3 = 'class=\'active\' ';
  }
  elseif($this->klasa == 'plus')
  {
   $class4 = 'class=\'active\' ';
  }

  if($this->wrok)						//-wybrany rok
   $y = $this->wrok.'+';


  $t .= '
  	<li>
	 <a id=\'dpl_menu_all\' href=\''.$y.$this->akcja.'.html\'> RAZEM </a>
	</li>
	<li>
	 <a '.$class1.'href=\''.$mon.'open+'.$y.$this->akcja.'.html\'> OPEN  </a>
	</li>
	<li>
	 <a '.$class2.'href=\''.$mon.'sport+'.$y.$this->akcja.'.html\'> SPORT </a>
	</li>
	<li>
	 <a '.$class3.'href=\''.$mon.'fun+'.$y.$this->akcja.'.html\'> FUN </a>
	</li>
	<li>
	 <a '.$class4.'href=\''.$mon.'plus+'.$y.$this->akcja.'.html\'> 50+ </a>
	</li>';

  unset($class, $class1, $class2, $class3, $class4, $i, $mon, $y);

  return '<ul id=\'dlp_menu\'>'.$t.'</ul>';

 }

 /**
 *
 * nr miesiąca na nazwę miesiąca
 *
 */

 private function nrToMonth($n = 1)
 {

  if($n > 2 && $n < 10)
   return $this->month[$n];									//-tablica z nazwammi miesięcy
  else
   return 0;

 }

 /**
 *
 *
 *
 */

 private function wybMonth()
 {

  if($this->nrMonth > 2 && $this->nrMonth < 10)
   return $this->month[$this->nrMonth];					//-tablica z nazwammi miesięcy
  else
   return $this->nrToMonth($this->nrMonthNow);

 }

 /**
 *
 * opisy pod tabelką
 *
 *
 */

 private function opis()
 {
  if($this->year > 2016)
  {
	$this->w .= '
	<div id=\'opis_dlp\'>
	 <p>- klasyfikacji podlegają loty powyżej 15 pkt.&nbsp;&nbsp;&nbsp;LZ - LICZBA ZWYCIĘSTW &nbsp;&nbsp;&nbsp;KL - klasa skrzydła:  O - open,  S - sport;  F - fan</p>
	 <p>- punktowanie: 1. tójkąt FAI -> 1km = 1.5 pkt.;  2. trójkąt płaski -> 1km = 1.3 pkt; 3. przelot otwarty -> 1km = 1.0 pkt</p>
	 <p>- plik ze śladem lotu zoptymalizowany pod względem długości lotu wg <a href=\'http://xcportal.pl\' target=\'_blanc\'>XCportal</a>
	 <p class=\'center\'><a href=\'https://www.facebook.com/Dolno%C5%9Bl%C4%85ska-Liga-Paralotniowa-1635901036706352/\' title=\'zobacz stronę DLP na Facebook\'u\' target=\'_blanc\'>Strona DLP na Facebook\'u</a></p>
	</div>';
  }
  else
  {
   if($this->klasa != 'plus')
   $this->w .= '
  <div id=\'opis_dlp\'>
	<p>- klasyfikacja > 15 pkt.&nbsp;&nbsp;&nbsp;LZ - LICZBA ZWYCIĘSTW &nbsp;&nbsp;&nbsp;KL - klasa skrzydła:  O - open,  S - sport;  F - fan</p>
	<p>- punktacja z dnia to ilość pilotów ponad dystansem minimalnym razy 100 + pkt za lot, lecz nie więcej niż 400 + pkt za lot</p>
	<p>- współczynniki: 1. tójkąt FAI - 1km: 1.5pkt;  2. trójkąt płaski - 1km: 1.3pkt; 3. przelot otwarty - 1km: 1.0pkt</p>
	<p>- plik ze śladem lotu zoptymalizowany pod względem długości lotu wg <a href=\'http://xcportal.pl\' target=\'_blanc\'>XC</a> Portal lub <a href=\'http://xcc.paragliding.pl\' target=\'_blanc\'>XCC</a></p>
	<p class=\'center\'><a href=\'https://www.facebook.com/pages/Dolno%C5%9Bl%C4%85ska-Liga-Paralotniowa-2015/748196908590722?fref=ts\' title=\'zobacz stronę DLP 2015 na Facebook\'u\' target=\'_blanc\'>Strona DLP 2015 na Facebook\'u</a></p>
	</div>';
   else
    $this->w .= '
  <div id=\'opis_dlp\'>
	<p>- klasyfikacja > 50 pkt.&nbsp;&nbsp;&nbsp;LZ - LICZBA ZWYCIĘSTW &nbsp;&nbsp;&nbsp;KL - klasa skrzydła:  O - open,  S - sport;  F - fan</p>
	<p>- punktowanie: 1. tójkąt FAI - 1km: 1.5pkt;  2. trójkąt płaski - 1km: 1.3pkt; 3. przelot otwarty - 1km: 1.0pkt</p>
	<p>- plik ze śladem lotu zoptymalizowany pod względem długości lotu wg <a href=\'http://xcportal.pl\' target=\'_blanc\'>XC</a> Portal lub <a href=\'http://xcc.paragliding.pl\' target=\'_blanc\'>XCC</a></p>
	<p class=\'center\'><a href=\'https://www.facebook.com/pages/Dolno%C5%9Bl%C4%85ska-Liga-Paralotniowa-2015/748196908590722?fref=ts\' title=\'zobacz stronę DLP 2015 na Facebook\'u\' target=\'_blanc\'>Strona DLP 2015 na Facebook\'u</a></p>
	</div>';
  }
 }

 /**
 *
 * linki liverack24 i inne
 *
 */

 private function livetrack()
 {
  return;

  $this->w .= '
  <div id=\'dlp_us_livetrack\' >
   <p>LiveTrack (status)</p>
	<iframe src=\'http://www.livetrack24.com/user/faflik/status\' target=\'_blank\'></iframe>
	<iframe src=\'http://www.livetrack24.com/user/mecik/status\' target=\'_blank\'></iframe>
	<iframe src=\'http://www.livetrack24.com/user/cienias/status\' target=\'_blank\'></iframe>
	<iframe src=\'http://www.livetrack24.com/user/kwscore/status\' target=\'_blank\'></iframe>
	<iframe src=\'http://www.livetrack24.com/user/grzeschicago/status\' target=\'_blank\'></iframe>
	<iframe src=\'http://www.livetrack24.com/user/jarekbalicki/status\' target=\'_blank\'></iframe>
	<iframe src=\'http://www.livetrack24.com/user/sabina2008/status\' target=\'_blank\'></iframe>
	<iframe src=\'http://www.livetrack24.com/user/benedykt/status\' target=\'_blank\'></iframe>
	<iframe src=\'http://www.livetrack24.com/user/mateusz/status\' target=\'_blank\'></iframe>
	<iframe src=\'http://www.livetrack24.com/user/goray/status\' target=\'_blank\'></iframe>
	<iframe src=\'http://www.livetrack24.com/user/hancu_2008/status\' target=\'_blank\'></iframe>
	<iframe src=\'http://www.livetrack24.com/user/starry/status\' target=\'_blank\'></iframe>
	<iframe src=\'http://www.livetrack24.com/user/s2rh/status\' target=\'_blank\' ></iframe>
	<iframe src=\'http://www.livetrack24.com/user/FrodoBaggins/status\' target=\'_blank\' ></iframe>

	<p>SPOT (link)</p>
	<a target="_blank" href="http://share.findmespot.com/shared/faces/viewspots.jsp?glId=0v9foLchgSqMhVj7NC6Zz81laC5CFjvRA">Mecik</a>
   <a target="_blank" href="http://share.findmespot.com/shared/faces/viewspots.jsp?glId=00rVvxYncbTrB0jjmRpz8MZc6oXTdelHU">Świstak</a>
   <a target="_blank" href="http://share.findmespot.com/shared/faces/viewspots.jsp?glId=0ccQIFeqkPxSMZOF4sv7t8ASCq1TNBPqp">Adam Grzech</a>
   <a target="_blank" href="http://share.findmespot.com/shared/faces/viewspots.jsp?glId=0oG0fNnevUXS37oTpfgkTB3crdJRFEkH6">Piotr Zakrzewski</a>
   <a target="_blank" href="http://share.findmespot.com/shared/faces/viewspots.jsp?glId=048QVrauBbrhWijurszXSr7VTMgSEbK33">Jumbo</a>

  </div>';

 }

 /**
 *
 * przekazanie wyniku działania klasy do wtyczki
 *
 *
 */

 public function wynik()
 {
  $tt ='';

  if($this->w)
  {
	 $tt = $this->w;
	 $this->w = '';
  }

  $seo_tyt = 'DLP '.$this->year.' - Dolnośląska Liga Paralotniowa - na stronach - ';

  if(!C::get('seo', false) && $seo_tyt) C::change('seo', $seo_tyt);

  $con_desk = 'Dolnośląska Liga Paralotnowa '.$this->year.' - tabela wyników. Zobacz stronę DLP '.$this->year.' na facebooku';

  if($con_desk) C::change('con_desk', $con_desk);

  unset($seo_tyt, $con_desk);



  if($this->nrMonthNow < 3 && $this->rokNow == $this->year)
   $w = '
    <h2 class=\'dlp_tyt\'>Dolnośląska Liga Paralotniowa '.$this->year.' - start 1 marca !!</h2>
	 <h2 class=\'dlp_tyt\'>Zobacz wyniki z lat poprzednich</h2>
	 '.$this->w;
  else
  {
   if($this->nrMonthNow > 2)
	 $m = ' '.$this->wybMonth();

	 if($this->stat['licz_lot'] < 1)
	  $w = '
	 <h2 class=\'dlp_tyt\'>Dolnośląska Liga Paralotniowa '.$this->year.'</h2>
    <h2>Na razie brak sklasyfikowanych lotów.</h2>
	 <h2 class=\'dlp_tyt\'>Zobacz wyniki z lat poprzednich.</h2>';
    else
	 {

     if($this->ended) $m .= ' - Zakończona!';

     $w = '
    <h2 class=\'dlp_tyt\'>Dolnośląska Liga Paralotniowa '.$this->year.$m.'</h2>';

	 if(!$this->pil)
	  $w .= $this->menu().$this->w;
    }

  }



  if($this->mmonth)
   $akcja_back = $this->mmonth.'+';

  if($this->klase)
   $akcja_back = $this->klase.'+';

	$class2015 = $class2016 = $class2017 ='';

	if($this->wrok == '2015') $class2015 = 'class=\'active\' ';
	if($this->wrok == '2016') $class2016 = 'class=\'active\' ';
	if($this->wrok == '2017') $class2017 = 'class=\'active\' ';
	if($this->wrok == '2018') $class2018 = 'class=\'active\' ';
	if($this->wrok == '2019') $class2019 = 'class=\'active\' ';
	if($this->wrok == '2020') $class2020 = 'class=\'active\' ';
	if($this->wrok == '2021') $class2021 = 'class=\'active\' ';

	if($this->pil)
	{
	 $akcja_back = explode('+', $this->opcja[0]);
	 $akcja_back = $akcja_back[0].'+';
	}

	if($this->akcja == 'pilot')
	{
	 $akcja_back = $this->pilot .'+';
	}


	 if($this->stat['rekord'][3] != '')
	  $trak = ' | <a href='.$this->stat['rekord'][3].' target="_blank">Track</a>';
	 else
	  $trak = '';

    if($this->stat['rek_mie'][3] != '')
	  $trak2 = ' | <a href='.$this->stat['rek_mie'][3].' target="_blank">Track</a>';
	 else
	  $trak2 = '';


	if($this->stat['licz_lot'] > 0)
	 $this->w = '
   <div class=\'dlp_stat\'>
	 <span title=\'liczba pilotów, którzy wykonali lot licząc od poczatku sezonu\'>pilotów = <b>
	 '.$this->stat['licz_pil'].'</b></span>
	 <span title=\'liczba wykonanych lotów od początku sezonu\'>lotów = <b>
	 '.$this->stat['licz_lot'].'</b></span>
	 <span title=\'liczba dni od poczatku sezonu, w których wykonano loty klasyfikowane w DLP\'>lotnych dni = <b>'.$this->stat['licz_dni_lot'].'</b></span>
	 <span title=\'punkty jakie zdobyli razem piloci DLP od poczatku sezonu\'>razem = <b>'.$this->stat['km'].' pkt</b></span>
	 <span title=\'rekordowy lot DLP w aktualnym sezonie\'>Rekord = <b>'.$this->stat['rekord'][0].' pkt</b></span><br />
	 <span title=\'data | pilot | link do traku\'>'.$this->stat['rekord'][1].' | '.$this->stat['rekord'][2].$trak.'</span>';

	 if($this->nrMonthNow2 > 3 && $this->stat['pil_mie'] > 0)
	 {
	  $this->w .= '
	 <p>W miesiącu: '.$this->nrToMonth($this->nrMonthNow2).'</p>
	 <span title=\'liczba pilotów, którzy wykonali lot w wybranym miesiącu\'>pilotów = <b>
	 '.$this->stat['pil_mie'].'</b></span>
	 <span title=\'liczba wykonanych lotów w  wybranym miesiącu\'>lotów = <b>'.$this->stat['lot_mie'].'</b></span>
	 <span title=\'liczba dni w  wybranym miesiącu, w których wykonano loty klasyfikowane w DLP\'>lotnych dni = <b>'.$this->stat['dni_mie'].'</b></span>
	 <span>razem = <b>'.$this->stat['km_mie'].' pkt</b></span>';

	 if($this->stat['rek_mie'][0])
	  $this->w .= '
	 <span title=\'punkty jakie zdobyli razem piloci DLP w wybranym miesiącu\'>Rekord = <b>'.$this->stat['rek_mie'][0].' pkt</b></span><br />
	 <span title=\'data | pilot | link do traku\'>'.$this->stat['rek_mie'][1].' | '.$this->stat['rek_mie'][2].$trak2.'</span>';
	 }

	 //$this->w .= '</div>'; // 2018-12-27


	 $this->w .= '
	<ul id=\'dlp_menu\'>
	  <li><a '.$class2015.'href=\''.$akcja_back.'2015+'.$this->akcja.'.html\'> 2015 </a></li>
	  <li><a '.$class2016.'href=\''.$akcja_back.'2016+'.$this->akcja.'.html\'> 2016 </a></li>
	  <li><a '.$class2017.'href=\''.$akcja_back.'2017+'.$this->akcja.'.html\'> 2017 </a></li>
	  <li><a '.$class2018.'href=\''.$akcja_back.'2018+'.$this->akcja.'.html\'> 2018 </a></li>
	  <li><a '.$class2019.'href=\''.$akcja_back.'2019+'.$this->akcja.'.html\'> 2019 </a></li>
	  <li><a '.$class2020.'href=\''.$akcja_back.'2020+'.$this->akcja.'.html\'> 2020 </a></li>
	  <li><a '.$class2021.'href=\''.$akcja_back.'2021+'.$this->akcja.'.html\'> 2021 </a></li>
	</ul>'.$w;


  if($this->jo)
  {
	//$akcja = $this->nr_testu.'+8+'.$id.'+'.$this->akcja;
  	//$this->w .= '<a class=\'test_nota\' href=\''.S::linkCode(array($this->tab, $id, 'edycja','', $akcja)).'.htmlc\' >EDIT</a>';
	//$akcja = $this->nr_testu.'+8+id+'.$this->akcja;

	//$this->w .= '<a class=\'test_nota\' href=\''.S::linkCode(array($this->tab, $id, 'edycja','', $akcja)).'.htmlc\' >EDIT</a>';

	$edit = '<a class=\'edit_dlp\' href=\''.S::linkCode(array($this->tabp,0,'formu','', $this->akcja, '')).'.htmlc\' >DODAJ PILOTA</a>';
	$edit .= '<a class=\'edit_dlp\' href=\''.S::linkCode(array($this->tabl,0,'formu','', $this->akcja, '')).'.htmlc\' >DODAJ LOT</a>';
	$edit .= '<div id=\'edit_dlp\'> </div>';

	$this->w = $edit . $this->w;

	unset($edit);

  }

   C::add('adcss', '
	<link rel="stylesheet" href="./application/glajtem_dlp_20170319.css" type="text/css" media="screen" />');			//-css dlo formatowania zdjęć

 	return array($this->w.$tt, $this->year, $this->xx);
 }

 /**
 * reklamy sposorów DLP
 *
 */

 private function sponsor()
 {
  //if($this->year <> 2017) return;

  //return;
  if($this->wrok == '2017')
   $sp = '
  <div id=\'sponsor\'>
	<img class=\'sponsor_icon\' src="./application/skin/m_sps.png" alt=\'Stowarzyszenie Paralotnowe Sudety\'/>
	<img class=\'sponsor_icon\' src="./application/skin/m_kkp.png" alt=\'Karkonoski Klub Paralotniowy\'/>
	<img class=\'sponsor_icon\' src="./application/skin/m_browar.png" alt=\'Browar Świdnica\'/>
	<img class=\'sponsor_icon\' src="./application/skin/m_ensky.png" alt=\'Serwis Paralotniowy EnSky\'/>
	<img class=\'sponsor_icon\' src="./application/skin/m_ifly.png" alt=\'Sklep i Komis Paralotniowy IFLY\'/>
	<img class=\'sponsor_icon\' src="./application/skin/m_tryfly.png" alt=\'TryFly Extreme Shop\'/>
   <img class=\'sponsor_icon\' src="./application/skin/m_gradient.png" alt=\'SkyTrakking - dystrybutor marki Gradient\'/>
	<img class=\'sponsor_icon\' src="./application/skin/m_skytrekking.png" alt=\'SkyTrakking - szkoła - sklep - serwis\'/>
	<img class=\'sponsor_icon\' src="./application/skin/m_ccoptyk.png" alt=\'CCoptyk - Adam Grzech sponsorem DLP2017\'/>
  </div>';


  if($this->wrok == '2018')
   $sp = '
  <div id=\'sponsor\'>

  <a href=\'http://www.paralotnie-sudety.pl/\' title=\'Stowarzyszenie Paralotnowe Sudety - Twój klub paralotniowy\' target=\'_blanc\'>
	<img class=\'sponsor_icon\' src="./application/skin/m_sps.png" alt=\'Stowarzyszenie Paralotnowe Sudety\'/></a>

	<img class=\'sponsor_icon\' src="./application/skin/m_browar.png" alt=\'Browar Świdnica\'/>

  <a href=\'http://para-spa.pl/\' title=\'Serwis Paralotniowy En-Sky\' target=\'_blanc\'>
	<img class=\'sponsor_icon\' src="./application/skin/m_ensky.png" alt=\'Serwis Paralotniowy En-Sky\'/></a>

  <a href=\'http://ifly.com.pl/\' title=\'Sklep i Komis Paralotniowy IFLY\' target=\'_blanc\'>
	<img class=\'sponsor_icon\' src="./application/skin/m_ifly.png" alt=\'Sklep i Komis Paralotniowy IFLY\'/></a>

  <a href=\'http://www.tryfly.pl/\' title=\'TryFly Extreme Shop\' target=\'_blanc\'>
	<img class=\'sponsor_icon\' src="./application/skin/m_tryfly.png" alt=\'TryFly Extreme Shop\'/></a>

  <a href=\'http://www.skytrekking.pl/\' title=\'SkyTrekking - szkoła - sklep - serwis\' target=\'_blanc\'>
	<img class=\'sponsor_icon\' src="./application/skin/m_skytrekking3.png" alt=\'SkyTrakking - szkoła - sklep - serwis\'/></a>

  <a href=\'https://flylite.pl/\' title=\'TryFly Extreme Shop\' target=\'_blanc\'>
	<img class=\'sponsor_icon\' src="./application/skin/flylite_logo_pion.png" alt=\'flylite - sklep paralotniowy Pawła Farona\'/></a>


	<a href=\'https://flyadventure.pl/\' title=\'FlyAdventure - Szkoła Sportów Lotniczych w Mieroszowie\' target=\'_blanc\'>
	 <img class=\'sponsor_icon\' src="./application/skin/flyadventure_logo_pion.png" alt=\'FlyAdventure - Szkoła Sportów Lotniczych w Mieroszowie\'/></a>
  </div>';

  if($this->wrok == '2019')
   $sp = '
  <div id=\'sponsor\'>

  <a href=\'http://www.paralotnie-sudety.pl/\' title=\'Stowarzyszenie Paralotnowe Sudety - Twój klub paralotniowy\' target=\'_blanc\'>
	<img class=\'sponsor_icon\' src="./application/skin/m_sps.png" alt=\'Stowarzyszenie Paralotnowe Sudety\'/></a>


  <a href=\'http://para-spa.pl/\' title=\'Serwis Paralotniowy En-Sky\' target=\'_blanc\'>
	<img class=\'sponsor_icon\' src="./application/skin/m_ensky.png" alt=\'Serwis Paralotniowy En-Sky\'/></a>

  <a href=\'http://ifly.com.pl/\' title=\'Sklep i Komis Paralotniowy IFLY\' target=\'_blanc\'>
	<img class=\'sponsor_icon\' src="./application/skin/m_ifly.png" alt=\'Sklep i Komis Paralotniowy IFLY\'/></a>

  <a href=\'http://www.skytrekking.pl/\' title=\'SkyTrekking - szkoła - sklep - serwis\' target=\'_blanc\'>
	<img class=\'sponsor_icon\' src="./application/skin/m_skytrekking3.png" alt=\'SkyTrakking - szkoła - sklep - serwis\'/></a>

	<a href=\'https://flyadventure.pl/\' title=\'FlyAdventure - Szkoła Sportów Lotniczych w Mieroszowie\' target=\'_blanc\'>
	 <img class=\'sponsor_icon\' src="./application/skin/flyadventure_logo_pion.png" alt=\'FlyAdventure - Szkoła Sportów Lotniczych w Mieroszowie\'/></a>

	 <a href=\'https://www.facebook.com/TowarzystwoLotniczewSwiebodzicach\' title=\'Towarzystwo Lotnicze Świebodzice\' target=\'_blanc\'>
	 <img class=\'sponsor_icon\' src="./application/skin/m_tls.png" alt=\'Towarzystwo Lotnicze Świebodzice\'/></a>

  </div>';

  if($this->wrok == '2020')
   $sp = '
  <div id=\'sponsor\'>

  <a href=\'http://www.paralotnie-sudety.pl/\' title=\'Stowarzyszenie Paralotnowe Sudety - Twój klub paralotniowy\' target=\'_blanc\'>
	<img class=\'sponsor_icon\' src="./application/skin/m_sps.png" alt=\'Stowarzyszenie Paralotnowe Sudety\'/></a>

	<a href=\'https://opcsport.com/\' title=\'OPC\' target=\'_blanc\'>
	<img class=\'sponsor_icon\' src="./application/skin/m_opc.png" alt=\'OPC\'/></a>

	<a href=\'https://flylite.pl/\' title=\'flylite-sklep paralotniowy Pawła Farona\' target=\'_blanc\'>
	<img class=\'sponsor_icon\' src="./application/skin/flylite_logo_pion.png" alt=\'flylite - sklep paralotniowy Pawła Farona\'/></a>


	<a href=\'http://www.fscebook.com/solparagliderspolska/\' title=\'Sol Paragliders Polska\' target=\'_blanc\'>

	<img class=\'sponsor_icon\' src="./application/skin/m_sol.jpg" alt=\'Sol Paragliders Polska\'/></a>


  </div>';


  return $sp;
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