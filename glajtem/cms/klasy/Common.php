<?
defined('_CMSPATH') or header('HTTP/1.1 404 File Not Found');


/** ./cms/klasy/Common.php
*
* klasa : metody dla klasy Cms.php : v.2.5
*
* 2021-01-13 : modyfikacje do wersji PHP 7.xx
*
* 2016-05-28 -> modyfikacja dla pola pliku dla zdjeć dopinanych z innych publikacji ( tylko opisy, pozycja i kasowanie )
* 2015-05-17 -> poprawa odwołania do dynamicznego ładowania miniatur
*
* 2015-03-14 -> menu lista tabel uwzględnia ograniczenia dla amina którego stat < 10
* 2013-06-27 -> poprawki
* 2013-03-26 -> poprawki
* 2013-01-21 -> poprawki
* 2013-01-03 -> poprawa ukrywania pól formularza dla znacznika V
*
* 2012-11-04 -> zmiany dla !
* 2012-10-23 -> poprawko dla znacznika R
* 2012-03-03 -> selektor M bez parametry też możliwy
*
* 2012-03-02 -> komunikaty w wersji językowej
*
* 2011-12-05 -> poprawiony znacznik M
*
* 2011-10-12 -> poprawiono wyświetlanie zdjeć do kadrowania i dodano help do tej operacji
* 2011-04-23
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -- 2010-03-17 ---UTF-8
*
* protected function korektaDoMenu($t) - korekta polskich znaków w nazwach dla linków menu CMS'a - ok
* protected function korektaDoForm($i, &$wdot) - korekta wartości przed wygenerowaniem formularza - ok
* protected function formatPola($i, $n, $n2, $tr) - przygotowanie pól formularza zgodnie z definicją - ok
* private   function fileUploadTest($i) - walidacja pliku przed uploadem - ok
* protected function walidPola($i) - walidacja danych dla pól formularza od strony serwera po odbiorze danych z formularza - ok
* protected function addOrChange() - metoda będąca ekwiwalentem skryptu dodaj i zmień -> doizm.php - ok
* protected function korektaDoIzm($i) - korekta dla określonych znaczników - ok
* protected function poleData($i, $readOnly) - przygotowanie formularza dla danych typu date idatetime - ok
* protected function polePlik($i) - przygotowanie formularza dla danych typu file - ok
* protected function passKodCms($pass) - kodowanie hasła - ok
* protected function passDekodCms($zak, $pass) - odkodowanie do porównania - ok
* protected function kaspl($kpl) - kasowanie pliku z dysku - ok
* protected function kdata($d, $c) - konwersja daty na polski format ok
* protected function tablesList() - zwraca listę wszystkich tabel w aktywnej bazie dla klucza ok
*

*/


class Common
{
 const HELP_KADR = 'aby wykadrować zdjęcie, zaznacz obszar kadru na zdjęciu trzymając lewy przycisk myszki';

 //private $skip = false; //-znacznik pomijania pola podczas tworzenia kwerendy dla do i zm

 protected $wfot = array();

 /** ./cms/klasy/Common.php
 *
 * korekta wartości przed wygenerowaniem formularza
 * korekta dla wartości predefiniowanych
 * tylko dla klasy Cms.php
 */

 protected function korektaDoForm($i, &$wdot)
 {

  if(substr($this->nt[$i], 0, 4)=='DATE' && !isset($this->tr[$i]))		//-korekta dla daty przy dodawaniu nowego rekordu
  {
	$this->tr[$i] = C::get('datetime_teraz');
  }

  if($wdot)																					//-wartości wstępnie predefiniowane przy wypełnianiu formularza
  {

   foreach($wdot as $k => $v)
    if($this->n[$i] == $k)
    {
     $this->tr[$i] = $v;

     unset($wdot[$k]);
    }

   unset($v, $k);
  }

 }

 /** -> ./cms/klasy/Common.php
 *
 * - przygotowanie pól formularza zgodnie z definicją
 * - tylko dla klasy Cms.php
 */

