
			jQuery(function($){

				$.supersized({

					slide_interval       :   5000,		// Length between transitions
					transition           :   1, 			// 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
					transition_speed		:	2000,			// Speed of transition
					//fit_always				: 1,

					slide_links				:	'blank',	// Individual links for each slide (Options: false, 'num', 'name', 'blank')
					slides 					:  [				// Slideshow Images
					{image : './application/zdjecia/sps_tlo.jpg',
					  		title : '1',
							thumb : '',
							url : ''},
					{image : './application/zdjecia/kkp_tlo.jpg',
					  		title : '2',
							thumb : '',
							url : ''},
					{image : './application/zdjecia/browar_tlo.jpg',
					  		title : '9',
							thumb : '',
							url : ''},
					{image : './application/zdjecia/ensky_tlo.jpg',
					  		title : '3',
							thumb : '',
							url : ''},
					{image : './application/zdjecia/ifly_tlo.jpg',
					  		title : '4',
							thumb : '',
							url : ''},
					{image : './application/zdjecia/tryfly_tlo.jpg',
					  		title : '5',
							thumb : '',
							url : ''}
				 ]
				});
		    });