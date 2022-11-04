<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* klasa uniwersalna-> dane kontaktowe / adresowe v.1.2
*
* 2021-01-09 : modyfikacje do wersji PHP 7.xx
*
* 2014-03-04 : nowy link admina
* 2012-12-30 : nowy link admina
* 2012-09-27
*
* 2011-04-01 -> 2011-05-12 -> 2011-11-30 -> 2011-12-28 -> 2012-08-13 :: poprawka mapy googl'a
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2011-03-14 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/

/* !!!!!!!

takie parametry jak wyświetlanie danych, wyświetlanie mapy, umieścić jako checkboxy w tabeli danych adresowych

*/


class DaneAdres
{

 const ZOOM = 12;		//- domyślny zoom dla mapy

 /**
 *
 *
 */

 private $w = ''; 					//-wynik działania klasy
 private $d = array();				//-tabele z danymi
 private $t = array();				//-tabele z danymi

 /**
 *
 *
 */

 function __construct()
 {

  switch(C::get('lang'))
  {
	case 'DE':

	 $this->t = array
	 (
		'tel1' 				=> 'Telefon',
		'tel2' 				=> 'Mobile',
		'fax'  				=> 'Fax',
		'mapa_tytul' 		=> 'Lageplan ...',
		'brak_danych' 		=> 'Address Daten nicht verfügbar',
		'ustaw_dane'		=> 'ustaw dane adresowe',
		'edycja_danych'	=> 'edycja danych',
	 	'edytuj'				=> 'edytuj ustawienia właściciela serwisu',
		'brak_danych_kom'	=> 'Dane w trakcie redagowania, zapraszamy z kilka minut.'
	 );

	break;

   default:

    $this->t = array
	 (
		'tel1' 				=> 'Telefon',
		'tel2' 				=> 'Komórka',
		'fax'  				=> 'Fax',
		'mapa_tytul' 		=> 'Mapa ...',
		'brak_danych' 		=> 'Brak danych adresowych - Komunikat widoczny tylko dla Administratora',
		'ustaw_dane'		=> 'ustaw dane adresowe',
		'edycja_danych'	=> 'edycja danych',
	 	'edytuj'				=> 'edytuj ustawienia właściciela serwisu',
		'brak_danych_kom'	=> 'Dane adresowe niedostępne.'
	 );

  }

   $tab = 'SELECT * FROM '.C::get('tab_owner').' ORDER BY own_id LIMIT 1';

	if($tab = DB::myQuery($tab))
	 if(!$this->d = mysqli_fetch_assoc($tab))
	 {

     if(C::get('jo'))
	  $this->w = '
	 <div id=\'dane_adr\'>
	  <div class=\'ed edR edTr\'>
  		<a href=\''.S::linkCode(array(C::get('tab_owner'),0,'formu', '', C::get('akcja'))).'.htmlc\'
			title=\'ustaw dane adresowe\'>ustaw dane</a>
 	  </div>
	  <p class=\'dadr_admin\'>'.$this->t['brak_danych'].'</p>
	 </div>';


	 }
    else
	 {
	  if(!$this->d['own_stat'])
	  {
	   if($this->d['own_nazwa1'])
		 $this->w = '
 	<address class=\'adres_kontakt\'>
	 <p class=\'redakcja\'>'.$this->d['own_nazwa1'].'</p>
	</address>';
	   else
	    $this->w = '
 	<address class=\'adres_kontakt\'>
	 <p class=\'redakcja\'>'.$this->t['brak_danych_kom'].'</p>
	</address>';

	   unset($this->d['own_nazwa1']);
	  }

     if(C::get('jo'))
	   $this->w = '
	 <div id=\'dane_adr\'>
	  <div class=\'ed edL edTr\'>
  		<a href=\''.S::linkCode(array(C::get('tab_owner'), $this->d['own_id'],'edycja', '', C::get('akcja'))).'.htmlc\'
			title=\''.$this->t['edycja'].'\'>'.$this->t['edycja_danych'].'</a>
 	  </div>
	  <p class=\'dadr_admin\'>'.$this->t['brak_danych'].'</p>
	 </div>'.$this->w;

	 }

 }

 /**
 *
 *
 *
 */

 public function daneAdres()
 {
  if($this->d) $this->dane();

  return $this->w;
 }

 /**
 *
 *
 *
 */