 protected function formatPola($i)
 {

  $n = & $this->n[$i];
  $n2 = & $this->n[2];
  $tr = & $this->tr[$i];

  if($this->ns[$i])
	$err = 'border: 1px solid red; background: #FFF;';
  else
   $err = '';

  foreach($this->nttt[$i] as $k => $w)
  {
   //if($k)
	$dym[] = $k;

	switch(substr($k, 0 ,1)) 													//- musi być substr lub trim dla konwersji do string, bo prównanie == nie zdaje egzaminu
   {
	 case 'V':

	  if($this->userSet)
		return array('', '', '', 1, '', '', '');
	  elseif(!$this->ja)
	   return array('', '', '', 2, '', '', '');

	 break;

	 case 'A':

	  if($this->userSet) return array('', '', '', 1, '', '', '');

	 break;

	 case 'B':
	 	if(substr($k, 0, 2) == 'BB' && C::get('jo'))
		 $b = true;
		else
	    if($this->userSet)
		  $b = true;																//-znacznik pola tylko do odczytu

	 break;

	 case 'W':

	  $wa = 1;

	  if($tr)
		$fxt = ' checked=\'checked\'';
	  else
	   $fxt = '';

	  $fxt ='
		<input type=\'checkbox\' name=\''.$n.'\''.$fxt.' id=\'inpp'.$i.'\' />';

	 break;


	 case 'T': 																		//-text

	  if(is_array($w))															//-jeśli są osobne parametry dla ilości znakówi długości pola
	  {
		$dlupo = $w[0];
		$stylp = 'width:'.$w[1].'ex;';

		if(!$tr && $w[2]) $this->tr[$i] = $w[2];							//-wartość domyślna dla pola tekstowego
	  }
	  else
	  {
	   if(is_numeric($w))
		{
		 $dlupo = $w;																//-do skryptu liczącego znaki

		 if($w < $this->maxdl)
		   $stylp = 'width:'.$w.'ex;';
		  else
		   $stylp = 'width:'.$this->maxdl.'ex;';

		}
		else
		 $stylp = 'width:'.$this->maxdl.'ex;';								//-jeśli brak wartości parametru po T

	  }

	 break;


	 case 'C': case 'E': case 'F':  											//-cyfry, e-maili, telefon, ??? case 'Z':

	 	$stylp = 'width:'.($w + 2).'ex;';

	 	$dlupo = $w;

		$classPola = ' liczba';

	 break;

	 case 'R':																	  	//-select

	   /*
		$w[0] => szerokość pola opisu wartości w em powiekszona o 2 znaki
		$w[1] => indeks wartości domyślnej od 0
		$w[2] => pierwsza wartość
		...
		$w[n] => n-t awartość
		*/

	   if(is_array($w) && count($w) > 3)									//-długość pola i minimum 4 wartości
		{
	    $wa = 'R';


		 $stylp = 'width:'.(array_shift($w)+2).'em;';					//-pierwsza wartość to szerokość pola

		 $default =  array_shift($w);

		 $j = 0;

		 while($ta = current($w))
		 {

		  if($tr)
		  {
			if($tr == $ta)
			 $checked = 'checked=\'checked\' ';
		   else
			 $checked = '';

		  }
		  else
		  {

		   if($j == $default)
			 $checked = 'checked=\'checked\' ';
		   else
			 $checked = '';

		  }

		  $fxt .= '
			 <label for=\'formRadio'.$i.$j.'\' class=\'radio '.$n.'\'>
			 <input type=\'radio\' name=\''.$n.'\' value=\''.$ta.'\' '.$checked.'id=\'formRadio'.$i.$j++.'\' />&nbsp;<b>'.$ta.'</b></label>';


		  next($w);
		 }
		 unset($j, $checked);

		}
		else
		{
		 S::ErrorAdmin(array('brak obowiązkowych parametrów znacznika R w '.__METHOD__.'->'.__LINE__, $w));

		 /*
		 if($this->jo)
		 {
		  Test::stopPoint2('R:'.$n.' -> brak obowiązkowych parametrów znacznika R w '.__METHOD__.'->'.__LINE__, $w, false);
		 }
		 else
		  exit('brak obowiązkowych parametrów znacznika R w '.__METHOD__.'->'.__LINE__); */
		}

	 break;

	 case 'S':																								//-select

	  $fxt = '';

	  if(substr($n, -4, -1) != 'poz')																//-wyłaczenie dla pól xxx_pozn
	  {
	   if(is_array($w))
		{
	    $wa = 1;

		 if(trim($w[0]) == 'X')																			//-automatycznie z zakresu od p[2] do p[3]
		 {
		  if($w[1] == 'R')
		   $w[1] = substr(C::get('datetime_teraz'), 0, 4);										//-aktualny rok jako wartość początkowa
		  else
		  {
		   if($w[2] == 'R')
			 $w[2] = substr(C::get('datetime_teraz'), 0, 4);									//-aktualny rok jako wartość końcowa
		   else
		   {
			 //-zabezpieczenia dla Admina, nadania sobie i innym statusu wyższego niż posiadany

		    if($this->t == C::get('tab_admini') && $_SESSION['admin']['status'] < 10)
			  $w[2] = $_SESSION['admin']['status'];
		   }
		  }

		  if($w[1] > $w[2])
		  {
		   $stylp = 'width:'.(strlen(trim($w[1]))+2).'em;';

		   for($j = $w[1]; $w[2] <= $j; $j--)
		   {
			 if($tr == $j)
		     $fxt .= '
			 <option selected=\'selected\'>'.$j.'</option>';
		    else
		     $fxt .= '
			 <option>'.$j.'</option>';
		   }
		  }
		  else
		  {
		   $stylp = 'width:'.(strlen(trim($w[2]))+2).'em;';

			for($j = $w[1]; $w[2] >= $j; $j++)
		   {
			 if($tr == $j)
		     $fxt .= '
			 <option selected=\'selected\'>'.$j.'</option>';
		    else
		     $fxt .= '
			 <option>'.$j.'</option>';
		   }
		 }
		}
		else
		{
		 $stylp = 'width:'.($w[0]+2).'em;';

		 reset($w);
		 next($w);

		 while($ta = current($w))
		 {
		  if($tr == $ta)
		   $fxt .= '
			 <option selected=\'selected\'>'.$ta.'</option>';
		   else
		    $fxt .= '
			 <option>'.$ta.'</option>';

		  next($w);
		 }

		}

		$fxt = '
		<select name=\''.$n.'\' style=\''.$stylp.'\'>'.$fxt.'
		</select>';

		 unset($j, $stylp);
		}
		else
		 exit('brak obowiązkowych parametrów znacznika S! w '.__METHOD__.'-> line: '.__LINE__);
	  }

	 break;


	 case 'J':

	  if(!is_numeric($w)) exit('Dla znacznka J prametr musi być numeryczny! w '.__CLASS__);

	  $wa = 1;

	  $s = '
		<option>-wybierz-</option>';																	//-pierwszy element pola select

	  $pola = C::get('polahtml');																		//-indeksy z tablicy pól html

	  if($this->id > 0)																					//-odczytanie aktualnie wybranej wartości
	  {
	   try
	   {
	    $tab = "SELECT $n FROM $this->t WHERE id='$this->id'";  							//-odczyt zapisu w rekordzie $n - nazwa pola w sql tabeli

		 if($tab = DB::myQuery($tab))
		 {
	     $ta = mysqli_fetch_array($tab);

		  $select = $ta[$n];
		 }
	   }
	   catch(Exception $e)
	   {
       C::debug($e, 2);
      }

	  }
	  else //-podczas dodawania nowego rekordu, aby utrzymać w formularzu wartość na liście
	  {
	   $select = $tr;
	  }

     $k = 0;
	  while(isset($pola[$k]))
	  {
	   if($select == $pola[$k])
	    $s .= '
		<option selected=\'selected\'>'.$pola[$k++].'</option>';
		else
	 	 $s .= '
		<option>'.$pola[$k++].'</option>';
	  }

	  unset($tab, $ta, $k, $pola, $select);

	  $fxt = '
		<select name=\''.$n.'\' style=\'width:'.$w.'ex;\'>'.$s.'</select>';

	  unset($s, $w);

	 break;

	 case 'K':	//-pole selekt generowane z zawartości wskazanego katalogu

	  /*
	   w[0] -> szerokość pola
	   w[1] -> path
	   w[2] -> wartość domyślna
		w[3] -> path dodatkowy
	  */

	  $wa = 1;

	  if(!is_array($w))
		exit('nieprawidłowe parametry dlaznacznika K!');
	  else
	   $w = array_pad($w, 4, '');

	  $s = '
		<option>-wybierz-</option>';																	//-pierwszy element pola select

	  if($w[1])
		$tmp_lista_plik = glob($w[1].'*'._EX);														//-drugi parametr ( jeśli jest) wskazuje path to plików
	  else
	   S::ErrorAdmin('Brak paramatru PATH dla znacznika K dla '.$this->t);
	   //C::error('Brak paramatru PATH dla znacznika K dla '.$this->t);

	  if($w[3])
		$tmp_lista_plik = array_merge($tmp_lista_plik, glob($w[3].'W_*'._EX));


	  $k = 0;

	  if($this->id > 0)																					//-dla edycji rekordu
	  {
      try
      {
	    $tab = "SELECT $n FROM $this->t WHERE $n2='$this->id'";								//-odczyt zapisu w tabeli dla ustalenia wartości selected

		 if($tab = DB::myQuery($tab))
	    {
	     $ta = mysqli_fetch_array($tab);

	     while(isset($tmp_lista_plik[$k]))																	//-pętla po plikach w tabeli
	     {
		   if($tmp_lista_plik[$k])
			{
	       $tmp_lista_plik[$k] = basename($tmp_lista_plik[$k], _EX);

		    $sel = '';

	       if($ta[$n])																						//-jeśli pole ma ustaloną wartość
	       {
	        if($ta[$n] == $tmp_lista_plik[$k])
            $sel = 'selected=\'selected\'';
	       }
          else																								//-jeśli pole nie ma jeszcze ustalonej wartości
	       {
	        if($w[2] == $tmp_lista_plik[$k])														//-wartość domyślna zaszyta w definicji tabeli
		      $sel = 'selected=\'selected\'';
	       }

	       $s .= '
	     <option '.$sel.'>'.$tmp_lista_plik[$k++].'</option>';
		  }
	    }

	    unset($tab, $ta, $sel, $tmp_lista_plik, $k);
	   }
     }
     catch(Exception $e)
	  {
		C::debug($e, 2);
	  }

	 }
	 else																										//-dla rekordów dodawanych do bazy
	 {

	  while(isset($tmp_lista_plik[$k]))																//-pętla po plikach w tabeli
	  {
	   $tmp_lista_plik[$k] = basename($tmp_lista_plik[$k], _EX);


		$szer = strlen($tmp_lista_plik[$k]);


      if($tr == $tmp_lista_plik[$k])
	    $s .='
	     <option selected=\'selected\'>'.$tmp_lista_plik[$k++].'</option>';
		else
		 $s .='
	     <option>'.$tmp_lista_plik[$k++].'</option>';
	  }
	  unset($tmp_lista_plik, $k);
	  }


	 $fxt = '
		<select name=\''.$n.'\' style=\'width:'.$w[0].'ex;\'>'.$s.'</select>';

	  unset($s, $w);

	 break;


	 case 'U':																						//-lista

	  // w[0] - długość pola
	  // w[1] - nazwa tabeli
	  // w[2] - nazwa pola wiążącego
	  // w[3] - nazwa pola wyświetlanego
	  // w[4] - nazwa pola wiążącego -> dwie listy zależne muszą występować po sobie: lista główna -> lista zależna
	  // w[5] - wartość domyślna

	  $wa = 1;																						//-wskaźnik generowania pola tutaj

	  $s = '
		<option>-wybierz-</option>';															//-pierwszy element pola select

	  $wa = 1;																						//-wskaźnik generowania pola tutaj

	  $s = '
		<option>-wybierz-</option>';															//-pierwszy element pola select


	  if(is_array($w))																			//-jeśli są : tabela i nazwa pola
	  {
	   $w = array_pad($w, 6, '');																//-uzupełnienie tabeli to pełnego wymiaru

	   if($w[1] != '' && $w[2] != '')
      {
       $tabx = $w[1];
       $poxy = $w[2];
      }
		$stylp = 'width:'.$w[0].'ex;';
		$dlpola = $w[0];
	  }
     else																							//-jeśli tylko dlugośc pola
     {
	   $stylp = 'width:'.$w.'ex;';
		$dlpola = $w;

		$w = array($w);
		$w = array_pad($w, 6, '');						//-aby testy elementów tablicy w w dalszej części nie powodowały ostrzeżeń php

      $tabx = $this->t;																			//-aktualna tabela
      $poxy = $n;																					//-nazwa pola sql
     }


     try
     {
	   $selected = '';

		if($w[4])
		{
		 if($this->tr[($i-1)] == '') $this->tr[($i-1)] = 0;

		 $tab = "SELECT $poxy, {$w[3]} FROM $tabx WHERE $w[4] = {$this->tr[($i-1)]} ORDER BY {$w[3]}";
		}
		else
		{
       if(!$w[3])
		  $tab = "SELECT DISTINCT $poxy FROM $tabx ORDER BY $poxy";		//-odczyt danych z tabeli wskazanej lub tej samej
 		 else
		  $tab = "SELECT $poxy, {$w[3]} FROM $tabx ORDER BY {$w[3]}";	//-odczyt danych z tabeli wskazanej z wiązaniem niejawnym
		}


		if($tab = DB::myQuery($tab))
		{
       while($ta = mysqli_fetch_array($tab))
		  if($ta[$poxy])
		  {
			$sel = '';

			if($tr)
			{
          if($ta[$poxy] == $tr)
			 {
		     $sel = ' selected=\'selected\'';
			  $selected = true;								//-wskaźnik że wartość już istnieje na liście, dla wartości predefiniowanych istniejących
			 }
			}
			else
			{
			 if(isset($ta[$w[3]])) 							//-jeśli wartośc z tabeli powiązanej, do 2013-03-07 było if($ta[$w[3]])
			 {
			  if($ta[$w[3]] === $w[5])
				$sel = ' selected=\'selected\'';
			 }
			 else
			 {
			  if($ta[$poxy] === $w[5])  					//-jeśli wartośc z tabeli bierzącej
				$sel = ' selected=\'selected\'';
			 }
			}

	      if(!$w[3])
			 $s .= '
		<option '.$sel.'>'.$ta[$poxy].'</option>'; 	//-ustawia wartość predefiniowaną, ale dubluje przy normalnym generowaniu formularza
			else
			 $s .= '
	  	<option '.$sel.' value=\''.$ta[$poxy].'\'>'.$ta[$w[3]].'</option>';

			//-ustawia wartość predefiniowaną, ale dubluje przy normalnym generowaniu formularza ????????

			unset($sel);
		  }

		  if($tr && !$selected) $s .= '
		<option selected=\'selected\'>'.$tr.'</option>'; 							//if(!$selected && isset($_GET['bb']))


		 }
		 unset($selected);

		}
	   catch(Exception $e)
	   {
	    C::debug($e, 2);
	   }

	   unset($tab, $ta, $tabx);


		if(trim($k) == 'UB')															//-pole typu U z możliwością wpisania wartości dodatkowej
		{

		 $fx_tmp = '
		<input class=\'liZnak\' type=\'text\' name=\'n'.$n.'\' style=\''.$stylp.'\' alt=\''.$dlpola.'\' title=\'wpisz nową wartość jeśli nie ma jej na liście\' />';

       $ub = 'class=\'sel_UB\' ';
      }
		else
		 $fx_tmp = $ub = '';

		if($stylp) $stylp = ' style=\''.$stylp.'\'';

	   $fxt ='
		<select '.$ub.'name=\''.$n.'\''.$stylp.'>'.$s.'
		</select>'.$fx_tmp;

	  unset($fx_tmp, $ub, $stylp, $sel, $s, $val_tmp);

	  //return - NIE bo może być jeszcze co najmniej H !!!

	 break;

	 case '!':	//-pole password

	 	$wa = 1;

		if(!$this->userSetp)
		{
		 $stylp = ' style=\'width:'.($w+20).'ex;\''; //- 20 + bo kodowane
		}
		else
		 $stylp = '';

		if(!$this->ja || $this->userSet) $typeInp = 'password'; else $typeInp = 'text';

		//Test::tracer(__FILE__, __METHOD__, __FUNCTION__, __LINE__, 'pasd', $this->a);

	   $fxt = '
		<input'.$stylp.' type=\''.$typeInp.'\' name=\''.$n.'\' value=\''.$tr.'\' alt=\''.$w.'\' />
	 </div>
	 <div class=\'cms_pf\'>
		<label class=\'norm\'>weryfikacja hasła</label>
		<input'.$stylp.' type=\''.$typeInp.'\' name=\'passd\' value=\''.$this->pasd.'\' alt=\''.$w.'\' />';	//-drugie hasło do walidacji

	  unset($typeInp, $stylp);

	 break;


	 case 'O': 																										//-opis pola - jest potrzeby mimo wszystko

	  $opis = '<b class=\'opisPola\'>'.$w.'</b>';

	 break;


	 case 'H':																										//-help w dymku

	  $help = $w;

	 break;

	 case 'M':

	  if($w)
	  {
	   if($k === 'MM')
		{
		 if(is_array($w))
		 {
		  $box_start = '
		 <p class=\'tyt_for_sektor\'>'.$w[0].'</p>';
		 }
	    else
	  	  $box_start = '
		 <p class=\'tyt_for_sektor\'>'.$w.'</p>';
		}
		else
		{
	    if(is_array($w))
		 {
		  $box_start = '
		 <p class=\'tyt_for_sektor\'>'.$w[0].'</p>
		 <div class=\'form_selektor '.$w[1].'\' id=\'form_sektor_'.end(explode('_', $n)).'\'>';
		 }
	    else
	  	  $box_start = '
		 <p class=\'tyt_for_sektor\'>'.$w.'</p>
		 <div class=\'form_selektor\' id=\'form_sektor_'.end(explode('_', $n)).'\'>';

		}

	  }
	  else
	   $box_start = '
		 <div class=\'form_selektor\' id=\'form_sektor_'.end(explode('_', $n)).'\'>';

	 break;

	 case 'Z':

	  $box_end = '
</div>';

	 break;

   }
  }
  unset($k, $w);

  if($dym) $dym = implode('->', $dym);

  unset($tr, $n, $i);

  if(!isset($box_end)) $box_end = '';
  if(!isset($box_start)) $box_start = '';
  if(!isset($b)) $b = '';

  if(!isset($opis))
	$opis = '';
  else
   if($opis)
	 $opis = '<b class=\'opis\'>'.$opis.'</b>';

  if(!isset($dlupo)) $dlupo = '';
  if(!isset($classPola)) $classPola = '';

  if(!isset($stylp))
   $stylp = '';
  else
   if($stylp)
	 $stylp = ' style=\''.$stylp.$err.'\'';


  if(!isset($wa)) $wa = '';
  if(!isset($fxt)) $fxt = '';
  if(!isset($help)) $help = '';
  if(!isset($dym)) $dym = '';

  return array($dym, $help, $fxt, $wa, $stylp, $classPola, $dlupo, $opis, $b, $box_start, $box_end, $err);
 }

