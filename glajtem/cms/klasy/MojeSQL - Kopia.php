<?
defined('_CMSPATH') or header('location: http://'.$_SERVER['HTTP_HOST']);

/** ./cms/klasy/MojeSQL.php
*
* CMS - OBSŁUGA TABEL - VIP : ver.1.4
*
* 2012-11-23 -> zakodowano przesyłane odnośnikami dane
*
* 2012-09-25 -> dodano komunikat błędu dla różnych nazw pól tabeli
* 2011-11-29 -> poprawiony błąd zapisu pojedyńczej tabeli -NIE DALEJK JEST BŁĄD !!!!!!
*
* 2010-11-15 -> 2011-03-04 -> 2011-05-10 -> 2011-11-28

* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -- 2008-09-12-- UTF-8
* skrypt nie jest darmowy!!
* aby legalnie wykorzystywać skrypt należy posiadać wykupioną licencję lub sgodę autora
*
* dane wejściowe: C::get('akt_baza')

*

*/

class MojeSQL extends Common
{

 private $fx = ''; 																		//-wizualny wynik dzialania klasy
 private $fz = '';

 private $a = '';																			//-akcja
 private $arch = '';																		//-wybrane archiwum do dearchiwizacji
 private $t = '';																			//-wybrana tabela

 private $tap;																				//-definiecje mySQL tabel
 private $tan;																				//-definicje na potrzeby formatowania formularzy i walidacji

 private $sqlerror = array();		//-tablica komunikatów błędów


 function __construct()
 {

  if($this->upraw(9))
  {

   if(isset($_REQUEST['cod']))
   {

	 $cod = S::linkDecode(C::odbDane($_REQUEST['cod']));

	 $cod = explode(',', $cod);

	 if($cod[0])
	 {
	  $_SESSION['id-err'] = $cod[0];
	 }
	 else
	  $_SESSION['id-err'] = false;


	 $this->t  = $cod[1];

	 $this->id = $cod[2];

	 $this->a  = $cod[3];

   }
   else
   {
    $this->a = isset($_REQUEST['a'])?C::odbDane($_REQUEST['a']):''; 					//-akcja

    $this->arch = isset($_REQUEST['arch'])?C::odbDane($_REQUEST['arch']):false; 	//-wybrane archiwum do dearchiwizacji

    $this->t = isset($_REQUEST['t'])?C::odbDane($_REQUEST['t']):false; 				//-tabela

   }

   require_once 'hidden/wersja'._EX;
   require_once 'hidden/def_tab'._EX;  							 							//-definicja tabel

   if($this->t) $this->fx = '
		<p class=\'wska\'>wybrana tabela: <b>'.$this->t.'</b> | nowa wybrana akcja: <b>'.$this->a.'</b></p>';


	$this->menu();		//-menu działań na tabelach

   switch($this->a)
   {
    case 'us' 		: $this->delTable();   				break;
 	 case 'cz'  	: $this->clearTable();  			break;
 	 case 'za' 		: $this->delAndCreateTable();  	break;
 	 case 'se' 		: $this->saveTable();  				break;		//-zapis do pliku pojedyńczej tabeli
 	 case 'lo' 		: $this->loadTable();  				break;
 	 case 'po' 		: $this->pokazTabela();  			break;
	 case 'po2' 	: $this->pokaz();  					break;
 	 case 'zw' 		: $this->createAllTables();  		break;
 	 case 'de' 		: $this->defAllTables();  			break;
 	 case 'od' 		: $this->refreschTables();  		break;
 	 case 'sew' 	: $this->saveAllTables();			break;		//-zapis do plików,  wszystkich tabel ( bez tabeli systemowej )
 	 case 'low' 	: $this->loadAllTables(); 			break;		//-odbudowa wszystkich tabel z plików archiwum ( bez tabeli systemowej )
 	 case 'usw' 	: $this->delAllTables(); 			break;
   }



  }

 }

 /** ./cms/klasy/MojeSQL.php
 *
 * menu systemowe
 *
 */

