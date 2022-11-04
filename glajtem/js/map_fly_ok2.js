/**
*
* zmodyfikowany i zminimalizowany na potrzeby glajtem.pl
* 2016-06-08 :: dodano task
* 2016-01-02
*
*/

var infowindow = null;

function loadIGC() {

    var startMarkerImg = new google.maps.MarkerImage(
        "http://maps.gstatic.com/mapfiles/markers2/measle_blue.png",
        new google.maps.Size(5, 5),
        new google.maps.Point(0, 0),
        new google.maps.Point(3, 3)
    );

    var endMarkerImg = new google.maps.MarkerImage(
        "http://maps.gstatic.com/mapfiles/markers2/measle.png",
        new google.maps.Size(7, 7),
        new google.maps.Point(0, 0),
        new google.maps.Point(3.5, 3.5)
    );

    var mapOptions = {
        zoom: 16,
        mapTypeId: google.maps.MapTypeId.TERRAIN,
        map: {
            options: {
                mapTypeControlOptions: {
                    mapTypeIds: [
                        google.maps.MapTypeId.TERRAIN,  // SATELLITE, HYBRID
                        google.maps.MapTypeId.SATELLITE,
                        "relief"]
                }
            }
        }
    };

    var bounds = new google.maps.LatLngBounds();
    var map = new google.maps.Map(document.getElementById("map"), mapOptions);
    var infowindow = new google.maps.InfoWindow({
        content: "<div>InfoWindow</div>"
    });

    var pzty = new Array();

    for (var fi = 0; fi < flightPoints.length; fi++)
	 {

        var flightPlanCoordinates = [];
        var points = flightPoints[fi].points;
        var start = flightPoints[fi].start;
        var end = flightPoints[fi].end;
		  var kolor = flightPoints[fi].kol;

        for (var pi = 0; pi < points.length; pi++)
		  {
            var point = points[pi];
            var mapPoint = new google.maps.LatLng(point[0], point[1]);
            flightPlanCoordinates.push(mapPoint);
            bounds.extend(mapPoint);
        }

        var isFlying = (flightPoints[fi].type === 'flight');
		  //var isKolor = (flightPoints[fi].kol === 'kolory'); flight

		  if(flightPoints[fi].type === 'flight')
		  {
		   var flightPath = new google.maps.Polyline({
            path: flightPlanCoordinates,
            geodesic: true,
            strokeColor: flightPoints[fi].kol,
            strokeOpacity: 0.9,
            strokeWeight: 2,
            title: flightPoints[fi].pilot
         });
		  }
		  else
		  {
			if(flightPoints[fi].type === 'nosi')
			{
			 var flightPath = new google.maps.Polygon({
            path: flightPlanCoordinates,
            geodesic: true,
            strokeColor: '#FF0000',
				fillColor: '#FF0000',
            strokeOpacity: 0.6,
				fillOpacity: 0.6,
            strokeWeight: 0,
            title: flightPoints[fi].pilot
          });
			}
			else
			{
			 if(flightPoints[fi].type === 'task')
			 {


			  var pzPath = new google.maps.Circle({
				geodesic: true,
				fillColor: '#FFFF00',
				strokeColor: '#FFFF00',
            strokeOpacity: 0.8,
				strokeWeight: 1,
				fillOpacity: 0.35,
				center: new google.maps.LatLng(start[0], start[1]),
            radius: end[0],
				title: flightPoints[fi].pilot

           });

 				//var pz = flightPath;

			   pzty.push(pzPath);
			 }

        if(flightPoints[fi].type === 'ts')
		  {
		   var pzPath = new google.maps.Polyline({
            path: flightPlanCoordinates,
            geodesic: true,
            strokeColor: '#ffff00',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            title: flightPoints[fi].pilot
         });

			pzty.push(pzPath);
		  }

		 if(flightPoints[fi].type === 'tsp')
		 {
		  var pzPath = new google.maps.Marker({
         position: new google.maps.LatLng(start[0], start[1]),
         label: flightPoints[fi].pilot
        });

		  pzty.push(pzPath);
		 }


			}
		  }




        if(flightPath)
		  {

		   flightPath.setMap(map);


         google.maps.event.addListener(flightPath, 'click', function (event) {
            var content = "<div>" + this.title + "</div>";

				//flightPath.setMap(null);

            infowindow.setContent(content);
            infowindow.setPosition(event.latLng);
            infowindow.open(map, this);

         });

 		  }

        if(pzPath)
		  {

		   pzPath.setMap(map);

         google.maps.event.addListener(pzPath, 'click', function (event) {

			   //alert(pz + '->' + pzty.length);

				for(jj = 0; jj < pzty.length; jj++)
				{

				 //alert(pz + '->' + jj);
				 pzty[jj].setMap(null);
				}
				/*
				alert(' ### ');

				for(jj = 0; jj < pzty.length; jj++)
				{

				 //alert(pz + '->' + jj);
				 pzty[jj].setMap(map);
				} */
         });

		  }


if(flightPath)
{
		  if(start[0] && start[1])
		  {
         var startMarker = new google.maps.Marker({
            position: new google.maps.LatLng(start[0], start[1]),
            map: map,
            title: "Start",
            icon: startMarkerImg
         });
		  }

		  if(end[0] && end[1])
		  {
         var endMarker = new google.maps.Marker({
            position: new google.maps.LatLng(end[0], end[1]),
            map: map,
            title: "Stop",
            icon: endMarkerImg
         });
		  }
}
    }

    map.fitBounds(bounds);

}


window.onload = loadIGC;