 /** ./cms/klasy/Common.php
 *
 *	- walidacja pliku przed uploadem
 * - tlko dla klas wewnątrz Common
 *
 */

 private function fileUploadTest($i)
 {

  if($_FILES[$this->n[$i]]['size'] > C::get('maxsize_file_upload'))
   $error[] = 'rozmiar pliku '.$_FILES[$this->n[$i]]['name']. 'przekracza dopuszczalny limit';		  //- test na rozmiar pliku

  if($_FILES[$this->n[$i]]['size'] < 1)
   $error[] = 'plik : '.$_FILES[$this->n[$i]]['name']. 'jest pusty!';


  $ext = strtolower(substr($_FILES[$this->n[$i]]['name'], -3));													//test na rozszerzenie

  $this->x .= $ext;

  //C::test($this->nttt ,true);	//-test

  if(is_array($this->nttt[$i]['ext']))																						//-dla tablicy rozszerzeń
  {
   if(!in_array($ext, $this->nttt[$i]['ext']))
	 $error[] = 'A niedozwolony typ pliku : '.$_FILES[$this->n[$i]]['name'];
  }
  else
   if($ext != $this->nttt[$i]['ext'])																						//-dla pojedyńczego rozszerzenia
	 $error[] = 'B niedozwolony typ pliku : '.$_FILES[$this->n[$i]]['name'];

  unset($ext);

  //-test dla plików graficznych
  if(in_array($ext, array('jpg', 'jpeg', 'gif', 'png')))
  {
   $image = getimagesize($_FILES[$this->n[$i]]['tmp_name']);

	if(!is_array($image) || $image[0]<1 || $image[1]<1)
	 $error[] = 'plik graficzny : '.$_FILES[$this->n[$i]]['name'].' jest nieprawdłowy';

	 unset($image);
  }

  if($error)
	return implode('<br />', $error);
  else
   return false;
 }

