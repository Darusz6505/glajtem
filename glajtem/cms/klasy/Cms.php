<?
defined('_CMSPATH') or header('location: http://'.$_SERVER['HTTP_HOST']);

/**
*
* klasa : CMS v.1.8.2
*
* 2021-01-13 : modyfikacje do wersji PHP 7.xx
*
*
* 2015-05-17 -> poprawa odwołania do dynamicznego ładowania miniatur
*
* 2014-07-12 -> poprawiony powrót dla edycji bloku treści z poziomu zajawki
*
* 2014-04-08 -> poprawny pefix powrotu po ddodaniu rekordu
*
* 2013-01-21 -> poprawiona kontrola tabeli systemowej
* 2013-01-03 -> poprawa ukrywania pól formularza dla znacznika V
*
* 2012-12*-01 -> doodany bezpośredni powrót do fronside dla kasowania bezpośrednio z fronsidu
*
* 2012-11-13 -> parametr wymienny 'nowa wartość' dla pól UB
* 2012-09-26 -> zparametryzowano title dla pól formularza w zależnosci od wersji językowej
* 2012-03-02 -> dodanie pola typu ukryte;
*
* 2012-02-25 -> $nttt -> dla zdjęć działała tylko dla jednej tabeli !!!
*
* 2011-12-07 -> help i dym z a na span bo powodowało przeładowanie formularza z utratą wporwadzonych zmian !!!
* 2011-10-08 -> dodane opcje dostępu dla Usera
* 2010-09-29 -> 2011-05-10
*
*
*/

class Cms extends Common
{

 protected $x; 					//-wynik działania klasy - odpowiednik fx
 private   $y; 					//-wynik działania klasy - odpowiednik fy
 private   $z; 					//-wynik działania klasy - odpowiednik fy

 protected $a;						//-zmienna akcji

 protected $t;						//-zmienna aktywnej tabeli
 protected $id;					//-id edytowanego rekordu

 protected $n = array();		//-tablica nazw pól tabeli - MySQL
 protected $nt = array();		//- ok. 2012-03-04 tablica definicji MySQL pola
 protected $nttt = array(); 	//-tablica definicji pola
 protected $ns = array();		//-tablica walidacji
 protected $nst = false;		//-wskaźnik walidacji testowej = wymuszonej wywołaniem z klasu User

 protected $exSize = '';		//-wartość specjalnego skalowania miniatury :: 2012-05-10

 protected $kadr = array(); 	//-tablica znaczników kadrowania
 protected $pkadr = array(); 	//-tablica parametrów kadrowania -> kadr[i] = array(x1,y1,x2,y2,width,height)

 protected $tr = array(); 		//-tablica z zawartością rekordu tabeli dla formularza

 protected $tabSys;				//-tabela systemowa

 protected $u = false;			//-wskażnik usera po przesłaniu danych

 protected $hidden = false;   //-pole ukryte


 protected $jo = false;
 protected $ja = false;
 protected $adS = 0;				//-zamiennik dla jo i ja, dla skrócenia kodu

 protected $pasd = ''; 			//-hasło celem weryfikacji

 protected $maxdl = 90;			//-predefiniowana szerokość pola formualrza dla CMS'a

 protected $dodaj = 'nowa wartość';  	//-stworzone dla sorhurt.pl, który dla list wyboru z możliwością dodania nowej wartości, chciał aby tekt
 													//- 'nowa wartość' zastapić tekstem 'dodaj'
													//- parametr jest definiowany dla CMS'a w config_def i wymaga parametry w wywołaniu wtyczki jQuery

 protected $userSetp = false;
 protected $userSet = false;
 protected $userForm = false;
 protected $formSet = false;

 protected $kopia = false;

 /** ./cms/klasy/Cms.php
 *
 *
 */

