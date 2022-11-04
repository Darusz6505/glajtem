
			jQuery(function($){

				$.supersized({

					slide_interval       :   3000,		// Length between transitions
					transition           :   1, 			// 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
					transition_speed		:	1000,			// Speed of transition
					//fit_always				: 1,

					slide_links				:	'blank',	// Individual links for each slide (Options: false, 'num', 'name', 'blank')
					slides 					:  [				// Slideshow Images
					{image : './application/zdjecia/1.jpg',
					  		title : '1',
							thumb : '',
							url : ''},
					{image : './application/zdjecia/2.jpg',
					  		title : '2',
							thumb : '',
							url : ''}
				 ]
				});
		    });
