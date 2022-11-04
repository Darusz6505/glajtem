//
//

//-Dariusz Golczewski - 2011-03-21
//-Wprowadzona poprawka dla LightBoxa - ignoruje linki z parametrem rel='....'

 jQuery.fn.preLoader = (function($){ 
 
   return function(opcje) { 

    var ust = $.extend({ 
	 	lok: 'body',
		idCss: 'preLoader',
		Img: 'skin/load.gif',
		Text: 'proszę czekać'
    }, opcje); 
 

	 var loader = $('<div id="' + ust.idCss + '"><img src="' + ust.Img + '" alt="proszę czekać" /><p>' + ust.Text + '</p></div>').appendTo(ust.lok);
	 
	 return this.each(function() { 
   
	  $(this).click(function(){
	 	
	   var wy = $(window).height(); 						//-odczytanie parametrów okna przegladarki
	   var wx = $(document).width();	
		
		
	   if($(this).attr('alt'))
		 var akcja = $(this).attr('alt'); 				//-pobranie komunikatu z alt
		else
		 var akcja = false;
		
		var pokaz = true;
		
		if($(this).attr('rel') && substr($(this).attr('rel'),0, 8) == 'lightbox')
		 pokaz = false;
		 
		if($(this).attr('href') == '#')
		 pokaz = false; 
 

	   if(window.scrollY >= 0) 							//-odczytanie ewentualnego przewinięcia
       var scY = window.scrollY; 						//- x = window.scrollX;
  	   else 
       var scY = document.body.scrollTop;				//-dla IE
		
		
		wx -= loader.css('width').substr(0,3); 		//-odjęcie rozmiaru div'a odczytanegfo z css'a
	   wx = wx/2;	
		
	   wy -= loader.css('height').substr(0,3);		//-odjęcie rozmiaru div'a odczytanegfo z css'a
	   wy = wy/2 + scY;
		
		//alert('wy = ' + wy + ' :: wx = ' + wx + ' :: akcja =' + akcja + ' :: pokaz = ' + pokaz + ' :: scY = ' + scY); 
		
	   if(akcja)
		{
	    if(confirm(akcja) && pokaz)	
	     loader.css({'left': +wx, 'top':+wy}).show();
	    else
	     return false;	
		}
		else
	    if(pokaz)
		 {
		  loader.css({'left': +wx, 'top':+wy}).show();	
		 }	
		
	 });
   }); 
  }; 
 
 })(jQuery); 
 
 // przykład wywołania
 /*
 $(function() { 
  $('a').preLoader().css({'color': 'green'}); 
 });  */