 private function menu() //-główne menu dla operacji na tabelach bazy danych :: ok. 2011-04-06
 {
  $ff = '';
  $del = false;
  $bts = false;

	if($ttt = $this->tablesList())
	{

	 if($ttd = array_diff($ttt, array_keys($this->tan)))
	 {
	  $this->sqlerror[] =  'W bazie istenieją tabele, nie zdefiniowane w config_sql!<br>Należy skasować wszystkie tabele i założyć ponownie czyste tabele.';

	  $bts = true;
	  $del = true;

	  Test::tracer(__FILE__, __METHOD__, __FUNCTION__, __LINE__, 'ttt', $ttd);

	 }

	 if($ttd = array_diff(array_keys($this->tan), $ttt))
	 {
	  $ff .= 'Istnieją definicje tabel, które nie zostały jeszcze założone w aktualnej bazie!';
	  $ff .= '<br>Użyj odnośnika <b>ZW->T</b> (załóż wszystkie tabele)';
	  $ff .= '<br>Dotyczy tabel:';

     foreach($ttd as $wart)
	   $ff .= '
	  <p>'.$wart.'</p>';

	 }


	}
	else
	{
	 $this->sqlerror[] =  'Brak założonych tabel w aktualnej bazie danych.<br>Użyj odnośnika <b>ZW->T</b> (załóż wszystkie tabele).';
	 $bts = true;
	}


	if(!$this->sqlerror)
	 $this->fx = $this->TablesMenu($this->t, 'mysql').$this->fx;
	else
	{
	 foreach($this->sqlerror as $wart)
	  $ff .= '
	  <p>'.$wart.'</p>';

	}

   $this->fx .= $ff;

	if(!$bts)
	 $this->fz = '
	<a class=\'tab_ico bezp\' href=\''.S::linkCode(array(0,0,0,'od')).'+mysql.cmsl\' title=\'odśwież reguły w tabeli systemowej\'>O->TS</a>';
	else
	 $this->fz = '
	<a class=\'tab_ico noak\' title=\'Brak tabeli systemowej\'>O->TS</a>';

	$this->fz .= '
	<a class=\'tab_ico bezp\' href=\''.S::linkCode(array(0,0,0,'de')).'+mysql.cmsl\' title=\'pokaż definicje wszystkich tabel\' alt=\'pokaż definicje wszystkich tabel - wykonać?\'>DW->T</a>';


    if($this->t && !$bts) $this->fz .= '
	<a class=\'tab_ico bezp\' href=\''.S::linkCode(array(0,$this->t,0,'po')).'+mysql.cmsl\' title=\'pokarz definicje wybranej tabeli\' >PD->T</a>
	<a class=\'tab_ico bezp\' href=\''.S::linkCode(array(0,$this->t,0,'po2')).'+mysql.cmsl\' title=\'pokazuje dane z wybranej tabeli\' >PZ->T</a>
	<a class=\'tab_ico ostr\' href=\''.S::linkCode(array(0,$this->t,0,'lo')).'+mysql.cmsl\' title=\'zapisz do tabeli dane z pliku archiwum\' alt=\'załaduj dane z pliku - wykonać?\'>ZP->T</a>';
    else
     $this->fz .= '
	<a class=\'tab_ico noak\' title=\'pokarz definicje wybranej tabeli - Brak aktywnej tabeli!\'>PD->T</a>
	<a class=\'tab_ico noak\' title=\'pokazuje dane z wybranej tabeli - Brak aktywnej tabeli!\'>PZ->T</a>
	<a class=\'tab_ico noak\' title=\'zapisz do tabeli dane z pliku archiwum - Brak aktywnej tabeli!\'>ZP->T</a>';


	 if(!$bts) $this->fz .= '
	<a class=\'tab_ico ostr\' href=\''.S::linkCode(array(0,0,0,'low')).'+mysql.cmsl\' title=\'do wszystkich tabel wczytuje dane z archiwum\' alt=\'wczytaj do tabel dane z wszystkich plików archiwum - wykonać?\'>ZWP-WT</a>';
	 else
	  $this->fz .= '
	<a class=\'tab_ico noak\' title=\'Brak tabeli systemowej\'>ZWP-WT</a>';


    if($this->t && !$bts) $this->fz .= '
	<a class=\'tab_ico ostr\' href=\''.S::linkCode(array(0,$this->t,0,'se')).'+mysql.cmsl\' title=\'zapisuje do pliku archiwum dane w wybranej tabeli\' alt=\'zapisz dane z tabeli do pliku - wykonać?\'>ZT->P</a>';
    else
     $this->fz .= '
	<a class=\'tab_ico noak\' title=\'zapisuje do pliku archiwum dane w wybranej tabeli - Brak aktywnej tabeli!\'>ZT->P</a>';


    if(!$bts) $this->fz .= '
	<a class=\'tab_ico ostr\' href=\''.S::linkCode(array(0,0,0,'sew')).'+mysql.cmsl\' title=\'zapisuje wszystkie tabele do plików archiwum\' alt=\'zapisz wszystkie tabele do plików archiwum - wykonać?\'>WT->P</a>';
	 else
	  $this->fz .= '
	<a class=\'tab_ico noak\' title=\'Brak tabeli systemowej\'>WT->P</a>';


    if($this->t) $this->fz .= '
	<a class=\'tab_ico uwag\' href=\''.S::linkCode(array(0,$this->t,0,'us')).'+mysql.cmsl\' title=\'kasuj wybraną tabelę\' alt=\'kasuje wybraną tabelę - wykonać?\'>K->T</a>
	<a class=\'tab_ico uwag\' href=\''.S::linkCode(array(0,$this->t,0,'za')).'+mysql.cmsl\' title=\'kasuj wybraną tabelę i zakłóż ją na nowo\' alt=\'Kasuj wybraną tabelę i zakłada na nowo - wykonać?\'>K->T->Z</a>
	<a class=\'tab_ico uwag\' href=\''.S::linkCode(array(0,$this->t,0,'cz')).'+mysql.cmsl\' title=\'kasuj dane z wybranej tabeli\' alt=\'kasuje dane z wybranej tabeli - wykonać?\'>KD->T</a>';
    else
     $this->fz .= '
	<a class=\'tab_ico noak\' title=\'kasuj wybraną tabelę - Brak aktywnej tabeli!\'>K->T</a>
	<a class=\'tab_ico noak\' title=\'kasuj wybraną tabelę i zakłóż ją na nowo - Brak aktywnej tabeli!\'>K->T->Z</p></a>
	<a class=\'tab_ico noak\' title=\'kasuj dane z wybranej tabeli - Brak aktywnej tabeli!\'>KD->T</a>';

    $this->fz .= '
	<a class=\'tab_ico bezp\' href=\''.S::linkCode(array(0,0,0,'zw')).'+mysql.cmsl\' title=\'zakłóż wszystkie tabele, które jeszcze nie istnieją\' alt=\'załóż wszytkie tabele, które jeszcze nie istnieją - wykonać?\'>ZW->T</a>';

	 if(!$bts || $del) $this->fz .= '
	<a class=\'tab_ico uwag\' href=\''.S::linkCode(array(0,0,0,'usw')).'+mysql.cmsl\' title=\'kasuj wszystkie tabele w bazie danych\' alt=\'kasuj wszystkie tabele - wykonać?\'>KW->T</a>';
	 else
	  $this->fz .= '
	<a class=\'tab_ico noak\' title=\'Brak tabeli systemowej\'>KW->T</a>';

 }

 /** ./cms/klasy/MojeSQL.php
 @
 *
 *
 *
 */

 private function pokazTabela()
 {

  if($this->upraw(10))
  {
   $this->fx .= '
	<p class=\'wska\'>podgląd zawartości tabeli : <b>'.$this->t.'</b></p>
	<p class=\'wska\'>definicja tabeli</p>';

   $this->fx .= $this->poDefTab($this->tap, $this->tan, $this->t);

   $this->pokaz();
  }

 }


 /** ./cms/klasy/MojeSQL.php
 *
 * kontrola uprawnień
 *
 */

 private function upraw($status)
 {
  if($_SESSION['admin_stat_tmp'] < $status)
  {
   $this->fx .= '
    <p class=\'error\'>Nie masz uprawnień do tej operacji!</p>';

	return false;
  }
  else
   return true;
 }



 /** ./cms/klasy/MojeSQL.php
 @
 * wyświetla zawartość tabeli :: ok. 2011-04-06
 *
 */

 private function pokaz()
 {

  if($this->upraw(10))
  {

   try
   {

	 if($tab = DB::myQuery("SELECT * FROM $this->t"))
    {
     $this->fx.= '
		<p class=\'wska\'>zawartość tabeli : <b>'.$this->t.'</b></p>';

     while($ta = mysql_fetch_assoc($tab))
      while(list($kl, $wart) = each($ta))
       $fxt.= '
		 <p>'.$kl.'<span class=\'aa\'>=></span><span class=\'bb\'>'.htmlspecialchars(substr($wart,0,200)).'</span></p>';

     if($fxt)
	   $this->fx .= $fxt;
     else
      $this->fx .= '<p class=\'error\'>tabela jest pusta</p>';
    }

   }
   catch(Exception $e)
   {
    $this->fx .= C::debug($e, 0);
   }

   unset($kl, $wart, $fxt, $tab, $ta);
  }
 }

 /** ./cms/klasy/MojeSQL.php
 *
 * wyświetla definicje wszystkich tabel w bazie :: ok. 2011-04-06
 *
 */

 private function defAllTables()
 {
  if($_SESSION['admin_stat_tmp'] < 10)
   $this->fx .= '
    <p class=\'error\'>Nie masz uprawnień do tej operacji!</p>';
  else
  {
   $this->fx.= '
		<p class=\'ods\'>definicje wszystkich tabel !</p>';

   foreach($this->tan as $kl => $def)
   {
    $this->fx.= '
		<p class=\'wska b t\'>'.$kl.'</p>';

  	 $this->fx .= $this->poDefTab($this->tap, $this->tan, $kl);
   }
   unset($kl, $def);

  }
 }

