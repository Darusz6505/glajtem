<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* wtyczka dedykowana: dla glajtem.pl -> testy egzaminacyjne
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


class Dlp2015
{
 private $w = '';

 private $akcja = '';
 private $opcja = '';


 private $tabp = '';
 private $tabl = '';

 private $jo = false;

 private $month = array('', '', '', 'marzec', 'kwiecień', 'maj', 'czerwiec', 'lipiec', 'sierpień', 'wrzesień', 'październik','zakończona','');

 private $nrMonthNow = '';
 private $nrMonthNow2 = '';
 private $nrMonthNow3 = '';

 private $nrMonth = '';
 private $klasa = '';

 private $year = '2015';

 private $dniPrzed = 8;
 private $dodWierPo = 6;


 /**
 *
 *
 */

 function __construct()
 {
  $this->jo = C::get('jo');																	//- wskaźnik Admina

  $this->tabp = C::get('tab_piloci');
  $this->tabl = C::get('tab_dpl');
  $this->akcja = C::Get('akcja');

  list($rokNow, $this->nrMonthNow) = explode('-', date("Y-m", strtotime(C::get('datetime_teraz'))));
  $this->nrMonthNow2 = $this->nrMonthNow = $this->nrMonthNow * 1;



  if($this->jo) $this->nrMonthNow = 11;	//-do testów !!!

  if($this->opcja = C::Get('opcja'))
  {

	$op = array();

   $this->opcja = explode('+', $this->opcja);

	if($op = array_intersect($this->opcja, array('open', 'sport', 'fun')))
	{
  	 if(isset($op[0])) $this->klasa = $op[0];
	 if(isset($op[1])) $this->klasa = $op[1];
	}
	else
	 $this->klasa = '';


   if($op = array_intersect($this->opcja, array(3,4,5,6,7,8,9,10)))
	{
  	 $this->nrMonth = $op[0];

	 if($this->nrMonth > $this->nrMonthNow)
	  $this->nrMonth = '';

	}
	else
	 $this->nrMonth = '';

  }

  if($this->nrMonth)
   $this->nrMonthNow3 = $this->nrMonth;
  else
	$this->nrMonthNow3 = $this->nrMonthNow2;

  if($this->year !== $rokNow)
	$this->nrMonthNow = 11;

	//Test::trace(__METHOD__ .' nr miesiaca  -> ', $this->nrMonthNow);
 }

 /**
 *
 *
 *
 */

 public function dlp_month()
 {
  $this->dlp_p_moth();
 }

 /**
 *
 *
 *
 *
 */

 private function dlp_p_moth()
 {
   //pil_glajt
	$war = $war2 = '';

	$sum_pkt = array();

   if($this->klasa)	// warunek ograniczenia do danej klasy
	{
	 $s = strtoupper(substr($this->klasa, 0, 1));

	 switch($s)
	 {/*
	  case 'O': $war = ' WHERE pil_glajt = \'O\' OR pil_glajt = \'S\' OR pil_glajt = \'F\'';
		break; */
	  case 'S': $war = ' WHERE pil_glajt = \'S\' OR pil_glajt = \'F\'';
		break;

	  case 'F': $war = ' WHERE pil_glajt = \'F\'';

	 }


	 //Test::trace(__METHOD__ .' warunek', $tab);

    unset($s);
   }

 	 $tab = 'SELECT * FROM '.$this->tabp.$war;
	 //Test::trace(__METHOD__ .' tab1 ->', $tab);
	 unset($war);

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

		$tb2 = 'SELECT * FROM '.$this->tabl.' WHERE dpl_pilot = '.$ta['pil_id'].$war2;
		//Test::trace(__METHOD__ .' tab2 ->', $tb2);

		if($tb2 = DB::myQuery($tb2))
       while($tb = mysql_fetch_assoc($tb2))
	    {

		  $t[$ta['pil_name']][$tb['dpl_data']] =  array('x', $tb['dpl_km'], $tb['dpl_track']);

		  $pil['k'.$tb['dpl_pilot']]	= $ta['pil_name'];										//-tablica do wyłonienia zwycięzców

		  $pilot[$ta['pil_name']] = array($ta['pil_xc'], $ta['pil_glajt'], $ta['pil_xcc'], $ta['pil_id']);

		  $sum_pkt[$ta['pil_name']] = 0; 															//-dla nadania wartości - eliminuje ostrzeżenia kompilatora

		 }

		unset($tb, $tb2, $r);
	  }

