$j(window).load(function () {

 //$j('#fanpage_fb').css({'border': '1px solid red', 'min-height': '100px'});

   var fb_size = $j('#fanpage_fb').attr('rel');

	$j.ajax({

  		type: "POST",
  		url: "./application/fanpage_fb_load.php?fb_size=" + fb_size,
		dataType: "html",
		cache: false,
		beforeSend: function()
		{

		},
		success: function(html)
		{
		 var t = html.split('^');

		 $j('#fanpage_fb').html(t[0]);
		 $j('#fanpage_fb2').html(t[0]);

       //alert('jest' + fb_size);
		}
		});

	return false;

});




