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
  $this->opcja = C::Get('opcja');
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

 	 $tab = 'SELECT * FROM '.$this->tabp;

    if($tab = DB::myQuery($tab))
     while($ta = mysql_fetch_assoc($tab))
	  {

		$tb2 = 'SELECT * FROM '.$this->tabl.' WHERE dpl_pilot = '.$ta['pil_id'];

		if($tb2 = DB::myQuery($tb2))
       while($tb = mysql_fetch_assoc($tb2))
	    {

		  $day =	date("d-m", strtotime($tb['dpl_data']));

		  $t[$ta['pil_name']][$day] =  array('', $tb['dpl_km'], $tb['dpl_track']);

		  $pil['k'.$tb['dpl_pilot']]	= $ta['pil_name'];										//-tablica do wyłonienia zwycięzców

		  $pilot[$ta['pil_name']] = array($ta['pil_xc'], $ta['pil_glajt'], $ta['pil_xcc'], $ta['pil_id']);

		 }

		unset($tb, $tb2, $r);
	  }



  foreach($t as $k => $v)
  {
	 foreach($v as $k2 => $v2)
	  $tt[$k2] = $k2;
  }

  unset($k, $k2, $v, $v2);

  sort($tt);  //-tablica ( dat ) dni (kolumn) w kórych zgłoszono loty

  // ----------------------------- obliczanie zwycięstw


  //Test::trace(__METHOD__ .' tt ', $tt);

	/**
	 $t =
	 [0] => 06-03
    [1] => 07-03
    [2] => 08-03
   */

	 foreach($tt as $v)
	 {
      //$data = $v.'-2015';

		$d = explode('-', $v);

		$data = date("Y-m-d", mktime(0, 0, 0, $d[1], $d[0], 2015));

		$tb2 = "SELECT * FROM ".$this->tabl." WHERE dpl_data = '".$data."'";

		if($tb2 = DB::myQuery($tb2))
       while($tb = mysql_fetch_assoc($tb2))
		 {
		  $m['k'.$tb['dpl_pilot']] = $tb['dpl_km'];
		  $mid['k'.$tb['dpl_pilot']] = $tb['dpl_id'];	//-tabela identyfikatorów w bazie
		 }

	   array_multisort($m, SORT_DESC); //tablica lotów w danym dniu, na pierwszym miejscu zwycięzca

		//Test::trace(__METHOD__ .' day'.$D[1], $m);

	   $mm = each($m);
	   $max = $zw[$pil[$mm['key']]][$v] = $mm[1];  //-tablica zwycięzców w danym dniu to jest pilot max


		$mm = each($m);
		if($max == $mm[1]) $zw[$pil[$mm['key']]][$v] = $mm[1]; // - 2 gi egzekwo */


		//-dodatkowe punkty dla pilota jeśli było więcej lotów w danym dniu

		$licz_pilot_dnia = count($m);

		$st_pkt = 100;
		if($licz_pilot_dnia>1) $st_pkt = 200;
		if($licz_pilot_dnia>2) $st_pkt = 300;
		if($licz_pilot_dnia>3) $st_pkt = 400;

		$max_dyst = $max;

		//400 x 55,6 / 155,6 + 55,6 = 198,53 pkt

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

  $this->tabela($t, $zw, $pilot, $ob_pkt, $sum_pkt, $id);

 }

 /**
 * tworzenie tabelki
 *
 *
 */

 private function tabela($t, $zw, $pilot, $obl, $sum_pkt, $id)
 {

  $tt = $this->day($t);	// generuje tablicę kolumn (dni) które zawierają dane

  $lp = 1;					// liczba porządkowa

  $klas = $sum_pkt;

  foreach($klas as $k => $v)	// pętla po klasyfikacji, pilotach którzy zgłosili swoje loty
  {
   //$k pilot
	//$v suma km

	$lzw =  0;
   $licz_kol = 0;

	foreach($tt as $day)   		// pętla po dniach lotnych
   {
    $licz_kol++;

	 if($zw[$k][$day])
	 {
	  $czw = ' class=\'winer\'';
	  $lzw++;
	 }


	 if($t[$k][$day]) $dane = true; //-znacznik, że są jakieś dane w tabeli

	if($this->jo && $obl[$k][$day])
	{
	 $akcja = $this->akcja;
	 //$this->w .= '<a class=\'test_nota\' href=\''.S::linkCode(array($this->tab, $id, 'edycja','', $akcja)).'.htmlc\' >EDIT</a>';

	 $link_ed_lot = '<a class=\'ed_dlp\' href=\''.S::linkCode(array($this->tabl, $id[$k][$day], 'edycja','', $akcja)).'.htmlc\' title=\'Edytuj lot\'>EL</a>';
	}
	else
	 $link_ed_lot = '';


	 $pp = $t[$k][$day];

	 if(!$pp[2])
	 {
	  if($pp[1])
	   $p .= '<td'.$czw.'><p>'.$obl[$k][$day].'</br>'.$pp[1].'</p>'.$link_ed_lot.'</td>';
	  else
	   $p .= '<td>-</td>';
	 }
	 else
	  $p .= '<td'.$czw.'><a href=\''.$pp[2].'\' target=\'_blanc\' title=\'Zobacz lot na XC\'><p>'.$obl[$k][$day].'</br>'.$pp[1].'</p></a>'.$link_ed_lot.'</td>';


	 unset($czw, $pp, $ob);
	}

	$p .= $this->kols(3); // dodatkowe 3 kolumny

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
   foreach($tt as $wart)
   {
	 $hed .= '<td>'.$wart.'</td>';
   }

   $hed = '<tr class=\'tab_hed\'>
	 <td></td><td></td><td>KL</td><td>punkty</td><td title=\'Liczba zwycięstw w okresie zestawienia\'>LZ</td>'.$hed.'
	 </tr>';

	if($lp < 10)
    $w .= $this->rows(3, ($licz_kol+3));


   $this->w .= '
	<table id=\'dlp_tab\'>
    '.$hed.'
    '.$w.'
   </table>';

  //tworzenie tutułu tabeli
  $this->w = '
	<h2>Dolnośląska Liga Paralotniowa 2015 - marzec</h2>'.$this->w;

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
 * funkcja generuje tablicę kolumn (dni) które zawierają dane
 *
 *
 */

 private function day($t)
 {

  foreach($t as $k => $v)
  {
	 foreach($v as $k2 => $v2)
	 {
	   $s = substr($k2, 0, 2);
		$tt[$k2] = $k2;
	 }
  }

  array_multisort($tt, SORT_DESC);
  unset($k, $k2, $v, $v2);

  return $tt;
 }

 /**
 *
 * dodatkowe puste kolumny, na końcu tabelki
 *
 */

 private function kols($n)
 {
  $n = $n;

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
  for($i = 0; $i < $n; $i++)
   $row .= '
	<tr>
	 <td> - </td><td> - </td><td> - </td><td> - </td><td> - </td>'.$this->kols($p).'
	<tr>';

  return $row;
 }

 /**
 *
 *
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
 *
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


 public function wynik()
 {
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
