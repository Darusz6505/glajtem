<?
defined('_SYSPATH') or header('HTTP/1.1 404 File Not Found');

/**
*
* wtyczka uniwersalna : zajawki publikacji : dla glajtem.pl
*
* 2013-09-21
*
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2009-11-11 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*
* [linit ilości zajawek][limit długości zajawki]
*
* [limit długości zajawki] = 0 -> domyślnie 160 znaków | -1 -> link z tytuły do całego tekstu
*
*/

Test::start('zajawki_blog');

$r = new Publikacje($l['nazw'], $l['stro'], C::get('tab_fotb'));

$r->adresUst = 'galeria'; 			//-indywidualny adres strony z pełna publikacją, domyślnie = czytaj
$r->noLicznik = true;				//-ikrywa licznik odsłon artykułu
$r->noInfo   = true;			  		//-pole data dodania, ilość wejść

$r->zajOrder = 'mal';				//-porządkowanie zajawek rozsnąco ( od najstarszej do najnowszej ) :: priorytet na parametrem w wywołaniu !!
//array('rand', 'ros', 'mal')

//$r->linkName = 'off'; 			//'czytaj dalej';	//-nazwa odnośnika zajawki do całego tekstu z pominieciem pliku wersji

$r->linkName = 'zobacz całą galerię';

//$r->sortPole =  					//-dodatkowy warunek sortowania UWAGA! ostrożnie, tylko zajawka

//$r->zajLink = 'adres';			//-zajawka jako odnośnik do wskazanego adresu, czy to ma sens???

//$_SESSION['zajLinkBack'] = C::get('akcja').'.html';		//-strona powortu dla pełnego tekstu publikacji

$_SESSION['zajLinkBack'] = $l['stro'].'.html';

$r->ilFot = 2;							//-ilość zdjęć w zajawce

$r->zajawki(0, 400, true); 			//-[limit ilości zajawek, 0=bez limitu][limit długości tekstu zajawki, -1 = linki z tytułu][zdjęcia losowo]

$szab = $r->wynik();

//-HTML

$linki = '';
$wtyk = '';

if(C::get('jo'))
{
 $linkAdmina = array_pop($szab);

 $linkAdmina = array_pad($linkAdmina, 3, '');

 if($linkAdmina[2]) $linkAdmina[2] = ' class=\''.$linkAdmina[2].'\'';

 $linkAdmina = '<li'.$linkAdmina[2].' id=\''.$linkAdmina[0].'\'>'.$linkAdmina[1].'</li>';
}
else
 $linkAdmina = '';

if(is_array(end($szab)))
{
 $szab2 = end($szab);

 if(reset($szab2) == 'linki') 		//-linki porcjowania jeśli są
 {
  $linki = array_pop($szab);
  if($linki[0] == 'linki') $linki = $linki[1];
 }
}

foreach($szab as $wynik)
{
 $wynik = array_pad($wynik, 3, '');

 if($wynik[2])
  $class = ' class=\''.$wynik[2].' ses\'';
 else
  $class = ' class=\'ses\'';

 $wtyk .= '
 <li'.$class.' id=\''.$wynik[0].'\'>'.$wynik[1].'
 </li>';

 $class = '';
}

if($wtyk) $wtyk = '
<ul class=\'zaj\'>'.$linkAdmina.$linki.$wtyk.$linki.$linkAdmina.'
</ul>';

unset($r, $szab, $wynik, $class, $linkAdmina, $linki);

Test::stop('zajawki_blog');
?>