 /** ./cms/klasy/MojeSQL.php
 *
 * usuwa pojedyńczą tabelę z bazy : ok. 2011-04-06
 *
 */

 private function delTable()
 {

  if($_SESSION['admin_stat_tmp'] < 10)
   $this->fx .= '
    <p class=\'error\'>Nie masz uprawnień do tej operacji!</p>';
  else
  {
   $fx .= '
	<p class=\'wska\'>usuwam tabelę : <b>'.$this->t.'</b></p>';

   $this->fx .= $this->kasTabele($this->t);
 																			//-automatycznie odświeżenie tabeli systemowej
   $this->refreschTables();
  }

 }

 /** ./cms/klasy/MojeSQL.php
 *
 * -kasuje wszystkie tabele w bazie :: ok. 2011-04-06
 *
 */

 private function delAllTables()
 {
  if($_SESSION['admin_stat_tmp'] < 10)
  	$this->fx .= '
	 <p class=\'error\'>Nie masz uprawnień do tej operacji!</p>';
  else
  {

	$this->fx = '
		<p class=\'wska\'>kasuje wszystkie tabele z aktywnej bazy!!</p>';

	$aktbaza = C::get('akt_baza');
	$prefix = C::get('db_prefix');

   try
   {

	 if($tab = DB::myQuery('SHOW TABLES'))
     while($ta = mysql_fetch_assoc($tab))
	  {
	   $liTab = 0;

      if(substr($ta['Tables_in_'.$aktbaza], 0 ,strlen($prefix)) == $prefix || $prefix == '')
	   {
	    $t = $ta['Tables_in_'.$aktbaza];

	    //if($t != C::get('tab_tab')) // usunięte 2013-02-05

	     $this->fx .= '
		 <p class=\'wska\'>Usuwam tabelę : <b>'.$t.'</b></p>';

	     $this->fx .= $this->kasTabele($t);

		  $liTab++;

	   }
	  }

	 if($liTab > 0)
	 {
	  $this->fx .= '<p>Ilość skasowanych tabel = '.$liTab.'</p>';

     //$this->fx .= $this->kasTabele(C::get('tab_tab'));						//-na koniec kasuje tabelę systemową
	 }


    unset($taba, $ta, $t, $liTab, $aktbaza);

   }
   catch(Exception $e)
   {
    $this->fx .= '<p class=\'error\'>nie można odczytać listy tabel!!</p>';
    $this->fx .=  C::debug($e, 0);
   }

  }
 }

 /** ./cms/klasy/MojeSQL.php
 *
 * zakłada wszystkie tabele :: ok. 2011-04-06
 *
 */

 private function createAllTables()
 {

  if($_SESSION['admin_stat_tmp'] < 10)
   $this->fx .= '
	 <div class=\'adm_error\'>Nie masz uprawnień do tej operacji!</div>';
  else
  {
   $this->fx ='
	<div class=\'adm_kombox\'>zakładam wszystkie tabele które jeszcze nie istnieją!</div>';

 	foreach($this->tan as $kl => $dta)
  	 if($kl != '')
     $this->fx .= $this->zal_tab($kl, $dta, $this->tap[$kl]);
  	 else
     $this->fx.='
		<div class=\'adm_error\'>istnieje definicja tabeli, która nie ma przydzielonej nazwy w config.php!</div>';

  //-[nazwa tabeli][definicja tabeli][definicja pól tabeli][definicja tabeli systemowej][nazwa tabeli systemowej]---

  	unset($kl, $dta);

  	$this->refreschTables();						//-odświerza tabelę systemową
  }

 }

 /** ./cms/klasy/MojeSQL.php
 @
 */

 private function clearTable() 				//-czyści tabelę z danych :: ok. 2011-04-06
 {
  if($_SESSION['admin_stat_tmp'] < 10)
	 $this->fx .= '
	 <p class=\'error\'>Nie masz uprawnień do tej operacji!</p>';
  else
  {

  $this->fx .= '
	<p class=\'ods\'>kasuję dane z tabeli : <span>'.$this->t.'</span></p>';

  //include 'sql/kasplik.php'; //-kasowanie plików powiązanych z tabelą !!!!!!???????

  try
  {

	if(DB::myQuery("TRUNCATE TABLE $this->t"))
    $this->fx .= '
	 <p class=\'ok\'>tabela wyczyszczona prawidłowo.</p>';

  }
  catch(Exception $e)
  {
   $this->fx .= '<p class=\'error\'>Tabela nie została wyczyszczona!</p>';
   $this->fx .=  C::debug($e, 0);
  }

  unset($tab);
  }
 }

 /** ./cms/klasy/MojeSQL.php
 *
 * if($a == za)
 * zakłada tabelę, którą pierw kasuje 2010-02-28 ok. -> 2011-12-18
 */

 private function delAndCreateTable()
 {
  if($_SESSION['admin_stat_tmp'] < 10)
 	$this->fx .= '
	 <p class=\'error\'>Nie masz uprawnień do tej operacji!</p>';
  else
  {
   $this->fx = '
	<p class=\'ods\'>kasuję tabelę i zakładam ponownie! <b class=\'ok b\'>'.$this->t.'</b></p>';

   $this->fx .= $this->kasTabiZal($this->t, $this->tan, $this->tap , $this->tan[C::get('tab_tab')], C::get('tab_tab'), C::get('akt_baza'));

   $this->refreschTables();						//-na koniec odświerza tabelę systemową
  }
 }

 /** ./cms/klasy/MojeSQL.php
 @
 * odświerza tabelę systemową - ok. 2011-04-06 -> 2011-12-18
 * 1. usunąć tabelę
 * 2. założyć od nowa
 * 3. wczytać definicje tabel
 *
 * pozwala to modyfikować definicję tabel bez utraty danych zapisanych wcześniej do pliku
 * jeśli to konieczne dane w pliku mozna zmodyfikować tak aby mogły być wczytane do zmodyfikowanej tabeli
 */

 private function refreschTables()
 {
  $tabsys = C::get('tab_tab');

  $this->fx.= '
	<div class=\'adm_kombox\'>odświerzam tabelę systemową</div><br />';

  if($this->isTable($tabsys))  // jeśli jest tabela systemowa
  {

   try
   {
	 DB::myQuery("DROP TABLE ".$tabsys);
   }
   catch(Exception $e)
   {
    $this->fx .= C::debug($e, 0);
   }

  }
  else
   $this->fx .= '
	<div class=\'adm_error\'>
		<p class=\'error\'>Nie można usunąć tabeli systemowej, tabela nie istnieje!!</p>
		<p class=\'error\'>'.__FILE__.' -> '.__CLASS__.' -> '. __METHOD__.' -> '. __FUNCTION__.' -> '.__LINE__.'</p>
	</div>';

  try
  {

   if(DB::myQuery("CREATE TABLE ".$tabsys.$this->tan[$tabsys]))		//-zakłada tabelę systemową na nowo

    $aktb = C::get('akt_baza');
	 $pref = C::get('db_prefix');
	 $fxx = '';

	  if($tab = DB::myQuery('SHOW TABLES'))
      while($ta = mysql_fetch_assoc($tab))
		if(substr($ta['Tables_in_'.$aktb], 0, strlen($pref)) == $pref || $pref == '')
       foreach($ta as $p)
        if($this->tap[$p]) 																						//-tylko te tabele, które mają definicje pól
	      $fxx .= $this->zapdt($tabsys, $this->tan[$p], $this->tap[$p], $p);						//-zapis definicji pól do tabeli systemowej

	  if(!$fxx)
	   $this->fx .= '
		 <p class=\'wska b\'>Tabela odświerzona prawidłowo.</p>';
	  else
	   $this->fx .= '
		<div class=\'adm_error\'>'.$fxx.'</div>';


  }
  catch(Exception $e)
  {
   $this->fx .= '
	  <div class=\'adm_error\'>
		<p class=\'error\'>Nie można założyć tabeli systemowej !!</p>
		<p class=\'error\'>'.__FILE__.' -> '.__CLASS__.' -> '. __METHOD__.' -> '. __FUNCTION__.' -> '.__LINE__.'</p>
	  </div>';

   $this->fx .= C::debug($e, 0);
  }

  unset($tab, $ta, $p, $fxx, $aktb, $pref, $tabsys);
 }

