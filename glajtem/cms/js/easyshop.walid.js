/**
*
* walidacja logowania, nowego hasła i rejestracji
*
* 2012-11-17
* autorem skryptu jest
* projekt.etvn.pl & aleproste.pl Dariusz Golczewski -------- 2010-03-17 --- UTF-8
* skrypt nie jest darmowy!
* legalne wykorzystywanie skryptu wymaga opłaty licencyjnej lub indywidualnej zgody autora
*/


$j(function() {
   /*
	 var menge=/[0-9]+/;


	 var send = true;
	 var kom = '';


	function sended(lok)
	{

    if(send)
	 {
	  return true;
	 }
	 else
	 {
	  $j('#preLoader').hide();

	  if(kom)
	  {
	   kom = $j('<p id="jqkom" style="font-weight: bold; color: red;">' + kom + '</p>');
	   kom.appendTo(lok);
	  }

	  return false;
	 }

	};


	function menge(cs)
	{

	 if(!$j(cs+' input[type="text"]').val())
	 {
     kom = 'Proszę wybrać przynajmniej jeden towar';
	  return true;
    }
	 else
	  return false;

	};


   */

	$j('.zugshop').submit(function()
	{
	 alert('jestem');

	 /*
	 $j('input').css({'border-color':'red'});
	 send = true;

	 var cs = '.zugshop';

	 if($j('#jqkom').length > 0) $j('#jqkom').remove();  //-kasowanie wcześniejszych komunikatów

	 //if(menge(cs)) send = false;

	 //return sended(this); */

	});

});



