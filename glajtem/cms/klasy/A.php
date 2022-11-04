<?
defined('_CMSPATH') or header('HTTP/1.1 404 File Not Found');

/** v.1.24
*
* 2021-01-13 : modyfikacje do wersji PHP 7.xx
*
* 2014-09-20 : poprawki
* 2014-07-12 : poprawa powrotu do zajawki po edycji bloku treści z poziomu zajawki
* 2014-07-08 :
* 2013-10-14 : poprawione odnośniki
* 2013-02-15 : poprawki Notis
*
* 2012-12-04 : korektaDoMenu()
* 2012-11-21 : doodano metody walidacji logowania
* 2011-10-13 : poprawka metody get(), która powodowała zapętlanie się dla metod testowych z klasy Test.php
*
* -> 2011-06-02
*
@ klasa kontenera konfiguracji, oraz :
* debugowania akcji try
* strony błędów dla administartora
* skoków bezpośrednich do strony, lub skryptu
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2010-12-27 ------------ UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/


class A
{
 /**
 *
 *
 *
 */

 private $test = false;

 protected function adminLink()
 {

  $cod = explode('+', C::get('opcja'));

  if(C::get('ip_test')) $this->test = true;

  if($this->jo && $cod = S::linkDecode(reset($cod)))
  {
	$cod = explode(',', $cod);
	/*
	  $cod[0] -> NA/id publikacji
	  $cod[1] -> id rekordu
	  $cod[2] -> polecenie
	*/

	switch($cod[2])
	{

	 case 'kasp':	//-kasowanie całej publikacji, bloki i zdjęcia
	  $this->kasp($cod);
	 break;

	 case 'kasb':	//-kasowanie całego bloku treści ze  zdjęciami
	  $this->kasb($cod);
	 break;

	 case 'kasf': //-kasowanie zdjęć dla wybranego bloku
	  $this->kasf($cod);
	 break;

	}
  }
 }

 /**
 *
 *
 * 2014-07-08 : pref = '' do skoku
 */

 protected function odnAdminTytul($ta, $back, $pref = 'art')
 {

  if($this->test)
   $t_info =  ' | ->'.$back.'->'.$pref.'->'.$ta;
  else
	$t_info = '';

  return '
		 <a href=\''.S::linkCode(array($this->tabx, $ta, 'edycja','', $back, $pref, $ta)).'.htmlc\' title=\''.L::k('edT1').$t_info.'\'>'.L::k('edT').'-x1</a>
		 <a class=\'del\' href=\''.S::linkCode(array(1, $ta, 'kasp')).'+'.$this->akcja.'.html\'
		 	title=\''.L::k('kasT1').'\'
			alt=\''.L::k('kas').'\'>'.L::k('kasT').'-x2</a>';

 }

 /**
 *
 * odnośnik edycji tytułu i dodania treści
 *
 * występuje w zajawce jako dodanie 1-go bloku treści
 *
 */

 protected function odnAdminTytul2($ta, $back, $pref = 'art')
 {

  if($this->test)
   $t_info =  ' | ->'.$back.'->'.$pref.'->'.$ta;
  else
	$t_info = '';

  return $this->odnAdminTytul($ta, $back).'
		 	<a href=\''.S::linkCode(array($this->taby,0,'formu','tr_stat.1.tr_idte.'.$ta, $back, $pref, $ta)).'.htmlc\'
			title=\''.L::k('dodB1').$t_info.'\'>'.L::k('dodB').'-x3t2</a>';

 }

 /**
 *
 * tylko odnośnik dodania treści
 * 2014-03-04
 *
 */

 protected function odnAdminBlokTr($ta, $back, $pref = 'art')
 {

  if($this->test)
   $t_info =  ' | ->'.$back.'->'.$pref.'->'.$ta;
  else
	$t_info = '';


  return '
		 	<a href=\''.S::linkCode(array($this->taby,0,'formu','tr_stat.1.tr_idte.'.$ta, $back, $pref)).'.htmlc\'
			title=\''.L::k('dodB1').$t_info.'\'>'.L::k('dodB').'-x3</a>';

 }

 /**
 *
 * odnośnik usunięcia zdjęć z wybranego bloku
 *
 */

 protected function delFoto($tb, $ta)
 {
  //-id publikacji, tabela zdjeć, id bloku

  if($this->test)
   $t_info =  ' | ->$tb'.$tb.' | $ta ->'.$ta;
  else
	$t_info = '';

  return '<a class=\'del\' href=\''.S::linkCode(array($ta, $tb, 'kasf')).'+'.$this->akcja.'.html\'
		 	title=\''.L::k('kasF1').$t_info.'\'
			alt=\''.L::k('kas').'\'>'.L::k('kasF').'-x4</a>';

 }

 /**
 *
 * edycja bloku treści
 * kasowanie bloku treści
 * dodawanie bloku treści <-  różnica w stosunku do : odnAdminBlok2()
 * dodawanie zdjęć
 * dodawanie plików
 *
 *
 */

 protected function odnAdminBlok($ta, $tb, $back, $skok)
 {

  if($this->test)
   $t_info =  ' | - $ta >'.$ta.'- $tb >'.$tb.'- $back >'.$back.' - $skok >'.$skok;
  else
	$t_info = '';

  return '
		  <a href=\''.S::linkCode(array($this->taby, $tb, 'edycja', '', $back, $skok)).'.htmlc\'
		   rel =\'edytuj blok treści\'
			title=\''.L::k('edB1').$t_info.'\'>'.L::k('edB').'-33</a>

		  <a class=\'del\' href=\''.S::linkCode(array($ta, $tb, 'kasb')).'+'.$this->akcja.'.html\'
		 	title=\''.L::k('kasB1').$t_info.'\'
			alt=\''.L::k('kas').'\'>'.L::k('kasB').'-33</a>

		  <a href=\''.S::linkCode(array($this->taby,0,'formu','tr_stat.1.tr_idte.'.$ta, $back, 'art')).'.htmlc\'
			title=\''.L::k('dodB1').$t_info.'-'.$ta.'\'>'.L::k('dodB').'-33</a>

		  <a class=\'addPhoto\' href=\''.S::linkCode(array('fot',$this->tabf,0,'formu','fo_stat.1.fo_idte.'.$tb)).'.htmlc\'
		 	title=\''.L::k('dodfo').$t_info.'\'
			rel=\''.S::linkCode(array($this->tabf, S::k2($tb), $this->akcja, S::k2($ta), 'art')).'\'>'.L::k('dodfo').'-33</a>

		  <a class=\'addPlik\' href=\''.S::linkCode(array('fot',$this->tabp,0,'formu','fo_stat.1.fo_idte.'.$tb)).'.htmlc\'
		 	title=\''.L::k('dodpl').$t_info.'\'
			rel=\''.S::linkCode(array($this->tabp, S::k2($tb), $this->akcja, S::k2($ta), 'art')).'\'>'.L::k('dodpl').'-33</a>

		  <a class=\'addPhotog\' href=\''.S::linkCode(array('fot',$this->tabf,0,'formu','fo_stat.1.fo_idte.'.$tb)).'.htmlc\'
		 	title=\''.L::k('dodfg').$t_info.'\'
			rel=\''.S::linkCode(array($this->tabf, S::k2($tb), $this->akcja, S::k2($ta), 'art', $ta)).'\'>'.L::k('dodfg').'-55</a>';

 }

 /**
 * akcja z poziomu zajawki, posiadającej już blok treści
 * edycja bloku treści
 * kasowanie bloku treści
 * dodawanie zdjęć
 * dodawanie plików
 * 2014-07-12 : 6 parametr linkCode() - skok do zajawki
 */


 protected function odnAdminBlokZaj2($ta, $tb, $back, $pref = 'art')
 {
  // $ta = id bloku publikacji
  // $tb = id bloku treści
  // adres powrotu


  if($this->test)
   $t_info =  ' | ->'.$back.'->'.$pref.'->'.$ta;
  else
	$t_info = '';

  return '
		  <a href=\''.S::linkCode(array($this->taby, $tb, 'edycja', '', $back, $pref, $ta)).'.htmlc\'
		   rel =\'edytuj blok treści\'
			title=\''.L::k('edB1').$t_info.'\'>'.L::k('edB').'-44</a>

		  <a class=\'del\' href=\''.S::linkCode(array($ta, $tb, 'kasb', 'art', $ta)).'+'.$this->akcja.'.html\'
		 	title=\''.L::k('kasB1').$t_info.'\'
			alt=\''.L::k('kas').'\'>'.L::k('kasB').'-44</a>

		  <a class=\'addPhoto\' href=\''.S::linkCode(array('fot',$this->tabf,0,'formu','fo_stat.1.fo_idte.'.$tb)).'.htmlc\'
		 	title=\''.L::k('dodfo').$t_info.'\'
			rel=\''.S::linkCode(array($this->tabf, S::k2($tb), $this->akcja, S::k2($ta), 'art', $ta)).'\'>'.L::k('dodfo').'-44</a>

		  <a class=\'addPlik\' href=\''.S::linkCode(array('fot',$this->tabp,0,'formu','fo_stat.1.fo_idte.'.$tb)).'.htmlc\'
		 	title=\''.L::k('dodpl').$t_info.'\'
			rel=\''.S::linkCode(array($this->tabp, S::k2($tb), $this->akcja, S::k2($ta), 'art', $ta)).'\'>'.L::k('dodpl').'-44</a>

		  <a class=\'addPhotog\' href=\''.S::linkCode(array('fot',$this->tabf,0,'formu','fo_stat.1.fo_idte.'.$tb)).'.htmlc\'
		 	title=\''.L::k('dodfg').$t_info.'\'
			rel=\''.S::linkCode(array($this->tabf, S::k2($tb), $this->akcja, S::k2($ta), 'art', $ta)).'\'>'.L::k('dodfg').'-55</a>';

 }

 /**
 *
 * kasowanie publikacji, bloków treści i zdjęć
 *
 */

 private function kasp($cod)
 {

  // kod[0] -> NA
  // kod[1]	-> id publikacji
  // kod[2] -> polecenie
  // kod[4] -> taby
  // kod[5] -> tabf

  $error = array();

  $tab = 'SELECT tr_id FROM '.$this->taby.' WHERE tr_idte = '.$cod[1];

  if($tab = DB::MyQuery($tab))
   while($ta = mysqli_fetch_row($tab))
	{

	 if($er = self::kasb(array($cod[1], $ta[0]), true))
	  $error = array_merge($error, $er);

	}

 	if(!$error) //-kasowanie rekordu w tabeli publikacji
	{
	 $tax = 'DELETE FROM '.$this->tabx.' WHERE pu_id = '.$cod[1];

	 if(!DB::MyQuery($tax))
	  $error[] = __METHOD__.' -> line: '.__LINE__.' : Nie udało się usunąć rekordu pu_id = '.$cod[1].' tabeli '.$this->tabx;
	}

	unset($tab, $ta, $er, $tax);

 	if($error)
	{
	 S::ErrorAdmin($error);
	}
   else
    S::ggoto($this->akcja);

 }

 /**
 *
 * kasowanie bloku treści i zdjęć // DODAĆ KASOWANIE PLIKÓW !!!
 *
 */

 private function kasb($cod, $er = false)
 {
  /* $ta, $this->taby, $tb , 'kasb', taby, tabf
  *
  * kod[0] -> id publikacji
  * kod[1] -> id bloku
  * kod[2] -> polecenie
  * kod[3] -> prefix dla kotwicy
  * kod[4] -> id kotwicy

  * kasowanie zdjęć:
  * odczytanie identyfikatora zdjęcia
  * skasowanie zdjeć
  * skasowanie rekordu
  */

  $cod = array_pad($cod, 7, '');

  $error = $this->kasf(array($cod[0], $cod[1]), true);

  if(!$error) //-kasowanie rekordu tabeli bloków treści
  {
   $tax = 'DELETE FROM '.$this->taby.' WHERE tr_id = '.$cod[1];

   if(!DB::MyQuery($tax))
    $error[] = __METHOD__.' -> line: '.__LINE__.' : Nie udało się usunąć rekordu tr_id = '.$cod[1].' tabeli '.$this->taby;
  }

  unset($tab, $ta, $tax, $w, $f);

  if($er)
   return $error;
  else
  {
   if($error)
	 S::ErrorAdmin($error);
	else
	{
	 if($cod[3] && $cod[4]) $cod[3] = '#'.$cod[3].md5($cod[4]);

	 //S::ggoto(S::k($cod[0]).$this->akcja);

	 S::myHeaderLocation(S::k($cod[0]).$this->akcja.'.html'.$cod[3]);
	}
  }

 }

 /**
 *
 * kasowanie zdjęć z wybranego bloku
 *
 */

 private function kasf($cod, $er = false)
 {

  // kod[0]	-> id publikacji
  // kod[1]	-> id bloku
  // kod[2] -> polecenie

  $pat = C::get('fotyPath');

  $tab = 'SELECT fo_id, fo_fot0 FROM '.$this->tabf.' WHERE fo_idte = '.$cod[1];

  if($tab = DB::MyQuery($tab))
   while($ta = mysqli_fetch_row($tab))
   {
    if($ta[1])
	 {

	  if($f = glob($pat.'*'.$ta[1]))
	  {
	   foreach($f as $w)
	   {
	    if(!unlink($w))
	  	  $error[] = __METHOD__.' -> line: '.__LINE__.' : Nie udało się usunąć pliku: '.$w;
		}

		unset($f, $w);
	  }
	 }

	 $tax = 'DELETE FROM '.$this->tabf.' WHERE fo_id = '.$ta[0];

	 if(!DB::MyQuery($tax))
	  $error[] = __METHOD__.' -> line: '.__LINE__.' : Nie udało się usunąć rekordu fo_id = '.$ta[0].' tabeli '.$this->tabf;

	}

  if($er)
   return $error;
  else
  {
   if($error)
	 S::ErrorAdmin($error);
   else
	 S::ggoto(S::k($cod[1]).S::k($cod[0]).$this->akcja, 'art'.md5($cod[1]));		//-zawsze wraca do strony która wywołała akcję
  }

 } //end

}