	unset($war2);

  //Test::trace(__METHOD__ .' tabela t ', $t);

  if(!$t) return;

  $tt = $this->day($t);		//-tabela dni w których było latanie
  sort($tt);

  Test::trace(__METHOD__ .' tt2 ', $tt);

	/* po sortowaniu
	 $t =
	 [0] => 2015-03-06
    [1] => 2015-03-07
    [2] => 2015-03-08
   */

	/*
	pętla jest po wszystkich lotach w danym dniu, a przy filtrowaniu, lista pilotów jest ograniczona do danej klasy

	*/

	 foreach($tt as $v) //$v = data lotu
	 {

		$tb2 = "SELECT * FROM ".$this->tabl." WHERE dpl_data = '".$v."'";

		if($tb2 = DB::myQuery($tb2))
       while($tb = mysql_fetch_assoc($tb2))
		 {
		  if(isset($pil['k'.$tb['dpl_pilot']]))			//-ograniczenie tylko do pilotów w danej klasie, Ważne !!
		  {
		   $m['k'.$tb['dpl_pilot']] = $tb['dpl_km'];
		   $mid['k'.$tb['dpl_pilot']] = $tb['dpl_id'];	//-tabela identyfikatorów w bazie - identyfikator lotu
		  }
		 }

	   array_multisort($m, SORT_DESC); 						//tablica lotów w danym dniu, na pierwszym miejscu zwycięzca

		//Test::trace(__METHOD__ .' day'.$D[1], $m);

	   $mm = each($m);
	   $max_dyst = $zw[$pil[$mm['key']]][$v] = $mm[1];		//-tablica zwycięzców w danym dniu to jest pilot max

		$mm = each($m);
		if($max_dyst == $mm[1])
		 $zw[$pil[$mm['key']]][$v] = $mm[1]; 					// - 2 gi egzekwo */

		//-dodatkowe punkty dla pilota jeśli było więcej lotów w danym dniu

		$licz_pilot_dnia = count($m);

		$st_pkt = 100;
		if($licz_pilot_dnia>1) $st_pkt = 200;
		if($licz_pilot_dnia>2) $st_pkt = 300;
		if($licz_pilot_dnia>3) $st_pkt = 400;

		//400 x 55,6 / 155,6 + 55,6 = 198,53 pkt

		Test::trace(__METHOD__ .' m '.$v, $m);

		foreach($m as $k2 => $v2)
		{

		 $ob_pkt[$pil[$k2]][$v] = round(((($st_pkt*$v2)/$max_dyst) + $v2),0);

		 $id[$pil[$k2]][$v] = $mid[$k2];

       $sum_pkt[$pil[$k2]] = $sum_pkt[$pil[$k2]] + $ob_pkt[$pil[$k2]][$v]; //-sumowanie punktów dla danego pilota

		}

	  array_multisort($sum_pkt, SORT_DESC);

	  //Test::trace(__METHOD__ .' sum_pkt po ', $sum_pkt);

	  unset($m, $max);

    }

  $winers = $this->winers($t, $zw, $pilot, $ob_pkt, $sum_pkt, $id);

