<?
defined('_CONPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* definicje menu : dla glajtem.pl
*
* 2018-08-11
* 2018-01-29
* 2016-01-03
* 2015-07-22
* 2015-01-06
* 2014-04-18
* 2013-09-09
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2010-11-11 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
*/

$daate = explode('-', substr(C::get('datetime_teraz'), 0, 10));

 $this->m['m1'] = array
 (
 	'start.html'				=>	'start^latamy glajtem',
	'kamery.html'				=> 'kamery',
	'galerie.html'				=> 'galerie^zdjęcia zdjęcia zdjęcia',
	'blog.html'					=> 'blog^o tym jak ...',
	'latanie.html'				=> 'loty^książka lotów i wypraw',
	'stacje_meteo.html'		=> 'Stacje^Stacje pogodowe on-line',
	'icm.html'					=> 'ICM^prognozy pogody z ICM i FlyMet',
	'prognozy.html'			=> 'Prognozy^prognozy pogody inne',
	'igc.html'					=> 'IGC^Porównanie ciekawych lotów'

 );

 $this->m['m2'] = array
 (
 	'linki.html' => 'Linki^przydatne strony w sieci^_blanc',
	'pogoda.html'																=> 'pogoda^pogoda na latanie z Powered by ParaglidingMap.com',
	'szkoly.html'																=> 'Szkoły^Szkoły Paralotniowe',
	'miejsca.html'																=> 'Latamy...^miejsca do latania swobodnego',
	'polecam.html'																=> 'Polecam^o tym jak ...',
	'mojeloty.html'															=> 'Moje^moje loty',
	'elektronika.html'														=> 'Elektronika^elektronika do latania',
	'sprzet.html'																=> 'Sprzęt^sprzęt który miałem i mam możliwość używać',
	'testy.html'																=> 'Testy^testy egzaminacyjne',
	'filmy.html'																=> 'Video^Moje filmiki z latania',
	'dolnoslaska-liga-paralotniowa.html'								=> 'DLP^Dolnośląska Liga Paralotniowa',
	'loty.html'																	=> 'Loty PL^Analiza lotów PL',
	'terma+loty.html' 														=> 'Noszenia PL^Mapa noszeń w PL ',
	'prognozy.html'															=> 'Meteo^Prognozy meteorologiczne',
	'szybowce.html'															=> 'Szybowce^Tematy szybowcowe',
	'szybowcowe.html'															=> 'Szybowcowe^Dane techniczne szybowców',
	'szybowce-testy.html'													=> 'SPL^Testy SPL',
	'szybowce-instrukcje.html'												=> 'ISL^Testy inne',
	'kontakt.html'																=> 'Kontakt^formularz kontaktowy');


 $this->m['m3'] = array
 (
 	'start.html'				=>	'start^latamy glajtem',
	'kamery.html'				=> 'kamery',
	'galerie.html'				=> 'galerie^zdjęcia zdjęcia zdjęcia',
	'blog.html'					=> 'blog^o tym jak ...',
	'latanie.html'				=> 'loty^książka lotów i wypraw',
	'http://xcportal.pl/flights-table'	=> 'XC..^xcportal.pl - polecam^_blanc',
	'http://www.xcontest.org/world/en/flights/daily-score-pg/' => 'xcontest^loty na zcontest',
	'http://www.xcmeteo.net/?p=16.209603x50.673192,t='.$daate[0].'-'.$daate[1].'-'.$daate[2].'T9:00:00Z,s=Mierosz%C3%B3w'	=> 'XCMeteo^pogoda w pionie',
	'sat24.html'				=> 'sat24^aktualny widok z satelity z sat24.com',
	'flymet2.html'				=> 'FlyMet^prognozy pogody z FlyMet',
	'icm.html'					=> 'ICM^prognozy pogody z ICM i FlyMet',
	'stacje_meteo.html'		=> 'Stacje^Stacje pogodowe',
	'igc.html'					=> 'IGC^Porównanie ciekawych lotów'

 );

 /*


 	'https://www.windyty.com' => 'windyty^prognoza pogody',
	'http://mapy.in-pocasi.cz' => 'metoradar^czeski meteoradar^_blanc',
	*/

 //http://xcc.paragliding.pl/module.php?id=20&contest=PL&l=pl&date=20151204

 $this->m['m4'] = array
 (
   'http://pgforum.pl' 														=> 'Forum^Nowe, moderowane forum paralotniowe.^_blanc',
 	'https://groups.google.com/forum/#!forum/pl.rec.paralotnie' => '+Grupa^grupa paralotnie na google^_blanc',
	'pogoda.html'																=> 'pogoda^pogoda na latanie z Powered by ParaglidingMap.com',
	'http://para2000.org/' 													=> 'Para2000^Dane techniczne paralotni^_blanc',
	'http://xcplanner.appspot.com/'										=> 'Xcplaner^Planowanie lotów^_blanc',
	'http://psp.org.pl/'														=> 'PSP^Polskie Stowarzyszenie Paralotniowe^_blanc',
	'szkoly.html'																=> 'Szkoły^Szkoły Paralotniowe',
	'miejsca.html'																=> 'Latamy...^miejsca do latania swobodnego',
	'polecam.html'																=> 'Polecam^o tym jak ...',
	'mojeloty.html'															=> 'Moje^moje loty',
	'http://xcportal.pl/user/15641'										=> 'Moje XC^moje loty na XCportalu',
	'elektronika.html'														=> 'Elektronika^elektronika do latania',
	'sprzet.html'																=> 'Sprzęt^sprzęt który miałem i mam możliwość używać',
	'testy.html'																=> 'Testy^testy egzaminacyjne',
	'filmy.html'																=> 'Video^Moje filmiki z latania',
	'https://www.youtube.com/channel/UCew2e5HW9noB3qpo_d6NRSA'	=> 'YouTube^mój kanał na youtube',
	'https://www.facebook.com/dariusz.Fly'								=> 'FB^mój profil na Facebook\'u',
	'https://plus.google.com/u/0/113109719808400696825/about'	=> 'Google+^mój profil na Google+',
	'dolnoslaska-liga-paralotniowa.html'								=> 'DLP^Dolnośląska Liga Paralotniowa',
	'loty.html'																	=> 'Loty PL^Analiza lotów PL',
	'terma+loty.html' 														=> 'Noszenia PL^Mapa noszeń w PL ',
	'prognozy.html'															=> 'Meteo^Prognozy meteorologiczne',
	'szybowce.html'															=> 'Szybowce^Tematy szybowcowe',
	'kontakt.html'																=> 'Kontakt^formularz kontaktowy');


  if(C::Get('jo'))
  $this->m['m2'] = array_merge($this->m['m2'],
	array(
	 'dlploty.php?itss78ffH$tr='.$daate[0].'-'.$daate[1].'-'.$daate[2] => 'LIGA^zestawienie lotów do wpisania^_blanc',
	 '/logs/dlploty21_trace.html' => 'DLP TRACE^podgląd działania automatu DLP^_blanc',
	 '/logs/dlp_naloty_trace.html' => 'DLP TEST^test^_blanc',
	 ));

 /*
 	'http://loty-biwakowe.glajtem.pl'				 					=> 'Loty Biwakowe^SESTAL Loty Biwakowe - oficjalna strona www',

 'igc.html' 																	=> 'IGC^wybrane loty igc',
 if(C::Get('jo'))
  $this->m['m2'] = array_merge($this->m['m2'],
	array(
	 'xloty+loty.html' => 'G-Loty^generowanie plik lotów',
	 'xterma+loty.html' => 'G-Noszenia^generowanie plik noszeń',
	 'igc.html' => 'IGC^wybrane loty igc'
	 )); */

 //	'noszenia.html'															=> 'Noszenia^analiza noszeń na podstawie wykonanych lotów',

 unset($daate);

 // 	'noszenia.html'															=> 'Noszenia^analiza noszeń na podstawie wykonanych lotów',
 //'http://camplijak.com/'													=> 'Lijak^kamera on-line',
 /*
	'http://www.silp.paralotnie.pl/' 									=> 'SILP^Strona Stowarzyszenia Instruktorów Lotniowych i Paralotniowych^_blanc',
	'http://lecimy.org/'														=> 'Lecimy^wyznacz trasę lotu^_blanc',

 http://flypoint.eu/pl - rękawice paralotniowe

*/
?>