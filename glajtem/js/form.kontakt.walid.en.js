/**
*
* walidacja formularza kontaktowego
*
* 2012-12-02
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2012-11-29 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*/


$j(function() {

  	$j('.kontakt form#fform').submit(function()
	{

	 var meil = /^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/;
    var tel = /[0-9.*\(\)\[\]+\- ;:]/;

	 var kom = '';
	 var lok = $j('form#fform');
	 var sended = true;
	 var te = '';
	 var tb = '';
	 var start = $j('input[name="starttext"]').val();

	 $j('#inazw, #itel, #imail, #ftext').css({'border-color':'#FFF', 'color':'#000'});

	 if($j('#jqkom').length > 0) $j('#jqkom').remove();
	 if($j('.jqkom').length > 0) $j('.jqkom').remove();

    te = $j('#ftext');

	 if(!te.val() || te.val() == start )
	 {
	  if(!kom) kom = '#ftext';
	  te.css({'border-color':'red'}).after('<p class="jqkom">Please enter a message.</p>');
	 }

	 if(te.val().length > 600)
	 {
	  if(!kom) '#ftext';
	  tb.css({'border-color':'red'}).after('<p class="jqkom">Message maximum of 600 characters!</p>');
	 }


	 te = $j('#inazw');

    if(!te.val())
	 {
	  if(!kom) kom = '#inazw';
	  te.css({'border-color':'red'}).after('<p class="jqkom">Please sign the message.</p>');
	 }
	 else if(te.val().length > 80)
	 {
	  if(!kom) kom = '#inazw';
	  te.css({'border-color':'red'}).after('<p class="jqkom">Sign maximum of 80 characters!</p>');
	 }


	 te = $j('#itel');
	 tb = $j('#imail');

	 if(!te.val() && !tb.val())
	 {
	  if(!kom) kom = '#itel';
	  te.css({'border-color':'red'});
	  tb.css({'border-color':'red'}).after('<p class="jqkom">Please provide phone or e-mail.</p>');
	 }
	 else
	 {

     te = $j('#itel');
	  if(tb.val().length > 30)
	  {
	   if(!kom) kom = '#itel';
		tb.css({'border-color':'red'}).after('<p class="jqkom">tel. maximum of 30 characters!.</p>');
	  }
	  else if(te.val() && !tel.test($j('#itel').val()))
	  {
		if(!kom) kom = '#itel';
		te.css({'border-color':'red'}).after('<p class="jqkom">Only numbers, space and +()[]-.*;:</p>');
	  }

	  tb = $j('#imail');
	  if(tb.val().length > 50)
	  {
	   if(!kom) kom = '#imail';
		tb.css({'border-color':'red'}).after('<p class="jqkom">E-mai maximum of 50 characters!.</p>');
	  }
	  else if(tb.val() && !meil.test($j('#imail').val()))
	  {
		if(!kom) kom = '#imail';
		tb.css({'border-color':'red'}).after('<p class="jqkom">E-mail address is invalid.</p>');
	  }

	 }

	 if(kom)
	 {

     $j('#textarea').before('<p id="jqkom">Errors in form! - Please correct and resend.</p>');

	  $j('#preLoader').hide();

	  location = '#form_tyt';

	  return false;
	 }
	 else
	  return true;

	});//.css({'border':'1px solid red'});


});