 /** ./cms/klasy/Common.php
 @
 * - walidacja danych dla pól formularza od strony serwera po odbiorze danych z formularza
 *	- tylko dla klasy Cms.php
 * - 2012-11-16 : dodano $wal_war = tablica waldacji warunkowej
 * ( na razie tylko pojedyńczy warunek, który musi wystapić przez walidowanym warunkowo polem )
 *
 */

 protected function walidPola($i, $wal_war = false)
 {
  if(!isset($this->tr[$i])) $this->tr[$i] = '';		//2013-02-16

  if(!$this->tr[$i])																								//-dla pól pustych
  {

	if($wal_war)		//-walidacja warunkowa : warunek to pole checkbox, z nazwą pola które misi być wypełnione
	{						//-jeśli checkbox jest zaznaczony

	 //Test::stopPoint2('pola warunkowe ', $wal_war);

    foreach($wal_war as $p => $pp)
	 {
	  if($this->n[$i] == $pp) return L::co('pole_wymagane');
	 }

	 unset($p, $pp);
	}


   if(substr($this->nttt[$i][0], -1) =='*') 																//-wartość obowiązkowa
	{
    if(substr($this->n[$i], -4, -1) != 'fot' && substr($this->n[$i], -4, -1) != 'plik') 	//-jeśli nie jest to pole plikowe
    {
     return L::co('pole_wymagane'); 		// 'Pole obowiązkowe, musi zostać wypełnione!';
    }
    else												//-dla pól plikowych, sprawdzamy czy nastąpił transport pliku na serwer
	 {
	  if(!is_uploaded_file($_FILES[$this->n[$i]]['tmp_name']))	return 'brak obowiązkowego pliku!';
	 }
   }

	//-pierwsze ładowanie pliku walidowane jest tutaj !!!

	if(substr($this->n[$i], -4, -1) === 'fot' || substr($this->n[$i], -4, -1) === 'plik')
	{

	 if(is_uploaded_file($_FILES[$this->n[$i]]['tmp_name']))
	 {
	  //$wal_pliku = $this->fileUploadTest($i);															//-walidacja plików do osobnej tablicy

	  //if($wal_pliku)	$this->wfot[$i] = $wal_pliku;

	  if($wal_pliku = $this->fileUploadTest($i))	$this->ns[$i] = $wal_pliku;

	  unset($wal_pliku);
	 }

	}
  }
  else																											//-walidacja dla wartości innych niż puste
  {
   //-walidacja przy nadpisywaniu plików

   if(substr($this->n[$i], -4, -1) == 'fot' || substr($this->n[$i], -5, -1) == 'plik')	//-jeśli są pliki tworzymy listę plików
	{

	 if(is_uploaded_file($_FILES[$this->n[$i]]['tmp_name']))
	 {
	  //$wal_pliku = $this->fileUploadTest($i);															//-walidacja plików do osobnej tablicy

	  //if($wal_pliku)	$this->wfot[$i] = $wal_pliku;

	  if($wal_pliku = $this->fileUploadTest($i))	$this->ns[$i] = $wal_pliku;

	  unset($wal_pliku);
    }
	}
	else //-pozostałe pola
   foreach($this->nttt[$i] as $k => $w)
	{

	 switch(trim($k))																				//-musi być trim dla konwersji typu i poprawnego działania case
	 {
	  case 'C':																						//-cyfry ze znakami

  		$this->tr[$i] = preg_replace('/,/', '.', $this->tr[$i]);

		//if(!eregi("^[0-9,\.-]+$", $this->tr[$i])) //preg_match() (with the i (PCRE_CASELESS) modifier)
		// return _WAL_C; 																			//'w tym polu mogą być tylko cyfry, przecinek, minus i kropka';

	  break;

	  case 'I':
																										//-dla I=INTEGER tylko cyfry
	   //if(!eregi("^[0-9]+$",$this->tr[$i]))
      // return _WAL_I; //'w tym polu mogą być tylko cyfry';

	  break;

	  case 'E':																						//-walidacja - e-mail

		//if(!eregi("^[0-9a-z_.-]+@|(\(\+\))([0-9a-z-]+(\.)+)+[a-z]{2,4}$",$this->tr[$i]))
    	// return _WAL_E; //'to nie jest poprawny adres e-mail';

	  break;

	  case '!':
      if($this->nst) return false;

		if($this->tr[$i] != C::odbDane($_REQUEST['passd']))
		{
		 return _WAL_PASSCONF; 																  //'hasła nie są identyczne';
	   }
	  break;

	 }

	}
	unset($k, $w);
  }

  return false;
 }

 /** ./cms/klasy/Common.php
 @
 * - metoda będąca ekwiwalentem skryptu dodaj i zmień -> doizm.php
 * - tylko dla klasy Cms.php
 */

