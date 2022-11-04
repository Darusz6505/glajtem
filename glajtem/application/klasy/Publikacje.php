<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* wtyczka uniwersalna: Zajawki publikacji z tutułem 1 członowym : v.3.5
*
* 2021-01-09 : modyfikacje do wersji PHP 7.xx
*
* 2018-02-12 : parametr $rok w wywołaniu, pokazuje tyko publikacje z danego roku jeśli jest
* 2016-05-27 : Doklejanie zdjęć z innych publikacji do nowych publikacji
* 2015-07-26 : lzajawki adresowe z tytułem zajawki
* 2014-07-12 : poprawiony powrót po edyzji bloku treści z poziomu zajawki
* 2014-07-08 : poprawki sortowania zajawek
*
* 2013-07-04 : poprawione kotwice powrotu po akcjach admina
*
*
* 2013-05-17 : poprawki Notice
* 2013-05-16 : poprawka dla linitu ilości zajawek
* 2013-04-18 : dodane pliki pdf do pobrania
*
* 2013-03-26 : poprawki dla pojedyńczej publikacji
* 2013-02-15 : zmiana zdublowanych method
* 2013-02-13 : poprawki Notice
*
* tytuły w pjedyńczej komórce
* wersje językowe
*
* 2012-12-14 : implementacja porcjowania
* 2012-12-13 : poprawione odnośniki
* 2012-12-05 : nowe odnośniki administartora ( ala FB )
* 2012-11-25 : poprwki -> nowe odnośniki administracyjne, wsp. z poprawionym skryptem add_photo
* 2012-04-21 : 2012-04-27
* 2012-03-19 :
* 2012-01-07 : gruntowna modernizacja klasy
*
* __construct
* private static function lang()
* public function zajawki($limit, $dl_zaj, $rand = false, $iden = false)
* private function zajawka($rand = false, $ident = false)
* private function zaja($limit, $t, $et) -> ograniczenie tekstu do x znaków
* public function publikacja()
* private function publikacjaPriv()
* private function addFoto($id, $rand = false, $limit = false)
* private function addFile($id)
* private function addFileZaj($id)
* private function galeriaZajawki($f, $alt = '', $title = '')
* private function script()
* public function wynik()
* function __destruct()
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2009-11-11 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/


class Publikacje extends A
{
 private $w = array(); 											//-wynik działania klasy

 protected $jo = false;  										//-wskaźnik admina

 protected $tabx = '';											//-tablica nagłówków
 protected $taby = '';											//-tablica bloków treści
 protected $tabf = '';											//-tablica ze zdjęciami
 protected $tabd = '';											//-tabela zdjęć doklejonych z innych publikacji 2016-05-27

 protected $tabp = '';											//-tablica z plikami ( 2013-04-05 )

 protected $akcja = '';											//-akcja URL
 private $opcja = '';											//-opcja URL

 private $limit = '';											//-limit ilości zajawek -> jako parametr metody
 private $dl_zaj = '';											//-limit ilości znaków w zajawce -> jako parametr metody

 private $box = '';												//-lokacja po nazwie boxu (5 wyst.) -> ładowany przez konstruktor $l['nazw']
 																		//-jeśli w polu to nazwa pola
 private $warStr = false;										//-warunek nazwy strony -> ładowany przez konstruktor $l['stro']

 public $sortPole = '';
 private $sortPo = ',pu_dapu';								//-dodatkowe sortowanie zajawek

 public $adresUst = '';
 private $adresPt = 'czytaj';									//-adres domyslny strony z pełną publikacją
 private $adresPtSkok = false;

 public $linkAddTytul ='';
 private $linkAddTytulPriv = '';								//-indywidualna nazwa odnośnika = dodaj publikację

 public $linkName = '';
 private $linkNamePriv = '';									//-indywidualna nazwa odnośnika = czytaj pełną publikację

 public $zajOrder = false;										//-sortowanie zajawek
 private $zajOrderPriv = false;								//-sortowanie losowe zajawek

 public $noInfo = false;										//-wskażnik wyłaczenia pola info
 private $noInfoPriv = false;

 public $noLoadFiles = false;									//-wskaźnik wyłaczenia odnośnika do ładowania plików=zdjeć
 private $noLoadFilesPriv = false;

 public $noBackLink = false;									//-wskażnik wyłączenia odnośnika "powrót" pod publikacją
 private $noBackLinkPriv = false;

 public $linkBackName = false;
 private $linkBackNamePriv = '';								//-nazwa odnośnika powrotu dla pełnej publikacji

 public	$ilFot = '';
 private $ilFotPriv = 1;										//-domyślna ilość zdjęć do zajawki

 public $prefTumbs = false;
 private $thumsPrefixPriv = 'm_';							//-domyślny prefix miniatur

 private static $l = array();									//-komunikaty w danej wersji językowej

 public $ilLinkNaStrone = false;								//-ilość zajawek na stronę, opcja ust. przy wywołaniu klasy

 public $targetAdress = '';

 public $tytZajPub = '';

 public $tytZajaw = '';
 private $tytZaj = 'h2';

 public $tytul = '';
 private $tytPub = 'h2';

 public $tytuBlokTre = '';
 private $tytBl = 'h3';

 public $opcjaPu = '';
 private $opcjaPr = false;

 private $rokPubPr = false;

 /**
 *
 *
 */

 function __construct($box, $warStro, $tabf = false, $rokPub = false)
 {
  //$this->jo = C::get('jo');																	//- wskaźnik Admina

  $this->jo = C::get('ja');																	//-2015-03-14 - proteza !!


  //Test::trace('$this->jo', $this->jo);

  $this->tabx = C::get('tab_publikacje');													//- tabela tematów publikacji

  $this->taby = C::get('tab_teksty');														//- tabela zawartości publikacji

  $this->tabd = C::get('tab_fotx');															//- tabela zdjęć doklejonych 2016-05-27

  $this->tabp = C::get('tab_pliki');														//- tabela z plikami

  if($tabf)
   $this->tabf = $tabf;
  else
   $this->tabf = C::get('tab_fota');														//- tabela zdjęć

  if(!$this->akcja = C::get('akcja'))														//-lokacja po adresie strony z URL
   S::ErrorAdmin('brak warunku lokacji lub warunku tematu w '.__METHOD__.' line: '.__LINE__);

  $this->box = $box;																				//- lokalizacja po nazwie box'u

  $this->adminLink();

  if($warStro === 'all')
	$this->warStr = true;																		//- znacznik, że na wszystkich stronach
  else
	$this->warStr = $warStro;

  unset($box, $warStro);

  if($rokPub)
   $this->rokPubPr = $rokPub;
	//-publikacje tylko ze wskazanego roku


  self::lang();
 }

