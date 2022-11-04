<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* klasa (wtyczka) wyświetlająca stronę z formularzem dla ładowania hurtowego zdjęć : v.2.7
*
* 2014-09-20 : poprawki linkowania powrotu do miejsca z którego nastąpiło wywołanie
* 2013-05-17 : poprawki nitice
*
* 2013-04-12 : poprawki ustawienia statusu admina
* 2013-03-16 : poprawki Notice
* 2013-02-09 : poprawki
* 2012-12-28 : poprawki
* 2012-12-04 : poprawka dla nazwy tabeli
* 2012-11-25 : kolejne drobne poprawki
* 2012-11-24 : poprawiono mozliwość wyboru tabeli zdjęć
* 2012-11-21 : poprawiona metoda S::k()
* 2011-09-18
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2011-05-12 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/


class W_AddPhoto
{
 private $w = ''; 				//-kod HTML powstały w wyniku działania klasy
 private $error = '';

 private $id_gal = '';			//-id galerii

 private $tabf = '';				//-tabela zdjęć, przekazywana jest w zakodowanym odnośniku

 private $teraz = '';

 //-tablice dla pól formularza

 private $plik = array();		//-fizyczna nazwa pliku
 private $skip = array();		//-pomiń plik
 private $kas  = array();		//-skasuj plik
 private $tytu = array();		//-tytuł pliku
 private $opis = array();		//-opis pliku

 private $path = '';				//-stała ścieżka do katalogu ze zdjeciami
 private $adres = '';			//-adrs powrotny dla strony addFoto
 private $skok = '';				//-skok do bloku publikacji id bloku metodą md5

 private $opcja = '';			//-odkodowana opcja adresu
 private $ja = false;

 /**
 *
 */

 function __construct()
 {
  $this->ja = C::get('ja');

  if(!C::get('jo')) 				//-zabezpieczenie, klasa dostepna tylko dla administratora
  {
   $this->error = '<p class=\'adm_error\'>Nie masz uprawnień do tej akcji</p>';
  }

  //-sekcja ustawienia $_SESSION['admin']['status'] zmodyfikowana 12-04-2013

  if(!isset($_SESSION['admin']['status']))
	$_SESSION['admin']['status'] = 0;

  if(C::get('jo'))
	$_SESSION['admin']['status'] = 9;

  if(C::get('ja'))
   $_SESSION['admin']['status'] = 10;


  $this->teraz = C::get('datetime_teraz');			  		//-data i czas startu skryptu
  $this->path  = C::get('tmpPath_foty');						//-ścieżka do tymczasowego katalogu na zdjęcia

  $this->opcja = explode(',', S::linkDecode(C::get('opcja')));

  Test::tracer(__FILE__, __METHOD__, __FUNCTION__, __LINE__, 'opcje_addPhoto', $this->opcja);

  /*
  [0] - tabela zdjeć
  [1] - id bloku treści
  [2] - akcja = podstrona
  [3] - id publikacji
  [4] - prefix skoku
  [5] - id skoku

  */
  if(count($this->opcja) > 4)
  {
   if(!$this->tabf = $this->opcja[0]) $this->error = '
		<p class=\'adm_error\'>Brak nazwy tabeli zdjeć w '.__METHOD__.' line: '.__LINE__.'</p>';

	if(is_numeric($this->opcja[1]) && is_numeric($this->opcja[3]))
	{
	 $this->id_gal = substr($this->opcja[1], 3, -3); 							//-numer publikacji, galerii
	 $id_pub = substr($this->opcja[3], 3, -3);									//-numer galerii = publikacja

	}
	else
	 $this->error .= '
		<p class=\'adm_error\'>Parametr id publikacji / galerri musi być liczbą! w '.__METHOD__.' line: '.__LINE__.'</p>';



	if($this->opcja[4])
	{
	 if($this->opcja[5])														//-dodano 2014-09-20
	  $this->skok = '#'.$this->opcja[4].md5($this->opcja[5]);	//-adres skoku - jeśli jest id skoku
	 else
	  $this->skok = '#'.$this->opcja[4].md5($this->id_gal);		//-jeśli nie to adres galerii
	}

   if($this->adres = $this->opcja[2]) // zamieniono = na  == ??
	{
    if(is_numeric($id_pub))
	  $this->adres = $this->opcja[1].'+'.$this->opcja[3].'+'.$this->adres.'.html'.$this->skok;	//-adres powrotny
	 else
	  $this->adres = $this->adres.'.html'.$this->skok;															//-adres powrotny jeśli jest to strona typu 'o mnie'
	  																															//-czyli strona z publikacją docelową
	}
	else
	 $this->error .= '
		<p class=\'adm_error\'>Brak adresu podstrony dla powrotu w '.__METHOD__.' line: '.__LINE__.'</p>';


    if(!$this->error) $this->odbior();														//-wywołanie metodu odbioru danych wysłanych przez formularz po załadowaniu zdjęć


  }
  else
	 $this->error = '
		<p class=\'adm_error\'>Adres podstrony jest niekompletny!<br /> w '.__METHOD__.' line: '.__LINE__.'</p>';


 }