 protected function addOrChange()
 {
  //-> metoda wstawia nowy rekord lub modyfikuje wskazany
  //-> wybór typu kwerendy i ewentualnie warunki

  if(isset($_POST['kopia']))
   if($_POST['kopia'])
	{
	 $this->kopia = true;
	 $this->a = 'dodaj';
	} 								 																//-replika rekordu

  if($this->a == 'zmien') 																	//-zmiana danych rekordu
  {
   $typzap = 'UPDATE';
   $zapw   = ' WHERE '.$this->n[2].'='.$this->id;									//-warunek dla kwerendy
  }
  else
  {
   $typzap = 'INSERT INTO'; 																//-dodanie nowego rekordu
	$zapw = '';
  }

  $foty2 = false;

  $i=3;
  while(isset($this->n[$i])) 																//-pętla po wszystkich polach rekordu -lista zapisu do tabel powiązanych
  {

	$skip = $this->korektaDoIzm($i);														//-ustawia skip

	if(!$skip)
   {

	 if(substr($this->n[$i], -4, -1) == 'fot' || substr($this->n[$i], -5, -1) == 'plik')	//-jeśli są pliki tworzymy listę plików
	 {

	  if(is_uploaded_file($_FILES[$this->n[$i]]['tmp_name']))					//-jeśli jest transport pliku
  	  {

		if(!$this->ns[$i])																	//-jeśli plik zwalidowany pozytywnie
	   {

	    if($this->tr[$i] && !$_POST['kopia'])											//-jeśli zwykłe nadpisanie pliku, NIE DUPLIKAT !
		 {
	     $snp[$this->tr[$i]] = array($i, $this->nttt[$i][path]);				//-$snp[] zapamiętanie nazwy pliku do skasowania i katalogu
	    }

		 list($t0, $t1) = microtime();													//-parametr dla nowej unikalnej nazwy pliku

		 $this->tr[$i] = uniqid($i+(float)$t1 + (float)$t0).'.'.strtolower(substr($_FILES[$this->n[$i]]['name'],-3));	//-nazwa dla pliku

		 $foty[$this->n[$i]] = array($i, $this->tr[$i]);							//-tablica plików :: numer, nazwa
		}
	  }
	  else																						//-jeśli nie ma transportu pliku
	  {

		if($_POST['k'.$this->n[$i]])														//-jeśli plik zanaczony do skasowania
		{
		 $snp[$this->tr[$i]] = array($i, $this->nttt[$i][path]);					//-lista plików do skasowania wraz z katalogiem i numerem pola

		 $this->tr[$i] = '';																	//-jeśli kasujemy plik to czyścimy dotychczasową jego nazwę z tabeli
		}


		if($this->tr[$i] && $this->kopia)												//-jeśli replikowany rekord ma pliki
   	{
		 $typ = pathinfo($this->tr[$i]);													//-odczytanie parametrów pliku

	    $sntr = $this->tr[$i];																//-zapamietanie nazwy pliku replikowanego

	    $this->tr[$i] = uniqid(rand()).'.'.$typ['extension'];			  		//-nowa nazwa dla pliku replikowanego

	    $rnp[$this->tr[$i]] = array($i, $sntr, $this->nttt[$i][path]);		//-tablica plików do replikowania array[nowa_nazwa] = stara_nazwa

		 unset($sntr, $typ);
		}

	  }

	  //-tablica zdjęć do kadrowania - w drugim obiegu

	  if($this->tr[$i] && $this->pkadr[$i])											//-warunek wyklucza pliki przewidziane do skasowania
	  {
		$foty2[$this->n[$i]] = array($i, $this->tr[$i]);							//-tablica plików :: numer pola formularza, nazwa
	  }


	  if($this->exSize)																		//-extra skalowanie :: 2012-05-10
	  {
	   $this->exSize = preg_replace('/[x]/', ':', $this->exSize);
		$this->exSize = preg_replace('/[a-zA-z ąęśćźżłóĄĘŚĆŻŹÓŁ]/', '', $this->exSize);

	   $fskall = substr($this->n[$i], -1);

		$fskal[$fskall] = explode(':', $this->exSize);								//-w indeksie nr pla plikowego w tabeli z nazwy fot0 =>fot[0]

		if(count($fskal[$fskall]) < 2) $fskal[$fskall] = null;

		unset($fskall);
	  }

	 } //-end dla pola plikowego

	 //-to jest extra skalowanie odczytywane z dodatkowego rekordu w tabeli ( ekstra pole w tabeli dla indywidualnego rozmiaru skalowania)

	 if(substr($this->n[$i], -4, -1) == 'roz' && $this->tr[$i])										//-testujemy czy jest skalowanie dla pliku
    {
	  $this->tr[$i] = preg_replace('/s/', '', $this->tr[$i]);

     $fskal[substr($this->n[$i], -1)] =  explode('x', $this->tr[$i]);							//-jeśli tak dodajemy dane do tablicy skalowania
    }

	 //$this->kopia
	 if($this->n[$i] == 'dado' && $this->kopia) $this->tr[$i] = $teraz;							//-dla repliki aktualna data dodania

	 if(substr($this->n[$i], -4, -1) == 'kli' && $this->kopia) $this->tr[$i] = 0;				//-dla repliki rekordu zeruje liczniki

	 if(isset($this->tr[$i]))																					//-!!! musi być isset dla pól typu checkbox ->
	  $zapy[] = $this->n[$i].'=\''.$this->tr[$i].'\'';
	}

	unset($skip);

   $i++;
  }


  if($this->userSet && $this->pwartStale)
  {
   foreach($this->pwartStale as $tmp_ky => $tmp_wa)
	 $zapy[] = $tmp_ky.'=\''.$tmp_wa.'\'';

	unset($tmp_ky, $tmp_wa);
  }

  $zapy = implode(',', $zapy).$zapw;																		//-złożenie treść zapytania MySQL, $zapw = warunek kwerendy


  try
  {

	$tab = DB::myQuery("$typzap $this->t SET $zapy");  		 										//-kwerenda SQL

	unset($typzap, $zapw, $zapy);

  }
  catch(Exception $e)
  {
   $this->x .= '<p>'.$zapy.'</p>'.C::debug($e, 0);
  }


  if($tab)																											//-jeśli dane do rekordu zapisane prawidłowo
  {

	if($this->a == 'dodaj')
   {
    $this->id = mysqli_insert_id(); 																			//-nie powinno mieć znaczenia dla klasy USER ???

	 if($_SESSION['backlink'])
	 {

	  $tt_id = explode('#', $_SESSION['backlink']);

	  if(!$tt_id[1])
	   $_SESSION['backlink'] .= '#'.$_SESSION['backlink_fix'].md5($this->id);


	 }



	 /*
	 if($_SESSION['backlink'])
	 {
	  if($_SESSION['backlink_fix'] != '#id')
	  {
	   $_SESSION['backlink'] .= '#'.$_SESSION['backlink_fix'].md5($this->id);

	  }
	  else
	  {
		//$_SESSION['backlink'] .= '#'.$this->id;

		$_SESSION['backlink'] = preg_replace('/id/', $this->id, $_SESSION['backlink']);

	  }


	  $_SESSION['backlink_fix'] = '';
	 } */

   }

	$_SESSION['sended'] = C::odbDane($_POST['send']);
	//-wskażnik wykożystania danych z formularza, zabezpiecza przed dodatkowym zapisem po przeładowaniu przeglądarki

	$this->x .= '
		<p class=\'ok\'>rokord zapisany prawidłowo...</p>';

  	$this->a = 'pokaz';

	$wlp = '';

	if(isset($rnp))																								//-replikacja plików jeśli są takie, z tablicy replikazji rnp
   {
    foreach($rnp as $name => $par)																			//-$snp[nazwa pliku] = array( $i, stara+nazwa pliku, pat);
    {
	  $wlp .= $this->kaspl($par[1].$name);

	  if(!copy($par[2].$par[1], $par[2].$name))
		$this->x .= '
		<p class=\'error\'>pliku : '.$par[1].' -> nie udało się powielić</p>';
	  else
      $this->x .= '
		<p class=\'ok\'>plik : '.$par[1].' -> został powielony</p>';


	  foreach($this->nttt[$par[0]]['th'] as $k => $v)
		if($k != 'L_')
		{
		 if(!copy($par[2].$k.$par[1], $par[2].$k.$name))
		  $this->x .= '
		<p class=\'error\'>pliku : '.$k.$par[1].' -> nie udało się powielić</p>';
	    else
        $this->x .= '
		<p class=\'ok\'>plik : '.$k.$par[1].' -> został powielony</p>';
      }
	 }
    unset($par, $name, $snp);
   }

   $this->x .= $wlp;

   unset($wlp);

	Test::trace('foty przed wgraniem', $foty);

	if(isset($foty)) 															  			//-jeśli jest plik = jest transport,  ładujemy go na serwer
   {

	 $tmp_dir = true;																		//-testowanie katalogów
	 $foto_dir = true;

	 //-jeśli jeszcze nie istnieją to tworzymy odpowiednie katalogi, na foty i tymczasowy na foty przed kadrowaniem

	 if(!is_dir(C::get('tmpPath_foty'))) 											//-katalog na uploadowane pliki tymczasowe
	  $tmp_dir = mkdir(C::get('tmpPath_foty'), 0777);

	 if(!is_dir(C::get('fotyPath'))) 												//-katalog docelowy na pliki
	  $foto_dir = mkdir(C::get('fotyPath'), 0777);


	 if($tmp_dir && $foto_dir)															//-jeśli już istnieją wymagane foldery
	 {

     foreach($foty as $k => $v)
	  {

	   $pdt = $_FILES[$k]['tmp_name'];


	   if(!is_array($this->nttt[$foty[$k][0]]['ext']))				//-jeśli parametr ext dla pliku nie jest tablicą, to robimy z niego tablicę
	    $ext_array = array($this->nttt[$foty[$k][0]]['ext']);
	   else
	    $ext_array = &$this->nttt[$foty[$k][0]]['ext'];


		if(array_intersect($ext_array, array('jpg', 'jpeg', 'gif', 'png')))
		{
	    $fo = move_uploaded_file($_FILES[$k]['tmp_name'], C::get('tmpPath_foty').$v[1]);

		 if(!chmod(C::get('tmpPath_foty').$v[1] , 0777)) exit('nie można ustawić praw do pliku w '.__CLASS__);

		 //Test::trace('move to tmp foty', $fo);
		}
	   else //-plik jest ładowany bezpośrednio do katalogu plików
	   {
	    $fo = move_uploaded_file($_FILES[$k]['tmp_name'], C::get('fotyPath').$v[1]);

	    $foty[$k][0] = false;															//-plik nie podlega obróbce, bo to nie jest plik graficzny

		 //Test::trace('move to foty', $fo);
	   }

		unset($ext_array);


	   if($fo)
		{
		 $this->x .= '
	    <p class=\'ok\'>plik : '.$_FILES[$k]['name'].' -> został załadowany</p>';

		 if(!$this->kadr[$foty[$k][0]]) $foty2[$k] = $foty[$k];		//jeśli plik załadowany

		 Test::trace('nr foty', $foty[$k][0]);

		}
	   else
	   {
	    $this->x .= '
		 <p class=\'error\'>plik : '.$_FILES[$k]['name'].' -> NIE ZOSTAŁ ZAŁADOWANY</p>';

	    $foty[$k][0] = false;  														//-zaznaczamy plik z listy którego nie udało się załadować !!!
		 // !! tu powinno być odwołanie do $foty2 !!! ???
	   }

	  } //-koniec pętli


	 }
	 else
	  $this->x .= '
		<p class=\'error\'>Nie udało się utworzyć katalogu tymczasowego na pliki graficzne!</p>';
   }

	// !!!! tu powinno być przypisanie $foty2 = $foty;


	// ta petla wydaje się być bzdurą  bo po co ta pętla? jeśli kalsa operuje na całej tabeli $foty2 ???

	Test::trace('walidacja', $this->ns);
	Test::trace('foty', $foty);
	Test::trace('foty2', $foty2);
	Test::trace('foty3', $foty3);
	Test::trace('fskal = extra size', $fskal);
	//Test::trace('this->nttt', $this->nttt);
	Test::trace('this->kadr', $this->kadr);
	Test::trace('this->pkadr', $this->pkadr);

   if($foty2)
   {

	   $r = new FotoSkalMark($foty2, $fskal, $this->nttt, $this->kadr, $this->pkadr);
	   /*
		- tablica zdjęć
		- tablica parametrów skalowania
		- tablica definicji utworzona na podtsawie definicji znaczników dla tabeli MySQL = $this->nttt !!!!!!
		- tablica wskaźników kadrowania : array(nr_pola, 1=kadrowanie 0=brak kadrowania)
		- tablica parametrów kadrowania  : jeśli jest kadrowanie, to tu są wszystkie dane do kadrowania z javascrypt'u
	   */


      $this->x .= $r->outFoto(); //-wyświetlenie wyników działania klasy

	   unset($r);


    unset($foty2, $fskal);
   }

	/*
   if($foty2)
    foreach($foty2 as $k => $v)
    {
	  if($this->pkadr[$foty2[$k][0]])
	  {
	   //Test::trace('parametry skalowania', $foty2);
	   //Test::trace('parametry skalowania', $this->pkadr[$foty2[$k][0]], 0);

      //-dopiero po załadowaniu zdjęcia

	   $r = new FotoSkalMark($foty2, $fskal, $this->nttt, $this->kadr, $this->pkadr);
	   /*
		- tablica zdjęć
		- tablica parametrów skalowania
		- tablica definicji utworzona na podtsawie definicji znaczników dla tabeli MySQL = $this->nttt !!!!!!
		- tablica wskaźników kadrowania : array(nr_pola, 1=kadrowanie 0=brak kadrowania)
		- tablica parametrów kadrowania  : jeśli jest kadrowanie, to tu są wszystkie dane do kadrowania z javascrypt'u
	   *//*


      $this->x .= $r->outFoto(); //-wyświetlenie wyników działania klasy

	   unset($r);
	  }

    unset($foty2, $fskal);
   }
  */

	if(isset($snp))
	{
	 $wlp = '';
	 if($snp && !$this->kopia)			      						//-jeśli są jakieś pliki do skasowania = snp - kasuje, ale tylko gdy nie jest to replika
    {

     foreach($snp as $name => $par)								  //-tablica plików do skasowania :: $snp[nazwa pliku] = array( $i,  pat);
     {
	   $wlp .= $this->kaspl($par[1].$name);

	   foreach($this->nttt[$par[0]]['th'] as $k => $v)
		 if($k != 'L_')
		  $wlp .= $this->kaspl($par[1].$k.$name);

	  }
     unset($par, $name, $snp);

    }

    $this->x .= $wlp;

    unset($wlp);																			//-KONIEC kasowania plików do skasowania
   }

   //-jeśli są tabele powiązane -> DO ZROBIENIA !!!!!

  } //-end if($tab)

 }