 /**
 *
 * komunikaty w wybranej wersji językowej
 *
 * wywołanie -> self::$l['ed_tresc']
 *
 */

 private static function lang()
 {
  switch(C::get('lang'))
  {
	case 'DE':

	 self::$l = array(
	   'kom_brak_publ'		=> 'Publikacja w trakcie redagowania!<b>Zapraszamy wkrótce.</b>',
	 	'data_dodania'			=> 'dodano',
	 	'czytaj_dalej'			=> 'czytaj więcej',
		'data_dodania' 		=> 'data dodania',
		'kom_brak_tow' 		=> 'Oferta chwilowo niedostępna.',
		'szcz_oferty'			=> 'Szczegóły oferty',
		'dodaj_publ'			=> 'dodaj publikacje',
		'dodaj_publ_title'	=> 'dodaj nową lub kolejną publikację',
		'link_back_name'		=> 'powrót',
		'link_back_name_tit'	=> 'powrót do listy publikacji',
	 	'edytuj'					=> 'edytuj ustawienia właściciela serwisu',
		'brak_danych_kom'		=> 'Dane w trakcie redagowania, zapraszamy z kilka minut.',
		'do_kadr'				=> 'DO KADROWANIA'
	 );

	break;

   default:

	 self::$l = array(
	   'kom_brak_publ'		=> 'Publikacja w trakcie redagowania!<b>Zapraszamy wkrótce.</b>',
	 	'data_dodania'			=> 'dodano',
	 	'czytaj_dalej'			=> 'czytaj więcej',
		'data_dodania' 		=> 'data dodania',
		'kom_brak_tow' 		=> 'Oferta chwilowo niedostępna.',
		'szcz_oferty'			=> 'Szczegóły oferty',
		'dodaj_publ'			=> 'dodaj publikacje',
		'dodaj_publ_title'	=> 'dodaj nową lub kolejną publikację',
		'link_back_name'		=> 'powrót',
		'link_back_name_tit'	=> 'powrót do listy publikacji',
	 	'edytuj'					=> 'edytuj ustawienia właściciela serwisu',
		'brak_danych_kom'		=> 'Dane w trakcie redagowania, zapraszamy z kilka minut.',
		'do_kadr'				=> 'DO KADROWANIA'
	 );

  }
 }

 /**
 *
 * wywołanie metody prywatnej = zajawki (publikacji) + przekazanie parametrów wejściowych
 *
 */

 public function zajawki($limit, $dl_zaj, $rand = false, $iden = false)
 {
  // [lokacja][limit zajawek][max. długość tekstu dla zajawki][indywidualny identyfikator dla porcjowania]
  // $limit ::
  // $dl_zaj ::
  // $adresUst :: opcjonalnie adres do pełnego tekstu
  // $sortPole :: opcjonalnie sposób sortowania zajawek
  // $ :: opcjonalnie tytuł odnośnika dodania tytuły publikacji
  // $ :: opcjonalnie tytuł odnośnika zajawki do pełnej publikacji
  // $ :: opcjonalnie tytuł odnośnika dodania zawartości publikacji


  if($this->prefTumbs)
   $this->thumsPrefixPriv = $this->prefTumbs.'_';

  if($this->linkAddTytul != '')
  {
   if(is_string($this->linkAddTytul))
	 $this->linkAddTytulPriv = $this->linkAddTytul;
   else
    S::ErrorAdmin('nieporawidłowy parametr linkAddTytulPriv w '.__METHOD__.' line: '.__LINE__);
  }
  else
   $this->linkAddTytulPriv = self::$l['dodaj_publ'];


  if($this->linkName)  /* 2013-05-13 */
  {
   if(is_string($this->linkName))
	{
	 if($this->linkName === 'off')
	  $this->linkNamePriv = false;
	 else
	  $this->linkNamePriv = $this->linkName;
	}
   else
    S::ErrorAdmin('nieporawidłowy parametr linkName w '.__METHOD__.' line: '.__LINE__);

  }
  else
   $this->linkNamePriv = self::$l['czytaj_dalej'];


  if($this->sortPole != '')
   $this->sortPo = ', '.$this->sortPole;

  if($limit > 0)
	$this->limit = ' LIMIT '.$limit;							//-limit ilości zajawek

  if($dl_zaj)
	$this->dl_zaj = $dl_zaj;									//-długość tekstu zajawki :: jeśli -1 to tylko tytuł, bez pola info, a tytuł jest linkiem
  else
   $this->dl_zaj = 160;


  if(array_intersect(array($this->opcjaPu), array('adres')))
  {
   $this->opcjaPr = $this->opcjaPu;
	unlink($this->opcja);
  }

  if($this->adresUst)
  {
	$this->adresUst = explode('#', $this->adresUst);

	$this->adresPt = $this->adresUst[0];					//-adres pełnego tekstu jako opcja

	if(isset($this->adresUst[1])) $this->adresPtSkok = '#'.$this->adresUst[1];
  }


  if(array_intersect(array($this->zajOrder), array('rand', 'ros', 'mal'))) //- porządkowanie :: 2013-06-27
  {
   $this->zajOrderPriv = $this->zajOrder;
	unset($this->zajOrder);
  }

  if($this->noInfo)
   $this->noInfoPriv = true;									//-pole data dodania, ilość wejść :: 2012-03-19

  if($this->ilFot)
   $this->ilFotPriv = $this->ilFot;

  if($this->tytZajPub)
   $this->tytZaj = tytZajPub;									//-znacznik tytułu dla zajawki

  $this->zajawka($rand, $iden);								//-wywołanie metody prywatnej
 }