 private function dane()
 {
  if(C::get('jo'))
  {
  	$li_atrw = '
	 <div id=\'dane_adr\'>
	  <div class=\'ed edR edTr\'>
  		<a href=\''.S::linkCode(array(C::get('tab_owner'), $this->d['own_id'],'edycja', '', C::get('akcja'))).'.htmlc\'
			title=\''.$this->t['edycja'].'\'>'.$this->t['edycja_danych'].'</a>
 	  </div>';

   $li_end = '</div>';
  }

  if($this->d['own_imie']) $wt['own_nazwa1'] = '
	<p class=\'naz1\'>'.S::formZaja($this->d['own_imie']).'</p>';

  if($this->d['own_nzwi']) $wt['own_nazwa2'] = '
	<p class=\'naz2\'>'.S::formZaja($this->d['own_nzwi']).'</p>';

  if($this->d['own_opis']) $wt['own_opis'] = '
   <blockquote>'.formText($this->d['own_opis'], C::get('jo')).'
	</blockquote>';


  if($this->d['own_kodp']) $wt['own_miasto'] = $this->d['own_kodp'].'&nbsp;';

  if($this->d['own_mias']) $wt['own_miasto'] = '
	<p class=\'miasto\'>'.$wt['own_miasto'].$this->d['own_mias'].'</p>';


  if($this->d['own_ulic']) $wt['own_ulica'] .= $this->d['own_ulic'].'&nbsp;';

  if($wt['own_ulica'])
  {
   if($this->d['own_plac']) $wt['own_ulica']  = $this->d['own_plac'].'&nbsp;'.$wt['own_ulica'];

   if($this->d['own_nrdo']) $wt['own_ulica'] .= '&nbsp;'.$this->d['own_nrdo']; else $this->d['own_nrdo'] = '';

   if($this->d['own_nrlo'])
   {
    $this->d['own_nrlo'] = '/'.$this->d['own_nrlo'];
    $wt['own_ulica'] .= $this->d['own_nrlo'];
   }
   else $this->d['nrlo'] = '';

   if($this->d['own_ulic']) $wt['own_ulica'] = '
	<p class=\'ulica\'>'.$wt['own_ulica'].'</p>';
  }


  if($this->d['own_woje']) $wt['own_wojew'] = '
	<p class=\'woje\'>województwo : '.$this->d['own_woje'].'</p>';


  if($this->d['own_firm'])																		//-jeśli firma to nip i regon + ewentualnie PESEL
  {
   if($this->d['own_nip']) $wt['own_firma'] = '
	<p class=\'nip\'>NIP : '.$this->d['own_nip'].'</p>';

   if($this->d['own_rego']) $wt['own_firma'] .= '
	<p class=\'regon\'>REGON : '.$this->d['own_rego'].'</p>';

   if($this->d['own_pese']) $wt['own_firma'] .= '
	<p class=\'pesel\'>PESEL : '.$this->d['own_pese'].'</p>';
  }
  else
   if($this->d['own_pese']) $wt['own_firma'] = '
	<p class=\'pesel\'>PESEL :'.$this->d['own_pese'].'</p>';						//-jeśli osoba prywatna to PESEL


  if($this->d['own_tel1'])
  {
	$wt['own_tel1'] = '<b>'.$this->d['own_tel1'].'</b>';

   if($this->d['own_ote1']) $wt['own_tel1'] = $this->d['own_ote1'].' - '.$wt['own_tel1'];

   $wt['own_tel1'] = '<p class=\'tel\'>'.$wt['own_tel1'].'</p>';
  }


  if($this->d['own_tel2'])
  {
	$wt['own_tel2'] = '<b>'.$this->d['own_tel2'].'</b>';

   if($this->d['own_ote2']) $wt['own_tel2'] = $this->d['own_ote2'].' - '.$wt['own_tel2'];

   $wt['own_tel2'] = '<p class=\'tel\'>'.$wt['own_tel2'].'</p>';
  }

  if($this->d['own_fax'])
  {
   $wt['own_fax'] = '
	<p class=\'fax\'>'.$t['fax'].' : <b>'.$this->d['own_fax'].'</b>';

   if($this->d['own_ofa']) $wt['own_fax'] .= ' '.$this->d['own_ofa'];

   $wt['own_fax'] .= '</p>';
  }

  for($ki = 0; $ki < 5 ; $ki++)
  {
   if($this->d['own_ma'.$ki] && $this->d['own_oma'.$ki] != 'off')
   {

	 if($this->d['own_ma'.($ki+1)] || $this->d['own_ma'.($ki-1)]) $kki = $ki; else $kki = '';

    $wt['own_mail'] .= '
	<p class=\'mail mail'.$ki.'\'>e-mail '.($kki).' - <b>'.preg_replace('/@/', '<img src="./ed.php?ed=2" alt=\'(ed)\'>', $this->d['own_ma'.$ki]).'</b>';

    if($this->d['own_oma'.$ki]) $wt['own_mail'] .= ' <i> '.$this->d['own_oma'.$ki].'</i>';

    $wt['own_mail'] .= '</p>';
   }
  }


  if($this->d['own_nab1']) $wt['own_nab1'] = '
	<p class=\'bank\'>'.$this->d['own_nab1'].'</p>';

  if($this->d['own_nrk1']) $wt['own_nrk1'] = '
	<p class=\'kontob\'>'.$this->d['own_nrk1'].'</p>';

  if($this->d['own_nab2']) $wt['own_nab2'] = '
	<p class=\'bank\'>'.$this->d['own_nab2'].'</p>';

  if($this->d['own_nrk2']) $wt['own_nrk2'] = '
	<p class=\'kontob\'>'.$this->d['own_nrk2'].'</p>';

  if(is_array($wt)) $wt = implode(' ', $wt);

  //-HTML----------------------

  if($wt)
   $this->w = $li_atrw.'
	<address class=\'adres_kontakt\'>'.$wt.'
	</address>'.$li_end;

  unset($li_atrw, $wt, $ki, $kki, $li_end);
 }

