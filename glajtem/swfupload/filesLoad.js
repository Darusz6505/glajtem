/*

grupowe ładowanie plików za pomocą javaScript'u

2011-09-18 -> 2012-01-22 -

*/

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
				file_size_limit : "2 MB", //-koniecznie códzysłów
				file_types : "*.jpg",                //-koniecznie códzysłów
				file_types_description : "JPG Images", //-koniecznie códzysłów
				file_upload_limit : "0",  //-koniecznie códzysłów

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