 /**
 *
 * zajawka parametryczna, generowana na podstawie tabeli nagłowka i 1-go bloku treści
 * parametr $rand = true dotyczy losowj kolejności dla miniatur zajawki
 *
 */

 private function zajawka($rand = false, $ident = false)
 {

  $opcja = C::get('opcja');

  $target = $ekran = '';
  $separ = '_';

  if($opcja)
  {
   $opcja = explode('+', $opcja);

   $opcja = explode($separ, array_pop($opcja));				//-ostatni element w opcji adresu strony

	$opcja = array_pad($opcja, 3, false);

	if($opcja[1] !== false)
	{
	 $ekran = $opcja[1];

	 if($opcja[2])
	 {
	  if($opcja[2] === $ident)
	   $_SESSION['ident_porc'][$ident] = $ekran;

    }
	}
  }

  $back = $this->akcja;

  //Test::trace('back', $back);

  $wtyk = array();																	//-wersja gdzie wynikiem jest tablica, dokodowana we wtyczce, która wywołuje klasę

  $blokx = '';
  //pu_dapu < '2018-02-12 19:54:16'

  if(!$this->jo)
  {
	if($this->rokPubPr) $blokx = "pu_dapu > '".($this->rokPubPr-1)."-12-31' AND pu_dapu < '".($this->rokPubPr+1)."-01-01' AND ";

	$blokx .= "pu_blok=0 AND pu_dapu < '".C::get('datetime_teraz')."' AND ";
  //-dla usera tylko rekordy nie blokowane z datą publikacji < teraz
  }

  if($this->warStr)
   $loka = "pu_box = '$this->box'";												//-dla boxów bublikowanych na wszystkich stronach
  else
   $loka = "pu_stro = '$this->akcja' AND pu_box = '$this->box'";		//-podwójny warunek: strona i nazwa box'u


  if($this->zajOrderPriv === 'rand') //if($this->zajOrderPriv === 'rand' || $rand)
   $order = 'rand()';
  elseif($this->zajOrderPriv === 'ros')
   $order = 'pu_stat, pu_dado, pu_id';
  else
   $order = 'pu_stat DESC, pu_dado DESC, pu_id DESC';												//-poprawiony oczywisty błąd 2013-05-21


  if(!$this->limit)
  {
   $tab = 'SELECT * FROM '.$this->tabx.'
  		  	  WHERE '.$blokx.$loka.'
  		  	  ORDER BY ' . $order . $this->sortPo.S::limit($ekran,$this->ilLinkNaStrone, $ident);
  }
  else
  {
   $tab = 'SELECT * FROM '.$this->tabx.'
  		  	  WHERE '.$blokx.$loka.'
  		  	  ORDER BY ' . $order . $this->sortPo . $this->limit;
  }

  unset($order, $desc);

  $seo_tyt = '';
  $name = '';

  //exit($tab);

  if($tab = DB::myQuery($tab))
   while($ta = mysqli_fetch_assoc($tab))
   {

	 $ta['pu_tytu'] = S::langVersion(strip_tags($ta['pu_tytu']));								//-wydzielenie wersji językowej, dla tytułu

	 if(!$seo_tyt && $this->akcja != 'start') $seo_tyt = S::seoText($ta['pu_tytu'], 500).' :: ';

	 $title = S::seoText($ta['pu_tytu'], 100).' : '.self::$l['czytaj_dalej'];


	 if($this->opcjaPr == 'adres')
	 {
		$link = $ta['pu_tytu'];
		$ta['pu_tytu'] = parse_url($ta['pu_tytu']);
		$ta['pu_tytu'] = $ta['pu_tytu']['host'];
		$target = ' target=\'_blanc\'';
		//-pozwala wykadrować foto jeśli link jest adresem zewnętrznej strony
		$link2 = S::seoLink($ta['pu_tytu'], $ta['pu_id'], $ekran).'czytaj.html'.$this->adresPtSkok;
	 }																												//-link do zewnętrznej strony jako parametr metody
	 else
	  $link = S::seoLink($ta['pu_tytu'], $ta['pu_id'], $ekran).$this->adresPt.'.html'.$this->adresPtSkok;

	 $blok = '';

    if($this->jo)
	 {
	  $addClass[] = 'edZaj';
	  $li_atr = $this->odnAdminTytul($ta['pu_id'], $this->akcja);								//-link admina, do edycji tytułu publikacji
	 }
	 else
	 {
     $blok = "tr_blok=0 AND tr_dapu<'".C::get('datetime_teraz')."' AND";
	  $li_atr = false;
	  $addClass = false;
	 }

	 if($ta['pu_tytu']) $ta['pu_tytu'] = S::formText($ta['pu_tytu']);

	 //-treść publikacji, w zajawkach tylko pierwszy blok

    $tab2 = 'SELECT * FROM '.$this->taby.'
	  			 WHERE '.$blok.' tr_idte='.$ta['pu_id'].'
				 ORDER BY tr_stat DESC, tr_dapu
				 LIMIT 1';

  	 unset($blok);

	 $opis = '';

	 if($tab2 = DB::myQuery($tab2))
     if($tb = mysqli_fetch_assoc($tab2))
	  {
      $fo = false;
		$alt = '';

		$tb['tr_text'] = S::langVersion($tb['tr_text']);							//-wydzielenie wersji językowej


		if($this->linkNamePriv != '')
		 $title = $this->linkNamePriv;
		else
		 $title = self::$l['czytaj_dalej'];


		if($this->dl_zaj > -1)																//-jeśli zajawka jest typowa (fragment tekstu i odnośnik do reszty)
	   {

	    if($this->jo) $li_atr .= $this->odnAdminBlokZaj2($ta['pu_id'], $tb['tr_id'], $back);

		 $fo = $this->addFoto($tb['tr_id'], $rand, $this->ilFotPriv);			//-tworzy tablicę galerii dla wskazanego bloku treści, id = $tb['tr_id']

	    if(substr($tb['tr_text'], 0, 6) != '[null]') 								//-treść [null] wyłancza linki pod zajawką, bo pole text jest obowiązkowe!
	    {

	     if(strlen($tb['tr_text']) > $this->dl_zaj || $fo || $this->opcjaPr == 'adres')		//-jeśli tekst przekracza długość zajawki lub jest zdjęcie
		  {
	      if(!$this->noLicznik && $ta['pu_kli0'])
			 $name = '<i>'.$ta['pu_kli0'].'</i>';
			else
			 $name = '';

		   $name .= '<a class=\'more\' href=\''.$link.'\' title=\''.$title.'\''.$target.'>'.$this->linkNamePriv.'</a>';
		  }

		  if($fo)
		   if($this->jo && $this->opcjaPr == 'adres') //-pozwala wykadrować foto jeśli link jest adresem zewnętrznej strony
		   {
			 $fo = '
			 <div class=\'galeriaZaj\'>
			  <a href=\''.$link2.'\' title=\''.$title.'\'>'.$this->galeriaZajawki($fo, $alt, $title).'</a>
			 </div>';
			}
		   else
			{
			 $fo = '
			<div class=\'galeriaZaj\'>
			 <a href=\''.$link.'\' title=\''.$title.'\''.$target.'>'.$this->galeriaZajawki($fo, $alt, $title).'</a>
			</div>';
			}



		  $fi = $this->addFileZaj($tb['tr_id']);

	     if(!$this->noInfoPriv)
			$name = '
		<div class=\'info\'>'.self::$l['data_dodania'].' '.S::kdata($ta['pu_dapu'],0).' '.$name.'</div>';
		  else
			$name = '
		<div class=\'info\'>'.$name.'</div>';


		  if($this->dl_zaj > 1)															//-warunek aby fotka była linkiem
		  {

			if(!$fo) $fo = '';

	      if($tb['tr_text']) $opis = '
		<blockquote>'.$fo.'
		 <a class=\'zajText\' href=\''.$link.'\' title=\'czytaj całość\''.$target.'>
		  <p class=\'akapit\'>'.S::formZaja($this->zaja($this->dl_zaj, $tb['tr_text'], ' ... czytaj całość')).'</p>'.$fi.'
		 </a>
		</blockquote>';

		    if($this->opcjaPr == 'adres')
			 {
			  $opis = '<h3>'.$tb['tr_tytu'].'</h3>'.
			  $opis;

			 }
			}
			else  // dla dl_zaj == 1
			{
			 $opis .= $fo;
			}

	     }
		  else  //dla znaczniku [null] w treści bloku
		  {

			if($fo)
			 $opis .= '
		<blockquote>
		 <div class=\'galeriaZaj\'>'.$this->galeriaZajawki($fo, $alt, $title).'
		 </div>
		</blockquote>';

		  }


		  if($li_atr) $li_atr = '
		  <div class=\'ed edR edTr\'>'.$li_atr.'
		  </div>';

		  $wt = $li_atr.'
		 <a class=\'zajTytLink\' href=\''.$link.'\' title=\''.$title.'\''.$target.'>
		 <'.$this->tytZaj.' class=\'tyt\'>'.$ta['pu_tytu'].'</'.$this->tytZaj.'></a>'.$opis.$name;

       //-zawartość publikacji - END - złożenie zajawki

   	 }
		 else //-jeśli zajawka jest linkiem = -1
		 {

		  if($li_atr) $li_atr = '
		  <div class=\'ed edR edTr\'>'.$li_atr.'
		  </div>';

		  $wt = $li_atr.'
		 <a href=\''.$link.'\' class=\'zaj_link\' title=\''.$title.'\'>'.$ta['pu_tytu'].'</a>';

		 }

	   } 		//-end if taby = pierwszy blok treści
      else 	//-brak bloku treści !!
		{

		 if($this->jo)
		 {
		  //-dodatkowo opcja dodania bloku treści

		  if($li_atr = $this->odnAdminTytul2($ta['pu_id'], $this->akcja))
			$li_atr = '
		  <div class=\'ed edR edTr\'>'.$li_atr.'
		  </div>';
       }


		 $wt = $li_atr.'
		 <a href=\''.$link.'\' class=\'zaj_link\' title=\''.$title.'\''.$target.'>
			<'.$this->tytZaj.' class=\'tyt\'>'.$ta['pu_tytu'].'</'.$this->tytZaj.'></a>';
		}


      if($ta['pu_blok'])														//-oznaczenie bloków zablokowanych i przygotowanych do publikacji
	    $addClass[] = 'blok';
	   else
	    if($ta['pu_dapu'] > C::get('datetime_teraz'))
		  $addClass[] = 'dopu';

		if(is_array($addClass)) $addClass = implode(' ', $addClass);


	   if($ta['pu_tytu'])
		{

		 $wtyk[] = array('art'.md5($ta['pu_id']), $wt, $addClass);
		}

	   unset($fo, $opis, $name, $do, $title, $link, $wt, $addClass);

     } //end while tabx = tytuły

    if(!$wtyk)
	 {
	  $wtyk[] = array(false, '<p class=\'brak_pub\'>'.self::$l['kom_brak_publ'].'</p>');
	  //-Publikacja w trakcie przygotowania. Zapraszamy wkrótce!
	 }
	 else
	 {
	  if($l = S::ekranLink($this->tabx, $blokx.$loka, $this->akcja, $ekran, $this->ilLinkNaStrone, $ident))
		$wtyk[] = array('linki', $l);

	 }

    if($this->jo)
    {
	  if(!$this->targetAdress) $this->targetAdress = $this->akcja;

	  $wtyk[] = array
	  (
		'addPub',
		'<a class=\'dodaj\'
		href=\''.S::linkCode(array($this->tabx,0,'formu','pu_stat.10.pu_blok.1.pu_box.'.$this->box.'.pu_stro.'.$this->targetAdress, $back, 'art')).'.htmlc\'
		title=\''.self::$l['dodaj_publ_title'].'\' >'.$this->linkAddTytulPriv.'-x8</a>'
	  );

	  //Test::trace('link dodaj publikacje', array($this->tabx,0,'formu','pu_stat.10.pu_blok.1.pu_box.'.$this->box.'.pu_stro.'.$this->targetAdress, $back, 'art'));

    }

	 if(!C::get('seo', false) && $seo_tyt) C::change('seo', $seo_tyt);


	 if(C::get('plusGoogle'))
	  C::add('javascript_top', '
	<meta itemprop=\'name\' content=\''.C::get('con_nazw', false).'\'>
	<meta itemprop=\'description\' content=\''.C::get('con_desk', false).'\'>');


    unset($tab, $ta, $tab2, $tb, $li_atr, $blok, $lok, $seo_tyt);

    $this->w = $wtyk;
 }