 /** .../DaneAdres.php
 *
 * Automatycznie generowana mapa googl'a
 * z danych adresowych
 *
 * dynamiczne dodanie skryptu obsługującego mapę
 */

 public function mapa($sze, $wys)
 {
  if(!$this->d['own_kraj']) $this->d['own_kraj'] = 'Polska'; //-polska domyślnie

  if($this->d['own_mias'])
  {
   $this->mapaGoogle($sze, $wys);

	return $this->w;
  }
  else
   if(C::get('ja'))
	{

	 if(!$this->d['own_id']) return 'Brak danych adresowych!<br />
	 <a href=\'powr .'.C::get('tab_owner').',0,formu,own_stat.1,edycja.html\' title=\'ustaw dane adresowe\'>ustaw dane adresowe</a>';
	 else
	 return 'Brak danych adresowych!<br />
	 <a href=\'powr .'.C::get('tab_owner').','.$this->d['own_id'].',edycja.html\' title=\'ustaw dane adresowe\'>ustaw dane adresowe</a>';

	}
	else
	 return 'Mapa niedostępna';
 }

 /**
 *
 *
 *
 */

 private function mapaGoogle($sz, $wy)
 {


  $adres = $this->d['own_kraj'].', '.$this->d['own_mias'].', '.$this->d['own_ulic'].' '.$this->d['own_nrdo'];

  $opis = $this->d['own_imie'].' '.$this->d['own_nzwi'].' : '.$this->d['own_kodp'].' '.$this->d['own_mias'].', '.$this->d['own_plac'].' '.$this->d['own_ulic'];

  $size = explode('x', trim(C::get('con_size')));

  if($size != '')
  {
   if(is_numeric(trim($size[0]))) $sz = (int)trim($size[0]); else $sz = '400';
	if(is_numeric(trim($size[1]))) $wy = (int)trim($size[1]); else $wy = '300';
  }

  $zoom = (C::get('con_zoom'))? C::get('con_zoom') : DaneAdres::ZOOM;

  $this->w = '
  <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
  <p class=\'mapaTytul\'>'.$this->t['mapa_tytul'].'</p>
  <div class=\'mapa\' style=\'width: '.$sz.'px; height: '.($wy+20).'px;\'>
	<input type=\'hidden\' id=\'gaddress\' value=\''.$adres.'\' />
	<input type=\'hidden\' id=\'gzoom\' value=\''.(int)$zoom.'\' />
	<input type=\'hidden\' id=\'gmap_title\' value=\''.$opis.'\' />
	<div id=\'mapa\' style=\'width: '.$sz.'px; height:'.$wy.'px;\'>
	 <script type="text/javascript" src="js/mapa_adres.js"></script>
	</div>
  </div>';

  //<a href=\'\'>zobacz mapę w dużym oknie</a>

  unset($adres, $opis, $size, $sz, $wy, $zoom);

 }

 /**
 *
 *
 */

 function __destruct()
 {
  unset($this->d, $this->w);
 }
}
?>