 /**
 *
 * odbiór przesłanych danch z formularza, po załadowaniu zdjęć
 *
 */

 private function odbior()
 {

  if(isset($_POST['plik']))
  {

   $this->plik = $_POST['plik'];
   foreach($this->plik as $k => $w) $this->w .= '<p>plik -> '.$k.'->'.$w.'</p>';

	if(isset($_POST['skip']))
   {
    $this->skip = $_POST['skip'];
    foreach($this->skip as $k => $w) $this->w .= '<p>skip -> '.$k.'->'.$w.'</p>';
   }

   if(isset($_POST['kas']))
   {
    $this->kas = $_POST['kas'];
    foreach($this->kas as $k => $w) $this->w .= '<p>kasuj -> '.$k.'->'.$w.'</p>';
   }

   if(isset($_POST['tyt']))
   {
    $this->tytu = $_POST['tyt'];
    foreach($this->tytu as $k => $w) $this->w .= '<p>tytuł -> '.$k.'->'.$w.'</p>';
   }

   if(isset($_POST['opis']))
   {
    $this->opis = $_POST['opis'];
    foreach($this->opis as $k => $w) $this->w .= '<p>opis -> '.$k.'->'.$w.'</p>';
   }


	foreach($this->plik as $nu => $pl)
	{
	 if($this->skip[$pl] != 'on')
	 {
	  if($this->kas[$pl] != 'on')
	   $this->addNewRecord($nu);								//-utworzenie nowego rekordu dla zdjęcia
	  else
	   $this->kasuj($pl);										//-skasowanie zdjecia z dysku
	 }
	}
  }
  else
   $this->w .= '
	<p class=\'error\'>Nie odebrano żadnych plików!</p>';

 }

 /**
 *
 * kasowanie pliku z katalogu tymczasowego
 *
 */

 private function kasuj($pl)
 {
  if(file_exists($this->path.$pl))
	unlink($this->path.$pl);
  elseif($this->ja)
   $this->w .= '
	<p class=\'error\'>Nie można usunąć pliku!</p>';
 }

 /**
 *
 * utworzenie rekordu dla zdjęcia i zmiana nazwy pliku
 * zmiana nazwy pliku jest konieczna bo plik nadal pozostaje w katalogu tymczasowym, aż do czasu wykadrowania
 * zmiana nazwy powoduję, że plik nie jest brany pod uwagę przy kolejnej analizie plików z katalogu
 * tym samym nie pojawi sie na liście plików, które można załadować do kolejnej lub dodać do aktualnej galerii
 *
 *
 *
 *
 */