 /** ./cms/klasy/MojeSQL.php
 @
 * zapis pojedyńczej tabeli do pliku
 * w nowym katalogu = archiwum
 *
 * if($a == se) //-zapisuje do lokalnego pliku, dane z tabeli 2010-03-06 ok.
 *
 */

 private function saveTable()
 {

  if($this->upraw(10))
  {

   $this->fx = '
		<p class=\'wska\'>Zapisuje do pliku dane z tabeli : <b>'.$this->t.'</b></p>';

   $cmsTStart = microtime();

   $this->fx .= $this->zapDoPliku($this->t, $this->tan[$this->t], $this->tap[$this->t], date('Ymd_His', time()).'/');
   //-ostatni parametr to nazwa aktualnego katalogu tworzonewgo archiwum

   $this->fx .= '
 		<p class=\'czas\'>Czas zapisu : '.$this->czasOper($cmsTStart).'</p>';

   unset($cmsTStart);

	//$this->kasujArchiwum(); - to może spowodować utratę archiwum ze wszystkimi tabelami !!!

  }
 }

 /** ./cms/klasy/MojeSQL.php
 @
 *
 * if($a == lo)
 * wczytanie danych z pliku do wybranej tabeli 2010-02-28 ok.
 *
 */

 private function loadTable()
 {

  if($this->upraw(10))
  {

   if($this->arch) 			//-akcja dla wybranego archiwum
   {

    $this->fx = '
		<p class=\'wska\'>wczytuję dane z pliku do tabeli : <b class=\'ok b\'>'.$this->t.'</b></p>';

    $cmsTStart = microtime();

    $this->fx .= $this->kasTabiZal($this->t, $this->tan, $this->tap , $this->tan[C::get('tab_tab')], C::get('tab_tab'), C::get('akt_baza'));

    $this->fx .= '
 		<p class=\'czas\'>Czas kasowania i założenia tabeli : '.$this->czasOper($cmsTStart).'</p>';

    $cmsTStart = microtime();

    $this->fx .= $this->zaDoTabeli(C::get('tab_tab'), $this->t, $this->arch);

    $this->fx .= '
 		<p class=\'czas\'>Czas wczytania danych do tabeli : '.$this->czasOper($cmsTStart).'</p>';


	 unset($cmsTStart);
   }
   else
	 $this->wybArchiwum($this->t);	//-wybór archiwum

  }

 }

 /** ./cms/klasy/MojeSQL.php
 @
 * - archiwizacja do plików wszystkich tabel oraz plików 2010-05-11
 *
 */

 private function saveAllTables()
 {

  //-archiwizacja plików, tylko tych, których jeszcze nie ma w archiwum

  $cmsTStart = microtime();

  $dirs1 = array_map("basename", glob(C::get(fotyPath).'*.*'));				//-tablica aktualnych plików

  $dirs2 = array_map("basename", glob(_PATH_ARCH_FOTO.'*.*'));					//-tablica plików z archiwum

  $this->fx .= '<p>aktualna ilość plików : '.count($dirs1).'</p>';
  $this->fx .= '<p>iość plików w archiwum : '.count($dirs2).'</p>';

  $dirs1 = array_diff($dirs1, $dirs2);

  rsort($dirs1); 																				//-ustawia nowe klucze od 0 do n

  $licz_dir1 = count($dirs1);

  $this->fx .= '<p>ilość plików do archiwizacji : '.$licz_dir1.'</p>';

  if($licz_dir1>0)
  {
   if(!is_dir(_PATH_ARCH_FOTO)) mkdir(_PATH_ARCH_FOTO, 0777); 					//-zakłada katalog główny archiwum jeśli taki jeszcze nie istnieje

   for($i = 0; $licz_dir1 > $i; $i++)
   {

    if(!copy(C::get(fotyPath).$dirs1[$i], _PATH_ARCH_FOTO.$dirs1[$i]))
	  $this->fx .= '
		<p class=\'error\'>nie powiodło się kopiowanie pliku : '.$dirs1[$i].'</p>';
	 else
	  $copy++;
   }

   $this->fx .= '<p></p><p class=\'ok\'>Dokonano archiwizacji : '.$copy.' plików</p>';
  }

  unset($copy, $dirs1, $dirs2, $licz_dir1, $i);

  $this->fx .= '
 		<p class=\'czas\'>Całkowity czas obsługi archiwizacji plików : '.$this->czasOper($cmsTStart).'</p>';


  try
  {
   if($tab = DB::myQuery('SHOW TABLES'))
   {

	 $this->fx .= '
		<p class=\'wska\'>Zapisuje do plików dane z wszystkich tabel</p>';

    $kat_arch = date('Ymd_His', time()).'/';

	 $prefix = C::get('db_prefix');
	 $prefix_len = strlen(C::get('db_prefix'));
	 $baza   = C::get('akt_baza');
	 $tabtab = C::get('tab_tab');

	 $cmsTStartW = microtime();		//-pomiar czasu dla wszystkich tabel

    while($ta = mysql_fetch_assoc($tab))
	 {
     if(substr($ta['Tables_in_'.$baza], 0, $prefix_len) == $prefix || $prefix == '')
	  {
	   $t = $ta['Tables_in_'.$baza];

	   if($ta['Tables_in_'.$baza] != $tabtab)
	   {
	    $cmsTStart = microtime();

	    $this->fx .= $this->zapDoPliku($t, $this->tan[$t], $this->tap[$t], $kat_arch);

	    $this->fx .= '
 		 <p class=\'czas\'>Czas zapisu: '.$this->czasOper($cmsTStart).'</p>';
	   }
	  }
	 }

	 unset($prefix, $prefix_len, $tabtab, $baza);

    $this->fx .= '
 		<p class=\'czas\'>Czas zapisu wszystkich tabel : '.$this->czasOper($cmsTStartW).'</p>';
   }

   unset($tab, $ta, $t, $kat_arch, $cmsTStart, $cmsTStartW);

	$this->kasujArchiwum();

  }
  catch(Exception $e)
  {
   $this->fx .= '<p class=\'error\'>Nie można odczytać listy tabel, archiwizacja nie została wykonana!</p>';
   $this->fx .=  C::debug($e, 0);
  }

 }

