<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* klasa (wtyczka) wyświetlająca stronę z formularzem dla ładowania hurtowego zdjęć : v.2.8
*
* 2014-11-26 : poprawki dla publikacji z kilkoma blokami treści
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
* aleproste.pl Dariusz Golczewski -------- 2011-05-12 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/


class W_AddPhotoGal
{

 private $w = ''; 				//-kod HTML powstały w wyniku działania klasy

 private $jo = false;			//-znacznik administratora
 private $tabx = '';				//-tabela publikacji = tytułów
 private $taby = '';
 private $tabz = '';
 private $tabf = '';

 private $adres = '';			//-rozkodowanyf adres


 /**
 *
 */

 function __construct()
 {

  $this->jo = C::get('ja');																	//-2015-03-14 - proteza !!

  $this->tabx = C::get('tab_publikacje');													//- tabela tematów publikacji
  $this->taby = C::get('tab_teksty');														//- tabela bloków tekstu
  $this->tabz = C::get('tab_fotb');															//- tabela zdjęć

  $this->tabf = C::get('tab_fotx');


  $this->adres = $back = explode(',', S::linkDecode(C::get('opcja')));

  //Test::tracer(__FILE__, __METHOD__, __FUNCTION__, __LINE__, 'opcje_addPhoto', $this->opcja);

  //-testowo

  foreach($this->adres as $k => $v)
  {
	if($k == 1 || $k == 3)
	 $v2 = substr($v, 3, -3);
	else
	 $v2 = 'NN';

   $this->w .= '<p>'.$k.' - > '.$v.' -> '.$v2.'</p>';
  }

	/*
  [0] - tabela zdjeć
  [1] - id bloku treści
  [2] - akcja = podstrona
  [3] - id publikacji
  [4] - prefix skoku
  [5] - id skoku
  */

	if($back[4])
	{
	 if($back[5])																//-dodano 2014-09-20
	  $back[4] = '#'.$back[4].md5($back[5]);							//-adres skoku - jeśli jest id skoku
	 else
	  $back[4] = '#'.$back[4].md5(substr($back[1], 3, -3));		//-jeśli nie to adres galerii
	}



   if($this->back = $back[2]) // zamieniono = na  == ??
	{
    if(is_numeric($back[3]))
	  $this->back = $back[1].'+'.$back[3].'+'.$this->back.'.html'.$back[4];	//-adres powrotny
	 else
	  $this->back = $this->back.'.html'.$back[4];															//-adres powrotny jeśli jest to strona typu 'o mnie'
	  																															//-czyli strona z publikacją docelową
	}
	else
	 $this->error .= '
		<p class=\'adm_error\'>Brak adresu podstrony dla powrotu w '.__METHOD__.' line: '.__LINE__.'</p>';

 }

 /**
 *
 *
 *
 */

 public function addPhotoGal()
 {

  $this->menu();

  $this->odbierz();

  $this->wybrane();

  $this->tytuly();
 }

 /**
 *
 * odbiór danych wybranych do przypięcia zdjęć
 *
 */