 function __construct()
 {

  if(isset($_REQUEST['cod']) && $_REQUEST['cod'])
  {
    //exit('jest cod : '.__CLASS__.__LINE__);

	 $cod = S::linkDecode(C::odbDane($_REQUEST['cod']));

	 $cod = explode(',', $cod);

	 $cod = array_pad($cod, 7, '');

	 Test::trace('zdekodowany link cmsa', $cod);

 	 /** Nowy układ
	 *
	 * 0 = nazwa tabeli
	 * 1 = id tabeli [1]
	 * 2 = akcja
	 * 3 = wartości predefiniowane

	 * 4 = adres strony dla powrotu
	 * 5 = prefix dla skoku do id
	 * 6 = id publikacji ( dla edycji treści z zajawki ) ->
	 *
	 */


	 if($cod[4])
	 {
	  $_SESSION['backlink'] = $cod[4].'.html';



	  if($cod[5] == 'fot' && $cod[1])
	   $_SESSION['backlink'] .= '#'.$cod[5].md5($cod[1]);
	  else
	   if($cod[6])
	   {
		 if($cod[5])
		  $_SESSION['backlink'] .= '#'.$cod[5].md5($cod[6]);
		 else
		  $_SESSION['backlink'] .= '#'.md5($cod[6]);
	   }


	 }
	 else
	  $_SESSION['backlink'] = '';



	 $this->t  = $cod[0];
	 $this->id = $cod[1];


	 if($cod[5]) $_SESSION['backlink_fix'] = $cod[5]; else $_SESSION['backlink_fix'] = '';


	 if($cod[2]) $this->a  = $cod[2]; else $this->a = 'lista';

	 if($cod[3]) //-wartości predefiniowane
	 {
	  $tabb = explode('.', $cod[3]);

	  $tabb_l = count($tabb);

	  if($tabb_l > 1)
     {
	   $tabb_l--;

	   for($j = 0; $j < $tabb_l; $j=$j+2)
		{
		 $wdo_t[$tabb[$j]] = $tabb[$j+1];
		}

     }
     unset($tabb, $j, $tabb_l);
	 }

  }
  else
  {
   //exit('stare linki : '.__CLASS__.__LINE__);

   $this->t = isset($_REQUEST['t'])?C::odbDane($_REQUEST['t']):false;		//-nazwa tabeli

   $this->a = isset($_REQUEST['a'])?C::odbDane($_REQUEST['a']):'lista';		//-akcja, tak musi być ze względu na wywołania zewnętrzne ???

   if(isset($_POST['kasuj']))
	 if($_POST['kasuj']) $this->a = 'kasuj';											//-wskaźnik kasowania

   if(!$this->id && isset($_REQUEST['id']))
	 $this->id = C::odbDane($_REQUEST['id']);	 										//-wskażnik edytowanego rekordu

  }

  $this->tabSys = C::get('tab_tab');													//-tabela systemowa

  if(!$this->jo) $this->jo = C::get('jo');

  $this->ja = 	C::get('ja');

  $this->adS = $_SESSION['admin']['status'];

  if(!isset($wdo_t)) $wdo_t = false;	//2013-02-06

  $this->myCms($wdo_t);																		//-parametrem jest tablica wartości predefiniowanych

 }

 /** ./cms/klasy/Cms.php
 *
 * przygotowanie tablic definiujacych pola tabeli mysql
 *
 * $this->n
 * $this->nt
 * $this->nttt
 *
 *
 */

 protected function tabDef()
 {
  	if(!in_array($this->tabSys, $this->tablesList())) 										//-jeśli istieje tabela systemowa
	 return 0;
	else
	 try
	 {
	  $tab = "SELECT * FROM ".$this->tabSys." WHERE nazwa='$this->t'";

	  if($tab = DB::myQuery($tab))
	  {
	   $ta = mysqli_fetch_row($tab);

 	   $i=2;

 	   while($ta[$i])
 	   {
		 //-> znaczniki (np. nazwa^T;20)|nazwa pola (np. pu_nazwa)|definicja pola MySQL (np. VARCHAR(10) )

  	    $zz = explode('|',$ta[$i]);

		 $this->n[$i]  	= $zz[1];																//-tablica nazw pól MySQL
  	    $this->nt[$i]    = $zz[2]; 																//-tablica definicji MySQL pola
		 $this->ns[$i]		= '';																		//-inicjowanie tablicy walidacji

		 $nt1 = explode('^', $zz[0]);																//-tablica znaczników do pola tabeli

		 $nt3[0] = array_shift($nt1);

		 $k = 0;

		 while(isset($nt1[$k]))
		 {

		  $nt2 = explode(';', $nt1[$k]);

		   $lip = count($nt2);

			if($lip < 2)
			{
			 $nt3[array_shift($nt2)] = '';
			}
			elseif($lip < 3)
			{
			 $nt3[array_shift($nt2)] = $nt2[0];
			}
			else
			{
			 $nt3[array_shift($nt2)] = $nt2;

			}

		  $k++;
		 }


		 if(isset($nt3['path']) && substr($nt3['path'], -1) != '/') $nt3['path'] .= '/';

		 if(isset($nt3['th']))
		 {
		   if(!is_array($nt3['th'])) $nt3['th'] = array($nt3['th']);

		   foreach($nt3['th'] as $k => $w)
			{
		    $nt2 = explode(',', $w);
		    if(count($nt2) > 2 && $nt2[0] && $nt2[1] && $nt2[0])
			 {
			  $nt3['th'][array_shift($nt2)] = $nt2;
			  unset($nt3['th'][$k]);
			 }
			 else
			  exit('nieprawidłowe parametry znacznika dla pola '.$nt3[0]);

		   }
			unset($k, $w);
		 }

		 $this->nttt[$i] = $nt3;

		 unset($nt3, $nt2, $nt1, $k);

		 $i++;
 	   }

	  }
	  unset($tab, $ta, $zz, $i);

	  return 1;
    }
	 catch(Exception $e)
    {
	  C::debug($e, 2);
	 }

 }

 /** ./cms/klasy/Cms.php
 *
 * wyniki działania klasy
 *
 */

 public function wynik()
 {
  return array($this->x, $this->y, $this->z);
 }

 /** ./cms/klasy/Cms.php
 *
 * odbiór wartości predefiniowanych przy wstępnym wypełnianiu formularza
 * wartości predefiniowane przy wstępnym wypełnianiu formularza
 * format-> [nazwa pola].[wartość].[nazwa pola].wartość ....
 * tablica -> $wdo_t['nazwa_pola'] = wartość
 */

 private function odbPredefWart()
 {
	// do sprawdzenia czy jeszcze jest używane ? 2016-02-29

	$tab = explode('.', C::odbDane($_GET['bb']));

   if($tab)
   {
    $i = 0;

    while(isset($tab[$i]) && isset($tab[$i+1]))
    {
	  if($tab[$i] && $tab[$i+1])
	  {
      $wdo_t[$tab[$i]] = $tab[$i+1];
      $i = $i+2;
	  }
    }
   }
   unset($tab, $i);

	return $wdo_t;
 }