 private function addNewRecord($nr)
 {

  $plikName = substr($this->plik[$nr], 2);

  $tab = 'INSERT INTO '.$this->tabf." SET
   fo_idte='".$this->id_gal."',
  	fo_dapu='".$this->teraz."',
	fo_dado='".$this->teraz."',
	fo_tytu='".$this->tytu[$nr]."',
	fo_fot0='".$plikName."',
	fo_poz0=1,
	fo_opf0='".$this->opis[$nr]."'";


  if(DB::myQuery($tab)) 										//-jeśli dodanie rekordu prawidłowe, to zmiana nazwy pliku (usunięcie przedrostka x-)
  {
	if(file_exists($this->path.$this->plik[$nr]))
	 if(!rename($this->path.$this->plik[$nr], $this->path.$plikName));
	  $this->w .= '
	 <p class=\'error\'>Błąd zmiany nazwy pliku nr: '.$nr.'</p>';

  }
  else
   $this->w .= '
	<p class=\'error\'>Błąd utworzenia rekordu dla pliku nr: '.$nr.'</p>';
 }

 /**
 *
 * - metoda wywołująca kod i skrypt ładowania zdjęć
 *
 */

 public function addPhoto()
 {
  if(!$this->error)
  {

   $this->help();									//-komunikat

   //C::set('javascript', $this->script()); //-dodanie skryptu w sekcje head
	C::add('javascript', $this->script());

   $this->addFoto();								//-główna metoda

  }
  else
   $this->w = $this->error;
 }

 /**
 *
 * prezentacja zdjęć z katalogu tmp_foto i dodanie formularza
 *
 *
 */

 private function addFoto()
 {

  $path = C::get('tmpPath_foty');

  if(!file_exists($path))
  {
	 //-założenie katalogu i nadanie mu uprawnień -> DO ZROBIENIA

   exit('Brak katalogu : '.$path);
  }

	$dir = new DirectoryIterator($path);							// Read the files from the saved images folder

	$licz = 0;
	$img = '';

	foreach ($dir as $fileinfo)
	{
	 if (!$fileinfo->isDot() && $fileinfo->isFile() && substr($fileinfo->getFilename(), 0 , 2) === 'x-')
	 {
     //S::linkCode2(array($fileinfo->getFilename(), session_id()))
     // thu
	  $img .= '
		<div class=\'swf_formPlace\'>
		 <img class=\'swf_thumbs\' src="./thumbnail.php?id='.$fileinfo->getFilename().':'.session_id().'" />
		 <input type="hidden" name="plik[]" value="'.$fileinfo->getFilename().'">
		 <p>...'.substr($fileinfo->getFilename(), 10).'</p>
		 <div >
		  <label for="tyt'.$licz.'">tytuł</label>
		  <input type="text" id="tyt'.$licz.'" name="tyt[]" value="">
		 </div>
		 <div>
		  <label for="opis'.$licz.'">opis</label>
		  <input type="text" id="opis'.$licz.'" name="opis[]" value="">
		 </div>
		 <div >
		  <label for=\'kasuj'.$licz.'\'>kasuj</label>&nbsp;
		  <input type=\'checkbox\' name="kas['.$fileinfo->getFilename().']"  id=\'kasuj'.$licz.'\'>
		  <label for=\'skip'.$licz.'\'>pomin</label>&nbsp;
		  <input type=\'checkbox\' name="skip['.$fileinfo->getFilename().']"  id=\'skip'.$licz.'\'>
		 </div>
		</div>';

		$licz++;
	 }
	}

   unset($licz);
   //-rel = identyfikator sesji_numer galerii dla javaScriptu

  if($img)
	$pol_ster = '
  	 <div id=\'swf_ster_form\'>
	  <input type=\'submit\' value=\'wczytaj\'>
	 </div>
	 <div class=\'sw_info\'>
	  <blockquote>
	   <p>Akcja spowoduje założenie w bazie danych rekordu dla każdego zdjęcia.</p>
	   <p>Zaznacz zdjęcia, które chcesz pominąć, będziesz mógł je wykorzystać później.</p>
	   <p>Możesz też zaznaczyć zdjęcia, które od razu chcesz skasować.</p>
	  </blockquote>
	 </div>';
  else
   $pol_ster = '';

  //Test::trace(__FILE__.'admin-status',	$_SESSION['admin']['status']);
  //Test::trace(__FILE__.'session_id()', session_id());
  //Test::trace(__FILE__.'session_id()', md5(_ZM_KOD));

  //S::linkCode2(array(session_id(), md5(_ZM_KOD)))

  $this->w .= '
   <form id=\'swf_addphoto\' rel=\''.session_id().':'.md5(_ZM_KOD).'\'>
		<div title=\'wybierz zdjęcie lub kilka zdjęć z wciśniętym CTR lub SHIFT\'>
			<button id="spanButtonPlaceholder">
			 <div id=\'sw_button\'>
			  <img src=\'./cms/skin/load4.gif\' alt=\'Proszę czekać!\'>
			  <p id=\'sw_button_p\'>Proszę czekać...</p>
			 </div>
			</button>
		</div>
		<!--
	  <input id="btnCancel" type="button" value="Cancel All Uploads" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px; color: #000;" />	  -->

	</form>

	<div id=\'divFileProgressContainer\'></div>

	<form id=\'swf_loadForm\' action=\'\' method=\'post\'>
	 <input type=\'hidden\' name=\'galeria_id\' value=\''.$this->id_gal.'\' />
	 <div id=\'swf_thumbnails\'>'.$img.'
	 </div>'.$pol_ster.'
	</form>
	<p><b>galeria id : </b>'.$this->id_gal.'<b> tabela: </b>'.C::korektaDoMenu2($this->tabf).'</p>
	<a class=\'back\' href=\''.$this->adres.'\' title=\'powrót do strony, do której dodajesz zdjęcia\'>wróć do strony galerii &uarr;</a>';

	//tu potrzebny adres zwrotny bo nie zawsze będzie to galeria !!

  unset($path, $img, $dir, $fileinfo, $pol_ster, $oop);

 }

