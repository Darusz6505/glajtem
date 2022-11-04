/**
*
* zmodyfikowany i zminimalizowany na potrzeby glajtem.pl
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


    for (var fi = 0; fi < flightPoints.length; fi++) {
        var flightPlanCoordinates = [];
        var points = flightPoints[fi].points;
        var start = flightPoints[fi].start;
        var end = flightPoints[fi].end;
		  var kolor = flightPoints[fi].kol;

        for (var pi = 0; pi < points.length; pi++) {
            var point = points[pi];
            var mapPoint = new google.maps.LatLng(point[0], point[1]);
            flightPlanCoordinates.push(mapPoint);
            bounds.extend(mapPoint);
        }

        var isFlying = (flightPoints[fi].type === 'flight');
		  //var isKolor = (flightPoints[fi].kol === 'kolory');

		  if(flightPoints[fi].type === 'nosi')
		  {
		   var flightPath = new google.maps.Polygon({
            path: flightPlanCoordinates,
            geodesic: true,
            strokeColor: '#FF0000',
				fillColor: '#FF0000',
            strokeOpacity: 0.9,
				fillOpacity: 0.9,
            strokeWeight: 0,
            title: flightPoints[fi].pilot
         });

		  }
		  else
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

		  //strokeColor: (isKolor) ? flightPoints[fi].kol : '#000000',

        flightPath.setMap(map);

        google.maps.event.addListener(flightPath, 'click', function (event) {
            var content = "<div>" + this.title + "</div>";

            infowindow.setContent(content);
            infowindow.setPosition(event.latLng);
            infowindow.open(map, this);
        });

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

    map.fitBounds(bounds);

}

window.onload = loadIGC;