 /** ./cms/klasy/Cms.php
 *
 * przycięcie odbieranych łańcuchów do zdefiniowanej długości pola w tabeli MySQL
 * oraz korekta dla zanków 2 bajtowych
 *
 */

 private function walLenth($i)
 {
  $dl = 0;

  if(stristr($this->nt[$i], 'VARCHAR')) $dl = substr($this->nt[$i], 8, -1);

  if(isset($this->nttt[$i]['T'])) $dl = $this->nttt[$i]['T'];

  if(isset($this->nttt[$i]['E'])) $dl = $this->nttt[$i]['E'];

  if($dl > 1)  																// > 1 ze względu na znacznik W !!
  {
   $l = substr(C::odbDane($_REQUEST[$this->n[$i]]), 0, $dl); 	//-odebranie przyciętego łańcucha z pola formularza

   if(ord(substr($l, -1)) > 194) $l = substr($l, 0, -1);			//-warunek trzeba uszczegółowić !!!

	/*
	sprawdzenie ostatniego znaku po przycięciu
	jeśli jest to znak 2-bajtowy, czyli np. polskie i niemickie znaki diakrytyczne
	to n akońcu łańcucha zostaje jeden z 2 bajtów, który trzeba usunąć
	*/

   return $l;
  }
  else
   return C::odbDane($_REQUEST[$this->n[$i]]);

 }

 /** ./cms/klasy/Cms.php
 *
 * właściwa metoda CMS, wykonuje się jako pierwsza po contruct
 *
 */