 /** ./cms/klasy/Common.php
 *
 *
 *
 */

 protected $zdtp = array();															//-tablica danych dla tabel powiązanych

 /** ./cms/klasy/Common.php
 *
 * korekta dla określonych znaczników
 * tylko dla klasy wewnętrznej Common
 *
 */

 protected function korektaDoIzm($i)
 {
  $skip = false;

  foreach($this->nttt[$i] as $k => $w)
  {

   switch(trim($k)) 									//-musi być substr lub trim dla konwersji do string, bo prównanie == nie zdaje egzaminu
   {
	 case 'UB':																				//-znacznik pola wyboru ze wskazanej kolumny wskazanej tabeli

		if($w[1] && $w[2])
	   $this->zdtp[$w[1]] = $w[2].'^'.$this->tr[$i];							//-tabela zdtp - zapis do tabel powiązanych ?????????

	 break;

	 case 'A':

	  if(!C::get('jo') && !C::get('ja')) $skip = true;				//-jeśli pole typu Admin i nie JA czyli VIP i nie JO czyli Admin klasy 9 to kwerenda dla pola jest pomijana

	  if($this->userSet) $skip = true;

	 break;

	 case 'V':

	  //if(!C::get('ja')) $skip = true;

	  if($this->userSet) $skip = true;

	 break;

	 case 'W':																				//-korekta dla checkboxa

		 if(isset($this->tr[$i]))
		 {
	     if($this->tr[$i] === 'on')
	      $this->tr[$i] = 1;
		  else
		   $this->tr[$i] = 0;
		 }

	 break;

	 case '!': 																				//-pole szyfrowane, dotyczy tylko hasła dostepu

	  if($this->a == 'dodaj')
	  {
		$this->tr[$i] = $this->passKodCms($this->tr[$i]);
	  }
	  elseif($this->a == 'zmien')
	   if($pas = $this->isSamePass($i))
		{
		 //Test::tracer(__FILE__, __METHOD__, __FUNCTION__, __LINE__, 'password', $pas);

		 if($this->tr[$i] != $pas)
		  $this->tr[$i] = $this->passKodCms($this->tr[$i]);

			//-jeśli aktualne hasło jest inne , to kodujemy i podstawiamy do kwerendy
		}

   }
  }
  unset($k, $w);

  return $skip;
 }

