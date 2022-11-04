


//################### BANER

function zamknij() {
  widoczne=false;
}

function gone() 
{
  if (window.scrollX>=0) 
  { 
    x = window.scrollX; y = window.scrollY;
  } 
  else 
  { 
   x = document.body.scrollLeft;  
   y = document.body.scrollTop; 
  }

  if (y == 0) y = document.documentElement.scrollTop;
 
  docelowax = pozycjax + x;
  doceloway = pozycjay + y;
	
  yd = (doceloway - parseInt(obj.style.top)) / 5;
	
  //document.getElementById('reklamal').innerHTML = '<p style=\'margin-top: 100px;\'>' + '<br>y=' + y + '<br>top=' + parseInt(obj.style.top) + '</p>';		
		
  xd = (docelowax - parseInt(obj.style.left)) / 5;

  obj.style.left = parseInt(obj.style.left) + xd + 'px';

  obj.style.top =  parseInt(obj.style.top) + yd + 'px';

	
  if(widoczne) 
  { setTimeout("gone()", 50); }
  else 
  { obj.style.visibility = 'hidden'; }

}

function baner()
{
 pozycjax = -165;
 pozycjay = 10;
 widoczne = true;
 yd = 0;
 xd = 0;
 obj = document.getElementById('reklamal');
 obj.style.top = pozycjay + 'px';
 obj.style.left = pozycjax + 'px';
 obj.style.visibility = 'visible';
 
 obj2 = document.getElementById('banery0');
 obj2.style.position = 'relative';
 
 gone();
}