 protected function myCms($wdo_t = array())
 {
  if(!isset($_POST['send'])) $_POST['send'] = false;

  if($_POST['send'] != '' && $_SESSION['sended'] == C::odbDane($_POST['send']))		//-zabezpieczenie przed ponownym wysłaniem danych
  {

   if($this->userSet)
	{
	 S::komunikat(_SEND_BREAK);
	}
	else
    $this->x .= _SEND_BREAK;																			//-z formularza po odświerzeniu strony
  }
  else
  {

   if(isset($_GET['bb']))
	{
	 $wdo_t = $this->odbPredefWart();									//-odbiór wartości predefinowanych metodą GET
   }

	//-przygotowanie tablic definiujacych pola tabeli mysql na podstawie tabeli systemowej

   if(!$this->tabDef(C::get('tab_tab')))
	{
	 $this->x .= 'Brak Tablicy Systemowej';
	}
	else
	{
    $this->y .= $this->TablesMenu($this->t, 'cms');
	}

	//-odbiór danych z formularza

	if($this->a == 'dodaj' || $this->a == 'zmien') 														//|| $this->a == 'szukaj'
	{

	 if(C::get('nowa_wartosc', false)) $this->dodaj = C::get('nowa_wartosc');

	 $wal_war = array();																							//-tablica walidacji warunkowej w budowie

 	 $i=3;

 	 while(isset($this->n[$i])) 			  																	//-petla po wszystkich polach tabeli bez id
 	 {

	  if(isset($_REQUEST[$this->n[$i]]))  $this->tr[$i] = $this->walLenth($i);

  	  if(isset($this->tr[$i]))
		if($this->tr[$i] === '-wybierz-')
		 $this->tr[$i] = '';

     //-odbiór wartości dodatkowych

	  // doodany isset a nie w zamian za test -> 2013-02-16

	  if(isset($_REQUEST['n'.$this->n[$i]]))
      if($_REQUEST['n'.$this->n[$i]] != '') 								// !!! nie zmieniać bo isset powoduje błąd i aktywuje warunek dla nstro = ''
	    if($_REQUEST['n'.$this->n[$i]] != $this->dodaj)
		  $this->tr[$i] = C::odbDane($_REQUEST['n'.$this->n[$i]]); 									//-jeśli jest nowa wartość dla list typu UB

	  if(substr($this->n[$i], -4, -1) == 'fot' || substr($this->n[$i], -5, -1) === 'plik')	//-dla pól plikowych odbiór poprzedniej nazwy pliku
  	  {

   	if($_POST['n'.$this->n[$i]]) $this->tr[$i]  = C::odbDane($_POST['n'.$this->n[$i]]);	//-odczyt nazwy pliku

		if(isset($_POST['w_'.$this->n[$i]]))
		 if($_POST['w_'.$this->n[$i]])
		  $this->kadr[$i] = true;																				//-odczyt znacznika kadrowania

	   Test::trace('parametr kadrowania', $this->kadr[$i]);


		if(isset($_POST['kadr_img'.$i]))
		 if($_POST['kadr_img'.$i])
		  $this->pkadr[$i] = explode(',', $_POST['kadr_img'.$i]);									//-tablica kadrowania


		 $this->exSize = C::odbDane($_POST['size_'.$this->n[$i]]);									//-pole skalowania indywidualnego(ekstra) z priorytetem
  	  }																												//-wyższym od definicji w def_tab
																														//-wartość nie jest przechowywana w tabeli !!!
     if(isset($this->nttt[$i]['W']))
	   if($this->nttt[$i]['W'] && $this->tr[$i])
		 $wal_war[$this->n[$i]] = $this->nttt[$i]['W'];													//-tablica walidacji warunkowej : W BUDOWIE !!!


	  if($wal = $this->walidPola($i, $wal_war)) $this->ns[$i] = $wal;								//-walidacja pola od strony serwera
	  //tablica: walidacji pól -> tablica błędów;

	  unset($wal);

	  //-dla pól daty rozpoznawanych po nazwie pola -> odbiór i konwersja daty do formatu dd-mm-rrrr ---

	  if(stristr($this->nt[$i], 'DATE'))
  	  {
   	if(isset($_POST[$this->n[$i]]))
	 	 $this->tr[$i] = $this->kdata(C::odbDane($_POST[$this->n[$i]]),1);
		else
	    $this->tr[$i] = C::get('datetime_teraz');
  	  }

	  if($this->tr[$i] == '' || !is_numeric($this->tr[$i]))
	  {
	   if(stristr($this->nt[$i], 'INT') || stristr($this->nt[$i], 'FLOAT')) $this->tr[$i] = '0';
		//-puste pola dla wartości typu integer lub typu float zastępujemy zerem
	  }

  	  $i++;
	 }
	}



   if(self::zawTab($this->ns)) $this->a = 'formu'; 					//-jeśli walidacja negatywna - ponowne otwarcie formularza do edycji danych

	switch($this->a)
	{
	 case 'dodaj': case 'zmien':												//-dodanie lub modyfikacja rekordu w bazie

	  $iq = $this->addOrChange();												//-tu by się przydało jakieś zwrotne info o ewentualnych błędach


	  if(self::zawTab($this->ns) || $this->kadr)			  				//-jeśli jest plik o negatywnej walidacji lub do kadrowania to powrót do formularza
	  {
	   if($this->kadr) $_SESSION['id-cms-err'] = 'kadr_start';		//-przewija do zdjęcia które ma być kadrowane;

		$this->a ='formu';
	  }
	  else
	  {

	   if($_SESSION['us_zalog']['goto'] && $this->userSetp)
		{
		 exit(__METHOD__);
		}
		elseif($this->userForm)
		{
		 return;
		}
		else
		{

		 if($_SESSION['backlink'])
		 {

		  $mySkok = $_SESSION['backlink'];

		  $_SESSION['backlink'] = '';
		  $_SESSION['backlink_fix'] = '';

		  S::myHeaderLocation($mySkok);

		 }



		}
	  }

	 break;

	 case 'lista':
		$this->lista();
	 break;

	 case 'pokaz':
	   $this->pokaz(); 										//-podglad pojedynczego rekordu
	 break;

	 case 'kasuj':
	  $this->kasuj(); 										//-usuwanie rekordu w bazie :: nn konieczne bo niesie informacje o katalogu gdzie są pliki

	  /* jeśli jest parametr strony powrotu to powrót do strony */

	  if($_SESSION['backlink'])
		S::myHeaderLocation($_SESSION['back']);
	  else
	   $this->lista();

	 break;

	 case 'edycja':
	  $this->edycja(); 										//-edycja rekordu
	 break;

    case 'test':
	  $this->edycja(true); 									//-wymuszona walidacja rekordu
	 break;
	}

	//-dodatkowe opcje w menu -> DODAJ REKORD i LISTA OGÓLNA

	if($this->t && !$this->userSet)
	{

 	 $this->z = '
   <form class=\'form_tab\' action=\'cms.cmsl\' method=\'post\'>
    <input type=\'hidden\' name=\'a\' value=\'lista\' />
    <input type=\'hidden\' name=\'t\' value=\''.$this->t.'\' />
    <input type=\'submit\' value=\'lista ogolna\' />
   </form>

   <form class=\'form_tab\' action=\'cms.cmsl\' method=\'post\'>
    <input type=\'hidden\' name=\'a\' value=\'formu\' />
    <input type=\'hidden\' name=\'t\' value=\''.$this->t.'\' />
    <input type=\'submit\' value=\'dodaj rekord\' />
   </form>

	<p id=\'tab_info\'>Aktywna Tabela -> <span>'.C::korektaDoMenu2($this->t).'</span>&nbsp;&nbsp;</p>

	<div>'.$this->z.'</div>';

	unset($lok_adz);

	}

 /* formularz uniwersalny */

 if($this->a == 'formu')
 {
  //$minut = mktime()+60*$minut; 																			//-opóźnienie publikacji

  $data = isset($_POST['data'])?C::odbDane($_POST['data']):C::get('datetime_teraz');		//-data dla pól data

  if($this->id)																									//-jeśli jest id to edycja rekordu
  {
   $akcja = 'zmien';
	$guzik  = 'popraw';
  }
  else
  {
   $akcja = 'dodaj';

	if($this->formSet)
	 $guzik  = _GUZIK_FORM;
	else
    $guzik  = 'dodaj';
  }

  $parJava = '';  // to jest raczej zbędne - nigdzie nie jest wykorzystane !!! - potestować 2013-06-08


  if($this->userSetp)
  {
  	if($_SESSION['us_zalog']) $user = '
 <input type=\'hidden\' name=\'user\' value=\''.md5($_SESSION['us_zalog']['user']).'\' />';

	$action = C::get('akcja').'.html';
	$idForm = 'user_main_form';
  }
  else
  {
   $user = '';
   $action = 'cms.cmsl';
	$idForm = 'cms_main_form';

	$this->x .= '
<p>-> typ formularza -> '.$guzik.' dane -> tabela -> '.$this->t.'</p>';
  }


  $this->x .='
<form id=\''.$idForm.'\' name=\'wpda\' action=\''.$action.'\' method=\'post\' enctype=\'multipart/form-data\'>
 <input type=\'hidden\' name=\'t\' value=\''.$this->t.'\' />
 <input type=\'hidden\' name=\'id\' value=\''.$this->id.'\' />
 <input type=\'hidden\' name=\'MAX_FILE_SIZE\' value=\''.C::get('con_ropl', false).'\' />
 <input type=\'hidden\' name=\'send\' value=\''.uniqid().'\' />
 <input type=\'hidden\' name=\'a\' value=\''.$akcja.'\' />'.$user.$parJava;


  if(self::zawTab($this->ns)) $this->x .= '
	<p id=\'error\'>Błędy w formularzu, popraw i wyślij ponownie</p>';				//-komunikat o błędach walidacji

  unset($parJava, $user, $action, $idForm);

  $i=3;

  while(isset($this->n[$i]))													//-tworzenie pol formularza w petli po wszystkich polach rekordu
  {
	$tmp_pol = '';

	$this->korektaDoForm($i, $wdo_t);

	list($dym, $help, $fxt, $wa, $stylp, $claasPola, $dlupo, $opis, $onlyRead, $start_sektor, $end_sektor) = $this->formatPola($i);

	//Test::tracer(__FILE__, __METHOD__, __FUNCTION__, __LINE__, $i, $opis);


	if($this->userSetp) unset($stylp); 								// dla Usera i Forms obowiązują style z css

	if(!$wa || $fxt)
	{
	 //-$wa = wskażnik że pole zostało wygenerowane jako niestandardowe
	 //-jeśli $wa to $fxt zawiera kod
	 //-formatowanie pól na podstawie znaczników opisu

	 if($onlyRead)
	 {
	  $onlyRead = 'readOnly';			//-znacznik pola onlyRead
	  $classOR = ' stReadOnly';		//-klasa dla pola onlyRead
	 }
	 else
	 {
	  $onlyRead = '';
	  $classOR = '';
	 }

	 if($help) $helpp = '<i class=\'hel\' title=\''.$help.'\'></i>'; else $helpp = '';		//-$help : potrzebne dalej

	 if(C::get('ja') && !$this->userSetp)
	 $helpp = '<span class=\'dym1\' title=\''.$this->n[$i].'->'.$this->nt[$i].'->'.$dym.'\'></span>'.$helpp;
	 //-komunikat testowy w dymie

    $tmp_pol .= '
	<label class=\'norm\' for=\'inpp'.$i.'\'>'.$helpp.$this->nttt[$i][0].'</label>'.$fxt;      //-fxt wartość generowana przez metodę formatPola

	 //-data
	 if(substr($this->nt[$i], 0, 4) == 'DATE' && !$wa)
	 {
	  $wa = 1;

     if(isset($this->nttt[$i]['D']))
	  {
	   if($this->nttt[$i]['D'] === 'hidden' && $this->userSet)
	    $tmp_pol = $this->poleData($i, $onlyRead);
		else
	    $tmp_pol .= $this->poleData($i, $onlyRead);
	  }

	 }

	 /*
	 if($this->nttt[$i]['W'] && $opis)
	 {
	  $wa = 1;
	  $tmp_pol .= $opis;
	 }
	 */

	 //-pliki
	 if(!C::get('noFilesFile'))
     if(substr($this->n[$i], -4, -1) == 'fot' || substr($this->n[$i], -5, -1) == 'plik')
     {
	   $wa = 1;
	   $tmp_pol .= $this->polePlik($i);
	  }


	 if(substr($this->n[$i] ,-4 , -1) === 'poz') 													//-pozycjonowanie plików graficznych jpg, png, giv
	 {
	  $wa = 1;

	  $tmp_pol .= $this->pozycja($this->n[$i], $this->tr[$i], $this->nttt[$i]['H']);	//-[ , , opisa pola pozycja w chmurce]
	 }

    //-text area
    if($this->nt[$i] === "TEXT" || $dlupo >= $this->maxdl) 											//- max. dlugosc input
	 {
	  $wa = 1;

	  $lok_tit = 'Wpisz: '.$this->nttt[$i][0];

	  if($opis)	$lok_tit .= ' - '.strip_tags($opis);

	  if($help) $lok_tit .= ' ('.$help.')';

	  $tmp_pol .='
	<textarea class=\'liZnak'.$claasPola.'\' name=\''.$this->n[$i].'\' '.$stylp.' title=\''.$lok_tit.'\'
		alt=\''.$dlupo.'\'>'.$this->tr[$i].'</textarea> '; //.$opis;
	 }

    //-pozostale pola
    if(!$wa)
	 {																				//-$wa -> to wskaźnik, że pole zostało zdefiniowane wyżej lub w for_pol

	  $lok_tit = 'Wpisz: '.$this->nttt[$i][0];
	  if($opis)	$lok_tit .= ' - '.strip_tags($opis);
	  if($help) $lok_tit .= ' ('.$help.')';

     $tmp_pol .='
	<input class=\'liZnak'.$classOR.$claasPola.'\' type=\'text\' name=\''.$this->n[$i].'\' value=\''.$this->tr[$i].'\' '.$stylp.' title=\''.$lok_tit.'\' alt=\''.$dlupo.'\' '.$onlyRead.'/> '; //.$opis;
	 }

	 if($opis)
	 {
	  $tmp_pol .= $opis;
	 }

	 if($this->ns[$i]) $tmp_pol .= '
	<p class=\'alert\'>'.$this->ns[$i].'</p>';							//-jeśli jest komunikat błędu po walidacji pola lub pliku

	 unset($dym, $fxt, $stylp, $dlupo, $classOR, $onlyRead, $help, $helpp, $lok_tit); //$wa,
	}


	if($wa == 2) //-pola dostęne wyłącznie dla Admina klasy 10 = Vip = V
	{
	 $tmp_pol .='
	 <input type=\'hidden\' name=\''.$this->n[$i].'\' value=\''.$this->tr[$i].'\' />';
	}
	elseif(!$this->hidden && $tmp_pol)
	{
    $tmp_pol = '
	<div class=\'cms_pf\'>'.$tmp_pol.'
	</div>';
	}

	$i++; //-licznik pól

	$this->x .= $start_sektor.$tmp_pol.$end_sektor;

	unset($tmp_pol, $start_sektor, $end_sektor); //$this->hidden

  } //-end while - tworzenie pol formularza

  //-pole sterujace formularza

  if(!$this->userSet)
  {
   if($this->kopia)
	 $iq = ' checked=\'checked\'';
   else
	 $iq = '';

	  $cmsSter = '
  <div id=\'cms_kopia\'>
   <label for=\'cms_kop\'>
   <input type=\'checkbox\' id=\'cms_kop\' name=\'kopia\''.$iq.' /> duplikat</label>
   <label for=\'cms_kas\'>
   <input type=\'checkbox\' id=\'cms_kas\' name=\'kasuj\' /> kasuj</label>
  </div>
  <input type=\'reset\' value=\'wyczyść formularz\' />';

   $cmsSterId = 'cms';
  }
  else
   $cmsSterId = 'user';





    $this->x .='
 <div id=\''.$cmsSterId.'_for_ster\'>'.$cmsSter.'
  <input type=\'submit\' value=\''.$guzik.'\' />
 </div>
</form>';

    unset($guzik, $iq, $this->ns, $cmsSter, $cmsSterId);
   }
  }
 }


