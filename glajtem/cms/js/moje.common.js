/**
*
* 2012-11-25 : poprawki dla odnosnika add_Photo
*
*/


/**
*
* ukrywanie i wyświetlanie linków administarcyjnych
* Dariusz Golczewski - 2011-07-03
*
*/

jQuery.fn.admHover = (function($){

 return function(opcje) {

  var ust = $.extend({
   sel : '.ladtre'
   }, opcje);


  return this.each(function() {

   $(this).hover(function(){

	 $(this).find(ust.sel).fadeIn();

   }, function(){

	 $(this).find(ust.sel).fadeOut();

   });

  });
 };

})(jQuery);

/**
*
* v.1.2 : 2012-01-08 -> 2012-11-25
* zmiana odnośnika dla dodawanai zdjęć do galerii w golczewski.pl
* na mechanizm ładowana hurtowego
*
*/

 jQuery.fn.newLink = (function($){

   //jeśli jest włączony javascript, to zmiana adresu dla funkcji ładowania zdjeć do galerii
   return function(opcje) {

    var ust = $.extend({
	 	plik: 'add_photo.html'
    }, opcje);


	 return this.each(function() {

	  adres = $(this).attr('rel');

	  //adres = adres.split('+');
     /*
	  akcja = $(this).attr('href');
	  akcja = akcja.split(',');

	  id = akcja.pop();
	  id = akcja.pop();

	  akcja = id.split('\.');
	  id = akcja.pop(); */

	  //$(this).attr('href', id+'+'+ust.plik);

	  $(this).attr('href', adres + '+' + ust.plik);

   });
  };
 })(jQuery);

/**
*
* rozszerzenie jQuery zliczające ilość możliwych pozostałych do wpisania znaków w pole formularza
*
* Dariusz Golczewski - 2011-01-08 -> 2011-01-08 -> 2012-03-04
*
* dorobić rozpoznawanie polskich znaków, które powinny być zliczane podwójnie
*
*/

jQuery.fn.ileZnakow = (function($){

 return function(opcje) {

  var ust = $.extend({
  	pozy		: 80,
	pozx		: '50%',
   stylCss 	: 'liczZnak',
   textA 	: 'pozostało:',
	textB 	: ' znaków',
	poz		: 'cms',
	wstawDo 	: 'body'
   }, opcje);

  var zn = new Array('ą','ć','ę','ł','ń','ó','ś','ź','ż','Ą','Ć','Ę','Ł','Ń','Ó','Ś','Ż','Ź','ü','Ü','ö','Ö','ä','Ä','ß');

  return this.each(function() {

   var pozycja = $(this).offset();

	var dlPola = $(this).attr('alt'); 		//-limit znaków odczytany z alt

	$(this).focus(function(){

	var rozmY = $(this).outerHeight();

	dlTextu = $(this).val().length;
	//dlLancucha = dlTextu;						//-długość łańcucha znaków bez korekty dla polskich znaków

	if(dlTextu < dlPola)
	 dlLancucha = dlTextu;
	else
	 dlLancucha = dlPola;

	for(n in zn)
 	 dlTextu += $(this).val().split(zn[n]).length-1;

	 if(ust.poz == 'cms')
	 {
	  $('<span id="nowyLiczZnak">' + ust.textA + '<b>' + (dlPola - dlTextu) + '</b>' + ust.textB + '</span>')
	 	.appendTo('body')
		.css({'display':'block', 'position': 'fixed', 'background':'#F00', top: ust.pozy, left: ust.pozx});
	 }
	 else
	 {
	  $('<span id="nowyLiczZnak">' + ust.textA + '<b>' + (dlPola - dlTextu) + '</b>' + ust.textB + '</span>')
	 	.appendTo('body')
		.css({'display':'block', 'position': 'absolute', 'background':'#CCF', top: pozycja.top + 25, left: pozycja.left-330});
    }

	});

	//-wartoy może dodać jeszcze funkcję klik, przy kopiowaniu tekstów myszką !!!
	//-doodatkowo przycinanie tekstów od kończa, jeżeli wklejony tekst jest dłuzszy niż dozwolony limit !!!
	//-to samo po stronie servera !!!

	$(this).keyup(function(){

	  var rozmY = $(this).outerHeight();

	  dlTextu = $(this).val().length;

	  if(dlTextu < dlPola)
	   dlLancucha = dlTextu;
	  else
		dlLancucha = dlPola;

	  for(n in zn)
 	   dlTextu += $(this).val().split(zn[n]).length-1;


	  if(dlTextu <= dlPola)
	  {

	   $('#nowyLiczZnak b').replaceWith('<b>' + (dlPola - dlTextu) + '</b>');
		//$('#nowyLiczZnak').css({top: 80});

	  }
	  else
	  {
	   --dlLancucha;

		$(this).val($(this).val().substring(0, dlLancucha));
	  }
   });

   $(this).blur(function(){

		$('#nowyLiczZnak').remove();

   });
 });
 };
})(jQuery)

