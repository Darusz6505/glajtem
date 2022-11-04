/*
* akcje javascript in CMS v: 1.01
*
* 2013-06-07 :: dodano wyłacznik dla odnośników wywołujących preloader

* 2011-11-25 -> 2012-11-19
*
*
*/

// document.cookie = "ciacho=test";

var $j = jQuery.noConflict();

$j(function() {

	$j('[title]').tipTip();

	$j('a:not(#xxx, .xxx), [type=submit]').preLoader({'Img':'cms/skin/load.gif'});

	$j('textarea').autogrow();

	$j('.liZnak').ileZnakow().css({'border':'1px solid green'});

	$j('.sel_UB').nowaWartSelect().css({'border': '1px solid blue'});

	$j('.kadr').imgKadr({'form':'#cms_main_form'}).css({'border':'2px solid #00F'});

	$j('input[type=file]:not(.no_kadr)').inputFile().css({'border':'1px solid #00F'});

	$j('img.thu').imgZoom({showOverlay : true, opacity : 0.5}).css({'border': '1px solid #00F'});

	//$j('select[name=ofe_kate]').selectBind().css({'background':'#Ff6600'});

	$j('.data').myDatePicker().css({'border':'1px solid green'});

	$j('form div:first input').focus(); 	// 2011-11-28 :: w definicji należy okreslić które pole jest pierwsze i nadać mu klasę

	var boxY = $j('#men_box').outerHeight();
	$j('#strona').css({top: boxY + 10});

	$j(window).unload( function () { if($j('#preLoader').length > 0) $j('#preLoader').remove(); });

});


