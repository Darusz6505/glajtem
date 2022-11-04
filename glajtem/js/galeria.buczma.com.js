$j(function() {

 		$j('#gal').carouFredSel({
					//responsive: 'true', // rozciąga na pełną szerokość
					//width: '100%',
					width: '200px',  //-rozwala format i usuwa boczne marginesy
					//align: 'false', //false-likwiduje prawy i lewy margines, czyli dopasowanie ilości zdjeć do szerokości okna
					scroll: {
						items: 1,
						pauseOnHover: true,
						duration: 600
					},
					prev: '#cRight',
					next: '#cLeft',
					auto: {}
				});

});
