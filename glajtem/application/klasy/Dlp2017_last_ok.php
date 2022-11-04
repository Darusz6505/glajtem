<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* wtyczka dedykowana: dla glajtem.pl -> 2017
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
 private $years = array('2015','2016','2017');

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


 private $mmonth = '';					//-elemnet linku miesiąc
 private $klase = '';					//-element linku klasa

 private $wrok = '';						//-wybrany rok

 private $pil = FALSE;					//-znacznik wyboru strony -> pilot
 private $pilot = 0;						//-identyfikator pilota

 private $stat = array('licz_dni_lot' => 0,'licz_lot' => 0, 'licz_pil' => 0, 'rekord' => array());


 function __construct()
 {
  $this->jo = C::get('jo');																	//- wskaźnik Admina

  $this->tabp = C::get('tab_piloci');
  $this->tabl = C::get('tab_dpl');
  $this->akcja = C::Get('akcja');


  list($rokNow, $this->nrMonthNow) = explode('-', date("Y-m", strtotime(C::get('datetime_teraz'))));

  //list($this->rokNow, $this->nrMonthNow) = array('2017', '3'); //do testów

  $this->opcja = C::Get('opcja');

  $opcc = explode('+', $this->opcja);

  $this->pilot = 	$opcc[0];

  //if(!$this->opcja) $this->opcja = $this->nrMonthNow.'+open+'.$rokNow;


  if(!$opccd[0] = array_intersect($opcc, array(3,4,5,6,7,8,9,10)))
   $opccd[0] = $this->nrMonthNow * 1;
  else
   $opccd[0] = $this->adr($opccd[0]); // - pierwsza wartość w tablicy

  if(!$opccd[1] = array_intersect($opcc, array('open', 'sport', 'fun', 'plus')))
	$opccd[1] = 'open';
  else
   $opccd[1] = $this->adr($opccd[1]);


  if(!$opccd[2] = array_intersect($opcc, array('2015', '2016', '2017')))
   $opccd[2] = $rokNow;
  else
   $opccd[2] = $this->adr($opccd[2]);


  $this->opcja = implode('+', $opccd);

  $this->wrok = $opccd[2];

  //if($opcc[2] < $rokNow) $this->nrMonthNow = '11';


  $this->ended = $opccd[2].'-09-30';
  $this->year = $opccd[2];


  $this->nrMonth = $this->mmonth = $opccd[0];

  $this->klase = $this->klasa = $opccd[1];


  $parametry = array($this->nrMonth, $this->klase, $this->year);

  unset($opcc, $opccd);


  //$this->w = '<h2>'.$this->opcja.'</h2>'; // do testów


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

	Test::trace(__METHOD__ .' parametry  -> ', $parametry);

	unset($parametry);
 }

 /**
 *
 * funkcja zwracając pierwszą wartość w tablicy
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
  //pil_glajt
  //$war =

  $war2 = '';

  $t = $sum_pkt = array();

  $tab = 'SELECT * FROM '.$this->tabp; //.$war;  //-tabela pilotów
  //Test::trace(__METHOD__ .' tab1 ->', $tab);
  //unset($war);

	 if($this->nrMonth)
	 {
	  $war2 = ' AND dpl_data < \''. date("Y-m-d", mktime(0, 0, 0, ($this->nrMonth + 1), 1, $this->year)).'\'';
 	  $war2 .= ' AND dpl_data > \''. date("Y-m-d", mktime(0, 0, 0, 3, 1, $this->year)).'\'';
	 }
	 else
	 {
	  $war2 = ' AND dpl_data < \''. date("Y-m-d", mktime(0, 0, 0, ($this->nrMonthNow + 1), 1, $this->year)).'\'';
 	  $war2 .= ' AND dpl_data > \''. date("Y-m-d", mktime(0, 0, 0, 3, 1, $this->year)).'\'';
	 }


    if($tab = DB::myQuery($tab))
     while($ta = mysql_fetch_assoc($tab))
	  {

	  // if($par) $ta['pil_id'] = $this->pilot;

		$tb2 = 'SELECT * FROM '.$this->tabl.' WHERE dpl_pilot = '.$ta['pil_id'].$war2; //-tabela lotów
		//Test::trace(__METHOD__ .' tab2 ->', $tb2);

		if($tb2 = DB::myQuery($tb2))
       while($tb = mysql_fetch_assoc($tb2))
	    {

		  $t[$ta['pil_name']][$tb['dpl_data']] =  array('x', $tb['dpl_km'], $tb['dpl_track']);

		  $pil['k'.$tb['dpl_pilot']]	= $ta['pil_name'];										//-tablica do wyłonienia zwycięzców

		  $pilot[$ta['pil_name']] = array($ta['pil_xc'], $ta['pil_glajt'], $ta['pil_xcc'], $ta['pil_id']);

		  $sum_pkt[$ta['pil_name']] = 0; 															//-dla nadania wartości - eliminuje ostrzeżenia kompilatora

		 }

		unset($tb, $tb2);

	  }

	unset($war2);



  if(!$t) return;				//-nie ma tabeli lotów => wyjście

  $tt = $this->day($t);		//-tabela dni w których było latanie
  sort($tt);

  $this->stat['licz_dni_lot'] = count($tt);

  $rek = $this->rekord($t);

  $this->stat['rekord'] = reset($rek);

  Test::trace(__METHOD__ .' rekord = ', $rek);

	/* po sortowaniu
	 $t =
	 [0] => 2015-03-06
    [1] => 2015-03-07
    [2] => 2015-03-08
   */

	// pętla jest po wszystkich lotach w danym dniu, a przy filtrowaniu, lista pilotów jest ograniczona do danej klasy

	//Test::trace(__METHOD__ .' TABB', $tt);

	 foreach($tt as $v) 											//$v = data lotu
	 {

		$tb2 = "SELECT * FROM ".$this->tabl." WHERE dpl_data = '".$v."'";

		if($tb2 = DB::myQuery($tb2))
       while($tb = mysql_fetch_assoc($tb2))
		 {

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

	   array_multisort($m, SORT_DESC); 							//-tablica lotów w danym dniu, na pierwszym miejscu zwycięzca

		//Test::trace(__METHOD__ .' day'.$D[1], $m);

	   $mm = each($m);
	   $max_dyst = $zw[$pil[$mm['key']]][$v] = $mm[1];		//-tablica zwycięzców w danym dniu to jest pilot max

		$mm = each($m);
		if($max_dyst == $mm[1])
		 $zw[$pil[$mm['key']]][$v] = $mm[1]; 					// - 2 gi egzekwo -> zrobić ewentualnie 3-go :)


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

  //----
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
 {      //$this->tabela($t, $zw, $pilot, $ob_pkt, $sum_pkt, $id);

   $sumatmp = array();
	$nmm = array();

   if($this->klasa)											// warunek ograniczenia do danej klasy
	{

	 $s = strtoupper(substr($this->klasa, 0, 1));

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

	 //Test::trace(__METHOD__ .' warunek', $tab);

    unset($s);
   }
	else
	 $war_klasy = array('O', 'S', 'F', 'P', '');

  $klas = $sum_pkt;

  $tt = $this->day($t);																// generuje tablicę kolumn (dni) które zawierają dane

  $lp = 1;																				// liczba porządkowa

  $w = '';

  //$this->stat['licz_pil'] = count($klas);

  foreach($klas as $k => $v)														// pętla po klasyfikacji, pilotach którzy zgłosili swoje loty
  {
   //$k pilot
	//$v suma km

   if(array_intersect($pilot[$k], $war_klasy) && $v > 0)
	{

	 $lzw =  0;
    $licz_kol = $liczDni = $liczMie = $liczPustMie = $sumatmpakt = 0;

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


	  if(!$nm = $this->sumaMonth($day))											//-dla aktualnego miesiąca wyświetla loty, a dla poprzednich zlicza punkty
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
	      $pt = '<td'.$czw.'><p>'.$obl[$k][$day].'</br>'.$pp[1].'</p>'.$link_ed_lot.'</td>';

		 }
	    else
	     $pt = '<td>-</td>';
	   }
	   else
		{
		 if($this->year == '2017')
		  $pt = '<td'.$czw.'><a href=\''.$pp[2].'\' target=\'_blanc\' title=\'Zobacz lot na XC\'><p>'.$obl[$k][$day].'</p></a>'.$link_ed_lot.'</td>';
		 else
	     $pt = '<td'.$czw.'><a href=\''.$pp[2].'\' target=\'_blanc\' title=\'Zobacz lot na XC\'><p>'.$obl[$k][$day].'</br>'.$pp[1].'</p></a>'.$link_ed_lot.'</td>';


		}
	   $liczDni ++;


		if($this->klasa == 'plus')
		{
		 if($pp[1] <= 50)
		  $pt = '<td>-</td>';
		}

		$p .= $pt;

		unset($pt);

	   if(isset($obl[$k][$day])) $sumatmpakt += $obl[$k][$day];
     }
	  else
	   if(isset($obl[$k][$day]))
	   {
		 if(isset($sumatmp[$nm]))
		  $sumatmp[$nm] += $obl[$k][$day]; 		 //-sumowanie punktów dla poprzednich miesięcy
		 else
		  $sumatmp[$nm] = $obl[$k][$day];
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

	$nazwa = '<p>'.preg_replace('/\|/', '</br>/', $k).'</p>';

	//$nazwa = '<p>'.$k.'</p>';

	if(!$pilot[$k])
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
	  <td id=\'pilot_nr'.$pilot[$k][3].'\'>&nbsp;'.($lp++).'&nbsp;</td><td class=\'pil_name\'>'.$name.$link_ed_pilot.'</td><td>&nbsp;'.$pilot[$k][1].'&nbsp;</td><td class=\'suma\'><a href=\''.$pilot[$k][3].'+'.$this->wrok.'+pilot.html\' title=\'zobacz listę lotów pilota zakwalifikowanych do DLP\'>'.$sum_pkt[$k].'</a></td><td>'.$lzw.'</td>'.$p;

   $w .= '
	 <tr'.$kl_wiersz.'>'.$row.'</tr>';




	unset($p, $licz, $lzw, $name, $nazwa, $kl_wiersz);
  }
  }

  $this->stat['licz_pil'] = $lp-1;

  if($w) 							  					//-jeśli są wiersze z danymi to tworzony jest nagłówek tabeli
  {
   $hed = '';


   foreach($tt as $wart) 							//-dni aktualnego miesiąca
   {

	 if(!$nm = $this->sumaMonth($wart))
	  $hed .= '<td>'.substr($wart,5).'</td>';
	 else
	  $nmm[$nm] = $this->nrToMonth($nm);

   }

	$nmm = $this->dodPusteMie($nmm);														//-uzupełnienie tablicy o dodatkowe puste miesiące

	if(!$this->nrMonth || $this->nrMonth == $this->nrMonthNow || $this->jo)
    if($liczDni < $this->dniPrzed)
	  if(!$this->ended)
	   $hed =  $hed . $this->kols($this->dniPrzed-$liczDni);  					//-dodatkowe kolumny jeśli mało dni lotnych

	foreach($nmm as $wart)																	//-i suma poprzedniego
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
 *
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

	//Test::trace(__METHOD__ .' TAB', $sum_pkt);

   foreach($pilot as $k => $v)
   {
    $p[$v[3]] = array($v[0], $v[1], $v[2], $k);
   }

	$pilot = $p;
	unset($p);

	ksort($t[$pilot[$n][3]]);

   //Test::trace(__METHOD__ .' PILOT2', $p);

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
	  <td><a href=\''.$v[2].'\' target=\'_blank\' title=\'zobacz track\'>track</a></td>
	 </tr>';

	 ////$suma = $suma + $ob_pkt[$pilot[$n][3]][$k];
	}

	$hed = '<td>L.p.</td><td>data lotu</td><td>długość lotu</td><td>przyznane punkty</td><td title=\'liczba sklasyfikowanych pilotów danego dnia\'>l.lotów</td><td>track</td>';


	foreach($sum_pkt as $k => $v)
	{
	 $sum_pkt[$k] = array($v, ++$poz);
	}

	$this->w .= '

   <h2>'.$pilot[$n][3].'&nbsp;&nbsp;<b>suma punktów : </b>'.$sum_pkt[$pilot[$n][3]][0].'&nbsp;&nbsp;<b>liczba zwycięstw : </b>'.$lz.'&nbsp;&nbsp;<b>pozycja : </b>'.$sum_pkt[$pilot[$n][3]][1].' | <a href=\''.$pilot[$n][0].'\' target=\'_blank\' title=\'Zobacz konto pilota na XC\'>XC konto</a></h2>

   <div id=\'smart_window\'>
	<table id=\'dlp_tab\'>
    '.$hed.'
    '.$p.'
   </table>
	<br />
	<a class=\'back\' href="./dolnoslaska-liga-paralotniowa.html#pilot_nr'.$n.'">Wróć do DLP</a>
	</div>';

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
	 $kkk = $this->nrMonth;
	else
	 $kkk = $this->nrMonthNow;

   for($kk = 3; $kk < $kkk; $kk++)
	{
	 if($p)
    {
	  if(!isset($nmm[$kk])) $nmm[$kk] = $this->nrToMonth($kk);
	 }
	 else
	 {
	  if(!isset($nmm[$kk])) $nmm[$kk] = 0;
	 }
	}
	unset($kk, $kkk);

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
 * funkcja wybiera rekordowy lot
 *
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
  //$month = array('', '', 'marzec', 'kwiecień', 'maj', 'czerwiec', 'lipiec', 'sierpień', 'wrzesień', 'październik', '', '');
  //if(!$this->jo) return;

  $t = $kl = '';

  if($this->klasa)
   $kl = '+'.$this->klasa;

  if($this->wrok)								// wybrany rok
   $kl = $kl.'+'.$this->wrok;


  //exit('<p>nrMonthNow = '.$this->nrMonthNow.'</p>');

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
   return '';

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
	 <p>- plik ze śladem lotu zoptymalizowany pod względem długości lotu wg <a href=\'http://xcportal.pl\' target=\'_blanc\'>XC</a> Portal, <a href=\'http://xcc.paragliding.pl\' target=\'_blanc\'>XCC</a> lub <a href=\'http://http://www.xcontest.org/world/en/flights/daily-score-pg/\' target=\'_blanc\'>XContest</a></p>
	 <p class=\'center\'><a href=\'https://www.facebook.com/Dolno%C5%9Bl%C4%85ska-Liga-Paralotniowa-1635901036706352/\' title=\'zobacz stronę DLP 2015 na Facebook\'u\' target=\'_blanc\'>Strona DLP na Facebook\'u</a></p>
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
  <div id=\'dlp_status_livetrack\' >
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



  if($this->nrMonthNow < 3 && $this->rokNow > $this->year)
   $w = '
    <h2 class=\'dlp_tyt\'>Dolnośląska Liga Paralotniowa '.$this->year.' - start 1 marca !!</h2>
	 <h2 class=\'dlp_tyt\'>Zobacz wyniki z lat poprzednich.</h2>
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

	if($this->pil)
	{
	 $akcja_back = explode('+', $this->opcja[0]);
	 $akcja_back = $akcja_back[0].'+';
	}

	/* bo powyżej ?
	if($this->akcja == 'pilot')
	{
	 $akcja_back = $this->pilot .'+';
	}
   */

	 if($this->stat['rekord'][3] != '')
	  $trak = ' | <a href=\"'.$this->stat['rekord'][3].'\">Track</a>';
	 else
	  $trak = '';


	if($this->stat['licz_lot'] > 0)
	 $this->w = '
   <div class=\'dlp_stat\'>
	 <span>Liczba sklasyfikowanych pilotów = <b>'.$this->stat['licz_pil'].'</b> | </span>
	 <span>Liczba sklasyfikowanych lotów = <b>'.$this->stat['licz_lot'].'</b> | </span>
	 <span>Liczba lotnych dni = <b>'.$this->stat['licz_dni_lot'].'</b></span>
	 <br />
	 <span>Rekord = <b>'.$this->stat['rekord'][0].' km</b>'.$this->stat['rekord'][1].' | '.$this->stat['rekord'][2].$trak.'</span>
	</div>';


	$this->w .= '
	<ul id=\'dlp_menu\'>
	  <li><a '.$class2015.'href=\''.$akcja_back.'2015+'.$this->akcja.'.html\'> 2015 </a></li>
	  <li><a '.$class2016.'href=\''.$akcja_back.'2016+'.$this->akcja.'.html\'> 2016 </a></li>
	  <li><a '.$class2017.'href=\''.$akcja_back.'2017+'.$this->akcja.'.html\'> 2017 </a></li>
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
	<link rel="stylesheet" href="./application/glajtem_dlp_20170319.css" type="text/css" media="screen" />'); //-css dla ligi

	/* !!!! skorygować zawartość $this->w dla $this->pil */
	if($this->pil)
	 return array($tt, $this->year);
	else
 	 return array($this->w.$tt, $this->year);
 }

 /**
 *
 *
 */

 private function sponsor()
 {
  //if($this->year <> 2017) return;

  //return;

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