<?
defined('_CMSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* Główna klasa obsługująca panel administratora :: v.1.2
*
* 2021-01-09 : modyfikacje do wersji PHP 7.xx
*
* 2013-01-04 :: zmieniono lokalizację wtyczek dla menu dodatkowego na application/cms/
* 2012-02-01 :: doodano menu doodatkowe
*
* połączyć tą klasę z CmsStart !!!!!!!!
*
*
* Klasa obsługujaca wtyczki CMS'a i CMS'a E-sklepu
*
* klasy dla silnika etvn & aleproste.pl  ---------------------- 2010-10-08
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -- 2010-06-15 ---UTF-8
*
*/

class Admin
{

 private $m = array();						//-tablica menu głównego
 private $md = array();						//-tablica menu dodatkowego
 private $side = ''; 						//-podstrona -> wtyczka CMS'a
 private $opcja = ''; 						//-opcja
 private $x = '';       					//-wynik działania klasy

 /**
 *
 *
 */

 function __construct($m = false, $md = false)
 {
  Test::trace(__METHOD__);

  require_once './cms/config/config_cms'._EX;										//-konfig statyczny dla CMS'

  $this->opcja = C::get('opcja');														//-załadowanie zmiennej 'opcja'

  $this->side = isset($_GET['sp'])?C::odbDane($_GET['sp']):''; 				//-odbiór nazwy wtyczki dla CMS'a -> podstrona panelu

  if($this->side=='wyloguj' || $this->side=='logout')								//-akcja dla wylogowania z CMS'a
  {
	require_once './cms/logout'._EX;														//-wylogowanie -> zrobić z tego metodę !!!
  }
  else
  {

	if($m) $this->m = $m;  else exit('Brak menu podstawowego! -> '.__METHOD__. '-> '.__LINE__);

	//if($m[1]) if($m[1]) $this->md = $m[1]; else $m[1] = false;				//-załadowanie menu dodatkowego 2013-02-05
   // menu dodatkowe tylko w application/cms

	unset($m);

   $this->adm();																				//-wywołanie metody ciała CMS'a
  }

 }

 /** ./cms/klasy/Admin.php
 @
 * - połączenie z bazą danych
 * - test czy istnieje baza danych
 * - załadowanie odpowiedniej wtyczki
 * - główny szkielet strony CMS'a
 */

 private function adm()
 {
  Test::trace(__METHOD__);

  $fx = '';
  $fy = '';
  $fz = '';

  $mysql = new Db;

  try				//-sprawdza, czy baza istnieje
  {

	$d['baza'] = '
   <b class=\'error\'>BAZA DANYCH NIE ISTNIEJE!!</b>';

	 if($tab = DB::myQuery('SHOW DATABASES'))
     while($ta = mysqli_fetch_assoc($tab))
      if(C::get('akt_baza') == $ta['Database'])
       $d['baza'] = 'aktywna baza : <b>'.$ta['Database'].'</b>';

  }
  catch(Exception $e)
  {
   $fx .= C::debug($e, 0);
  }

  Test::trace('$d[baza]', $d['baza']);

  unset($tab, $ta);

	//-określenie ścieżki dla oddzielonych CMS'sów serwisu i sklepu
	/*
	if($this->opcja == 'sklep')
	 $sw = 1;
	else
	 $sw = $_SESSION['admin']['status'];

   switch($sw)
	{
	 case 1:
	 	$path = './cms_sklep/d_';

		if($this->opcja)
 			$_SESSION['back2'] = $this->opcja.'+'.$this->side.'.smsl';
		else
 			$_SESSION['back2'] = $this->side.'.smsl';

	 break;

	 default:
	  $path = './cms/';
	}
	unset($sw); */

	$path = './cms/';

   if(file_exists($path.$this->side._EX) && $this->side)
	{
    include $path.$this->side._EX; 													//-wczytanie wtyczki CMS'a jeśli itnieje
	}
   elseif(file_exists('./application/cms/'.$this->side._EX) && $this->side)
	{
	 include './application/cms/'.$this->side._EX;								//-wczytanie dodatkowej wtyczki, dedykowanej dla danego serwisu
	}
	else
    if($this->side && $this->side != 'cms') $fx .= '
	<p class=\'error\'>brak wtyczki! -> '.$this->side._EX.' dla lokalizacji cms/ lub application/cms/ w '.__METHOD__.' -> line: '.__LINE__.'</p>';

	//-jeśli wtyczka CMS'a nie istnieje komunikat

	Test::trace();


	if(is_array($this->m))  $menu = $this->menu($this->m);					//-menu główne

	if(is_array($this->md))	$menud = $this->menu($this->md);					//-menu dodatkowe

	switch($_SESSION['admin']['status'])											//-przełożenie nr statusu na komunikat
	{
	 case 10: $d['status'] = 'VIP'; break;
	 case 9:  $d['status'] = 'Administrator Główny'; break;
	 case 8:  $d['status'] = 'Administrator D'; break;
	 case 7:  $d['status'] = 'Administrator C'; break;
	 case 6:  $d['status'] = 'Administrator B'; break;
	 case 1:  $d['status'] = 'Administrator A';
	}


	if(!$fz) $fz .= '
	<ul id=\'menu_dod\'>'.$menud.'
	</ul>'.$fy;

	Test::trace();

	//-szkielet HTML dla strony

	$this->x .= '
<div id=\'men_box\'>
 <div id=\'info\'>'._CMS_HEADER.' '.$_SESSION['admin_zalog'].' status: '.$_SESSION['admin']['status'].' - '.$d['status'].' | '.$d['baza'].' | klucz: '.C::get('db_prefix').' | podstrona : '.$this->side.'</div>
 <div id=\'men_box2\'>
  <ul id=\'menu\'>'.$menu.'</ul>
  <div id=\'cms_info\'>'.$fz.'
  </div>
 </div>
</div>
<div id=\'strona\'>'.$fx.'
</div>';

	unset($fx, $fy, $fz, $menu, $menud, $d);

	unset($mysql);												//-zakończenie połączenia z bazą MySQL
 }

 /** ./cms/klasy/Admin.php
 *
 * - menu główne CMS'a
 *
 */

 private function menu($tab)
 {
  $menu = '';

  foreach($tab as $kt => $vt)
  {
   $vt = explode(';', $vt);

   if($vt[2]) $vt[2] = 'target="'.$vt[2].'" ';

   if($vt[4])
   {
    $vt[2] .= 'alt=\'wykonać?\' '; 	//-sprawdzić czy jeszcze potrzebne
    $preload = ' ostr';
   }
   else
   {
    $vt[2] .= '';
    $preload = '';
   }

	$kt2 = explode('+',$kt);

   if($this->side == substr(end($kt2),0, -5))
    $act = 'class=\'active tipTip'.$preload.'\' ';
   else
    $act = 'class=\'tipTip'.$preload.'\' ';

	if(!$vt[3]) $vt[3] = '';

   $menu .= '
		<li><a '.$act.'href=\''.$kt.'\' '.$vt[2].'title=\''.$vt[1].'\'>'.$vt[0].'</a>'.$vt[3].'</li>';
  }
  unset($act, $kt, $kt2, $vt, $preload, $tab, $side);

  return $menu;
 }

 /** ./cms/klasy/Admin.php
 *
 *
 */

 public function wynik()
 {
  return $this->x;
 }

 /** ./cms/klasy/Admin.php
 *
 *
 */

 function __destruct()
 {
  unset($this->m, $this->x, $this->side, $this->md);
 }

}
?>