 /**
 *
 * zajawka wiekszego tekstu - przycięcie tekstu :: 2011-03-25 -> 2012-01-07
 * limit = limit znaków, $t = tekst, $et = dodatkej do okrojonego tekstu
 *
 */

 private function zaja($limit, $t, $et)
 {

  if(strlen($t) > $limit)  								//-jeśli tekst źródłowy jest dłuższy od limitu
  {

	$tmp = substr($t, 0, $limit);							//-przycinamy tekst dozadanego limitu

	$tmp = substr($tmp, 0 , strrpos($tmp, ' '));		//-przycinamy po raz kolejny do ostatniej spacji

	while(substr($tmp, -2, -1) == ' ')					//-jeśli przed ostatni znak to spacja, przycinamy jeszcze o 2 znaki
	{
	 $tmp = substr($tmp, 0, -2);							//-powtarzamy aż do skutku dla np. dlatego i w takich ...
	}

   return $tmp.$et;
  }
  else
   return $t;
 }

 /** ############################################################################################################
 *
 * wywołanie metody publikacji wybranego tematu
 * 2012-006-26 : poprawka wyłacznika pola info
 *
 */

 public function publikacja()
 {

  $this->opcja = C::get('opcja')? C::get('opcja') : '';

  if($this->noBackLink)
  {
   $this->noBackLinkPriv = true;							//-link "powrót" pod publikacją
	unset($this->noBackLink);
  }
  else
   if($this->linkBackName)
	{
	 $this->linkBackNamePriv = $this->linkBackName;
	 unset($this->linkBackName);
	}
	else
	 $this->linkBackNamePriv = self::$l['link_back_name'];

  if($this->noInfo)
  {
   $this->noInfoPriv = true;
   unset($this->noInfo);
  }

  if($this->prefTumbs)
   $this->thumsPrefixPriv = $this->prefTumbs.'_';

  $this->publikacjaPriv();
 }

