
$j(window).load(function () {



 $j('#reklamad').css({'border-color': 'green'});

	$j.ajax({

  		type: "POST",
  		url: "./syst/reklama_logout.php",
		dataType: "html",
		cache: false,
		beforeSend: function()
		{

		},
		success: function(html)
		{
		 $j('#reklamad').html(html);
		}
		});

	return false;

});



