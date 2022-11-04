/**
*
* 2016-05-10
* 2013-01-03
* 2012-12-04 -> dodano link laddel do kasowania bezpośredniego
* 2011-11-24 -> 2012-02-27 -> 2012-11-25
* ukrywanie i wyświetlanie linków administarcyjnych v.1.0
* Dariusz Golczewski - 2011-07-03
*
*/

$j(function() {

  $j('.ed').css({'display':'none'}); 							//-nowe rozwiązanie ala FB

  $j('#stopka, #footer, .stopka, #dane_adr').admHover({'sel':'.edTr'});

  $j('.klko').admHover({'sel':'.edBox'}); 					//-nowe rozwiązanie ala FB

  $j('.edFoto').admHover({'sel':'.edF'}); 					//-nowe rozwiązanie ala FB

  $j('.edZaj, .blok_publ').admHover({'sel':'.edTr'});		//-nowe rozwiązanie ala FB .edTyt,

  $j('.edT').admHover({'sel':'.edTt'});  						//-nowe rozwiązanie ala FB .edTyt,

  $j('.addPhoto').newLink();   									//-podmienia linki dla dodawania zdjęć do galerii	cms/js/moje.common.js

  //2016-05-10
  $j('.addPhotog').newLink({'plik':'add_photo_gal.html'});   	//-podmienia linki dla dodawania zdjęć do galerii	cms/js/moje.common.js

  //.css({'border':'1px solid red'});
});