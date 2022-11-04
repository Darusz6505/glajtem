/*

grupowe ładowanie plików za pomocą javaScript'u

2011-09-18 -> 2012-01-22 -

*/
/* tak było do 2013-02-11 i zostało przeneisione do handler.js
 jQuery.fn.loadImg = (function($){

   return function(opcje) {

    var ust = $.extend({
	 	plik: '',
		idCss: '',
		Img: '',
		Text: ''
    }, opcje);


	 return this.each(function() {

	  	 //akcja = $(this).attr('src');

	    akcja = $(this).attr('src').split('=');

		 plik = akcja[1]; // + '.jpg'; 2013-02-09

	  	 inp = '<div class=\'swf_formPlace\'>'+"\n";
		 inp += '<p>' + plik + '</p>';
		 inp += '<input type=\'hidden\' name="plik[]" value="' + plik + '" />' + "\n";
		 inp += '<div><label for=\'skip\'>pomiń</label><input type=\'checkbox\' name="skip['+plik+']"  id="skip" /></div>' + "\n";
		 inp += '<div><label for=\'tyt\'>tytuł</label><input type=\'text\' for=\'tyt\' name="tyt[]" /></div>' + "\n";
		 inp += '<div><label for=\'opis\'>opis</label><input type=\'text\' for=\'opis\' name="opis[]" /></div>' + "\n";
		 inp += '<div><label for=\'kasuj\'>kasuj</label><input type=\'checkbox\' id=\'kasuj\' name="kas[' + plik + ']" /></div>' + "\n";

		 inp += '</div>' + "\n";

	    inp = $(inp);

	    $(this).after(inp);

   });
  };

 })(jQuery); */
 //-------------------------------------------------------------------------------------

    $j(function() {

	  //$j('#thumbnails img:not(.thumbs)').loadImg(); //.css({'border': '1px solid red'});

		var swfu;

		window.onload = function () {

			var sid = $j('form#swf_addphoto').attr('rel');

			//alert('SID =' + sid);

			swfu = new SWFUpload({
				// Backend Settings
				upload_url: "upload.php",
				post_params: {"PHPSESSID": sid},

				// File Upload Settings
				file_size_limit : "10 MB",
				file_types : "*.jpg", //file_types : "*.jpg;*.png",
				file_types_description : "JPG Images; PNG Image",
				file_upload_limit : 2,

				// Event Handler Settings - these functions as defined in Handlers.js
				// The handlers are not part of SWFUpload but are part of my website and control how
				// my website reacts to the SWFUpload events.

				swfupload_preload_handler : preLoad,
				swfupload_load_failed_handler : loadFailed,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,

				// Button Settings
				button_image_url : "", //images/SmallSpyGlassWithTransperancy_17x18.png
				button_placeholder_id : "spanButtonPlaceholder",
				button_width: 210,
				button_height: 30,
				button_text : '<span class="button" id="swf_addButton">WYBIERZ ZDJĘCIA</span>',
				button_text_style : '.button {font-weight: bold; font-family: Arial; font-size: 14pt; color: #FFFFFF; text-align: center; letter-spacing: 1px;}',
				button_text_top_padding: 15,
				button_text_left_padding: 0,
				button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
				button_cursor: SWFUpload.CURSOR.HAND,

				// Flash Settings
				flash_url : "swfupload/swfupload.swf",
				flash9_url : "swfupload/swfupload_fp9.swf",

				custom_settings : {
					upload_target : "divFileProgressContainer",

					/*
					thumbnail_height: 800,
					thumbnail_width: 900,
					thumbnail_quality: 100 */
				},

				// Debug Settings
				debug: false
			});
		};
	 });