 /**
 *
 * odczytuje aktualne, zakodowane hasło z tabeli
 * 2012-11-20
 *
 */

 private function isSamePass($i)
 {
  //aktualne hasło jest już zakodowane
  if(!$i) return 0;

  $tab = 'SELECT '.$this->n[$i].' FROM '.$this->t.' WHERE '.$this->n[2].'='.$this->id;

  if($tab = DB::MyQuery($tab))
  {
   if($tab = mysqli_fetch_row($tab))
	 return $tab[0];
	else
	 return 0;
  }
  else
   return 0;
 }

 /** ./cms/klasy/Common.php
 *
 * przygotowanie pól formularza dla danych typu date i datetime
 *
 */

 protected function poleData($i, $readOnly)
 {
  $typ = 'text';
  $onData = 1;

  if(isset($this->nttt[$i]['D']))
  {
   if($this->nttt[$i]['D'] === 'onlyData') $onData = 0; else $onData = 1;

   if($this->nttt[$i]['D'] === 'hidden' && $this->userSet)  	//-pole ukryte dla User'a
   {
  	 $typ = 'hidden';
    $this->hidden = true;													// wycina cały div z formularza
   }
  }

  if($this->tr[$i]) //  && $iq != 'd') 							//-jeśli duplikat po negatywnej walidacji to aktualna data !!!!!!!
   $vdata = $this->kdata($this->tr[$i], $onData);
  else
   $vdata = $this->kdata(C::get('datetime_teraz'), $onData);


  if($readOnly === 'readOnly')
  {
   $data = '
		 <input id=\'data'.$i.'\' class=\'data stReadOnly\' type=\''.$typ.'\' name=\''.$this->n[$i].'\' value=\''.$vdata.'\' '.$readOnly.'/>';
  }
  else
  {
   $data = '
		 <input id=\'data'.$i.'\' class=\'data\' type=\''.$typ.'\' name=\''.$this->n[$i].'\' value=\''.$vdata.'\' title=\'kliknij aby zmienić datę\'/>';
  }

  unset($vdata, $readOnly, $typ, $onData);

  return $data;
 }

 /** ./cms/klasy/Common.php
 *
 * 2016-05-28 :: modyfikacja dla tabeli zdjęć dopiętych z innych publikacji
 *
 * przygotowanie formularza dla danych typu file
 * 2013-06-07 :: poprawki -> $this->nttt[$i]['ext'] dla dalszych operacji musi być tablicą, nawet jednoelementową
 * 2013-01-04 :: dodano ograniczenie typu pliku jako można pobrać
 *
 */

 protected function polePlik($i, $size = '30')
 {
  if(!isset($this->n[$i])) return;

  if($this->t == C::get('tab_fotx')) 		//-znacznik dla zdjęć dopiętych
	$dop = TRUE; else $dop = FALSE;

  //Test::trace(__METHOD__, $this->nttt[$i]['ext']);

  if(!is_array($this->nttt[$i]['ext'])) $this->nttt[$i]['ext'] = array($this->nttt[$i]['ext']);
  //$this->nttt[$i]['ext'] dla dalszych operacji musi być tablicą, nawet jednoelementową

  //Test::trace('$this->nttt[$i][ext]', $this->nttt[$i]['ext']);

  $ext = 'accept=\'image/'.implode(',image/', $this->nttt[$i]['ext']).'\'';

  //Test::trace('parametry dla rekordu', $this->nttt[$i]);

  //-extra size tylko dla plików graficznych
  //-dla innych wstawia ikonę w tej wersji 'pdf'
  if(array_intersect($this->nttt[$i]['ext'], array('jpg','jpeg','png','gif')))
   $fo = true;
  else
   $fo = false;


  $all_kadr = C::get('java');		//odczyt ustawień dla domyślnego kadrowania

  if(!$fo || !$all_kadr['kadrowanie'])
   $no_kadr = ' class=\'no_kadr\''; else $no_kadr = '';


  if(!$dop) $fx = '
	<input'.$no_kadr.' type=\'file\' name=\''.$this->n[$i].'\' size=\''.$size.'\' '.$ext.'
		title=\'wskaż lokalizację pliku na swoim dysku\' alt=\''.$i.'\'/>';


	if($this->exSize) $fx .= '<span > size:</span><input type=\'text\' name=\'size_'.$this->n[$i].'\' value=\''.$this->exSize.'\' title=\'niestandardowy rozmiar skalowania: szer x wys\' />';


   unset($ext, $no_kadr);

  //-jesli istnieje plik to zawsze przy edycji przesyłana jest aktualna zakodowana nazwa pliku

  if($this->tr[$i]) //-jeśli jest plik w bazie
  {
   //-pole checkbox do zanaczenia skasowania pliku

   if(!$dop) $fx .= '
	<label class=\'oder\' for=\'inpk'.$i.'\' title=\'zaznacz aby skasować plik\'>kasuj</label>
	<input type=\'checkbox\' name=\'k'.$this->n[$i].'\' id=\'inpk'.$i.'\' title=\'zaznacz aby skasować plik\' />';


   if(C::get('ja')) //-widok dla VIP'a
    $fx .= '
	 <br />
	<input class=\'vip_plik\' type=\'text\' name=\'n'.$this->n[$i].'\' value=\''.$this->tr[$i].'\' readOnly title=\'lokalna nazwa pliku\' />';
   else
    $fx .= '
	<input type=\'hidden\' name=\'n'.$this->n[$i].'\' value=\''.$this->tr[$i].'\' />';

	//- !!! disabled="disabled" -> nie może być bo bo wtedy pole nie przekazuje wartości

	if($fo)
	{
	 if(!isset($this->kadr[$i]))
	  $this->kadr[$i] = false;
	}
   else
	 $this->kadr[$i] = false;


	 if(!$this->kadr[$i])																						//-jeśli kadrowanie wyłączone
	 {
	  if(file_exists($this->nttt[$i]['path'].$this->tr[$i]))											//-jeżeli jest duże zdjęcie
	  {
	   list($sze, $wys) = getimagesize($this->nttt[$i]['path'].$this->tr[$i]);

	   if(file_exists($this->nttt[$i]['path'].'m_'.$this->tr[$i]))									//-jeśli jest miniatura to miniatura
      {
	    $fx .= '
		<div class=\'tumbs\'>
		 <a href=\''.$this->nttt[$i]['path'].$this->tr[$i].'\' title=\'kliknij aby powiększyć :: rozmiar oryginalny: '.$sze.' x '.$wys.'\' target=\'_blannk\' >
		  <img class=\'thu\' src=\'./thumbnail.php?id=m_'.$this->tr[$i].':'.session_id().':100:100:t:k:f\' alt=\''.__FILE__.'\' />
		 </a>
		 <p>'.$this->nttt[$i]['path'].$this->tr[$i].'</p>
		</div>';																										//-skalowanie, tworzenie miniatur w locie, tutaj z miniatury
      }
      else																											//-jeśli nie ma miniatury
      {
		  $fx .= '
		<div class=\'tumbs\'>
	    <a href=\''.$this->nttt[$i]['path'].$this->tr[$i].'\' title=\'kliknij aby powiększyć :: rozmiar oryginalny: '.$sze.' x '.$wys.'\' target=\'_blannk\' >
		  <img class=\'thu\' src=\'./thumbnail.php?id=m_'.$this->tr[$i].':'.session_id().':100:100:t:k:f\' alt=\''.__FILE__.' 2\' />
		 </a>
		</div>';																										//-skalowanie, tworzenie miniatur w locie, tutaj z dużego
	   }

	  }
	  else																											//-jeśli nie ma dużego zdjęcia
	  {
	    if(file_exists(C::get('tmpPath_foty').$this->tr[$i]))										//-jeśli jest jeszcze oryginał
	    {
	     if($_SESSION['id-cms-err'] != 'kadr_start')
	     {
	      $_SESSION['id-cms-err'] = 'kadr_start';

	      $id_kadtr_start = ' id=\'kadr_start\'';
	     }

	 	 //-ten kod wyświetla się po ponownym wejściu do edycji rekordu, jeśli plik nie został wykadrowany

        $fx .= '
	  <div'.$id_kadtr_start.' class=\'do_kadr\'>
	   <p>wykadruj zdjęcie</p>
		<img class=\'kadr\' id=\'img'.$i.'\' src=\'./thumbnail.php?id='.$this->tr[$i].':'.session_id().':300:300:k:t\' />
		<a href=\'#\' class=\'hel\' title=\''.Common::HELP_KADR.'\'></a>
	  </div>';

	     unset($id_kadtr_start);
		 }

	  }

	 }
	 else																												//-jeśli jest kadrowanie WŁĄCZONE
	 {
	  if($fo)
	  {
	   $_SESSION['id-cms-err'] = 'kadr_start';

	   $id_kadtr_start = ' id=\'kadr_start\'';

	   //-ten kod wyświetla się zaraz po załadowaniu pliku

	   $fx .= '
	  <div'.$id_kadtr_start.' class=\'do_kadr\'>
	   <p>wykadruj zdjęcie</p>
	   <img class=\'kadr\' id=\'img'.$i.'\' src=\'./thumbnail.php?id='.$this->tr[$i].':'.session_id().':300:300:k:t\' />
		<span class=\'hel\' title=\''.Common::HELP_KADR.'\'></span>
		<p>1670</p>
	  </div>';

	   unset($id_kadtr_start);

	   /*
	   * kadrowanie : identyfikacja po klasie = 'kadr'
	   * id zdjęcia, tutaj img.$i - identyfikuje wygenerowane pole input do odbioru danych
	   * pole input doodaje do tego prefix : kadr_ co razem daje kadr_img.$i
	   */

     }
	 }

	 if(!$fo && !$dop) // jeśli to nie jest zdjęcie dopięte i fotografia to plik ??
	  $fx .= '
		<div class=\'tumbs\'>
		 <img src=\'./cms/skin/pdf50.png\' alt=\''.substr($this->tr[$i], -3).'\'>
		</div>';

   unset($sze, $wys, $wys1, $sze1, $sze2);

  } //-end warunku dla wersji gdy plik już istnieje ( dokładnie jego nazwa )

  return $fx;
 }

