/* REJRAT - intro strona startowa -> 2011-03-24 -> 2012-05-02 */

//document.cookie = "ciacho=test";
//var gone_stop = 0;

function mfout(adres) {

  gone_stop = 1;
  baner('intro2');

  window.scrollTo(0,0);

  //-musi być $j

  $j('#start, #menu, #menu2, #galeria').fadeOut(800);
  $j('#kontakt').animate({'opacity': 0}, 800);

  $j('#intro2').animate({'left': '175px', 'top': '292px'}, 800, 'swing');

  $j('#f5').animate({'left': '190px', 'bottom': '190px'}, 800, 'swing');

  $j('#intro1').animate({'right': '175px', 'top': '112px'}, 800, 'swing', function(){

   $j('#intro1, #intro2').animate({'opacity': 0}, 500, function(){

	  location=adres;

	});

  });

}

/**
*
* wtyczka dla linków wyjściowych, generujących animację, a następnie przeniesienie pod adres odnośnika
*
*/

jQuery.fn.outAction = (function($){

   return function(opcje) {

    var ust = $.extend({
	   adres: 'start.html',
		  fun: function(adres) { location = adres; }
    }, opcje);

    return this.each(function() {

	  $(this).click(function(){

		if(!$(this).attr('rel') && $(this).attr('rel') != '#')
		{
	    ust.adres = $(this).attr('href');

		 if(ust.adres != '#')
		 {
		  $(this).attr('href', 'javascript:void(0);');

		  mfout(ust.adres);
		 }

		}

	 });
   });
  };

})(jQuery);


$j(function() {

  //if(!inex)
  //{
	//-ukrywa zdjęcia intro
	$j('#intro1').css({'right': '175px', 'top': '112px', 'opacity':'0'});
	$j('#intro2').css({'left': '175px', 'top': '292px', 'opacity':'0'});
	$j('#f5').css({'left': '190px', 'bottom': '190px'});

	//-ukrywa kontenery z treścią strony
	$j('#start, #kontakt, #menu, #menu2, #galeria').css({'opacity':'0'});

	//-animacja
	$j('#intro1, #intro2').animate({'opacity':'1'}, 500, function(){

   	$j('#start, #kontakt, #menu, #menu2, #galeria').animate({'opacity':'1'}, 800);

   	$j('#intro1').animate({'right': '0px', 'top': '0px'}, 800, 'swing');


   	$j('#intro2').animate({'left': '0px', 'top': '365px'}, 800, 'swing', function(){

	 	 gone_stop = false;
	 	 baner('intro2');

   	});


   	$j('#f5').animate({'left': '5px', 'bottom': '5px'}, 800, 'swing');


  		$j('a, [type=submit]').outAction();			//-akcje dla wyjścia

  });

 //}
 //else
 // baner('intro2');

});


function baner(div)
{
 if(!gone_stop)
 {
  pozycjax = 0;
  pozycjay = 365;
  widoczne = true;
  yd = 0;
  xd = 0;
  obj = document.getElementById(div);
  obj.style.top = pozycjay + 'px';
  obj.style.left = pozycjax + 'px';
  obj.style.visibility = 'visible';

  //obj2 = document.getElementById('banery0');
  //obj2.style.position = 'relative';

 gone();
 }
}


function gone()
{
  if (window.scrollX>=0)
  {
    x = window.scrollX; y = window.scrollY;
  }
  else
  {
   x = document.body.scrollLeft;
   y = document.body.scrollTop;
  }

  if (y == 0) y = document.documentElement.scrollTop;

  /*
  if(y > 120) 				//-tylko dla rejrat
	pozycjay = 160;
  else
   pozycjay = 365; */

  //document.getElementById('intro2').innerHTML = '<p style=\'background: red;\'>' + '<br>y=' + y + '<br>top=' + parseInt(obj.style.top) + '</p>';

  if(y > 120 || parseInt(obj.style.top) > 365)
  {
	pozycjay = 160;

   docelowax = pozycjax + x;

   doceloway = pozycjay + y;

   yd = (doceloway - parseInt(obj.style.top)) / 5;



   xd = (docelowax - parseInt(obj.style.left)) / 5;

   obj.style.left = parseInt(obj.style.left) + xd + 'px';

   obj.style.top =  parseInt(obj.style.top) + yd + 'px';
  }

  if(!gone_stop)
	setTimeout("gone()", 50);

}