 private function odbierz()
 {
  $this->w .= '<p>Jest odbierz</p>';

  if(isset($_POST['galeria']) && $_POST['galeria'] == 'on')
  {

   if($foty = $_POST['dod'])
	{

	 foreach($foty as $k => $w)
	 {
	  //$this->w .= '<p>dodaj -> '.$k.'->'.$w.' -tyt: '.$_POST['tyt'][$k].' - ops: '.$_POST['opis'][$k].'</p>'; // testowo

	  $this->newRekord($k, $_POST['tyt'][$k], $_POST['opis'][$k]);
	 }

	 $this->adres[7] = '';
	}
	else
    $this->w .= '
	<h1>Coś się jednak nie udało ;) !!</h1>';

  }

 }

 /**
 *
 * dodanie zdjecia do tabeli ze zdieciami dopiętymi
 *
 */

 private function newRekord($file, $tyt, $opis)
 {

  $tab = 'INSERT INTO '.$this->tabf." SET
   fo_idte='".substr($this->adres[1], 3, -3)."',
	fo_tytu='".$tyt."',
	fo_fot0='".$file."',
	fo_poz0=1,
	fo_opf0='".$opis."'";


  if(!DB::myQuery($tab)) 										//-jeśli dodanie rekordu prawidłowe, to zmiana nazwy pliku (usunięcie przedrostka x-)
   $this->w .= '
	<p class=\'error\'>Błąd utworzenia rekordu dla pliku: '.$file.'</p>';

 }

 /**
 *
 * wyświetla wybrane zdjęcia
 *
 */

 private function wybrane()
 {

  $ll = 0;
  $path = S::pathImg();

  $tab = 'SELECT * FROM '.$this->tabf.' WHERE fo_idte = "'.substr($this->adres[1], 3, -3).'"';

  if($tab = DB::myQuery($tab))
   while($ta = mysqli_fetch_assoc($tab))
	{

		$ww4 .= '
		<div class=\'swf_formPlace\'>
		 <img src=\''.$path.'m_'.$ta['fo_fot0'].'\' alt=\''.$ta['fo_opf0'].'\' title=\''.$ta['fo_tytu'].'\' />
		 <p>'.$ta['fo_fot0'].'</p>
		 <div >
		  <label for=\'wtyt'.$ll.'\'>tytuł</label>
		  <input type=\'text\' id=\'wtyt'.$ll.'\' name="wtyt[]" value="'.$ta['fo_tytu'].'">
		 </div>
		 <div>
		  <label for=\'wopis'.$ll.'\'>opis</label>
		  <input type=\'text\' id=\'wopis'.$ll.'\' name="wopis[]" value="'.$ta['fo_opf0'].'">
		 </div>
		 <div >
		  <label for=\'wks'.$ll.'\'>kasuj</label>&nbsp;
		  <input type=\'checkbox\' name=\'wkas['.$ta['fo_fot0'].']\' id=\'wkas'.$ll.'\'>
		 </div>
		</div>';

	 $ll++;

   }

   unset($ll);


   if($ww4)
	{

	 $this->w .= '
	 <div id=\'kot1\'>
	  <h1>Wybrane zdjęcia.</h1>
	 </div>
    <form id=\'swf_loadForm\' action=\'\' method=\'post\'>
	 <input type=\'hidden\' name=\'galeria\' value=\'on\' />
	 <div id=\'swf_thumbnails\'>'.$ww4.'
	 </div>
	 <div id=\'swf_ster_form\'>
	  <input type=\'submit\' value=\'edytuj\'>
	 </div>
	 <div class=\'sw_info\'>
	  <blockquote>
	   <p>Można jeszcze zmienić opis, tyuł albo usunąć zdjęcie.</p>
	  </blockquote>
	 </div>
	</form>
	<a class=\'back\' href=\''.$this->back.'\' title=\'powrót do publikacji, do której dodajesz zdjęcia\'>wróć do strony galerii &uarr;</a>';

	}
	else
	 $this->w .= '<h1>No add foto </h1>';

 }


 /**
 *
 * menu ze stron serwisu
 *
 */

 private function menu()
 {

  $tab = 'SELECT * FROM '.$this->tabx;

  $i = 0;

  if($tab = DB::myQuery($tab))
   while($ta = mysqli_fetch_assoc($tab))
   {
	 if(!in_array($ta['pu_stro'], $w)) $w[++$i] = $ta['pu_stro'];
   }

	$ww = '';

	sort($w);

   foreach($w as $k => $v)
	{

	 $x = $this->adres;
	 $x[6] = $v;

	 if($x[7]) unset($x[7]);

    $activ = '';

	 if($this->adres[6] == $v)
	  $activ = ' class =\'active\'';

	 $ww .= '
	 <li>
	  <a'.$activ.' href=\''.S::linkCode($x).'+add_photo_gal.html\'><b>'.$v.'</b></a>
	 </li>';

	}

	$ww = '
	<div id=\'menu\' class=\'menu width\' >
	 <ul>
	  '.$ww.'
	 </ul>
	</div>
	<h1>Dodanie zdjęć z istniejącej już publikacji.</h1>';

	$this->w .= $ww;

	unset($ww, $w, $k, $v, $i, $x);
 }

 /**
 *
 *
 *
 */

 private function tytuly()
 {
  if(!$this->adres[6]) return;

  $tab = 'SELECT * FROM '.$this->tabx.' WHERE pu_stro = "'.$this->adres[6].'" ORDER BY pu_dapu';

  $i = 0;

  if($tab = DB::myQuery($tab))
   while($ta = mysqli_fetch_assoc($tab))
   {

	 $i2 = $i3 = 0;

	 $tab2 = 'SELECT * FROM '.$this->taby.' WHERE tr_idte = "'.$ta['pu_id'].'"';   //-bloki tekstu w ramach danej publikacji

	 $w2 = $w3 = array();

	 if($tab2 = DB::myQuery($tab2))
     while($ta2 = mysqli_fetch_assoc($tab2))
	  {

	   $w2[++$i2] = $ta2['tr_id'];

		$tab3 = 'SELECT * FROM '.$this->tabz.' WHERE fo_idte = "'.$ta2['tr_id'].'"';   //-zdjecia załadowane do danego bloku tekstu

		if($tab3 = DB::myQuery($tab3))
       while($ta3 = mysqli_fetch_assoc($tab3))
		 {

			$w3[++$i3] = array($ta3['fo_fot0'], $ta3['fo_tytu'], $ta3['fo_opf0'], $ta3['fo_id']);  //-tablica parametrów -> id, tytuł i opis

		 }
	  }

	  $w[++$i] = array($ta['pu_stro'], $ta['pu_tytu'], $ta['pu_id'], $w2, $w3);

   }

	$ww = '';

	//sort($w);

   foreach($w as $k => $v)
	{

	 $ww3 = $ww2 = $ww4 ='';

	 if($this->adres[7] && $this->adres[7] == $v[2])
	 {

	  foreach($v[3] as $k2 => $v2)
	  {
	   $ww2 .= '<u> -> '.$v2.'</u>';		//- nr kolejnych bloków tekstu
	  }

	  $ll = 0;

     foreach($v[4] as $k3 => $v3)
	  {
	   //$ww3 .= '<u>'.$v3[0].'</u> ;';		//- nr kolejnych bloków tekstu
		//$ww4 .= '<img src=\''.S::pathImg().'m_'.$v3[0].'\' alt=\''.$v3[1].'\' title=\''.$v3[2].'\' />';

		//- dodać kontrolę czy istnieje plik ;) !!!

		$ww4 .= '
		<div class=\'swf_formPlace\'>
		 <img src=\''.S::pathImg().'m_'.$v3[0].'\' alt=\''.$v3[1].'\' title=\''.$v3[2].'\' />
		 <p>'.$v3[0].'</p>
		 <div >
		  <label for=\'tyt'.$ll.'\'>tytuł</label>
		  <input type=\'text\' id=\'tyt'.$ll.'\' name="tyt['.$v3[0].']" value="'.$v3[1].'">
		 </div>
		 <div>
		  <label for=\'opis'.$ll.'\'>opis</label>
		  <input type=\'text\' id=\'opis'.$ll.'\' name="opis['.$v3[0].']" value="'.$v3[2].'">
		 </div>
		 <div >
		  <label for=\'dod'.$ll.'\'>dodaj</label>&nbsp;
		  <input type=\'checkbox\' name=\'dod['.$v3[0].']\' id=\'dod'.$ll.'\'>
		 </div>
		</div>';

		 $ll++;
	  }

     unset($ll);
	 }


	 if($ww4)
	 {

	  $ww4 = '
	<form id=\'swf_loadForm\' action=\'#kot1\' method=\'post\'>
	 <input type=\'hidden\' name=\'galeria\' value=\'on\' />
	 <div id=\'swf_thumbnails\'>'.$ww4.'
	 </div>
	 <div id=\'swf_ster_form\'>
	  <input type=\'submit\' value=\'dodaj\'>
	 </div>
	 <div class=\'sw_info\'>
	  <blockquote>
	   <p>Akcja spowoduje założenie w bazie danych rekordu dla każdego zdjęcia.</p>
	   <p>Zaznacz zdjęcia, które chcesz przypiąc do aktualnej publikacji.</p>
	  </blockquote>
	 </div>
	</form>';

    }

	 //-jeśli jest zdjęcie -> można jeszcze zrobić test czy istnieje fizycznie, bo to tylko nazwa

	 $x = $this->adres;
	 $x[7] = $v[2];		//-id publikacji

	 if($v[4][1])        //-jeśli są zdjęcia
	  $ww .= '
	 <li id=\'gal'.$v[2].'\'>
	  <span>'.($k+1).'</span><a href=\''.S::linkCode($x).'+add_photo_gal.html#gal'.$v[2].'\'>'.$v[1].'</a>'.$ww2.$ww3.$ww4.'
	 </li>';
	  else
	   $ww .= '
	 <li id=\'gal'.$v[2].'\'>
	  <span>'.($k+1).'</span><span>'.$v[1].'</span>'.$ww2.$ww3.'
	 </li>';

	}

	unset($ww2, $ww3, $ww4);

	$ww = '
	<ul id=\'fotogallist\'>
	 '.$ww.'
	</ul>';


	$this->w .= $ww;

	unset($ww, $w, $k, $v, $i);
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
	<link type="text/css" rel="stylesheet" href="./cms/styl/imgareaselect-default.css"  media="screen" />';

 }

 /**
 *
 *
 *
 *
 */

 public function wynik()
 {
   C::add('javascript', $this->script());


   return '
	<div id=\'addPhoto\' class=\'fotoedit\'>'.$this->w.'
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