 /** ./cms/klasy/Common.php
 *
 * kodowanie hasła
 *
 */

 protected function passKodCms($pass)
 {
  //return $pass;

  $kod = substr(md5(time()), 0, 2);

  return md5($kod.$pass).$kod;
 }

 /** ./cms/klasy/Common.php
 @
 * -odkodowanie do porównania
 */

 protected function passDekodCms($zak, $pass)
 {
  //return $pass;

  $kod = substr($zak, -2);

  return md5($kod.$pass).$kod;
 }

 /** ./cms/klasy/Common.php
 @
 *  - kasowanie pliku z dysku
 */

 protected function kaspl($kpl)
 {
  if(file_exists($kpl))
  {
   if(@unlink($kpl))
    $kom = '<p class=\'cms_ok\'>plik: '.$kpl.' usunięty prawidłowo.</p>';
   else
    $kom = '<p class=\'cms_alert\'>nie mogę usunąć pliku: '.$kpl.'!</p>';
  }
  $kom = '<p class=\'cms_alert\'>brak pliku: '.$kpl.'!</p>';


  if($this->userSet)
   return;
  else
   return $kom;
 }

 /** ./cms/klasy/Common.php
 *
 * konwersja daty
 *
 */

 protected function kdata($d, $c) //-konwersja daty w formacie rrrr-mm-dd
 {
  if($d)
  {
   $da  = explode('-', substr($d,0,10));

   switch($c)
   {
    case 0: return  $da[2].'-'.$da[1].'-'.$da[0];							//-dd-dd-rrrr
    case 1: return  $da[2].'-'.$da[1].'-'.$da[0].' '.substr($d,11); 	//-dd-mm-rrrr-godzina
    case 2: return  $da[2].'-'.$da[1].'-'.substr($da[0],2);				//-dd-dd-dd
   }
  }
  else
   return;
 }

 /** ./cms/klasy/Common.php
 *
 * zwraca listę wszystkich tabel w aktywnej bazie dla klucza
 *
 */

 protected function tablesList()
 {
  $t = array();

  $pref = C::get('db_prefix');
  $aktb = C::get('akt_baza');

  try
  {
	//-wyklucza wyświetlanie tabel z innych serwisów

	if($tab = DB::myQuery('SHOW TABLES'))
    while($ta = mysqli_fetch_assoc($tab))
     if(substr($ta['Tables_in_'.$aktb], 0, strlen($pref)) == $pref || $pref == '')
	   $t[] = $ta['Tables_in_'.$aktb];

    unset($tab, $ta, $aktb, $pref);

 	 return $t;

  }
  catch(Exception $e)
  {
	C::debug($e, 2);
  }

 }


 /** ./cms/klasy/MojeSQL.php
 *
 * wyświetla linki jako buttony formularza do wszystkich tabel serwisu
 *
 */

 protected function TablesMenu($t, $akcja)
 {

  try
  {

	if($tab = DB::myQuery('SHOW TABLES'))
   {
	 $doz_tab = array();

	 if($_SESSION['admin']['status'] < 10) $doz_tab = C::get('doz_tab');	// -2015-03-13 tablica dozwolonych tabel

    $aktb = C::get('akt_baza');
	 $pref = C::get('db_prefix');
	 $wt = '';

    while($ta = mysqli_fetch_assoc($tab))
     if(substr($ta['Tables_in_'.$aktb], 0, strlen($pref)) == $pref || $pref == '')
     { //-wyklucza wyświetlanie tabel z innych serwisów

		$blok = false;

		if($ta['Tables_in_'.C::get('akt_baza')] == $t)
       $akty = ' akt_tab';
	   else
	    $akty = '';

		$tabela = $ta['Tables_in_'.$aktb];

	   $wt2 = explode('_', substr($tabela, strlen($pref)));

		if($_SESSION['admin']['status'] < 10)
		{
		 if(($wt2[0]*1) > 89)
		  $blok = true;

		 if(!in_array($ta['Tables_in_'.$aktb], $doz_tab)) $blok = true; // -2015-03-13
      }



		if(!$blok)
		{
	    if(($wt2[0]*1) > 88)
	    {
	     $tab_vip = ' tab_vip';
	    }
	    else
	     $tab_vip = '';

	    $tabela = C::korektaDoMenu2($tabela);								//-korekta dla polskich znaków w nazwach w menu

		 $wt .= '
		  <a title=\''.$ta['Tables_in_'.$aktb].'\'class=\'tab_tab'.$tab_vip.$akty.'\' href=\''.S::linkCode(array($ta['Tables_in_'.$aktb])).'+'.$akcja.'.cmsl\'>'.$tabela.'</a>';
		}
	  }

     unset($tab, $ta, $akty, $tab_vip, $wt2, $tabela, $aktb);

     if($wt)
      return '
	<div id=\'tabele\'>'.$wt.'
	</div>';
     else
      return false;

    }

  }
  catch(Exception $e)
  {
   return '0'.C::debug($e, 0);
  }

 }

 /**
 *
 * sprawdza wartości tablicy, i przyjmuje wartość 1 jeśli co najmniej jeden element jest różny od 0 lub ''
 *
 */

 public static function zawTab($tab)
 {
  $sum = 0;

  foreach($tab as $w)
  {
   if($w) $sum++;
  }

  return $sum;
 }

 /** ./cms/klasy/Common.php
 *
 *
 */

 function __destruct()
 {

 }

}
?>