 /** ./cms/klasy/MojeSQL.php
 @
 * kasuje najstarsze archiwum ponad limit
 *
 */

 private function kasujArchiwum()
 {

  $dirs = glob(_PATH_ARCH.'*');											//-odczytujemy ilość zapisanych archowów

  sort($dirs);

  while(count($dirs) > _LIMIT_ARCH)										//kasujemy najstarsze, czyli pierwsze na liscie archiwum, aż do uzyskania limitu
  {
   $this->fx .= '
		<p class=\'wska b\'>Kasuje najstarsze archiwum! max.ilość punktów archiwizacji = '._LIMIT_ARCH.'</p>';

   $this->fx .= $this->rrmdir($dirs[0]);

   array_shift($dirs);
  }

 }

 /** ./cms/klasy/MojeSQL.php
 @
 * wczytuje do wszystkich tabel dane z odpowiednich plików jesli tylko są 2010-02-28 ok.
 *
 * wczytywane są dane tylko do tych tabel, których archiwa zanjduja się w wybranym katalogu
 * i tylko te archiwa, dla których istnieją tabele
 *
 */

 private function loadAllTables()
 {

  if($this->arch)		//-jeśli jest archiwum
  {

   $dirs = glob(_PATH_ARCH.$this->arch.'/'.C::get('db_prefix').'*.php');

   if($dirs)
   {
	 $cmsTStartW = microtime();

    foreach($dirs as $dir)
    {

     $t = substr(basename($dir), 0, -4);			//-nazwa pliku = nazwa tabeli

	  if($t != C::get('tab_licznik'))				//-nie wczytuje hurtem tabeli licznika, możliwe tylko dla pojedyńczej akcji
	  {

		if($this->isTable($t))							//-jeśli taka tabela istnieje w bazie danych serwisu
		{

       $this->fx .= '
		 <p>plik : '.$t.' -> tabela : '.$t.'</p>';

	    $cmsTStart = microtime();

       $this->fx .= $this->kasTabiZal($t, $this->tan, $this->tap , $this->tan[C::get('tab_tab')], C::get('tab_tab'), C::get('akt_baza'));

	    $this->fx .= '
 		 <p class=\'czas\'>Czas kasowania i założenia tabeli : '.$this->czasOper($cmsTStart).'</p>';

	    $cmsTStart = microtime();

       $this->fx .= $this->zaDoTabeli(C::get('tab_tab'), $t, $this->arch);

	    $this->fx .= '
 		 <p class=\'czas\'>Czas wczytania danych do tabeli : '.$this->czasOper($cmsTStart).'</p>';

		 $liczPlik++;
		}
	  }


    }

	 if($liczPlik > 0)
	 {
	  $this->fx = '
		<p class=\'wska\'>wczytuje dane do wszystkich tabel z odpowiednich plików ( jeśli istnieją )</p>'.$this->fx.'
 		<p class=\'czas\'>Całkowity czas dearchiwizacji : '.$this->czasOper($cmsTStartW).'</p>';
	 }
	 else
	  $this->fx = '
	  <p class=\'error\'>Brak Tabel w Bazie danych, dla kórych istnieje archiwum, lub jakiekolwiek tabele nie są jeszcze założone!</p>';


	 unset($cmsTStartW, $cmsTStart);
   }
   else
    $this->fx .= '
		<p class=\'error\'>Archiwum jest puste</p>';

  }
  else
   $this->wybArchiwum();	///-lista katalogów archiwum
 }


 /** ./cms/klasy/MojeSQL.php
 @
 * wybór archiwum (archiwów) które zawierają dane dla co najmniej 1 tabeli
 *
 */

 private function getDateArch($d)
 {

   $rok = substr($d, 0, 4);
	$mon = substr($d, 4, 2);
	$day = substr($d, 6, 2);
	$h = substr($d, 9, 2);
	$m = substr($d, 11, 2);
	$s = substr($d, -2);

  return $day.'-'.$mon.'-'.$rok.' '.$h.':'.$m.':'.$s;
 }


 private function wybArchiwum($t = '')
 {

   if(!is_dir(_PATH_ARCH) && defined(_PATH_ARCH))
   {
    $this->fx .= '<p class=\'error\'>Brak głównego katalogu archiwum : '._PATH_ARCH.'</p>';
   }
   else
   {
    $dirs = glob(_PATH_ARCH.'*');

	 if($t != '')
	  $t .= ',';
	 else
	  $t = '';

	 foreach($dirs as $dir)
	 {


	  $wt[] = '<a class=\'folder_arch\' href=\''.$t.$this->a.','.basename($dir).',mysql.cmsl\' title=\''.$this->getDateArch(basename($dir)).' ->  plików : '.count(glob($dir.'/'.C::get('db_prefix').'*.php')).'\'>
	   <img src="./cms/skin/folder.jpg" alt=\'\'><b>'.++$lf.'</b></a>';



	 }


	 if($wt)
	 {
	  $this->fx .= '
	  <h3 id=\'folder_tyt\'>Wybierz archiwum:</h3>'.implode(' . ', $wt).'
	  <p>Ustaw wskaźnik myszki nad wybranym folderem aby odczytać datę archiwum i ilość zawartych w archiwum plików.</p>	';

	 }
	 else
	  $this->fx .= '<p class=\'error\'>Brak archiwum : '._PATH_ARCH.'</p>';

    unset($dirs, $dir, $wt);
   }


 }


 /** ./cms/klasy/MojeSQL.php
 @
 */

 private function poDefTab($tap, $tan, $t)					// wyświetla definicje pojedyńczej tabeli na podstawie def_tab.php
 {
  $opisy = explode('|', $tap[$t]);								//-opisy pól
  $od = explode(',', substr($tan[$t], 1));					//-wiersze definicji tabeli

  $ll = count($od);

  for($i=0; $ll>$i; $i++)
   $fx .= '
	<p class=\'cms_pok_tab\'>'.$od[$i].'<b> -> [ '.$opisy[$i].' ]</b></p>';

  unset($i, $ll, $od, $opisy, $t, $tap, $tan);

  return $fx;
 }

 /** ./cms/klasy/MojeSQL.php
 @
 */

 private function kasTabele($t)
 {
 // SQL CMS -> kasuje wszystkie tabele z bazy danych MySQL
 // projekt.etvn.pl & aleproste.plDariusz Golczewski -- 2008-09-10 -- UTF-8

  try
  {
   $tab = mysql_query("DROP TABLE $t");

   if(!$tab)
    throw new Exception(mysql_error());
   else
    return '<p class=\'ok\'>Tabela usunięta prawidłowo.</p>';
  }
  catch(Exception $e)
  {
   return '
	<p class=\'error\'>tabela nie została usunięta!</p>
	'.C::debug($e, 0);
  }
 }