 /**
 *
 * prezentacja pojedyńczego rekordu tabeli
 *
 */

 private function pokaz()
 {

  try
  {
   $tab = "SELECT * FROM $this->t WHERE {$this->n[2]} = $this->id";

	if($tab = DB::myQuery($tab))
   {
    $ta  = mysqli_fetch_array($tab);

	 if($this->t == C::get('tab_admini') && $ta['admin_stat'] > $_SESSION['admin']['status'])
	 {
	  $this->x .= '<p>Próba dostepu do danych bez koniecznych uprawnień!</p>';

	  return;
	 }

    $id = $ta[$this->n[2]];

    $i=3;
    while(isset($this->n[$i]))
    {
	  $fxt = 0;
	  $img = $O = '';

	  foreach($this->nttt[$i] as $key => $val)
      switch(trim($key))
	   {
	    case 'V': if(!C::get('ja')) $fxt = 1; break; 													//-pole widoczne tylko dla VIP'a

	 	 case 'A': if(!C::get('jo')) $fxt = 1; break; 													//-pole widoczne tylko dla administratora

	 	 case 'W': 																									//-pole wyboru tak albo nie
		  if($ta[$this->n[$i]])
		   $ta[$this->n[$i]] = 'TAK';
		  else
		   $ta[$this->n[$i]] = 'NIE';
	 	 break;

		 case 'O':
		  $O = $val;
		 break;

		 case 'Y': // sorhurt !!
 			//-0 -> znacznik
			//-1 -> długość pola ( format )
			//-2 -> tabela
			//-3 -> pole wiążące
			//-4... -> pola wyświetlane na liście

		   $npn = $np[4];

			if($np[5]) $npn .= ', '.$np[5];
			if($np[6]) $npn .= ', '.$np[6];
		   try
			{
			 $tab2 = mysql_query("SELECT $npn FROM $np[2] WHERE $np[3] = '{$ta[$this->n[$i]]}'");	//-odczyt danych z tabeli zwiazanej lub tej samej $np[3]

   		 if(!$tab2)
	 		  throw new Exception(mysql_error());
			 else
   		 {
     		  $tb = mysqli_fetch_array($tab2, MYSQL_ASSOC);

			  if($tb[$np[5]]) $tb[$np[5]] = ', '.$tb[$np[5]];
			  if($tb[$np[6]]) $tb[$np[6]] = ', '.$tb[$np[6]];

			  $ta[$n[$i]] = trim($tb[$np[4]].$tb[$np[5]].$tb[$np[6]]);
			 }
			}
			catch(Exception $e)
			{
			 $this->x .=  C::debug($e, 0);
			}

	  		unset($npn);
       break;

	    case '!':																							//-pole hasła
		  if(!C::get('ja')) $ta[$this->n[$i]] = 'xxxxxxxxxxxxxxxxxxxx';
		 break;

      }


     if(!$fxt)																								//-warunkowe wyświetlanie danych
     {
      if(substr($this->nt[$i],0,4) == 'DATE') 													//-jeśli data to zmiana formatu na polski
       $ta[$this->n[$i]] = $this->kdata($ta[$this->n[$i]],1);


 	   if(substr($this->nt[$i],0,4) == 'TEXT')
	    $ta[$this->n[$i]] = preg_replace('/\r?\n/', '<br />', $ta[$this->n[$i]]);		//-uproszczone formatowanie tekstu

      //-wyświetlenie zdjęć	!!! wyeliminować C::get(fotyPath)
		//- && file_exists($this->nttt[$i]['path'].'m_'.$ta[$this->n[$i]])

      if(substr($this->n[$i],-4,-1)=='fot' && $ta[$this->n[$i]])
      {

	    if(file_exists($this->nttt[$i]['path'].$ta[$this->n[$i]]))
	    {
		  list($sze, $wys) = getimagesize($this->nttt[$i]['path'].'m_'.$ta[$this->n[$i]]);
		  $pref = '';
	    }
	    elseif(file_exists($this->nttt[$i]['path'].'m_'.$ta[$this->n[$i]]))
	    {
	  	  list($sze, $wys) = getimagesize($this->nttt[$i][path].'m_'.$ta[$this->n[$i]]);
		  $pref = 'm_';
		  $wys .= 'UWAGA! brak miniatury [m_]';
	    }

			$img = '
		<div class=\'tumbs\'>
		 <a href=\''.$this->nttt[$i]['path'].$ta[$this->n[$i]].'\' title=\'kliknij aby powiększyć :: rozmiar oryginalny: '.$sze.' x '.$wys.'\' target=\'_blannk\' >
		  <img class=\'thu\' src=\'./thumbnail.php?id=m_'.$pref.$ta[$this->n[$i]].':'.session_id().':100:100:t:k:f\' alt=\''.__FILE__.'\' />
		 </a>
		</div><p class=\'plik_name\'>'.$ta[$this->n[$i]].'</p>';

	     unset($sze, $wys, $pref);

		  $pole_wart = '';
       }
		 else
		  $pole_wart = '
			<p class=\'ppokaz\'>'.$ta[$this->n[$i]].'</p>';

	   if(substr($this->n[$i],-4,-1)=='poz' && $ta[$this->n[$i-1]])				//pozycja zdjecia zawsze wystepuje po zdjęciu i zawsze ma wartość
		 $fxl[$i-1] .= $this->pozycjaPokaz($ta[$this->n[$i]]);
	   else
       $fxl[$i] = '<label>'.$this->nttt[$i][0].' '.$O.'</label>'.$img.$pole_wart;
		}

		unset($pole_wart);

      $i++;
     }

	  unset($img, $O);

    //- HTML -

   $fx = '
	<div id=\'cms_pokaz\'>
	 <div class=\'pokaz\'>'.implode('
	 </div>
	 <div class=\'pokaz\'>', $fxl).'
	 </div>
	</div>';

   unset($fxl);
  //-przyciski do edycji rekordu

   $this->z .= '
	<div id=\'cms_pokaz_st\'>
	 <form action=\'cms.cmsl\' method=\'post\'>
	 	<input type=\'hidden\' name=\'t\' value=\''.$this->t.'\' />
	   <input type=\'hidden\' name=\'a\' value=\'kasuj\' />
	   <input type=\'hidden\' name=\'id\' value=\''.$ta[$this->n[2]].'\' />
	   <input class=\'del\' type=\'submit\' value=\'kasuj\' alt=\'napradę chcesz skasować, ta operacja jest nie odwracalna!\'/>
	 </form>
	 <form action=\'cms.cmsl\' method=\'post\'>
	   <input type=\'hidden\' name=\'t\' value=\''.$this->t.'\' />
	   <input type=\'hidden\' name=\'a\' value=\'edycja\' />
	   <input type=\'hidden\' name=\'id\' value=\''.$ta[$this->n[2]].'\' />
	   <input type=\'submit\' value=\'edytuj\'/>
	 </form>
	</div>';

   }
  }
  catch(Exception $e)
  {
	$this->x .=  C::debug($e, 0);
  }

  $this->x .= $fx;

  unset ($j, $k, $nw, $np, $tab, $ta, $fx, $fxt);
 }

