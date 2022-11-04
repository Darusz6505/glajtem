
$j(window).load(function () {

 //$j('#reklama_down').html('XXXX');
 //$j('#reklamad').css({'border-color': 'green'});

      var session = $j('#reklama_down').attr('rel');

		if(session === undefined || session === null) session = 0;

		$j.ajax({
  		 type: "POST",
  		 url: "./application/reklama_self_load.php?self_reklama=" + session,
		 dataType: "html",
		 cache: false,
		 beforeSend: function()
		{

		},
		success: function(html)
		{
		 var t = html.split('^');

		 $j('#reklama_down').html(t[0]);
		}
		});

	return false;
});



