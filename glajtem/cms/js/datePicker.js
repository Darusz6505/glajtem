/**
@
*
* 2011-11-23
*
* data picker: v.1.3
*
* plugin jQuery : myDatePicker()
* plik CSS : datePicker.css
* plik PHP : myDataPicker.php
* wartości domyślne:
*	zewnetrzny div kalendarza id = dataPicker
*  skrypt php kalendarza = myDataPicker.php
*  path dla skryptu php = ./cms/
*
* przykładowe wywołanie : $(".datePick").myDatePicker();
*
*/


jQuery.fn.myDatePicker = (function($){

  return function(opcje) {

    var ust = $.extend({
	 	path : './cms/',
	 	skrypt : 'myDataPicker.php',
		box : 'dataPicker'
    }, opcje);


    function removeKalendar()
    {
	  if($('#'+ust.box).length > 0) $('#'+ust.box).remove();
    }

    /**
    @
    */

     function closeKalendar()
     {
      $('#closeKalendar').click(function(){
		 //alert('klik');
	    removeKalendar();
      });
     }

    /**
    @
    */

	 function getMyDay(id)
    {
	  return $('.day').each(function() {

	   var element = $(this);

	   $(this).click(function(){

		 $('#'+id).val(element.attr('rel'));

		 removeKalendar();

	   });
     });
    }

    /**
    @
    */

	 function changeMonth(id)
    {

     return $('.month').each(function() {

	   $(this).click(function(){

		 var m = $(this).attr('rel');

		 $.ajax({
	  	  url: ust.path+ust.skrypt,
     	  cache: false,
	  	  dataType: 'json',
		  type: 'POST',
		  data: {'month': m},
		  beforeSend: function(html){

         //$('.kalendar').replaceWith('LOAD');

        },
     	  success: function(html){

			$('.kalendar').replaceWith(html);

		  },
		  error:	function(html){

         $('.kalendar').replaceWith('ERROR : '+html);

        },
		  complete:   function(html){

		   changeMonth(id);
		   getMyDay(id);
		   closeKalendar();

        }

		 });
	   });
     });
    }

    /**
    @
    */

	 return this.each(function() {

	  var id = $(this).attr('id');

	  var pozycja = $(this).offset();

	  var rozmX = $(this).outerWidth();

	  $(this).click(function(){

		if($('#'+ust.box).length > 0) $('#'+ust.box).remove();

		$.ajax({
	  	 url: ust.path+ust.skrypt,
       cache: false,
	    dataType: 'json',
		 beforeSend: function(html){

        //$('.kalendar').replaceWith('LOAD');
		  //$('<div id=\''+ust.box+'\'>LOAD</div>').appendTo('body').css({top: pozycja.top, left: pozycja.left-150});



       },
       success: function(html){

        //element.after('<div id=\''+ust.box+'\'>'+html+'</div>');

		  //$('.kalendar').replaceWith(html);

		  $('<div id=\''+ust.box+'\'>'+html+'</div>').appendTo('body').css({top: pozycja.top, left: pozycja.left+rozmX});
       },
		 complete:   function(){

		  changeMonth(id);
		  getMyDay(id);
		  closeKalendar();

       },
		 error:	function(html){

        $('<div id=\''+ust.box+'\'>ERROR : '+html+'</div>').css({top: pozycja.top+40, left: pozycja.left-150});

       }

	   });
	  });
    });
  };
 })(jQuery);