 /*
 *
 * prezentacja pozycji zdjęcia względem teksty publikacji
 *
 */

 private function pozycjaPokaz($i)
 {
  $p = array_fill(0, 10, '');
  $p[$i] = ' class=\'wyb\'';

  return '
  <div class=\'cms_pozycja_pok\'>
   <a href=\'javascript: void(0);\' title=\'umieść wskaźnik nad danym polem pozycji zdjęcia, aby uzyskać podpowiedź\'>help</a>
	<b'.$p[0].' title=\'nad tekstem do lewej\'></b><b'.$p[1].' title=\'nad tekstem do środka\'></b><b'.$p[2].' title=\'nad tekstem do prawej\'></b>
 	<b'.$p[3].' title=\'z lewej strony tekstu\'>text</b><b class=\'text\'>text</b><b'.$p[5].' title=\'z prawej strony tekstu\'>text</b>
 	<b'.$p[4].' title=\'z lewej strony tekstu<br>kolejne zdjęcie w pionie\'>text</b><b class=\'text\'>text</b><b'.$p[6].' title=\'z prawej strony tekstu <br>kolejne zdjęcie w pionie\'>text</b>
 	<b'.$p[7].' title=\'pod tekstem do lewej\'></b><b'.$p[8].' title=\'pod tekstem do środka\'></b><b'.$p[9].' title=\'pod tekstem do prawej\'></b>
  </div>';

 }

