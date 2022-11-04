/**
*
* zmodyfikowany i zminimalizowany na potrzeby glajtem.pl
* 2020-04-02 :: po zmianach na google maps, czasami tworzy się podwójna lista pilotów !!!
* 2016-06-08 :: dodano task
* 2016-01-02
*
*/

 var infowindow = null;
 var taski = new Array();
 var pzty = new Array();
 var traki = new Array();
 var map;
 var bounds;
 var akt_task = null;

 var piloty = new Array();

 function loadIGC() {

  //piloty = [];

  var loty = document.getElementById("loty");

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
		  zoomControl: false,
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


  bounds = new google.maps.LatLngBounds();
  map = new google.maps.Map(document.getElementById("map"), mapOptions);

  var infowindow = new google.maps.InfoWindow({
        content: "<div>InfoWindow</div>"
  });

  var zn = 1;  //- znacznik indeksu tablicy


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
      strokeOpacity: 0.8,
      strokeWeight: 2,
      title: flightPoints[fi].pilot
   });

	traki[fi] = flightPath;

	var xpilot = flightPoints[fi].pilot.split("<br />\r\n");


	 if(jestwtab(xpilot[1]) == 0)
	 {
	  loty.innerHTML = loty.innerHTML + '<label for="pil'+fi+'"><input type="checkbox" onclick="trackw('+fi+');" id="pil'+fi+'" checked="checked"><span style="color:'+flightPoints[fi].kol+'"> '+ xpilot[1] +' </span></label>';

		piloty[fi] = xpilot[1];
    }

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

		  }

		  if(flightPoints[fi].type === 'tsp')
		  {
		   var pzPath = new google.maps.Marker({
          position: new google.maps.LatLng(start[0], start[1]),
          label: flightPoints[fi].pilot
         });

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

		//problem ostatniego tasku!!

		if(flightPoints[fi].type === 'ts' || flightPoints[fi].type === 'tsp' || flightPoints[fi].type === 'task')
		{
		 if(zn != end[1])
		 {

		  taski[zn] = pzty;

		  zn = end[1];
		  pzty = [];

		  pzty.push(pzPath);

		 }
		 else
		 {
		  pzty.push(pzPath);
		 }

	  }

    }

  if(taski.length > 0)
  {
   taski[4] = pzty;

	for(c = 0; c < taski[1].length; c++)
	{
	 taski[1][c].setMap(map);
	}

	akt_task = 1;
  }

  map.fitBounds(bounds);

}

/**
*
*
*/

function jestwtab(zm)
{

 for(kk = 0; kk < piloty.length; kk++)
 {
   if(piloty[kk] == zm)
	 return 1;
 }

 return 0;
}

/**
*
*
*
*/

function taskiremov(nr)
{

 if(akt_task)
 {

  for(jj = 0; jj < taski[akt_task].length; jj++)
  {
   taski[akt_task][jj].setMap(null);
  }

 }

 if(nr)
  akt_task = nr;
 else
  akt_task = null;

}

/**
*
*
*/

function onclickTask(n)
{
 if(n == 1)
 {

  taskiremov(1);

  for(jj = 0; jj < taski[1].length; jj++)
  {
   taski[1][jj].setMap(map);
  }

 }

 if(n == 2)
 {
  taskiremov(2);

  for(jj = 0; jj < taski[2].length; jj++)
  {
   taski[2][jj].setMap(map);
  }
 }

 if(n == 3)
 {
  taskiremov(3);

  for(jj = 0; jj < taski[3].length; jj++)
  {
   taski[3][jj].setMap(map);
  }
 }

 if(n == 4)
 {
  taskiremov(4);

  for(jj = 0; jj < taski[4].length; jj++)
  {
   taski[4][jj].setMap(map);
  }
 }

 if(n == 0)
 {
  taskiremov();
 }

 map.fitBounds(bounds);
}

/**
*
*
*/

function trackw(n)
{

 var pil = 'pil' + n;

 var pilot = document.getElementById(pil);

 if(pilot.checked)
 {
  traki[n].setMap(map);
 }
 else
 {
  traki[n].setMap(null);
 }

 //map.fitBounds(bounds); //-bez tego utrzymuje zmienione pozycje i zoom

}

window.onload = loadIGC;