 /**
 *
 * właściwa metoda publikacji wybranego tematu
 *
 *
 */

 private function publikacjaPriv()
 {

  $blok = $wtyk = $seo_tyt = $limit = $ekran = $clwt = $fb = $con_desk = '';

  if($this->opcja)
	$back = $this->opcja.'+'.$this->akcja;
  else
	$back = $this->akcja;

  if($this->opcja)
  {

	$id_ekran = explode('+', $this->opcja);
	$id_ekran = explode('_', end($id_ekran));

	if(isset($id_ekran[0]))
	 $idd = substr($id_ekran[0], 3, -3);
	else
	 $idd = '';

	if(isset($id_ekran[1]))
	 if($id_ekran[1]) $ekran = '_'.$id_ekran[1].'+';

	unset($id_ekran);

	if(is_numeric($idd))
    $textId = "pu_id='".$idd."'";
	else
	 $textId = "pu_stro = '".$this->akcja."' AND pu_box = '".$this->box."'";

  }
  else
   $textId = "pu_stro = '".$this->akcja."' AND pu_box = '".$this->box."'"; 										//-dla publikacji bezpośrednich

  if(!$this->jo) $blok = "pu_blok=0 AND pu_dapu<'".C::get('datetime_teraz')."' AND";

  $tab = "SELECT * FROM $this->tabx WHERE $blok $textId LIMIT 1";

  if($tab = DB::myQuery($tab))
   if($ta = mysqli_fetch_assoc($tab))
   {
    $li_atr = '';

	 if($this->tytPub)

	 $ta['pu_tytu'] = strip_tags($ta['pu_tytu']);

    $ta['pu_tytu'] = S::langVersion($ta['pu_tytu']);																		//-wydzielenie wersji językowej

	 if($ta['pu_ogra']) C::set('warPub', true); 																				//- warunek dla publikacji, tylko pierwszy blok

	 $seo_tyt = S::seoText($ta['pu_tytu'], 500).' :: ';

    if(!$this->jo)
	  $blok = "tr_blok=0 AND tr_dapu<'".C::get('datetime_teraz')."' AND";
    else
	  $blok = '';

	 $tab2 = "SELECT * FROM $this->taby
	 			 WHERE $blok tr_idte='{$ta['pu_id']}'
				 ORDER BY tr_stat DESC, tr_dapu $limit";

	 $wt = $opis = '';
	 $liczBlok = 0;

	 //Test::trace('Query', $tab2);

    if($tab2 = DB::myQuery($tab2))
     while($tb = mysqli_fetch_assoc($tab2))   													// && !$tylkoZal
     {
	   $opis = '';

      $liczBlok++;

		$tb['tr_tytu'] = S::langVersion(strip_tags($tb['tr_tytu']));						//-wydzielenie wersji językowej
		$tb['tr_text'] = S::langVersion(strip_tags($tb['tr_text']));						//-wydzielenie wersji językowej

		/*
	   if($ta['pu_ogra'] && !$_SESSION['kl_zalog'] && !C::get('jo'))
		 $tylkoZal = true;																				//-jeśli publikacja dla zalogowanych to tylko pierwszy blok
		else
		 $tylkoZal = false; */

	   $fo = $this->addFoto($tb['tr_id']);															//-tworzy tablicę galerii dla wskazanego id

		$fi = $this->addFile($tb['tr_id']);															//-tworzy tablicę plików do pobrania ( pdf )


		$altZap = $titleZap = $nameZap  = $seo_tyt;

	   $fo = S::pozFotoNew($fo, C::get('fotyPathAbs'), $altZap, $titleZap, $nameZap, $this->thumsPrefixPriv, $this->tabf, $back); 	//-pozycjonowanie zdjęć

      if($this->jo && ($tb['tr_text'] || $fo))
	   {
	    $li_zaw3 = ' id=\'art'.md5($tb['tr_id']).'\'';

		 if($fo)
		  $li_zaw = $this->delFoto($tb['tr_id'], $ta['pu_id']);
		 else
		  $li_zaw = '';

		 $li_zaw = '
		 <div class=\'ed edR edTr\'>'.$this->odnAdminBlok($ta['pu_id'], $tb['tr_id'], $back, 'art').$li_zaw.'
		 </div>';
	   }
		else
		{
		 $li_zaw3 = '';
		 $li_zaw = '';
		}

		//-do FB - sekcji head description - część tekstu z pierwszego bloku bez znaczników html i BBbbcode

		if(!$con_desk) $con_desk = S::seoText($ta['pu_tytu'].'|'.$tb['tr_tytu'].'|'.$tb['tr_text'], 1000);


		if($tb['tr_text']) $tb['tr_text'] = S::formText($tb['tr_text']);													//-formatowanie tekstu


		if(!$this->tytBl)
		{

		 if(substr($this->tytul, -1) < 7)
		  $this->tytBl = 'h'.(substr($this->tytul, -1) + 1 );
		 else
		  S::ErrorAdmin('nieprawidłowy znacznik '.__METHOD__.':'.__LINE__);
		}

	   if($tb['tr_tytu']) $opis = '
		<'.$this->tytBl.'>'.S::formText($tb['tr_tytu']).'</'.$this->tytBl.'>';

      if($tb['tr_text'])
		{
       if($tb['tr_text'] != '[null]') $opis .= '
	<blockquote'.S::klasaPola($tb['tr_blok'], $tb['tr_dapu'], C::get('datetime_teraz'), 1).'>'.$fo[0].$fo[1].$fo[2].$tb['tr_text'].$fo[3].'
	</blockquote>';
	 	 else
	  	 $opis .= '
	<blockquote'.S::klasaPola($tb['tr_blok'], $tb['tr_dapu'], C::get('datetime_teraz'), 1).'>'.$fo[0].$fo[1].$fo[2].$fo[3].'
	</blockquote>'; // z wycietym przez [null] tekstem
 		}

	 	if($opis) $wt .= '
	<div'.$li_zaw3.' class=\'blok_publ\'>'.$li_zaw.$opis.$fi.'
	</div>';   //.$li_doFoty ???

	 	unset($opis, $fo, $fi, $altZap, $titleZap, $nameZap);

	  } //koniec petli po blokach treści

		unset($li_zaw, $li_zaw3);

	  if(!$wt)
	  {
		$wt = '
		<p class=\'brak_pub\'>'.self::$l['kom_brak_publ'].'</p>'; 	//-Publikacja w trakcie przygotowania. Zapraszamy wkrótce!


		if($this->jo)
		 if($li_atr = $this->odnAdminBlokTr($ta['pu_id'], $this->akcja, 'art'));

		 // protected function odnAdminTytul($ta, $back, $pref = 'art')
		 //-po skasowaniu ostatniego bloku treści, daje możliwość dodania bloku
		 //-dla stron docelowych daje możliwość dodania treści

	  }
 	  else
	  {

		if($seo_tyt) C::change('seo', $seo_tyt);

		if($con_desk) C::change('con_desk', $con_desk);

		if(C::get('fb')) $fb = S::FB_Like($seo_tyt.' | '.$con_desk);

		if(C::get('plusGoogle'))
		 C::add('javascript_top', '
		<meta itemprop=\'name\' content=\''.$seo_tyt.'\'>
		<meta itemprop=\'description\' content=\''.$con_desk.'\'>');

		$clwt = ' over';
	  }


     if($this->jo)
	  {
      $li_atr = '
		<div class=\'edT'.$clwt.'\'>
		 <div class=\'ed edR edTt\'>'.$this->odnAdminTytul($ta['pu_id'], $back).$li_atr.'
		 </div>';

	   $ende = '
	  	</div>'; //-tak aby link pokazywał się po najechaniu na dowolne miejsce w publikacji, na dowolnym bloku
	  }
	  else
	  {
		$li_atr = '';
		$ende = '';
	  }

     $wtyk = $li_atr.'
	   <'.$this->tytPub.'>'.S::formText($ta['pu_tytu']).'</'.$this->tytPub.'>'.$wt.$fb.$ende;

     unset($do, $wt, $clwt, $fb, $li_atr, $ende);
   }

  //-HTML

  if(!$wtyk)
  {

	$wtyk = '
	<p class=\'brak_pub\'>'.self::$l['kom_brak_publ'].'</p>';

   if(C::get('jo')) $wtyk .= '
		<p class=\'kom_admin\'>Brak publikacji dla tej lokacji!</p>
		<a class=\'dodaj\'
		href=\''.S::linkCode(array($this->tabx,0,'formu','pu_stat.10.pu_blok.1.pu_box.'.$this->box.'.pu_stro.'.$this->akcja, $back)).'.htmlc\'
		title=\''.self::$l['dodaj_publ_title'].'\' >'.self::$l['dodaj_publ'].'</a>';

  }
  else
  {
   C::add('javascript', $this->script());		//-skrypt przegladarki zdjęć
	C::add('adcss', $this->cssArtyk());			//-css dlo formatowania zdjęć

 	if(!$this->noInfoPriv) $wtyk .= '
	<div class=\'info\'>'._OPUBLIKOWANE.' '.S::kdata($ta['pu_dapu'], 0);
   else
	 $wtyk .= '
	<div class=\'info\'>';


	if(isset($_SESSION['zajLinkBack']))
	 $linkBack = $ekran.$_SESSION['zajLinkBack'];
	else
	 $linkBack = $ekran;

	if(!$linkBack)
	  $linkBack = 'start.html';					//-jeśli wejście nastąpiło za pośrednictwem adresu zewnętrznego



	Test::trace('seesjon-zjLinkBAck', $_SESSION['zajLinkBack']);
	Test::trace('$linkBack', $linkBack);

	if(isset($idd)) $idd = '#art'.md5($idd); else $idd = '';

	if(!$this->noBackLinkPriv) $wtyk .= '
	<a class=\'back\' href=\''.$linkBack.$idd.'\' title=\''.self::$l['link_back_name_tit'].'\'>'.$this->linkBackNamePriv.'</a>';

	$wtyk .= '</div>';
  }

  $this->w = $wtyk;

  unset($fb, $wtyk, $con_desk, $fb_tytul, $tab, $tabx, $ta, $tab2, $taby, $tb, $li_atr, $li_zaw, $textId, $wt, $blok, $idd, $lok, $linkBack, $liczBlok);
 }

