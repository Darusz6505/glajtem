<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* wtyczka dedykowana: dla glajtem.pl
*
* 2013-09-10
*
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2009-11-11 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/


class Glajtem
{
 private $w = array(); 											//-wynik działania klasy


 /**
 *
 *
 */

 function __construct()
 {
  $this->jo = C::get('jo');																	//- wskaźnik Admina

 }

 /**
 *
 * mapa pogodowa dla glajciarzy z
 *
 */

 public function pogoda()
 {


   $this->w = '


	';


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