 /**
 *
 * wczytanie danych rekordu z bazy, celem wpisania do formularza przed edycją
 *
 * 2012-11-17 : dodanie parametru test, i nst = wskażnik, wywołania wymuszonego do modyfikacji sposobu walidacji pól, które nie są zapisywane w bazie
 * w szczególności pole weryfikacji hasła
 * 2011-04-26
 *
 */

 private function edycja($test = false)
 {
  if($test) $this->nst = true;  //-patrz opis wyżej

  try
  {
   $tab = "SELECT * FROM $this->t WHERE {$this->n[2]} = $this->id";

	if($tab = DB::myQuery($tab))
   {
    $ta  = mysqli_fetch_array($tab);

    $i=3;
    while(isset($this->n[$i]))
    {
     $this->tr[$i] = $ta[$this->n[$i]]; 									//-n[i] -> nazwy pól z tabeli systemowej

	  if(isset($this->nttt[$i]['!']))
	   if($this->nttt[$i]['!'])
		 $this->pasd = $this->tr[$i];			//-hasło do weryfikacji

	  if($test)
	  {
	   if($wal = $this->walidPola($i)) $this->ns[$i] = $wal;

		unset($wal);
	  }

     $i++;
    }

    $this->a = 'formu';
   }
  }
  catch(Exception $e)
  {
   unset($this->a);

   $this->x .=  C::debug($e, 0);
  }

  unset($tab, $ta, $i, $n, $test, $this->nst);
 }

