
 function mapaGoole()
 {

  var geocoder;
  var map;


  var address = document.getElementById('gaddress').value;			//-pobranie adresu z pola input/hidden
  var map_title = document.getElementById('gmap_title').value;		//-pobranie poisu dla title markera

  var set_zoom = document.getElementById('gzoom').value;				//-pobranie poisu dla title markera

  if(set_zoom != '')
   set_zoom = set_zoom * 1;
  else
   set_zoom = 12;

  geocoder = new google.maps.Geocoder();

  var latlng = new google.maps.LatLng(53.429805, 14.537883);

  var myOptions = {
      zoom: set_zoom,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  map = new google.maps.Map(document.getElementById('mapa'), myOptions);



  geocoder.geocode({ 'address': address}, function(results, status)
  {
   if (status == google.maps.GeocoderStatus.OK)
	{
        map.setCenter(results[0].geometry.location);

        var marker = new google.maps.Marker({
            map: map,
				title: map_title,
            position: results[0].geometry.location
        });

   }
	else
	{
    alert("Przepraszamy, pobranie mapy Google jest aktualnie niemożliwe :\n" + address +"\nstatus:"  + status);
   }
  });
 }

 mapaGoole();