 /**
 *
 * pobranie galerii zdjeć należących do wskazanego id
 *
 * 2016-05-27 : zdjięcia doklejone z innych publikacji
 * 2012-01-07
 *
 */

 private function addFoto($id, $rand = false, $limit = false)
 {
  if(!$id || $id == '' || !is_numeric($id))
	S::ErrorAdmin('Parametr id musi być liczbą i jest obowiązkowy w '.__METHOD__.' line: '.__LINE__);

  if($limit && !is_numeric($limit))
   S::ErrorAdmin('Parametr limit musi być liczbą! w '.__METHOD__.' line: '.__LINE__);
  else
	$toLimit = 1;

  $pat_lok = C::get('fotyPath');


  $war = '';

  if(!$this->jo) $war = 'AND fo_blok = 0';		//-tylko zdjęcia, które nie są zablokowane

  if(!$rand)
   $tab = "SELECT * FROM ".$this->tabf."
			 WHERE fo_idte = $id $war
			 ORDER BY fo_id DESC";
  else
   $tab = "SELECT * FROM ".$this->tabf."
			 WHERE fo_idte = $id $war
			 ORDER BY rand()";


  if($tabx = DB::myQuery($tab))
  {
   while($ta = mysqli_fetch_assoc($tabx))
	{
    /* limit tak dlatego bo jeśli będzie brak jakiegoś pliku,
	 to zabraknie rekordów aby spełnic warunek ilości miniatur do zajawki*/

	 if($limit)
	 {
	  if(file_exists($pat_lok.$this->thumsPrefixPriv.$ta['fo_fot0']))
	  {

	   $f[] = $ta;

		if($toLimit == $limit)
		 return $f;
		else
		 $toLimit++;
	  }
	 }
	 else
	  $f[] = $ta;
	}

	/*
	 Jeśli ilość zdjęć mniejsza niż ustalony limit, to rpóba pobrania z katalogu tymczasowego.
	 To działa raczej tylko przed kadrowaniem !!! Czyli jak nie ma jeszc ze zdjeć, które zostały wykadrowane.
	 Przypadek przerwania kadrowania i ponowna edycja publikacji.
	*/

	if($toLimit <= $limit)
	{
	 $pat_lok = C::get('tmpPath_foty');

	 if($tabx = DB::myQuery($tab))
	 while($ta = mysqli_fetch_assoc($tabx))  //- tu prawdopodobnie zapytanie do bazy jest zbedne, ewent. reset $ta
	 {

	  if($limit)
	  {
	   if(file_exists($pat_lok.$ta['fo_fot0']))
	   {

	    $f[] = $ta;

		 if($toLimit == $limit)
		  return $f;
		 else
		  $toLimit++;
	   }
	  }
	  else
	   $f[] = $ta;
	 }
	}
  }

  /*
	na koniec zdjecia przypięte

   UWAGA! nie ma pola do blokowania widoczności zdjęć. Niepotrzebne, blokuje się raczej blok treści lub całą publikacje.
  */

  if(!$rand)
   $tab = "SELECT * FROM ".$this->tabd."
			 WHERE fo_idte = $id
			 ORDER BY fo_id DESC";
  else
   $tab = "SELECT * FROM ".$this->tabd."
			 WHERE fo_idte = $id
			 ORDER BY rand()";

  $pat_lok = C::get('fotyPath');

  if($tabx = DB::myQuery($tab))
  {
   while($ta = mysqli_fetch_assoc($tabx))
	{

	 if($limit)
	 {
	  if(file_exists($pat_lok.$this->thumsPrefixPriv.$ta['fo_fot0']))
	  {

	   $f[] = $ta;

		if($toLimit == $limit)
		 return $f;
		else
		 $toLimit++;
	  }
	 }
	 else
	  $f[] = $ta;
	}
  }

  unset($tab, $ta, $toLimit, $limit, $pat_lok, $rand, $id, $war);

  #######

  if(isset($f))
	return $f;
  else
	return;

 }

