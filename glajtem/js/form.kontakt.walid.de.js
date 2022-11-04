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

	 $j('input, textarea').css({'border-color':'#999', 'color':'#000'});

	 if($j('#jqkom').length > 0) $j('#jqkom').remove();
	 if($j('.jqkom').length > 0) $j('.jqkom').remove();

    te = $j('#ftext');

	 if(!te.val() || te.val() == start )
	 {
	  if(!kom) kom = '#ftext';
	  te.css({'border-color':'red'}).after('<p class="jqkom">Bitte geben Sie einen Text ein.</p>');
	 }

	  if(te.val().length > 600)
	  {
	   if(!kom) '#ftext';
		tb.css({'border-color':'red'}).after('<p class="jqkom">Text maximal 600 Zeichen!.</p>');
	  }


	 te = $j('#inazw');

    if(!te.val())
	 {
	  if(!kom) kom = '#inazw';
	  te.css({'border-color':'red'}).after('<p class="jqkom">Bitte unterschreiben Sie Ihre Nachricht.</p>');
	 }
	 else if(te.val().length > 80)
	 {
	  if(!kom) kom = '#inazw';
	  te.css({'border-color':'red'}).after('<p class="jqkom">Unterschrift maximal 80 Zeichen!</p>');
	 }


	 te = $j('#itel');
	 tb = $j('#imail');

	 if(!te.val() && !tb.val())
	 {
	  if(!kom) kom = '#itel';
	  te.css({'border-color':'red'});
	  tb.css({'border-color':'red'}).after('<p class="jqkom">Bitte geben Sie Ihre Kontaktdaten, tel. oder email.</p>');
	 }
	 else
	 {

     te = $j('#itel');
	  if(tb.val().length > 30)
	  {
	   if(!kom) kom = '#itel';
		tb.css({'border-color':'red'}).after('<p class="jqkom">tel. maximal 30 Zeichen!.</p>');
	  }
	  else if(te.val() && !tel.test($j('#itel').val()))
	  {
		if(!kom) kom = '#itel';
		te.css({'border-color':'red'}).after('<p class="jqkom">Nur Zahlen, space und +()[]-.*;:</p>');
	  }

	  tb = $j('#imail');
	  if(tb.val().length > 50)
	  {
	   if(!kom) kom = '#imail';
		tb.css({'border-color':'red'}).after('<p class="jqkom">E-mail maximal 50 Zeichen!.</p>');
	  }
	  else if(tb.val() && !meil.test($j('#imail').val()))
	  {
		if(!kom) kom = '#imail';
		tb.css({'border-color':'red'}).after('<p class="jqkom">Invalid format E-Mail-Adresse.</p>');
	  }

	 }

	 if(kom)
	 {

     $j('<p id="jqkom" style="color: red;">Fehler auf dem Formular, bitte korrigieren und erneut zu senden.</p>').appendTo(lok);

	  $j('#preLoader').hide();

	  location = kom;

	  return false;
	 }
	 else
	  return true;

	});//.css({'border':'1px solid red'});


});


