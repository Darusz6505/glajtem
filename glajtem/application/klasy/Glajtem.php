<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* wtyczka dedykowana: dla glajtem.pl
*
* 2016-01-23 -> adresy z new.meteo na meteo
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

  $this->start = array('czeszka', 'mieroszow', 'andrzejowka', 'dzikowiec', 'kudowa_czermna', 'srebrna_gora', 'zmij', 'czarna_gora', 'lysiec', 'walowa gora', 'rudnik','chelmiec','mala kopa','cerna hora','kamenec');
 }

 /**
 *
 * mapa pogodowa dla glajciarzy z ParaglidingMap.com

 *
 */

 public function pogoda()
 {

   $this->w = '
	 <h2>Pogoda na latanie ...</h2>
	 <div id=\'pogoda1\'>
	 <iframe class=\'pogoda1\'
	 	src="http://www.paraglidingmap.com/thirdpartywidget.aspx?lat=50.6100&lng=16.63800&zoom=12"></iframe>
	 <iframe class=\'pogoda1\'
	 	src="http://www.paraglidingmap.com/thirdpartywidget.aspx?lat=50.6927&lng=16.2700&zoom=12"></iframe>
	 <iframe class=\'pogoda1\'
	 	src="http://www.paraglidingmap.com/thirdpartywidget.aspx?lat=50.4590&lng=16.2493&zoom=13"></iframe>
	 <iframe class=\'pogoda1\'
	 	src="http://www.paraglidingmap.com/thirdpartywidget.aspx?lat=50.2531&lng=16.8079&zoom=13"></iframe>
	 </div>';

	 /*
	 <blockquote>
	  <h3>UWAGA!</h3>'.S::formText('[a][b]Informacje zawarte na mapie w żadnym przypadku nie mogą być podstawą w podejmowaniu decyzji o wykonywaniu lotów i stanowią jedynie pomoc w poszukiwaniu startowisk, na których prawdopodobnie istnieją sprzyjające warunki dla paralotniarzy. Niniejszy serwis, jak i strona www.paraglidingmap.com nie bierze odpowiedzialności za skutki wykonywania lotów we wskazaych lokalizcjach. Decyzja o wykonaiu lotu w każdym przypadku jest samodzielna decyzją pilota, podjętą na podstawie doświadczenai i obserwacji zastanych warunków pogodowych.[/b][/a]').'
	 </blockquote>'; */

  // Srebrna Góra 50.5717, 16.6574 (Srebrna Gorka)
  // Czeszka 50.6288, 16.6067
  // Łysajka 50.6935, 16.6422
  // Żmij 50.6362, 16.5462
  // Czarna Góra 50.2531, 16.8079
  // Łysiec 50.2869, 16.9267
  // Mieroszów 50.6727, 16.2094
  // Andrzejówka 50.6947, 16.2772 (Andrzejowka)
  // Kudowa-Czermna 50.4590, 16.2493
  // Dzikowiec 50.7206, 16.2111
 }

 /**
 *
 * mapa pogodowa dla glajciarzy z ParaglidingMap.com

 *
 */

 public function pogoda_wf()
 {

    $place = array('czeszka', 'mieroszow'); //, 'andrzejowka', 'dzikowiec', 'kudowa_czermna', 'srebrna_gora', 'zmij', 'czarna_gora');

    foreach($place as $wart)
	  $pl .= '
  	 <div class=\'pogoda3\'>
	 <script type="text/javascript" language="JavaScript" src="http://www.windfinder.com/widget/forecast/js/'.$wart.'?unit_wave=m&unit_rain=mm&unit_temperature=c&unit_wind=m_s&columns=1&days=2&show_day=0"></script>
	 <noscript>
	  <a href=\'http://www.windfinder.com/forecast/'.$wart.'?utm_source=forecast&utm_medium=web&utm_campaign=homepageweather&utm_content=noscript-forecast\'>Wind forecast for '.$wart.'</a> provided by <a href=\'http://www.windfinder.com?utm_source=forecast&utm_medium=web&utm_campaign=homepageweather&utm_content=noscript-logo\'>windfinder.com</a>
	 </noscript>
	 </div>';

   $this->w .= '
	 <h2>Pogoda na latanie ...</h2>
	 <div id=\'pogoda1\'>'.$pl.'
	 </div>';

  unlink($pl, $place);

  // Srebrna Góra 50.5717, 16.6574
  // Czeszka 50.6288, 16.6067
  // Łysajka 50.6935, 16.6422
  // Żmij 50.6362, 16.5462
  // Czarna Góra 50.2531, 16.8079
  // Łysiec 50.2869, 16.9267
  // Mieroszów 50.6727, 16.2094
  // Andrzejówka 50.6947, 16.2772
  // Kudowa-Czermna 50.4590, 16.2493
  // Dzikowiec 50.7206, 16.2111
 }

 public function pogoda_wf2()
 {

    $wart = C::get('opcja');

    $place = array('czeszka', 'mieroszow', 'andrzejowka', 'dzikowiec', 'kudowa_czermna', 'srebrna_gora', 'zmij', 'czarna_gora');

    if(in_array($wart, $place))
	  $pl = '
  	 <div class=\'pogoda2\'>
	 <script type="text/javascript" language="JavaScript" src="http://www.windfinder.com/widget/forecast/js/'.$wart.'?unit_wave=m&unit_rain=mm&unit_temperature=c&unit_wind=m_s&columns=1&days=4&show_day=0"></script>
	 <noscript>
	  <a href=\'http://www.windfinder.com/forecast/'.$wart.'?utm_source=forecast&utm_medium=web&utm_campaign=homepageweather&utm_content=noscript-forecast\'>Wind forecast for '.$wart.'</a> provided by <a href=\'http://www.windfinder.com?utm_source=forecast&utm_medium=web&utm_campaign=homepageweather&utm_content=noscript-logo\'>windfinder.com</a>
	 </noscript>
	 </div>';

   $this->w = '
	 <h2>Pogoda na latanie ...</h2>
	 <div id=\'pogoda1\'>'.$pl.'
	 </div>';

  unlink($pl, $wart);
 }

 /**
 *
 * Flymet ( Faflik )
 *
 */

 public function flymet()
 {

  $h = date('H', strtotime('+1 hour'));




  $this->w = '
   <h2>Pogoda by Flymet:</h2>
	<div id=\'flymet\'>
	 <blockquote>
	  <p>Dzisiejsza prognoza wyświetla się dynamicznie od 10:00 do 19:00 z wyprzedzeniem o 1h.</p>
	  <p>Prognoza na jutro wyświetla statycznie na godzinę 12:00</p>
	 </blockquote>';


  if($h < 19 && $h > 6)
  {
   if($h < 10) $h = 10;

   $this->w .= '
	<h4>Dzisiaj:</h4>
	<img src=\'http://flymet.meteopress.cz/cr/cudf'.$h.'.png\' />
	<img src=\'http://flymet.meteopress.cz/cr/vitrx'.$h.'.png\' />
	<img src=\'http://flymet.meteopress.cz/cr/srzk'.$h.'.png\' />';
  }

  $this->w .= '
   <h4>Jutro:</h4>
	<img src=\'http://flymet.meteopress.cz/crdl/cudf12.png\' />
	<img src=\'http://flymet.meteopress.cz/crdl/vitrx12.png\' />
	<img src=\'http://flymet.meteopress.cz/crdl/srzk12.png\' />';





  $this->w .= '
    <h3>Pokrycie i podstawa Cu</h3>
	  <h4>Dzisiaj</h4>
		<a href="http://rasp.linta.de/GERMANY/zsfclclmask.curr.1000lst.d2.png" target="_blank">10</a>
		<a href="http://rasp.linta.de/GERMANY/zsfclclmask.curr.1200lst.d2.png" target="_blank">12</a>
		<a href="http://rasp.linta.de/GERMANY/zsfclclmask.curr.1400lst.d2.png" target="_blank">14</a>
		<a href="http://rasp.linta.de/GERMANY/zsfclclmask.curr.1600lst.d2.png" target="_blank">16</a>
		<a href="http://rasp.linta.de/GERMANY/zsfclclmask.curr.1800lst.d2.png" target="_blank">18</a>
		<h5>Jutro</h5>
		<a href="http://rasp.linta.de/GERMANY/zsfclclmask.curr+1.1000lst.d2.png" target="_blank">10</a>
		<a href="http://rasp.linta.de/GERMANY/zsfclclmask.curr+1.1200lst.d2.png" target="_blank">12</a>
		<a href="http://rasp.linta.de/GERMANY/zsfclclmask.curr+1.1400lst.d2.png" target="_blank">14</a>
		<a href="http://rasp.linta.de/GERMANY/zsfclclmask.curr+1.1600lst.d2.png" target="_blank">16</a>
		<a href="http://rasp.linta.de/GERMANY/zsfclclmask.curr+1.1800lst.d2.png" target="_blank">18</a>

		<h3>Chwiejność atmosfery</h3>
		 <h4>Dzisiaj</h4>
		<a href="http://flymet.meteopress.cz/cr/cudf10.png" target="_blank">10</a>
		<a href="http://flymet.meteopress.cz/cr/cudf11.png" target="_blank">11</a>
		<a href="http://flymet.meteopress.cz/cr/cudf12.png" target="_blank">12</a>
		<a href="http://flymet.meteopress.cz/cr/cudf13.png" target="_blank">13</a>
		<a href="http://flymet.meteopress.cz/cr/cudf14.png" target="_blank">14</a>
		<a href="http://flymet.meteopress.cz/cr/cudf15.png" target="_blank">15</a>
		<a href="http://flymet.meteopress.cz/cr/cudf16.png" target="_blank">16</a>
		<a href="http://flymet.meteopress.cz/cr/cudf17.png" target="_blank">17</a>
		<a href="http://flymet.meteopress.cz/cr/cudf18.png" target="_blank">18</a>
		<h5>Jutro</h5>
		<a href="http://flymet.meteopress.cz/crdl/cudf10.png" target="_blank">10</a>
		<a href="http://flymet.meteopress.cz/crdl/cudf11.png" target="_blank">11</a>
		<a href="http://flymet.meteopress.cz/crdl/cudf12.png" target="_blank">12</a>
		<a href="http://flymet.meteopress.cz/crdl/cudf13.png" target="_blank">13</a>
		<a href="http://flymet.meteopress.cz/crdl/cudf14.png" target="_blank">14</a>
		<a href="http://flymet.meteopress.cz/crdl/cudf15.png" target="_blank">15</a>
		<a href="http://flymet.meteopress.cz/crdl/cudf16.png" target="_blank">16</a>
		<a href="http://flymet.meteopress.cz/crdl/cudf17.png" target="_blank">17</a>
		<a href="http://flymet.meteopress.cz/crdl/cudf18.png" target="_blank">18</a>

		<h3>Wiatr przyziemny</h3
		<h4>Dzisiaj</h4>

		<a href="http://flymet.meteopress.cz/cr/vitrx10.png" target="_blank">10</a>
		<a href="http://flymet.meteopress.cz/cr/vitrx11.png" target="_blank">11</a>
		<a href="http://flymet.meteopress.cz/cr/vitrx12.png" target="_blank">12</a>
		<a href="http://flymet.meteopress.cz/cr/vitrx13.png" target="_blank">13</a>
		<a href="http://flymet.meteopress.cz/cr/vitrx14.png" target="_blank">14</a>
		<a href="http://flymet.meteopress.cz/cr/vitrx15.png" target="_blank">15</a>
		<a href="http://flymet.meteopress.cz/cr/vitrx16.png" target="_blank">16</a>
		<a href="http://flymet.meteopress.cz/cr/vitrx17.png" target="_blank">17</a>
		<a href="http://flymet.meteopress.cz/cr/vitrx18.png" target="_blank">18</a>
	 <h5>Jutro</h5>

		<a href="http://flymet.meteopress.cz/crdl/vitrx10.png" target="_blank">10</a>
		<a href="http://flymet.meteopress.cz/crdl/vitrx11.png" target="_blank">11</a>
		<a href="http://flymet.meteopress.cz/crdl/vitrx12.png" target="_blank">12</a>
		<a href="http://flymet.meteopress.cz/crdl/vitrx13.png" target="_blank">13</a>
		<a href="http://flymet.meteopress.cz/crdl/vitrx14.png" target="_blank">14</a>
		<a href="http://flymet.meteopress.cz/crdl/vitrx15.png" target="_blank">15</a>
		<a href="http://flymet.meteopress.cz/crdl/vitrx16.png" target="_blank">16</a>
		<a href="http://flymet.meteopress.cz/crdl/vitrx17.png" target="_blank">17</a>
		<a href="http://flymet.meteopress.cz/crdl/vitrx18.png" target="_blank">18</a>

	<h3>wiatr okolo 1500m MSL</h3>

	 <h4>Dzisiaj</h4>

		<a href="http://flymet.meteopress.cz/cr/vitra10.png" target="_blank">10</a>
		<a href="http://flymet.meteopress.cz/cr/vitra11.png" target="_blank">11</a>
		<a href="http://flymet.meteopress.cz/cr/vitra12.png" target="_blank">12</a>
		<a href="http://flymet.meteopress.cz/cr/vitra13.png" target="_blank">13</a>
		<a href="http://flymet.meteopress.cz/cr/vitra14.png" target="_blank">14</a>
		<a href="http://flymet.meteopress.cz/cr/vitra15.png" target="_blank">15</a>
		<a href="http://flymet.meteopress.cz/cr/vitra16.png" target="_blank">16</a>
		<a href="http://flymet.meteopress.cz/cr/vitra17.png" target="_blank">17</a>
		<a href="http://flymet.meteopress.cz/cr/vitra18.png" target="_blank">18</a>

	 <h5>Jutro</h5>

		<a href="http://flymet.meteopress.cz/crdl/vitra10.png" target="_blank">10</a>
		<a href="http://flymet.meteopress.cz/crdl/vitra11.png" target="_blank">11</a>
		<a href="http://flymet.meteopress.cz/crdl/vitra12.png" target="_blank">12</a>
		<a href="http://flymet.meteopress.cz/crdl/vitra13.png" target="_blank">13</a>
		<a href="http://flymet.meteopress.cz/crdl/vitra14.png" target="_blank">14</a>
		<a href="http://flymet.meteopress.cz/crdl/vitra15.png" target="_blank">15</a>
		<a href="http://flymet.meteopress.cz/crdl/vitra16.png" target="_blank">16</a>
		<a href="http://flymet.meteopress.cz/crdl/vitra17.png" target="_blank">17</a>
		<a href="http://flymet.meteopress.cz/crdl/vitra18.png" target="_blank">18</a>



		<h3>Opady Flymet</h3>
		 <h4>Dzisiaj</h4>

		<a href="http://flymet.meteopress.cz/cr/srzk10.png" target="_blank">10</a>
		<a href="http://flymet.meteopress.cz/cr/srzk11.png" target="_blank">11</a>
		<a href="http://flymet.meteopress.cz/cr/srzk12.png" target="_blank">12</a>
		<a href="http://flymet.meteopress.cz/cr/srzk13.png" target="_blank">13</a>
		<a href="http://flymet.meteopress.cz/cr/srzk14.png" target="_blank">14</a>
		<a href="http://flymet.meteopress.cz/cr/srzk15.png" target="_blank">15</a>
		<a href="http://flymet.meteopress.cz/cr/srzk16.png" target="_blank">16</a>
		<a href="http://flymet.meteopress.cz/cr/srzk17.png" target="_blank">17</a>
		<a href="http://flymet.meteopress.cz/cr/srzk18.png" target="_blank">18</a>

		 <h5>Jutro</h5>

		<a href="http://flymet.meteopress.cz/crdl/srzk10.png" target="_blank">10</a>
		<a href="http://flymet.meteopress.cz/crdl/srzk11.png" target="_blank">11</a>
		<a href="http://flymet.meteopress.cz/crdl/srzk12.png" target="_blank">12</a>
		<a href="http://flymet.meteopress.cz/crdl/srzk13.png" target="_blank">13</a>
		<a href="http://flymet.meteopress.cz/crdl/srzk14.png" target="_blank">14</a>
		<a href="http://flymet.meteopress.cz/crdl/srzk15.png" target="_blank">15</a>
		<a href="http://flymet.meteopress.cz/crdl/srzk16.png" target="_blank">16</a>
		<a href="http://flymet.meteopress.cz/crdl/srzk17.png" target="_blank">17</a>
		<a href="http://flymet.meteopress.cz/crdl/srzk18.png" target="_blank">18</a>

   </div>';

 }

  /**
 *
 * Flymet ( Faflik )
 * wszystkie mapy na dziś i jutro - przewijane
 *
 */

 public function flymet2()
 {





  $h = date('H', strtotime('+1 hour'));




  $this->w = '
   <h2>Pogoda by Flymet:</h2>
	<div id=\'flymet2\'>
	 <blockquote>
	  <p>Na komputerach przewijanie za pomocą kursorów; prawo - lewo, po kliknięciu na mapę.</p>
	  <p>Na smartfonach przewijamy palcem.</p>
	 </blockquote>';


  $m1 = $m2 = $m3 = '';

  for($i = 10; $i < 19; $i++)
  {
   if($i == 10)
	 $nnm = 'id=\'mmfly\' ';
	else
	 $nnm = '';

	$m1 .= '
	<img '.$nnm.'class=\'mfly\' src=\'http://flymet.meteopress.cz/cr/cudf'.$i.'.png\' />';

   $m2 .= '
	<img class=\'mfly\' src=\'http://flymet.meteopress.cz/cr/vitrx'.$i.'.png\' />';

	$m3 .= '
	<img class=\'mfly\' src=\'http://flymet.meteopress.cz/cr/srzk'.$i.'.png\' />';

  }

  $i = ($i-10) * 804 + 2;

  $this->w .= '
	<h4>Dzisiaj:</h4>
	<div class=\'flymet2\'>
	 <div id=\'dmf1\' class=\'dmfly\' style=\'width:'.$i.'px\'>'.$m1.$m2.$m3.'</div>
	</div>';

  /*
  $this->w .= '
	<h4>Dzisiaj:</h4>
	<div class=\'flymet2\'>
	 <div id=\'dmf1\' class=\'dmfly\' style=\'width:'.$i.'px\'>'.$m1.'</div>
	</div>
	<div class=\'flymet2\'>
	 <div id=\'dmf2\' class=\'dmfly\' style=\'width:'.$i.'px\'>'.$m2.'</div>
	</div>
	<div class=\'flymet2\'>
	 <div id=\'dmf3\' class=\'dmfly\' style=\'width:'.$i.'px\'>'.$m3.'</div>
	</div>';
  */

  $m1 = $m2 = $m3 = '';

  for($i = 10; $i < 19; $i++)
  {

	$m1 .= '
	<img class=\'mfly\' src=\'http://flymet.meteopress.cz/crdl/cudf'.$i.'.png\' />';

   $m2 .= '
	<img class=\'mfly\' src=\'http://flymet.meteopress.cz/crdl/vitrx'.$i.'.png\' />';

	$m3 .= '
	<img class=\'mfly\' src=\'http://flymet.meteopress.cz/crdl/srzk'.$i.'.png\' />';

  }

  $i = ($i-10) * 804 + 2;

  $this->w .= '
   <h4>Jutro:</h4>
	<div class=\'flymet2\'>
	 <div id=\'dmf2\' class=\'dmfly\' style=\'width:'.$i.'px\'>'.$m1.$m2.$m3.'</div>
	</div>';


  $this->w .= '
    <h3>Pokrycie i podstawa Cu</h3>
	  <h4>Dzisiaj</h4>
		<a href="http://rasp.linta.de/GERMANY/zsfclclmask.curr.1000lst.d2.png" target="_blank">10</a>
		<a href="http://rasp.linta.de/GERMANY/zsfclclmask.curr.1200lst.d2.png" target="_blank">12</a>
		<a href="http://rasp.linta.de/GERMANY/zsfclclmask.curr.1400lst.d2.png" target="_blank">14</a>
		<a href="http://rasp.linta.de/GERMANY/zsfclclmask.curr.1600lst.d2.png" target="_blank">16</a>
		<a href="http://rasp.linta.de/GERMANY/zsfclclmask.curr.1800lst.d2.png" target="_blank">18</a>
		<h5>Jutro</h5>
		<a href="http://rasp.linta.de/GERMANY/zsfclclmask.curr+1.1000lst.d2.png" target="_blank">10</a>
		<a href="http://rasp.linta.de/GERMANY/zsfclclmask.curr+1.1200lst.d2.png" target="_blank">12</a>
		<a href="http://rasp.linta.de/GERMANY/zsfclclmask.curr+1.1400lst.d2.png" target="_blank">14</a>
		<a href="http://rasp.linta.de/GERMANY/zsfclclmask.curr+1.1600lst.d2.png" target="_blank">16</a>
		<a href="http://rasp.linta.de/GERMANY/zsfclclmask.curr+1.1800lst.d2.png" target="_blank">18</a>

	<h3>wiatr okolo 1500m MSL</h3>

	 <h4>Dzisiaj</h4>

		<a href="http://flymet.meteopress.cz/cr/vitra10.png" target="_blank">10</a>
		<a href="http://flymet.meteopress.cz/cr/vitra11.png" target="_blank">11</a>
		<a href="http://flymet.meteopress.cz/cr/vitra12.png" target="_blank">12</a>
		<a href="http://flymet.meteopress.cz/cr/vitra13.png" target="_blank">13</a>
		<a href="http://flymet.meteopress.cz/cr/vitra14.png" target="_blank">14</a>
		<a href="http://flymet.meteopress.cz/cr/vitra15.png" target="_blank">15</a>
		<a href="http://flymet.meteopress.cz/cr/vitra16.png" target="_blank">16</a>
		<a href="http://flymet.meteopress.cz/cr/vitra17.png" target="_blank">17</a>
		<a href="http://flymet.meteopress.cz/cr/vitra18.png" target="_blank">18</a>

	 <h5>Jutro</h5>

		<a href="http://flymet.meteopress.cz/crdl/vitra10.png" target="_blank">10</a>
		<a href="http://flymet.meteopress.cz/crdl/vitra11.png" target="_blank">11</a>
		<a href="http://flymet.meteopress.cz/crdl/vitra12.png" target="_blank">12</a>
		<a href="http://flymet.meteopress.cz/crdl/vitra13.png" target="_blank">13</a>
		<a href="http://flymet.meteopress.cz/crdl/vitra14.png" target="_blank">14</a>
		<a href="http://flymet.meteopress.cz/crdl/vitra15.png" target="_blank">15</a>
		<a href="http://flymet.meteopress.cz/crdl/vitra16.png" target="_blank">16</a>
		<a href="http://flymet.meteopress.cz/crdl/vitra17.png" target="_blank">17</a>
		<a href="http://flymet.meteopress.cz/crdl/vitra18.png" target="_blank">18</a>

   </div>';

 }
 /**
 *
 * prognoza z ICM dla wybranych miejsc
 *
 */

 public function pogodaIcm()
 {
   $daate = explode('-', substr(C::get('datetime_teraz'), 0, 10));

   $place = array('czeszka', 'mieroszow', 'andrzejowka', 'dzikowiec', 'kudowa_czermna', 'srebrna_gora', 'zmij', 'czarna_gora', 'lysiec', 'dzikowiec','rudnik', 'mala kopa', 'oleśnica');

	//http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1186


   $this->w = '
	 <h2>Pogoda na latanie : '.date('d-m-Y').'</h2>

    <div id=\'icm\'>

	 <div>
	  <span >Flymet</span><a class=\'wf\' href=\'flymet.html\'>Flymet</a>
	 </div>

	 <div>
	  <span >Czeszka (Bielawa - Jodłownik)</span><a
	  href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=992\' target=\'_blank\'>60h</a><a
	   href=\'http://www.xcmeteo.net/?p=16.623x50.69075,t='.$daate[0].'-'.$daate[1].'-'.$daate[2].'T9:00:00Z,s=bielawa\'
		target=\'blank\' title=\'XCmeteo\'>xcm</a><a
	  class=\'wf\' href=\''.$place[0].'+wf.html\' title=\'WF 4 day\'>WF 4</a>
	 </div>

	 <div>
	  <span >Srebrna Góra (Stoszowice)</span><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1186\' target=\'_blank\'>60h</a><a
	   href=\'http://www.xcmeteo.net/?p=16.658018x50.57015,t='.$daate[0].'-'.$daate[1].'-'.$daate[2].'T9:00:00Z,s=srebrna%20g%C3%B3ra\'
		target=\'blank\' title=\'XCmeteo\'>xcm</a><a
		class=\'wf\' href=\''.$place[5].'+wf.html\' >WF 4 day</a>
	 </div>

	 <div>
	  <span >Mieroszów</span><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1154\' target=\'_blank\'>60h</a><a
	  href=\'http://www.xcmeteo.net/?p=16.209603x50.673192,t='.$daate[0].'-'.$daate[1].'-'.$daate[2].'T9:00:00Z,s=Mierosz%C3%B3w\'
	  target=\'blank\' title=\'XCmeteo\'>xcm</a><a
		class=\'wf\' href=\''.$place[1].'+wf.html\' >WF 4 day</a>
	 </div>


	 <div>
	  <span >Klin, Bukowiec</span><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1150\' target=\'_blank\'>60h</a><a
	  href=\'http://www.xcmeteo.net/?p=16.276042x50.69229,t='.$daate[0].'-'.$daate[1].'-'.$daate[2].'T9:00:00Z,s=andrzejowka\'
	  target=\'blank\' title=\'XCmeteo\'>xcm</a><a
		class=\'wf\' href=\''.$place[2].'+wf.html\' >WF 4 day</a>
	 </div>


	 <div>
	  <span ><a href=\'\'>Dzikowiec (Boguszów-Gorce)</a></span><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1151\' target=\'_blank\'>60h</a><a
	  href=\'http://www.xcmeteo.net/?p=16.20494x50.75514,t='.$daate[0].'-'.$daate[1].'-'.$daate[2].'T9:00:00Z,s=boguszów-gorce\'
	  target=\'blank\' title=\'XCmeteo\'>xcm</a><a
		class=\'wf\' href=\''.$place[9].'+wf.html\' >WF 4 day</a>
	 </div>

	 <div>
	  <span >Czermna (Kudowa Zdrój)</span><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1036\'
		target=\'_blank\'>60h</a><a
	   href=\'http://www.xcmeteo.net/?p=16.24397x50.44297,t='.$daate[0].'-'.$daate[1].'-'.$daate[2].'T12:00:00Z,s=kudowa\'
		target=\'blank\' title=\'XCmeteo\'>xcm</a><a
		class=\'wf\' href=\''.$place[4].'+wf.html\' >WF 4 day</a>
	 </div>


	 <div>
	  <span >Rudnik, Wałowa Góra (Kowary)</span><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1020\'
		target=\'_blank\'>60h</a><a
	  href=\'http://www.xcmeteo.net/?p=15.83559x50.79313,t='.$daate[0].'-'.$daate[1].'-'.$daate[2].'T9:00:00Z,s=kowary\'
	  target=\'blank\' title=\'XCmeteo\'>xcm</a><a
		class=\'wf\' href=\''.$place[10].'+wf.html\' >WF 4 day</a>
	 </div>

	 <div>
	  <span >Mała Kopa (Karpacz)</span><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1019\'
		target=\'_blank\'>60h</a><a
	  href=\'http://www.xcmeteo.net/?p=15.83559x50.79313,t='.$daate[0].'-'.$daate[1].'-'.$daate[2].'T9:00:00Z,s=kowary\'
	  target=\'blank\' title=\'XCmeteo\'>xcm</a><a
		class=\'wf\' href=\''.$place[11].'+wf.html\' >WF 4 day</a>
	 </div>

    <div>
	  <span >Cerna Hora</span><a
		href=\'\'
		target=\'_blank\'>???</a><a
	  href=\'http://www.xcmeteo.net/?p=15.739926x50.649317,t='.$daate[0].'-'.$daate[1].'-'.$daate[2].'T9:00:00Z,s=cerna%20hora\'
	  target=\'blank\' title=\'XCmeteo\'>xcm</a><a
		class=\'wf\' href=\'\' >??</a>
	 </div>

	 <div>
	  <span >Żmij (Nowa Ruda)</span><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1039\'
		target=\'_blank\'>60h</a><a
	  href=\'http://www.xcmeteo.net/?p=16.50164x50.58008,t='.$daate[0].'-'.$daate[1].'-'.$daate[2].'T9:00:00Z,s=nowa%20ruda\'
	  target=\'blank\' title=\'XCmeteo-Nowa Ruda\'>xcm</a><a
		class=\'wf\' href=\''.$place[6].'+wf.html\' >WF 4 day</a>
	 </div>

	 <div>
	  <span >Stronie Śląskie : Łysiec </span><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1042\'
		target=\'_blank\'>60h</a><a
	  href=\'http://www.xcmeteo.net/?p=16.87397x50.29554,t='.$daate[0].'-'.$daate[1].'-'.$daate[2].'T9:00:00Z,s=stronie\'
	  target=\'blank\' title=\'XCmeteo-Stronie Śląskie\'>xcm</a><a
		class=\'wf\' href=\''.$place[8].'+wf.html\' >WF 4 day</a>
	 </div>

	 <div>
	  <span >Oleśnica : Borowa </span><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1090\'
		target=\'_blank\'>60h</a><a
	  href=\'http://www.xcmeteo.net/?p=17.38986x51.21338,t='.$daate[0].'-'.$daate[1].'-'.$daate[2].'T9:00:00Z,s=oleśnica\'
	  target=\'blank\' title=\'XCmeteo-Oleśnica\'>xcm</a><a
		class=\'wf\' href=\''.$place[13].'+wf.html\' >WF 4 day</a>
	 </div>

	 <div>
	  <span >Świdnica</span><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1129\'
		target=\'_blank\'>60h</a><a
	  href=\'http://www.xcmeteo.net/?p=16.48859x50.84378,t='.$daate[0].'-'.$daate[1].'-'.$daate[2].'T9:00:00Z,s=świdnica\'
	  target=\'blank\' title=\'XCmeteo-Świdnica\'>xcm</a>
	 </div>

    <div>
	  <span >Świebodzice EPWC</span><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1129\'
		target=\'_blank\'>60h</a><a
	  href=\'http://www.xcmeteo.net/?p=16.3282x50.85975,t='.$daate[0].'-'.$daate[1].'-'.$daate[2].'T9:00:00Z,s=świebodzice\'
	  target=\'blank\' title=\'XCmeteo-Świebodzice\'>xcm</a>
	 </div>


	 <div>
	  <span >Opole EPOP</span><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=952\'
		target=\'_blank\'>60h</a><a
	  href=\'http://www.xcmeteo.net/?p=17.92533x50.67211,t='.$daate[0].'-'.$daate[1].'-'.$daate[2].'T9:00:00Z,s=opole\'
	  target=\'blank\' title=\'XCmeteo-Opole\'>xcm</a>
	 </div>

	 <div>
	  <span >Żywiec</span><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=862\'
		target=\'_blank\'>60h</a><a
	  href=\'http://www.xcmeteo.net/?p=19.19243x49.68529,t='.$daate[0].'-'.$daate[1].'-'.$daate[2].'T9:00:00Z,s=Żywiec\'
	  target=\'blank\' title=\'XCmeteo-Żywiec\'>xcm</a>
	 </div>

	 <div>
	  <span >Jesenik-Mikulovice LKMI</span><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=921\'
		target=\'_blank\' titlr=\'Głuchołazy\'>60h</a><a
	  href=\'http://www.xcmeteo.net/?p=17.20464x50.22937,t='.$daate[0].'-'.$daate[1].'-'.$daate[2].'T9:00:00Z,s=jesenik\'
	  target=\'blank\' title=\'XCmeteo-Jesenik\'>xcm</a>
	 </div>

	 <div>
	  <span >Bezmiechowa</span><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=66\'
		target=\'_blank\'>60h</a><a
	  href=\'http://www.xcmeteo.net/?p=22.40987x49.5218,t='.$daate[0].'-'.$daate[1].'-'.$daate[2].'T9:00:00Z,s=bezmiechowa\'
	  target=\'blank\' title=\'XCmeteo-Bezmiechowa\'>xcm</a>
	 </div>

	 <div>
	  <span >Praga</span><a
		href=\'http://www.weather.uwyo.edu/cgi-bin/sounding?region=europe&TYPE=PDF%3ASKEWT&YEAR='.$daate[0].'&MONTH='.$daate[1].'&FROM='.$daate[2].'00&TO='.$daate[2].'00&STNM=11520\'
		target=\'_blank\'>00Z</a><a
	  href=\'http://www.weather.uwyo.edu/cgi-bin/sounding?region=europe&TYPE=PDF%3ASKEWT&YEAR='.$daate[0].'&MONTH='.$daate[1].'&FROM='.$daate[2].'00&TO='.$daate[2].'12&STNM=11520\'
	  target=\'blank\' title=\'\'>12Z</a>
	 </div>

	 <div>
	  <span >Wrocław</span><a
		href=\'http://www.weather.uwyo.edu/cgi-bin/sounding?region=europe&TYPE=PDF%3ASKEWT&YEAR='.$daate[0].'&MONTH='.$daate[1].'&FROM='.$daate[2].'00&TO='.$daate[2].'00&STNM=12425\'
		target=\'_blank\'>00Z</a><a
	  href=\'http://www.weather.uwyo.edu/cgi-bin/sounding?region=europe&TYPE=PDF%3ASKEWT&YEAR='.$daate[0].'&MONTH='.$daate[1].'&FROM='.$daate[2].'00&TO='.$daate[2].'12&STNM=12425\'
	  target=\'blank\' title=\'\'>12Z</a>
	 </div>


	 <div>
	  <span >Broumov : Lotnisko (Kudowa)</span><a
		class=\'wf\' href=\'http://www.airbroumov.eu/wd/index.html\' target=\'_blank\'>STP</a>
	 </div>

	</div>';

  //http://www.xcmeteo.net/?p=15.739926x50.649317,t=2018-02-09T12:00:00Z,s=czerna%20hora
  //http://www.xcmeteo.net/?p=16.24397x50.44297,t=2018-02-09T12:00:00Z,s=kudowa
  //http://www.xcmeteo.net/?p=16.658018x50.57015,t=2018-02-09T12:00:00Z,s=srebrna%20g%C3%B3ra
  //http://www.xcmeteo.net/?p=15.83559x50.79313,t=2018-02-09T12:00:00Z,s=kowary
  //






 }

 public function pogodaIcm_old()
 {

   $place = array('czeszka', 'mieroszow', 'andrzejowka', 'dzikowiec', 'kudowa_czermna', 'srebrna_gora', 'zmij', 'czarna_gora', 'lysiec', 'dzikowiec','rudnik', 'mala kopa', 'oleśnica');

	//http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1186


   $this->w = '
	 <h2>Pogoda na latanie : '.date('d-m-Y').'</h2>

    <div id=\'icm\'>

	 <div>
	  <span >Flymet</span><a class=\'wf\' href=\'flymet.html\'>Flymet</a>
	 </div>

	 <div>
	  <span >Czeszka (Bielawa - Jodłownik)</span><a
	  href=\'http://www.meteo.pl/php/meteorogram_id_coamps.php?ntype=2n&id=992\' target=\'_blank\'>84h</a><a
	  href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=992\' target=\'_blank\'>60h</a><a
	  class=\'wf\' href=\''.$place[0].'+wf.html\' >WF 4 day</a>
	 </div>

	 <div>
	  <span >Srebrna Góra (Stoszowice)</span><a
		href=\'http://www.meteo.pl/php/meteorogram_id_coamps.php?ntype=2n&id=1186\' target=\'_blank\'>84h</a><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1186\' target=\'_blank\'>60h</a><a
		class=\'wf\' href=\''.$place[5].'+wf.html\' >WF 4 day</a>
	 </div>

	 <div>
	  <span >Mieroszów</span><a
		href=\'http://www.meteo.pl/php/meteorogram_id_coamps.php?ntype=2n&id=1154\' target=\'_blank\'>84h</a><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1154\' target=\'_blank\'>60h</a><a
		class=\'wf\' href=\''.$place[1].'+wf.html\' >WF 4 day</a>
	 </div>

	 <div>
	  <span >Klin, Bukowiec, Chełmiec (Wałbrzych)</span><a
		href=\'http://www.meteo.pl/php/meteorogram_id_coamps.php?ntype=2n&id=1150\' target=\'_blank\'>84h</a><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1150\' target=\'_blank\'>60h</a><a
		class=\'wf\' href=\''.$place[2].'+wf.html\' >WF 4 day</a>
	 </div>

	 <div>
	  <span ><a href=\'http://dzikowiec.akl.prz.edu.pl\'>ST: Dzikowiec (Boguszów-Gorce)</a></span><a
		href=\'http://www.meteo.pl/php/meteorogram_id_coamps.php?ntype=2n&id=1151\' target=\'_blank\'>84h</a><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1151\' target=\'_blank\'>60h</a><a
		class=\'wf\' href=\''.$place[9].'+wf.html\' >WF 4 day</a>
	 </div>

	 <div>
	  <span >Czermna (Kudowa Zdrój)</span><a
	   href=\'http://www.meteo.pl/php/meteorogram_id_coamps.php?ntype=2n&id=1036\' target=\'_blank\'>84h</a><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1036\' target=\'_blank\'>60h</a><a
		class=\'wf\' href=\''.$place[4].'+wf.html\' >WF 4 day</a>
	 </div>

	 <div>
	  <span >Rudnik, Wałowa Góra (Kowary)</span><a
		href=\'http://www.meteo.pl/php/meteorogram_id_coamps.php?ntype=2n&id=1020\' target=\'_blank\'>84h</a><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1020\' target=\'_blank\'>60h</a><a
		class=\'wf\' href=\''.$place[10].'+wf.html\' >WF 4 day</a>
	 </div>

	 <div>
	  <span >Mała Kopa (Karpacz)</span><a
		href=\'http://www.meteo.pl/php/meteorogram_id_coamps.php?ntype=2n&id=1019\' target=\'_blank\'>84h</a><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1019\' target=\'_blank\'>60h</a><a
		class=\'wf\' href=\''.$place[11].'+wf.html\' >WF 4 day</a>
	 </div>

	 <div>
	  <span >Żmij (Nowa Ruda)</span><a
		href=\'http://www.meteo.pl/php/meteorogram_id_coamps.php?ntype=2n&id=1039\' target=\'_blank\'>84h</a><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1039\' target=\'_blank\'>60h</a><a
		class=\'wf\' href=\''.$place[6].'+wf.html\' >WF 4 day</a>
	 </div>

	 <div>
	  <span >Stronie Śląskie : Łysiec </span><a
	   href=\'http://www.meteo.pl/php/meteorogram_id_coamps.php?ntype=2n&id=1042\' target=\'_blank\'>84h</a><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1042\' target=\'_blank\'>60h</a><a
		class=\'wf\' href=\''.$place[8].'+wf.html\' >WF 4 day</a>
	 </div>

	 <div>
	  <span >Oleśnica : Borowa </span><a
		href=\'http://www.meteo.pl/php/meteorogram_id_coamps.php?ntype=2n&id=1090\' target=\'_blank\'>84h</a><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1090\' target=\'_blank\'>60h</a><a
		class=\'wf\' href=\''.$place[13].'+wf.html\' >WF 4 day</a>
	 </div>

	 <div>
	  <span >Świdnica</span><a
		href=\'http://www.meteo.pl/php/meteorogram_id_coamps.php?ntype=2n&id=1129\' target=\'_blank\'>84h</a><a
		href=\'http://www.meteo.pl/um/php/meteorogram_id_um.php?ntype=0u&id=1129\' target=\'_blank\'>60h</a>
	 </div>

	 <div>
	  <span >Broumov : Lotnisko (Kudowa)</span><a
		class=\'wf\' href=\'http://www.airbroumov.eu/wd/index.html\' target=\'_blank\'>STP</a>
	 </div>

	</div>';


 }

 /**
 *
 * widok satelitarny polski z sat24.com
 *
 *
 */

 public function sat24()
 {
  $data = date('Y:m:d:H:i', strtotime('-1 hour'));

  $d = explode(':', $data);

  $m = array_pop($d);

  $i = '00';

  $data = implode('', $d).$i;

  $h = array_pop($d);

  $date2 = implode('-', $d).'  '.$h.':'.$i;

   $this->w = '
	 <h2>Widok satelitarny z : '.$date2.' - '.$data.'</h2>
	 <div id=\'sat24\'>
	  <img src=\'http://www.sat24.com/image2.ashx?region=pl&amp;time='.$data.'&amp;ir=false\' alt=\'pogoda z galjtem.pl\' />

	 </div>
	 <a class=\'smart\' href=\'http://sat24.com\' target=\'_blank\' title=\'Zobacz na stronie sat24.com\'>Strona - sat24.com</a>';


 }


 /**
 *
 *
 */

 public function wynik()
 {
  C::add('adcss', '
	<link rel="stylesheet" href="./application/glajtem_icm_20170319.css" type="text/css" media="screen" />');

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