 /**
 *
 * prezentacja ikon plików do pobrania
 *
 */

 private function addFile($id)
 {

  if(!$id || $id == '' || !is_numeric($id))
	S::ErrorAdmin('Parametr id musi być liczbą i jest obowiązkowy w '.__METHOD__.' line: '.__LINE__);

  $pat_lok = C::get('fotyPath');

  $war = '';

  if(!$this->jo) $war = 'AND fo_blok = 0';		//-tylko pliki, które nie są zablokowane

  $tab = "SELECT * FROM ".$this->tabp."
			 WHERE fo_idte = $id $war
			 ORDER BY fo_id DESC";

  $files = '';

  if($tabx = DB::myQuery($tab))
  {
   while($ta = mysqli_fetch_assoc($tabx))
	 if(file_exists($pat_lok.$ta['fo_fot0']) && $ta['fo_fot0'])
	 {
	  if($this->jo)
	   $files .= '
	  <div class=\'download edFile\'>
	    <div class=\'ed edL edTr\'>
		  <a href=\''.S::linkCode(array('fot',$this->tabp,$ta['fo_id'],'edycja')).'.htmlc\' title=\''.L::k('edyF').'\'>'.L::k('edytuj').'</a>
		  <a class=\'del\' href=\''.S::linkCode(array('fot',$this->tabp,$ta['fo_id'],'kasuj')).'.htmlc\'
			title=\''.L::k('kasF').'\'
			alt=\''.L::k('kas').'\'>'.L::k('kasuj').'</a>
		 </div>
	  <img src=\'./skin/PDF50.png\' alt=\''.$ta['fo_tytu'].'\'><span >'.$ta['fo_tytu'].'</span></div></a>';
	  else
	   $files .= '
	  <a href=\''.$pat_lok.$ta['fo_fot0'].'\' target=\'_blank\' title=\'kliknij aby pobrać - '.$ta['fo_tytu'].'\'>
	  <div class=\'download\'>
	  <img src=\'./skin/PDF50.png\' alt=\''.$ta['fo_tytu'].'\'><span >'.$ta['fo_tytu'].'</span></div>';

    }
	 else
	 {
	 if($this->jo)
	   $files .= '
	  <div class=\'download edFile\'>
	    <div class=\'ed edL edTr\'>
		  <a href=\''.S::linkCode(array('fot',$this->tabp,$ta['fo_id'],'edycja')).'.htmlc\' title=\''.L::k('edyF').'\'>'.L::k('edytuj').'</a>
		  <a class=\'del\' href=\''.S::linkCode(array('fot',$this->tabp,$ta['fo_id'],'kasuj')).'.htmlc\'
			title=\''.L::k('kasF').'\'
			alt=\''.L::k('kas').'\'>'.L::k('kasuj').'</a>
		 </div>
	  <img src=\'./skin/PDF50.png\' alt=\''.$ta['fo_tytu'].'\'><span >BRAK PLIKU DLA REKORDU!</span></div>';

	 }
  }

  unset($tab, $ta, $pat_lok, $id, $war);

  if($files)
	return $files;
  else
	return '';

 }