 /** ./cms/klasy/MojeSQL.php
 *
 *
 *
 */

 private function zapdt($tabela, $def, $defp, $naz)
 {
  //-[nazwa tabeli systemowej][definicja tabeli][definicja pól tabeli][nazwa tabeli]

  //-funkcja zapisuje definicje tabeli do tabeli systemowej

  $k = preg_split('/,{1}/', substr(trim($def),1));

  $i=0;

  foreach($k as $p)
  {
   $p = preg_replace("/[[:blank:]]{1,}/"," ", trim($p));  //-zamienia więcej niż 1 spację na dokładnie 1 spację
   $tpo = explode(" ",trim($p));

   $defe[$i] = $tpo[0].'|'.$tpo[1];
   $i++;
  }

  //-definicja tabeli -> nazwa pola i typ // , pomija id

  $deta = explode ('|',$defp);

  $deta_li = count($deta);
  $defe_li = count($defe);

  if($deta_li != $defe_li)
  {		return '
	<p>Niedozwolony znak w opisie tabeli w def_tab może to jest znak \'|\'</p>';
   exit;
  }

  $defd = '';


  /*
  echo '<p>'.$deta_li.'</p>';

  echo '<pre>';
  print_r($deta);
  echo '</pre>';

  echo '<p>############################</p>';

  echo '<pre>';
  print_r($defe);
  echo '</pre>'; */




  for($i=0; $deta_li > $i; $i++)
   $defd .= ", n".$i."='".$deta[$i]."|".$defe[$i]."'";

  unset($deta_li);

  //-przygotowanie treści zapytania SET pole='wartosc', ...

  //-sprawdza czy jest zapis w tabeli ---
  //-kolejność działań jest prawidłowa, gdyż jeśli wpis już istnieje to jest tylko odświeżany !!!

  try
  {
   $tab = mysql_query("SELECT nazwa FROM $tabela WHERE nazwa='$naz'");

   if(!$tab)
    throw new Exception(mysql_error());
   else
   {
    $ta = mysql_fetch_row($tab);

	 try
	 {
     if($ta[0] != '')
     {
      $defd = substr($defd,1); 															//-usunięcie przecinka z przodu

	   $tab = mysql_query("UPDATE $tabela SET $defd WHERE nazwa='$naz'");
     }
     else
      $tab = mysql_query("INSERT INTO $tabela SET nazwa='$naz' $defd");

     if(!$tab) throw new Exception(mysql_error());

	  }
	  catch(Exception $e)
	  {
	   return '
		<p class=\'error\'>definicje pól nie zostały zapisane!</p>'.C::debug($e, 0).'->'.$defd;
	  }

   }
   return;
  }
  catch(Exception $e)
  {
   return C::debug($e, 0);
  }

 }

 /** ./cms/klasy/MojeSQL.php
 @
 * kasuje tabelę i zakłada na nowo -------------------- 2010-02-28
 *
 *
 */

 private function kasTabiZal($t, $tan, $tap , $defx, $t_tab, $baza)
 {

  try
  {

	if(DB::myQuery("DROP TABLE $t"))
    return '<p class=\'ok\'>tabela usunięta prawidłowo.</p>'.$this->zal_tab($t, $tan[$t], $tap[$t], $defx, $t_tab, $baza); 	//-zakładanie tabeli

  }
  catch(Exception $e)
  {
   return '
	<p class=\'error\'>tabela nie została usunięta, więc nowa nie może być założona!!</p>
	'.C::debug($e, 0);
  }
 }

 /** ./cms/klasy/MojeSQL.php
 *
 * 2013-02-05
 *
 */

 private function zal_tab($naz, $def, $defp)
 {
  //-[nazwa tabeli][definicja tabeli][definicja pól tabeli][definicja tabeli systemowej]

  $fx = '';
  $tpt = false;

  $x1 = explode('_', $naz);

  if(reset($x1) == substr(C::get('db_prefix'), 0, -1)) 			//-test czy zdefiniowana jest nazwa tabwli w config_sql
  {
   try
   {
    $tab = mysql_query('SHOW TABLES');

    if(!$tab)
    {
     $fx .= '
		<p class=\'error\'>Nie można odczytać listy tabel!</p>';

	  throw new Exception(mysql_error());
    }
    else
     while($ta = mysql_fetch_assoc($tab))
     {
	   if($ta['Tables_in_'.C::get('akt_baza')] == $naz) $tpt = true;
     }
   }
   catch(Exception $e)
	{
	 $fx .=  C::debug($e, 0);
	}


   if(!$tpt) 									//-zakładana tabeli, która jeszcze nie istnieje
   {
    try
    {
     $tab = mysql_query("CREATE TABLE $naz $def");

     if(!$tab)
     {
	   $fx .= '
		<p class=\'error\'>nie można utworzyć tabeli!! -> <b>'.$naz.'</b></p>
		<p class=\'wska t\'>definicja tabeli -> '.preg_replace("/,/", ',<br />', $def).'</p>';

      throw new Exception(mysql_error());
     }
     else
	   $fx.='
		<p class=\'wska\'>została utworzona tabela -> <b class=\'b\'>'.$naz.'</b></p>';

    }
    catch(Exception $e)
	 {
	  $fx .=  C::debug($e, 0);
	 }

   }
   else
    $fx .='
		<p class=\'error\'>już istnieje tabela! -> <b class=\'wska b\'>'.$naz.'</b></p>';

   unset($tab, $ta);

  }
  else
  $fx.='
	<p class=\'error\'>Nie zdefiniowana nazwa tabeli -> <b class=\'wska b\'>'.$naz.'</b></p>';

  unset($naz, $def, $defp, $tabela, $baza, $x1);

  return $fx;
 }

 /** ./cms/klasy/MojeSQL.php
 @
 */

