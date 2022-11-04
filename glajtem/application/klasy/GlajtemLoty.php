<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* wtyczka dedykowana: dla glajtem.pl - obsługa plików IGC
*
* 2015-05-20
* 2014-05-29
*
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2014-05-29 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/


class GlajtemLoty
{
 private $w = ''; 												//-wynik działania klasy
 private $jo = false;											//-znacznik admina

 private $lot = '';												//-wybrany lot do wizualizacji

 /**
 *
 *
 */

 function __construct()
 {
  $this->jo = C::get('jo');									//-znacznik admina

 }

 /**
 *
 * mapa pogodowa dla glajciarzy z
 *
 */

 public function loty()
 {
   if(C::get('opcja')) $op = C::get('opcja');

	if(!is_numeric($op)) $op = 0;



   $path = 'loty';

   if(!is_dir($path) && defined($path))
   {
    $this->w = C::infoBox('adm_errbox', 'Brak głównego katalogu archiwum : '.$path);
   }
   else
	{

    $dirs = glob($path.'/*.igc');

	 $lf = 0;

	 foreach($dirs as $dir)
	 {
     if(file_exists($dir))
	  {
	   ++$lf;

		if($op == $lf)
		{
		 $this->lot = $dir;

		 $name5 = 'act';
		}
		elseif(filesize($dir) > 5500)
		{
		 $name5 = 'big';
		}
		else
		 $name5 = 0;

		$name = preg_replace('/-/', '', basename($dir));

		if(strlen(basename($dir)) < 18 )
		{
		 $name = '20'.$name;
		}

		$name1 = substr($name, 0, 4);
		$name2 = substr($name, 4, 2);
		$name3 = substr($name, 6, 2);
		$name4 = substr($name, 8);

		$name1 = $name3.'-'.$name2.'-'.$name1.'-'.$name4;

		$wtt[$name] = array($name1, filesize($dir), strlen(basename($dir)), $name5, $lf);

	  }
	 }

	 unset($name1, $name2, $name3, $name4, $name5, $lf, $name5);

	 ksort($wtt);


	 foreach($wtt as $war)
	 {
	  ++$lf;

	  if($war[3])
	   $clas = ' '.$war[3].'';
	  else
	   $clas = '';

	  $wt[] = '
		<li>
		 <b>'.$lf.'</b>.<a class=\'mojeloty'.$clas.'\' href=\''.$war[4].'+'.C::get('akcja').'.html\' title=\''.$war[1].'-'.$war[2].'\'>['.substr($war[0], 0, 13).']</a>
		</li>';

	 }


	 if($wt)
	 {
	  $this->w .= '<p>Kliknij na plik który chcesz zobaczyć.</p>';
	  $this->w .=	'
	  <ul id=\'loty_lista\'>'.implode(' ', $wt).'
	  </ul>';

	 }
	 else
	  $this->w .= C::infoBox('adm_errbox', 'Brak plików w : '._PATH_ARCH);

    unset($dirs, $dir, $wt);
   }


   $this->w = '<h3>Moje Loty</h3>'.$this->w;


 }

 /**
 *
 *
 *
 */

 public function track()
 {

  if($this->lot)
  {
	$this->w .= '<p class=\'mojlot\'>'.basename($this->lot).' | size: '.filesize($this->lot).'</p>';

   $this->w .= '
	<iframe class=\'mojeloty\' src=\'http://www.victorb.fr/visugps/visugps.html?track=http://glajtem.pl/'.$this->lot.'\'></iframe>';
  }


 }


 /**
 *
 *
 */

 public function wynik()
 {
    C::add('adcss', '
	<link rel="stylesheet" href="./application/glajtem_loty_20170319.css" type="text/css" media="screen" />');


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