/*
*
* Dariusz Golczewski - 2011-05-15
* dołancza parametry kadrowania do wskazanego formularza, jako input z tablicą parametrów
* input otrzymuje id = inp_ + id grafiki img
* wywołanie -> imgKadr({form:'#id_formularza'});
* id pola input jest potrzebne do ewentualnego odłączenenia przy modyfikacji kadru
*
*/

jQuery.fn.imgKadr = (function($){

 return function(opcje) {

  var ust = $.extend({
	form : 'formul',
	id_inp : 'ikadr',
	name_inp : 'kadr_'
   }, opcje);


  return this.each(function() {

   var img_nr = $(this).attr('id');

	//na poczatek ustawiamy wyskalowanie na cały ograzek;

	var tab= new Array(0,0,0,0,0,0,0);
	$('<input id="' + ust.id_inp + img_nr + '" type="hidden" name="' + ust.name_inp + img_nr + '" value="' + tab +'" />').appendTo(ust.form);

	$(this).imgAreaSelect({ aspectRatio: '1:1', minWidth: '80', minHeight: '80',
    onSelectEnd: function (img, selection) {

	 //-tu faktyczne wyskalowanie
	 tab= new Array(selection.x1,selection.y1,selection.x2,selection.y2,selection.width,selection.height,img.width,img.height);

	 $('#' + ust.id_inp + img_nr).remove();
	 $('<input id="' + ust.id_inp + img_nr + '" type="hidden" name="' + ust.name_inp + img_nr + '" value="' + tab +'" />').appendTo(ust.form);
	}

	});
  });
 };

})(jQuery);


/**
*
* do kadrowania, dodaje checkbox

*/

jQuery.fn.inputFile = (function($){

 return function(opcje) {

  var ust = $.extend({

   }, opcje);


  return this.each(function() {

   var obb = $(this);

   $(this).change(function(){

	 var atr = $(this).attr('name');
	 var i = $(this).attr('alt');

	 if($('[name=kadrowanie]').val())
	  kadr = ' checked="checked"';
	 else
	  kadr = '';

	 $('#kadr' + i).remove();
	 $('label[for=kadr'+i+']').remove();

    if($(this).val() != '')
    {

	  $(this).after('<input type="checkbox"'+kadr+' name="w_'+atr+'" id="kadr'+i+'" title="' + atr + '" /><label class="oder" for="kadr'+i+'" title="zaznacz aby użyć kadrowania dla miniatur\">kadruj</label><span > size:</span><input type="text" name="size_'+atr+'" value="" title="niestandardowy rozmiar skalowania: szer x wys"" />');

    }
	 else
	 {

	  $('#kadr' + i).remove();
	  $('label[for=kadr'+i+']').remove();

	 }

   });
  });
 };

})(jQuery);