 /**
 *
 *
 *
 */

 private function addFileZaj($id)
 {
  if(!$id || $id == '' || !is_numeric($id))
	S::ErrorAdmin('Parametr id musi być liczbą i jest obowiązkowy w '.__METHOD__.' line: '.__LINE__);

  $pat_lok = C::get('fotyPath');

  $war = '';

  if(!$this->jo) $war = 'AND fo_blok = 0';		//-tylko pliki, które nie są zablokowane

  $tab = "SELECT * FROM ".$this->tabp."
			 WHERE fo_idte = $id $war";

  $files = '';
  $fcount = 0;

  if($tabx = DB::myQuery($tab))
  {
   while($ta = mysqli_fetch_assoc($tabx))
	 if(file_exists($pat_lok.$ta['fo_fot0']))
	 {
	  if($fcount < 2) $files[] = '<img src=\'./skin/PDF50.png\' alt=\''.$ta['fo_tytu'].'\'>';
	  $fcount++;
	 }
  }


  if($fcount > 1)
   $end = '...('.$fcount.')';
  else
   $end = '';

  if(is_array($files))
   $files = implode('
  ', $files).$end;

  unset($tab, $ta, $pat_lok, $id, $war);

  if($files)
	return $files;
  else
	return '';

 }

 /**
 *
 * galeria zajawki
 *
 * 2016-05-27 : zdjecia doklejone z innych publikacji
 * 2012-04-07
 *
 */

 private function galeriaZajawki($f, $alt = '', $title = '')
 {
  if(!$f) return; //-galeria pusta

  $fo = '';

  $pat_lok = C::get('fotyPath');
  $pat_tmp = C::get('tmpPath_foty');

  $path = S::pathImg();						//-path bezwględny np. dla FB

  for($i=0; $i < $this->ilFotPriv; $i++)
  {
   if(isset($f[$i]['fo_fot0']))
	{

	 if(file_exists($pat_lok.$this->thumsPrefixPriv.$f[$i]['fo_fot0']))
	 {

     if($f[$i]['fo_tytu']) $talt[] = html_entity_decode(strip_tags($f[$i]['fo_tytu']));

     if($f[$i]['fo_opf0']) $talt[] = html_entity_decode(strip_tags($f[$i]['fo_opf0']));

	  if(isset($f[$i]['fo_blok']))
	  {
	   if($f[$i]['fo_blok'])
		{
	    $fblok = ' class=\'adm_fblok\'';
       $zablo = '<p class=\'adm_zablok\'>'.L::k('blokF').'</p>';
		}
	  }
	  else
	  {
	   $fblok = '';
      $zablo = '';
	  }

	  if(isset($talt)) $alt = implode(', ', $talt); else $alt = '';

	  $fo .= $zablo.'
	<img'.$fblok.' src=\''.$path.$this->thumsPrefixPriv.$f[$i]['fo_fot0'].'\' alt=\''.$alt.'\' title=\''.$title.'\' />';
    }
	 else
	  if($this->jo)
	  {
	   if(file_exists($pat_tmp.$f[$i]['fo_fot0']))
		 $fo .= '
		<div class=\'do_kadr\'>
		 <p class=\'do_kadr_i\'>&nbsp;'.self::$l['do_kadr'].'&nbsp;</p>
		 <img src=\'thumbnail.php?id='.$f[$i]['fo_fot0'].':'.session_id().':170:170\' alt=\''.L::k('doKadr').'\' />
		</div>';
	  }

    unset($talt, $fblok, $zablo);
	}
  }

  unset($path, $fblok, $zablo);

  if($fo) return $fo; else return;
 }


 /**
 *
 * skrypty dołanczany przez klasę do metody publikacja :: galeria lightbox
 *
 */

 private function script()
 {
  return '
 <script type="text/javascript" src="jqlightbox05/js/jquery.lightbox-0.5.js"></script>
 <link rel="stylesheet" type="text/css" href="jqlightbox05/css/jquery.lightbox-0.5.css" media="screen" />';
 }

 /**
 *
 * styl dla pełnej publikacji wybranego artykułu
 * formatowanie zdjęć
 *
 */

 private function cssArtyk()
 {
  return '
 <link rel="stylesheet" href="./application/foto.css" type="text/css" media="screen" />';

 }

 /**
 *
 *
 */

 public function wynik()
 {
 	return $this->w;
 }

 /**
 *
 *
 */

 function __destruct()
 {
  unset($this->tabx, $this->taby, $this->limit, $this->dl_zaj, $this->akcja);
 }

}
?>