 /**
 *

  1. odczytuje z tabeli rekord identyfikowany po id
  2. przeszukuje wszystkie pola w poszukiwaniu tych które zawierają nazwy plków
  3. odnalezione nazwy zapisuje w tablicy jeśli nie są puste
  4. kasuje pliki zapisane w utworzonej tablicy

 */

 private function kasuj() //- :: 2011-004-26
 {

  $fx .= '
		<p>Kasowanie</p>';

  try
  {
   $tab = "SELECT * FROM $this->t WHERE {$this->n[2]} = $this->id";

	if($tab = DB::myQuery($tab))
   {
    $ta = mysqli_fetch_array($tab);

    $i = 3;

    while($this->n[$i]) 																//-pętla po wszystkich polach
    {
     if(substr($this->n[$i],-4,-1)=='fot' && $ta[$this->n[$i]]) 	 		//-jeżeli są pola plikowe z zawartością to musi być plik
		foreach($this->nttt[$i]['th'] as $k => $v)
		 if($k != 'L_')
		  $this->x .= $this->kaspl($this->nttt[$i]['path'].$k.$ta[$this->n[$i]]);
		 else
		  $this->x .= $this->kaspl($this->nttt[$i]['path'].$ta[$this->n[$i]]);

     $i++;
    }
   }

   $tab = "DELETE FROM $this->t WHERE {$this->n[2]} = $this->id";			//-skasowanie rekordu w tabeli

	if(DB::myQuery($tab))
 	 if(!$this->userSet) $this->x .= '
 	<p class=\'cms_ok\'>rekord usunięty prawidłowo</p>';
  }
  catch(Exception $e)
  {
	$this->x .=  C::debug($e, 0);
  }

  unset($tab, $ta, $i);
 }

 /**
 *
 * prezentacja listy rekordów wybranej tabeli :: 2011-04-26
 *
 *
 */

 protected function lista()
 {
  Test::tracer(__FILE__, __METHOD__, __FUNCTION__, __LINE__, 'lista', $this->t);

  Test::tracer(__FILE__, __METHOD__, __FUNCTION__, __LINE__, 'listab', $this->n);

  if($this->t)
  {
   $lista = new RekTabeli($this->n, $this->nttt, $this->t);

   //list($yy, $xx) = $lista->wynik();

	list($this->z, $this->x) = $lista->wynik();

   //$this->x .= $xx;
   //$this->z = $yy;

   unset($lista, $xx, $yy);
  }
 }

 /**
 *
 *  graficzna prezentacja pozycji dla zdjeć
 *
 */

 private function pozycja($i, $ch, $opis = '')
 {
  if($ch == '') $ch = 1;

  $wt = '';

  for($j=0; $j<10; $j++)
  {
   $k = $j;
	if($j == 4) $k = 5;
	if($j == 5) $k = 4;

   if($k == $ch)
	  $check = ' checked=\'checked\'';
	else
	  $check = '';

   $wt .= '
	 <input type=\'radio\' name=\''.$i.'\' value=\''.$k.'\''.$check.'/>';

   if($k == 3 || $k == 4)
	$wt .= '
	 <b></b>';
  }

  unset($i, $j, $k, $check, $ch);

  return '
	<div class=\'cms_poz\' title=\''.$opis.'\'>'.$wt.'
	</div>';
 }

 /**
 *
 *
 */

 function __destruct()
 {
  //unset();
 }

}
?>