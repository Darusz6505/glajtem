<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* wtyczka dedykowana: dla glajtem.pl - obsługa plików IGC
*
* 2014-05-29
*
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2014-05-29 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/


class GlajtemTrack
{
 private $w = array(); 											//-wynik działania klasy
 private $jo = false;											//-znacznik admina

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












   $this->w = '<h3>Moje Loty</h3>'.$this->w;


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
