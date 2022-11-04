

	var obr;
	var rozw = "";

	var x = 26;
	var size = 0;

	function changew(){


	 //alert("foto = " + fot[x].src);
	 //alert("foto width = " + size);

	 //rozw = " " + (4 + fot[x].width) * 9 + "px";

	 rozw = " " + (4 + size) * 9 + "px";

	 //alert("foto width = " + fot[x].width);

	 //obr = document.getElementById("mmfly");
	 //rozw = " " + (4 + obr.width) * 9 + "px";

	 //if(obr.width)

	 if(size)
	 {
	  document.getElementById("dmf1").style.width=rozw;
	  document.getElementById("dmf2").style.width=rozw;
    }
	 else
	 {
	  alert("Refresh window");
	  setTimeout("location.href=\"" + "http://glajtem.pl/flymet2.html" + "\"", 1000);
	 }
	}
	 //-start

	 var box = document.getElementById("dmf1");
	 var fot = box.getElementsByTagName("img");

	 //fot[0].onload = alert("foto = " + fot[0].src);



	 fot[x] = new Image();

	 fot[x].onload = function(){

	  size = fot[x].width;

	  changew();
	 };

	 //-end

	 //window.onload = changew();