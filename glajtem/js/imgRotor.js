
/**
* poprawione: 2012-05-12
*
* UWAGA! - należy uważać aby skrypt nie był wywołany więcej niż 1 raz !!
*
*
* W kontenerze zdjeć do rotowania, nie może znajdować się żaden inny element, gdyż polecenie
* current.next().length nie rozróżnia elementów
*
*/

jQuery.fn.imgRotor = (function($){

 return function(opcje) {

  var ust = $.extend({
	timeChange: 5000,
	timeSwitchOff: 0,
	timeSwitchOn: 1000,
	id: 'id'
  }, opcje);


  var tch = ust.timeChange;

  if(ust.id == 'id')
   var rotor = '#'+$(this).attr('id');

  if(ust.id == 'class')
   var rotor = '.'+$(this).attr('class');


  //Set the opacity of all images to 0
  $(rotor+' img').css({opacity: 0.0});

  //Get the first image and display it (gets set to full opacity)
  $(rotor+' img:first').addClass('show').css({opacity: 1.0});

  //var current = ($(rotor+' img.show')?  $(rotor+' img.show') : $(rotor+' img:first'));
  //var next = ((current.next().length)? ((current.next().hasClass('show'))? $(rotor+' img:first') :current.next()) : $(rotor+' img:first'));

	setInterval( function (){

   //Get the first image
	var current = $(rotor+' img.show')?  $(rotor+' img.show') : $(rotor+' img:first');

   //Get next image, when it reaches the end, rotate it back to the first image

   var next = ((current.next().length)? ((current.next().hasClass('show'))? $(rotor+' img:first') :current.next()) : $(rotor+' img:first'));

	//Hide the current image

	/*
	if(next.attr('rel'))
	{
	 //tch = next.attr('rel')*1;
	 //alert('tch = ' + tch);

	 tch = 4000;
	}
	else
	 tch = ust.timeChange; */


	current.animate({opacity: 0.0},ust.timeSwitchOff, function(){});

   next.animate({opacity: 1.0}, ust.timeSwitchOn, function(){ current.removeClass('show'); next.addClass('show');});

  }, tch);

  /*
  return this.each(function() {
  }); */

 };

})(jQuery);

$j(function() {

 $j("#top").imgRotor(); //.css({'border':'4px solid blue'});

});