 /**
 *
 * kominkat / instrukcja ładowania plików
 *
 */

 private function help()
 {
 	$this->w = '
	<div class=\'sw_info\'>
	 <blockquote>
	  <p>Aby dodać zdjęcia do wybranej galerii/publikacji, należy kliknąć pole "dodaj zdjęcia / select image".</p>
	  <p>Można dodać jednocześnie kilka zdjęć, w tym celu należy zaznaczyć kilka plików trzymająć wciśnięty klawisz CTR lub SHIFT</p>
	  <p>UWAGA! jeśli link "dodaj zdjęcia / select image" po kliku sekundach zwłoki się nie wyświetli, należy wcisnąć klawisz F5</p>
	 </blockquote>
	</div>';
 }

 /**
 *
 * skrypty javaScript ładowane dynamicznie + style
 *
 */

 private function script()
 {

  return '
	<link type=\'text/css\' rel=\'stylesheet\' href=\'./swfupload/swfupload.css\' media=\'screen\' />
	<link type=\'text/css\' rel=\'stylesheet\' href=\'./swfupload/swf_user.css\' media=\'screen\' />
	<script type="text/javascript" src="./swfupload/swfupload.js"></script>
	<script type="text/javascript" src="./swfupload/handlers.js"></script>
	<script type="text/javascript" src="./swfupload/filesLoad.js"></script>
	<link type="text/css" rel="stylesheet" href="./cms/styl/imgareaselect-default.css"  media="screen" />
	<script type="text/javascript" src="./cms/js/jquery.imgareaselect.pack.js"></script>';

 }

 /**
 *
 *
 */

 public function wynik()
 {

 	return '
	<div id=\'addPhoto\'>'.$this->w.'
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