 private function zapDoPliku($t, $tan, $tap, $akt_kat)
 {
  // SQL CMS -> zapisuje dane z tabeli do pliku ------- 2010-05-13 -> 2011-02-14 -> 2011-02-24
  // zmiana katalogu docelowego i usuniecie z nazwy przedrostka klucza
  // katalog docelowy definiowany w config_cms.php

  // projekt.etvn.pl & aleproste.pl Dariusz Golczewski -- 2009-11-11 --- UTF-8

  /* zapisuje dane z tabeli do pliku o nazwie tabeli -------------

	- odczytuje tabelę i zapisuje dane do pliku tekstowego o nazwie = nazwie tabeli
	- jeśli tabela nie jest pusta podejmujemy akcję

  1. generuje unikalny znacznik, który posłuży do dekodowania
  2. odczytuje strukturę tabeli w postaci nazw pól tabeli oddzielonych przecinkami
  3. odczytuje rekordy z tabeli oddzielone przecinkami w pojedyńczych apostrofach
  4. na końcu każdego rekordu dokleja ten sam unikalny znacznik
  5. zapisuje wszystko do pliku tekstowego w formacie:
  [długość znacznika][znacznik][nazwy pół,][znacznik][rekord1][znacznik]....[rekord nty][znacznik]\n

  Taki zapis pozwoli wczytać dane do tabeli o nawet zmodyfikowanej strukturze, jeśli nastąpi zmiana nawy pola
  przed wczytaniem należy poprawić odpowiednio nazwy w pliku !!!
  */

  $tap = explode('|', $tap); //-parametry dla pól tabeli

  try
  {
   $tab = mysql_query("SELECT * FROM $t"); 			//-odczyt rekordów z tabeli

   if(!$tab)
    throw new Exception(mysql_error());
   else
   {
    $pusta = mysql_fetch_row($tab);

    if($pusta[0]!='')
    {
	  //$zmlok = explode('ENGINE', $tan);												//-oddziela definicje za nawiasem ENGINE=MYISAM CHARACTER ...

	  $zmlok = reset(explode('ENGINE', $tan));										//-oddziela definicje za nawiasem ENGINE=MYISAM CHARACTER ...

	  $k = preg_split('/(\s+)([^,]*)(,*)(\s*\W*\s*)/', trim(substr($zmlok,1,-1)));

     $i=0;
     while($k[$i])
	  {
	   if(substr(end(explode('_', $k[$i])), 0, 3) == 'fot')
	   {
	    $pplik = explode(';', end(explode('^', $tap[$i])));

		 foreach($pplik as $ppp)
		 {
		  $ppx = explode(',', $ppp);
		  if(trim($ppx[2])) $wpliki .= $ppx[2]; 														//-przedrostek
		 }

		 $ipliki[] = $i;													//-tabela indeksów i parametrów pól plikowych w tabeli
		 $ppliki[] = $wpliki;

		 unset($pplik, $ppp, $ppx, $wpliki);
	   }
	   //echo '<p>'.substr(end(explode('_', $k[$i])), 0, 3).'</p>';

	   $zap[] = $k[$i++];
     }

	  $lpliki = count($ipliki);

     unset($zmlok, $i, $k);

	  if($zap) $zap = implode(',', $zap).$znkw; 												//-wiersz z nazwami pól tabeli zakończony unikalnym znacznikiem

	  //-END pierwszy wiersz = wiersz nazw pól tabeli

     $znkw = uniqid(rand()); 																//-znacznik rodzielenia rekordów przy dekodowaniu
     $dlzn = strlen($znkw);																//-długość znacznika

	  //$znkw = "\n";

	  try
	  {
      $tab = mysql_query("SELECT * FROM $t");

      if(!$tab)
	    throw new Exception(mysql_error());
      else
       while($ta = mysql_fetch_row($tab))
       {
		  if($lpliki)															//-jeśli tabela posiada pola plikowe to tworzymy tablicę plików do archiwizacji
		   for($i = 0; $i<$lpliki; $i++)
		   {
			 if($ta[$ipliki[$i]])
			 {
			  $pliki[] = $ta[$ipliki[$i]];

			  $ppp = explode('_', $ppliki[$i]);

			  foreach($ppp as $ppx)
			   if($ppx)
				 $pliki[] = $ppx.'_'.$ta[$ipliki[$i]];

			  unset($ppp, $ppx);
			 }
		   }
		  $fxp .= "'".implode("' ,'", $ta)."'".$znkw;
       }
	  }
	  catch(Exception $e)
	  {
	   return C::debug($e, 0);
	  }

     //-zapis do pliku w formacie [znacznik][nazwy pól tabeli][znacznik][rekordy tabeli[znacznik]]

	  if(!is_dir(_PATH_ARCH)) mkdir(_PATH_ARCH, 0777); 							//-zakłada katalog główny archiwum jeśli taki jeszcze nie istnieje

	  if(!is_dir(_PATH_ARCH.$akt_kat)) mkdir(_PATH_ARCH.$akt_kat, 0777);		//-zakłada katalog kolejnej archiwizacji

	  $h = fopen(_PATH_ARCH.$akt_kat.$t.'.php', 'w');

     fputs($h, '<? exit(\'sory\');?>'.$dlzn.$znkw.$zap.$znkw.$fxp."\n");

     fclose($h);
    }
    else
     return '
		<p class=\'error\'>tabela <b class=\'b\'>'.$t.'</b> jest pusta!</p>';

    unset($pusta, $znkw, $dlzn, $h, $zap);

	 unset($pusta, $znkw, $dlzn, $h, $zap, $pliki, $ipliki, $ppliki);

    return '
		<p class=\'ok\'>Zapis wykonany prawidłowo <b class=\'b\'>'.$t.'</b></p>
		<p>pól plikowych -> '.$lpliki.'</p>';
   }
  }
  catch(Exception $e)
  {
   return  C::debug($e, 0);
  }

 }

 /** ./cms/klasy/MojeSQL.php
 @
 * SQL CMS -> wczytuje dane z pliku do tabeli ------- 2010-03-06 -> 2011-02-14
 *
 * kasuje i zakłada tabelę, odczytuje plik o nazwie = nazwie tabeli, obrabia format i wczytuje do tabeli
 * include 'cms/sql/kasizal.php';
 * kasuje tabelę i zakłada ponownie strukturę tabeli
 * utworzenie tablicy [ $pola ] zawierającej nazwy pól aktualnej tablicy
 */

 private function zaDoTabeli($t_tab, $t, $arch)
 {

  try
  {

	if($tab = DB::myQuery("SHOW COLUMNS FROM $t"))							//-odczytanie pól tabeli bezpośrednio z tabeli, bez tabeli systemowej
    while($tb = mysql_fetch_assoc($tab))
     $dpola[] = $tb['Field'];


  if($dpola)
  {
   if($t and file_exists(_PATH_ARCH.$arch.'/'.$t.'.php')) 										//-jeśli istnieje plik do wczytania
   {
    $nazpl = _PATH_ARCH.$arch.'/'.$t.'.php';
    $h = fopen($nazpl, 'rb');
    $tr = fread($h, filesize($nazpl));
    fclose($h);

    if($tr)
    {
	  $tr = substr($tr, 18);																				//-wycina pierwsze 18 znaków (zabezpieczenie pliku przed odczytem)

     $dzna = substr($tr,0,2);										  										//-długości znacznika
     $znac = substr($tr,2,$dzna);																		//-wartości znacznika

     $rek  = explode($znac, substr($tr, $dzna+2)); 												//-podział na rekordy

     $pola = explode(',', $rek[0]); 																	//-pierwszy rekord = nazwy pól tabeli -> $pola[i] gdzie i=[0..n]

     $lopol = count($pola);																				//-licznik pól w tabeli

     $lirek = count($rek)-1;																			   //-licznik rekordów z danymi

	  /*
	  $eror .= '
		 <p class=\'cms_kom\'>liczba pól : '.$lopol.'</p>
		 <p class=\'cms_kom\'>liczba wierszy : '.$lirek.'</p>';
     */

     for($j=1; $lirek>$j; $j++) 																	  	   //-pętla po rekordach
     {
      $wapu = explode("' ,'", substr($rek[$j],1,-1)); 											//-rozdziela rekord na pola

      for($i=0; $lopol>$i; $i++) 																		//-wyprodukowanie zapytania mysql
      {
	    $wapu[$i] = trim($wapu[$i]);

	    if($pola[$i] == dapu || $pola[$i] == dado)												  //-jeśli daty publikacji lub doodania są puste
	     if($wapu[$i] == '')
		   $wapu[$i] = $teraz;																			  //-to wstawia aktualna datę


	    if(in_array($pola[$i], $dpola))
	     $zap[] = $pola[$i].'=\''.$wapu[$i].'\'';

	   }

	   if(is_array($zap))
	   {
	    $pytanie = implode(', ', $zap);
	    unset($zap);

	    DB::myQuery("INSERT INTO $t SET $pytanie"); 										//-zapis do tabeli

	   }
	   else
	   {
	    $j = $lirek + 10;
	    $eror .= '
		 <p class=\'cms_error\'>błąd pliku : '.$t.', nie można złozyć zapytania<br />porównaj nazwy pól tabeli w def_def i w pliku archiwum!</p>';
	   }
     }
    }
    else
     $eror .= '
		<p class=\'cms_error\'>błąd czytania pliku : '.$t.'</p>';
   }
   else
    $eror .= '
		<p class=\'cms_error\'>brak w achiwum pliku : '.$t.'</p>';
  }
  else
   $eror .= '
		<p class=\'cms_error\'>brak defincji tabeli w tabeli systemowej : '.$t.'</p>';

  unset($i, $j, $t, $tab, $wapu, $dpola, $pola, $wynik, $pytanie);

  if($eror)
   return '
		<p>historia operacji:</p>'.$eror;
  else
   return '
		<p class=\'ok\'>Dane z pliku wczytane do tabeli prawidłowo.</p>';


  }
  catch(Exception $e)
  {
   return C::debug($e, 0);
  }

 }


