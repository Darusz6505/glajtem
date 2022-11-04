/*
* rozszerzenia dla jQuery in CMS
*
* 2011-07-03
*/


/*
* rozszerzenie jQuery doodające nową wartość do listy rozwijanej
* Dariusz Golczewski - 2011-01-08 -> 2011-01-08
*
*/

jQuery.fn.nowaWartSelect = (function($){

 return function(opcje) {

  var ust = $.extend({
   text:'nowa wartość'
  }, opcje);

  return this.each(function() {

   var sel = $(this);
   var name = $(this).attr('name');
   var nowaWart = $(this).next();

	/*
	if(nowaWart.val() != '' && nowaWart.val() != ust.text)
	{
	 //-jeśli już jest nowa wartość wpisana to przenosi ją do listy
	 $('<option  selected="selected">' + nowaWart.val() + '</option>').appendTo(sel);
	} */

   nowaWart.hide();

	$('<option class="nowa">' + ust.text + '</option>').appendTo(sel);

	var mem = '';

	$(this).focus(function(){ //-zapamiętanie wartości początkowej

	 //mem = sel.val();
	 //alert(mem.text());
	 mem = $("select option:selected");

	});


   $(this).change(function(){
    //alert($(this).val());
    //alert($(this).len);

    if($(this).val() == ust.text)
    {
     nowaWart.show().focus();
    }

   });

   nowaWart.blur(function(){

	 var wart = $(this).val();

	 //alert($(this).children().size());

	 if(wart != '' && wart != ust.text)
    {
      sel.css({'background':'yellow'});

	   $('<option  selected="selected">' + wart + '</option>').appendTo(sel);
	 }
	 else
	 {
     //$("select[name=foo] option[text=bar]").attr("selected", true);
	  //jeśli nic nie zostało wpisane to powrót do wartości początkowej
	  mem.attr("selected", true);
	 }

	 nowaWart.val('').hide();
   });


   nowaWart.keydown(function(event) {

    var wart = $(this).val();

    if (event.keyCode == '13')
    {
     if(wart != ust.text && wart != '')
     {
      //alert(sel.options.length);
      //sel.options[sel.options.length] = new Option(wart, wart);
	   sel.css({'background':'yellow'});

	   $('<option  selected="selected">' + wart + '</option>').appendTo(sel);
	  }

     nowaWart.val('').hide();
     $('#' + ust.licznik).hide();
    }
   });

  });
 };
})(jQuery);