  $this->tabela($t, $zw, $pilot, $ob_pkt, $sum_pkt, $id, $winers);

 }

 /**
 * wyłonienie zwycięzców w poprzednich miesiącach i aktualnym
 *
 *
 *
 *
 */

 private function winers($t, $zw, $pilot, $obl, $sum_pkt, $id)
 {      //$this->tabela($t, $zw, $pilot, $ob_pkt, $sum_pkt, $id);

  $tt = $this->day($t);	//- generuje tablicę kolumn (dni) które zawierają dane


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
  Test::trace(__METHOD__ .' sumy_w_mie ', $sumy_w_mie);

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
	   {
	    $p[$k] = array($pl, $v);
	   }

	  }
	 }

   }


	Test::trace(__METHOD__ .' sumy_w_mie = '.$i.'->', $p);


   foreach($p as $k => $v)				//-przepisanie tablicy do postaci pierwotnej
    $winers[$k][$i] = $p[$k];

  }


	Test::trace(__METHOD__ .' winers ->', $winers);

	unset($i, $k, $v, $p, $tmp, $pl);


	return $winers;
 }

 /**
 * tworzenie tabelki
 *
 *
 *
 *
 */

 private function tabela($t, $zw, $pilot, $obl, $sum_pkt, $id, $winers)
 {      //$this->tabela($t, $zw, $pilot, $ob_pkt, $sum_pkt, $id);

  $tt = $this->day($t);	// generuje tablicę kolumn (dni) które zawierają dane

  $lp = 1;					// liczba porządkowa

  $w = '';

  $klas = $sum_pkt;

  foreach($klas as $k => $v)	// pętla po klasyfikacji, pilotach którzy zgłosili swoje loty
  {
   //$k pilot
	//$v suma km

	$lzw =  0;
   $licz_kol = $liczDni = $liczMie = $liczPustMie = $sumatmpakt = 0;

	$p = '';

	/*
 	for($ii=3; $ii<11; $ii++)   		// pętla po dniach lotnych
   {
	 $sumatmp[$ii] = 0;
   }

	unset($ii);	 */



	foreach($tt as $day)   		// pętla po dniach lotnych
   {

    $licz_kol++;

	 if(isset($zw[$k][$day]))
	 {
	  $czw = ' class=\'winer\'';
	  $lzw++;
	 }
	 else
	  $czw = '';


	 if(isset($t[$k][$day])) $dane = true; //-znacznik, że są jakieś dane w tabeli

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


	 if(!$nm = $this->sumaMonth($day))		//-dla aktualnego miesiąca wyświetla loty, a dla poprzednich zlicza punkty
	 {
	  if(!$pp[2])
	  {
	   if($pp[1])
	    $p .= '<td'.$czw.'><p>'.$obl[$k][$day].'</br>'.$pp[1].'</p>'.$link_ed_lot.'</td>';
	   else
	    $p .= '<td>-</td>';
	  }
	  else
	   $p .= '<td'.$czw.'><a href=\''.$pp[2].'\' target=\'_blanc\' title=\'Zobacz lot na XC\'><p>'.$obl[$k][$day].'</br>'.$pp[1].'</p></a>'.$link_ed_lot.'</td>';

	  $liczDni ++;

	  if(isset($obl[$k][$day])) $sumatmpakt += $obl[$k][$day];
    }
	 else
	  if(isset($obl[$k][$day]))
		$sumatmp[$nm] += $obl[$k][$day]; 	//sumowanie punktów dla poprzednich miesięcy

	 unset($czw, $pp, $ob);
	}

	if(!$this->nrMonth || $this->nrMonth == $this->nrMonthNow || $this->jo)
	 if($liczDni < $this->dniPrzed)
	  $p = $p . $this->kols($this->dniPrzed-$liczDni-$liczMie);  // dodatkowe kolumny jeśli mało dni lotnych


	$sumatmp = $this->dodPusteMie($sumatmp, false);		//-uzupełnienie tablicy o dodatkowe puste miesiące


	if($sumatmp)
	{
	 foreach($sumatmp as $kk => $wwart)							  //-i sumy poprzedniego
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

	 unset($sumatmp, $nm);
	}


/*
	if(!$this->nrMonth || $this->nrMonth == $this->nrMonthNow || $this->jo)
	 if($liczDni < $this->dniPrzed)
	  $p = $p . $this->kols($this->dniPrzed-$liczDni-$liczMie);  // dodatkowe kolumny jeśli mało dni lotnych
 */

	if(isset($winers[$k][$this->nrMonthNow3][0]))
    $p = '<td class=\'dlp_summ\'><p><b>'.$winers[$k][$this->nrMonthNow3][0].'</b><br />'.$sumatmpakt.'</p></td>'.$p;						//-suma dla aktualnego miesiąca
	else
	 if($sumatmpakt)
	  $p = '<td class=\'dlp_summ\'>'.$sumatmpakt.'</td>'.$p;
	 else
	  $p = '<td class=\'dlp_summ\'> </td>'.$p;

	//$nazwa = '<p>'.preg_replace('/ /', '</br>', $k).'</p>';
	$nazwa = '<p>'.$k.'</p>';

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
	  <td>&nbsp;'.($lp++).'&nbsp;</td><td class=\'pil_name\'>'.$name.$link_ed_pilot.'</td><td>&nbsp;'.$pilot[$k][1].'&nbsp;</td><td class=\'suma\'>'.$sum_pkt[$k].'</td><td>'.$lzw.'</td>'.$p;

   $w .= '
	 <tr'.$kl_wiersz.'>'.$row.'</tr>';

	unset($p, $licz, $lzw, $name, $nazwa, $kl_wiersz);

  }


  if($w) //-jeśli są wiersze to tworzony jest nagłówek tabeli
  {
   $hed = '';




   foreach($tt as $wart) 							//-dni aktualnego miesiąca
   {

	 if(!$nm = $this->sumaMonth($wart))
	  $hed .= '<td>'.substr($wart,5).'</td>';
	 else
	  $nmm[$nm] = $this->nrToMonth($nm);

   }

	$nmm = $this->dodPusteMie($nmm);				//-uzupełnienie tablicy o dodatkowe puste miesiące

	if(!$this->nrMonth || $this->nrMonth == $this->nrMonthNow || $this->jo)
    if($liczDni < $this->dniPrzed)
	  $hed =  $hed . $this->kols($this->dniPrzed-$liczDni-$liczMie+1);  // dodatkowe kolumny jeśli mało dni lotnych

	foreach($nmm as $wart)							//-i suma poprzedniego
	 $hed .= '<td>'.$wart.'</td>';

	unset($wart, $nmm, $nrmm);



	if($this->nrMonth) $this->nrMonthNow2 = $this->nrMonth;			// dodatkowa suma dla aktualnego miesiąca

   $hed = '<td>'.$this->nrToMonth($this->nrMonthNow2).'</td>'.$hed;


   $hed = '<tr class=\'tab_hed\'>
	 <td></td><td></td><td>KL</td><td>punkty</td><td title=\'Liczba zwycięstw w okresie zestawienia\'>LZ</td>'.$hed.'
	 </tr>';


	if($lp < $this->dodWierPo)
	 if(!$this->nrMonth || $this->nrMonth == $this->nrMonthNow || $this->jo)
	  $w .= $this->rows($this->dodWierPo - $lp, ($this->dniPrzed + $liczMie+1));

	 /*else
	  $w .= $this->rows($this->dodWierPo - $lp, $liczDni); */


   $this->w .= '
	<table id=\'dlp_tab\'>
    '.$hed.'
    '.$w.'
   </table>';

  //tworzenie tutułu tabeli
  $this->w = '
	<h2>Dolnośląska Liga Paralotniowa '.$this->year.' - '.$this->wybMonth().'</h2>
	'.$this->menu().$this->w;

  }
  else
  {

	$this->w .= '
	<h3>Brak sklasyfikowanych przelotów.</h3>';

  }

  if($this->jo)
  {
	//$akcja = $this->nr_testu.'+8+'.$id.'+'.$this->akcja;
  	//$this->w .= '<a class=\'test_nota\' href=\''.S::linkCode(array($this->tab, $id, 'edycja','', $akcja)).'.htmlc\' >EDIT</a>';
	//$akcja = $this->nr_testu.'+8+id+'.$this->akcja;



	$akcja = $this->akcja;


	//$this->w .= '<a class=\'test_nota\' href=\''.S::linkCode(array($this->tab, $id, 'edycja','', $akcja)).'.htmlc\' >EDIT</a>';


	$edit = '<a class=\'edit_dlp\' href=\''.S::linkCode(array($this->tabp,0,'formu','', $akcja, '')).'.htmlc\' >DODAJ PILOTA</a>';

	$edit .= '<a class=\'edit_dlp\' href=\''.S::linkCode(array($this->tabl,0,'formu','', $akcja, '')).'.htmlc\' >DODAJ LOT</a>';
	$edit .= '<div id=\'edit_dlp\'> </div>';

	$this->w = $edit . $this->w;

	unset($edit);

  }


  $seo_tyt = 'DLP 2015 - Dolnośląska Liga Paralotniowa - na stronach - ';

  if(!C::get('seo', false) && $seo_tyt) C::change('seo', $seo_tyt);

  $con_desk = 'Dolnośląska Liga Paralotnowa 2015 - tabela wyników. Zobacz stronę DLP 2015 na facebooku';

  if($con_desk) C::change('con_desk', $con_desk);

  unset($seo_tyt, $con_desk);

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
	  if(!$nmm[$kk]) $nmm[$kk] = $this->nrToMonth($kk);
	 }
	 else
	 {
	  if(!$nmm[$kk]) $nmm[$kk] = 0;
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
 * funkcja generuje tablicę kolumn (dni) które zawierają dane
 *
 *
 */

 private function day($t)
 {

  foreach($t as $k => $v)
   foreach($v as $k2 => $v2)
	 $tt[$k2] = $k2;

  array_multisort($tt, SORT_DESC);
  unset($k, $k2, $v, $v2, $s);

  return $tt;
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
 *
 */


 private function menu()
 {
  //$month = array('', '', 'marzec', 'kwiecień', 'maj', 'czerwiec', 'lipiec', 'sierpień', 'wrzesień', 'październik', '', '');

  //if(!$this->jo) return;

  $t = $kl = '';

  if($this->klasa)
   $kl = '+'.$this->klasa;

  for($i = 10; $i > 2; $i--)
  {
   if($i == $this->nrMonth)
	 $class = 'class=\'active\' ';
	else
	 $class = '';


	if($i <= $this->nrMonthNow)
    $t .= '
	<li>
	 <a '.$class.'href=\''.$i.$kl.'+'.$this->akcja.'.html\' title=\'zobacz wyniki na koniec mieciąca '.$this->month[$i].'\'>'.$this->month[$i].'</a>
	</li>';
	else
	 $t .= '
	<li>
	 <spin '.$class.'href=\''.$i.$kl.'+'.$this->akcja.'.html\'>'.$this->month[$i].'</spin>
	</li>';

  }

  if($this->nrMonth)
   $mon = $this->nrMonth.'+';
  else
   $mon = '';

  $class1 = $class2 = $class3 = '';

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



  $t .= '
  	<li>
	 <a id=\'dpl_menu_all\' href=\''.$this->akcja.'.html\'> RAZEM </a>
	</li>
	<li>
	 <a '.$class1.'href=\''.$mon.'open+'.$this->akcja.'.html\'> OPEN  </a>
	</li>
	<li>
	 <a '.$class2.'href=\''.$mon.'sport+'.$this->akcja.'.html\'> SPORT </a>
	</li>
	<li>
	 <a '.$class3.'href=\''.$mon.'fun+'.$this->akcja.'.html\'> FUN </a>
	</li>';

  unset($class, $class1, $class2, $class3, $i, $mon);

  return '<ul id=\'dlp_menu\'>'.$t.'</ul>';

 }

  /**
 *
 * nr miesiąca na nazwę miesiąca
 *
 *
 */

 private function nrToMonth($n = 1)
 {

  //$month = array('', '', 'marzec', 'kwiecień', 'maj', 'czerwiec', 'lipiec', 'sierpień', 'wrzesień', 'październik','','');

  if($n > 2 || $n < 11)
   return $this->month[$n];
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

  if($this->nrMonth > 2 && $this->nrMonth < 11)
   return $this->month[$this->nrMonth];
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

  $this->w .= '
  <div id=\'opis_dlp\'>
	<p>- wpis lotów od minimum 15,0 pkt&nbsp;&nbsp;&nbsp;LZ - LICZBA ZWYCIĘSTW DNIA&nbsp;&nbsp;&nbsp;KL - klasa skrzydła:  O - open,  S - sport;  F - fan</p>
	<p>- punktacja z dnia to ilość pilotów ponad dystansem minimalnym razy 100 + pkt za lot, lecz nie więcej niż 400 + pkt za lot</p>
	<p>- współczynniki: 1. tójkąt FAI - 1km: 1.5pkt;  2. trójkąt płaski - 1km: 1.3pkt; 3. przelot otwarty - 1km: 1.0pkt</p>
	<p>- plik ze śladem lotu zoptymalizowany pod względem długości lotu wg <a href=\'http://xcportal.pl\' target=\'_blanc\'>XC</a> Portal lub <a href=\'http://xcc.paragliding.pl\' target=\'_blanc\'>XCC</a></p>
	<p class=\'center\'><a href=\'https://www.facebook.com/pages/Dolno%C5%9Bl%C4%85ska-Liga-Paralotniowa-2015/748196908590722?fref=ts\' title=\'zobacz stronę DLP 2015 na Facebook\'u\' target=\'_blanc\'>Strona DLP 2015 na Facebook\'u</a></p>
	</div>';

 }

 /**
 *
 * linki liverack24 i inne
 *
 */

 private function livetrack()
 {
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
   if(!$this->w)
    $this->w .= '
	<h3>Brak sklasyfikowanych przelotów.</h3>';

   $this->opis();
	$this->livetrack();

 	return $this->w;
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