 /** ./cms/klasy/MojeSQL.php
 @
 * kasowanie plików powiazanych z tabelą
 * UWAGA! tylko tych zapisanych w tabeli o nazwie pola według klucza -> fot lub plik
 */

 private function delFiles()
 {

  try
  {

	//-odczytanie pól tabeli $t i sprawdzenie czy są pola plikowe

	if($tabe = DB::myQuery("SELECT * FROM ".C::get('tab_tab')." WHERE nazwa='$this->t'"))
   {
    $ta = mysql_fetch_row($tabe);

    $i=2;
    while($ta[$i])
    {
     $zz = explode('|',$ta[$i++]);

     if(substr($zz[1],0,3)=='fot' || substr($zz[1],0,4)=='plik')
     {
      $tp = explode('^',$zz[0]);
	   $tx = explode(';',$tp[3]);

	   $j = 0;

	   while($tx[$j])
	   {
	    $ty = explode(',',$tx[$j++]);

	    if($ty[2]) $tz[] = $ty[2];															//-tablica przedrostków dla pola
	   }

	   $pol[] = $zz[1];																			//-tablica nazw pól
	   $pat[] = $tp[2];																			//-tablica ścierzek dostepu do pliku
	   $prz[] = $tz;																				//-tablica przedrostków dla pola

	   unset($tz);
     }
    }
   }
  }
  catch(Exception $e)
  {
	$fx .= C::debug($e, 0);
  }

  unset($tabe, $ta, $zz, $tp, $tx, $ty, $i, $j);


  if($pol)
  {

   $kwer = implode(',', $pol);

   try
   {

	 if($tabe = DB::myQuery("SELECT $kwer FROM $t"))
     while($ta = mysql_fetch_array($tabe))
     {
      $i = 0;

      while($pol[$i])
      {
	    if($ta[$pol[$i]])
	    {
	     $this->fx .= '
		  <p>kasujemy : '.$pat[$i].'/'.$ta[$pol[$i]].'</p>';									//-kominikat

	     $this->fx .= $this->kaspl($pat[$i].'/', $ta[$pol[$i]], ''); 								//-kasowanie plików

	  	  if($prz[$i])
	     {
	      $j = 0;

	      while($prz[$i][$j])
	      {
	       $this->fx .= '
			  <p>kasujemy : '.$pat[$i].'/'.$prz[$i][$j].$ta[$pol[$i]].'</p>';				//-kominikat

			 $this->fx .= $this->kaspl($pat[$i].'/', $prz[$i][$j++].$ta[$pol[$i]], ''); 		//-kasowanie klonów plików
			}
		  }
	 	 }
	 	 $i++;
		}
	  }
	 }
 	 catch(Exception $e){$fthis->x .= C::debug($e, 0);}

  }
  unset($tabe, $ta, $i, $pol, $pat, $prz);
 }

 /** ./cms/klasy/MojeSQL.php
 @
 * - sprawdza czy dana tablica istnieje
 */

 private function isTable($t)
 {
  if(!$t || $t === '') //-sprawdza parametr
   return '<p>Parametr dla '.__FILE__.'->'.__CLASS__.'->'. __METHOD__.'->'. __FUNCTION__.'->'.__LINE__.' jest pusty!!</p>';

  try
  {
   $jest = 0;

	if($tab = DB::myQuery('SHOW TABLES'))
	{
	 while($ta = mysql_fetch_assoc($tab))
	 {
	  if($ta['Tables_in_'.C::get('akt_baza')] === $t)
	   $jest++;

	 }
   }

	switch($jest)
	{
	 case 1 : return true; break;

	 case 0 : return false; break;

	 default:

	  echo '<p>Duplikaty tabel!</p>';
	  exit(__FILE__.'->'.__CLASS__.'->'. __METHOD__.'->'. __FUNCTION__.'->'.__LINE__);

	}

  }
  catch(Exception $e)
  {
	C::debug($e, 0);
  }

 }


 /*
 @ kasuje katalog wraz z zawartością
 *
 */

 private function rrmdir($dir)
 {

  if(is_dir($dir))
  {
   $objects = scandir($dir);

	foreach ($objects as $object)
	{

    if($object != '.' && $object != '..')
	 {
     if(filetype($dir.'/'.$object) == 'dir')
	   rrmdir($dir.'/'.$object);
	  else
	   $w = unlink($dir.'/'.$object);

	  if($w)
	   $wt .= '<p class=\'ok\'>'.$dir.'/'.$object.' :: usunięty</p>';
	  else
	   $wt .= '<p class=\'error\'>'.$dir.'/'.$object.' :: NIE został usunięty</p>';
    }
   }

   reset($objects);
   rmdir($dir);

	unset($w);
  }

  return $wt;
 }

 /*
 @
 * -oblicza czas wykonywania operacji
 */

 private function czasOper($start)
 {
  $czas_end = explode(' ',microtime());
  $czas_sta = explode(' ', $start);

  $w = sprintf('%0.5f',($czas_end[1]-$czas_sta[1]) + ($czas_end[0]-$czas_sta[0]));

  unset($czas_end, $czas_sta, $start);

  return  $w.'s';
 }

 /*
 @
 */

 public function w()
 {
   $this->fz = '<p>Nowy MySQL</p>'.$this->fz;

 	return array($this->fx, $this->fz);
 }

 /*
 @
 */

 function __destruct()
 {

 }

}
?>
