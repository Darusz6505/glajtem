var FBinterval = null;
var FBproper_login = false;
var FBproper_mail = false;
var FBinitialized = false;
var tmpFb = false;

function DigFBInit(){
  FBinitialized = true;
   FB.init({
     appId  : '168732949822522',
     status : true,
     xfbml  : true,
     cookie: true
   });
}
/*
function DigFBConnect(){

  DigFBInit();

  FB.getLoginStatus(function(ra) {
    if (ra.session) {
      jQuery.ajax({
        url: '/ajax/dialog/fb_login.php',
        dataType: 'html',
        success: function(r){
          window.location.href =  'https://www.facebook.com/login.php?api_key='+r+'&cancel_url=http%3A%2F%2Fwww.digart.pl%2F&display=page&fbconnect=1&next='+encodeURIComponent(window.location.href)+'&return_session=1&session_version=3&v=1.0';
        }
      });
    }else{
      FBWizzard();
    }
  });
}

function FBWizzard(){

  if(!FBinitialized){
          DigFBInit();
  }

  FB.login(function(response) {
    if (response.session) {
       if (response.perms) {
            FB.api('/me', function(r) {
              tmpFb = r;
              jQuery.ajax({
                url: '/ajax/dialog/fb_check.php',
                dataType: 'html',
                type: "POST",
                data: "fid="+tmpFb.id,
                success: function(rfb){
                  if(rfb == "1"){
                      window.location.href =  window.location.href+'?session';
                  }else{
                      ajaxapp('fb_register','Uzupełnij swoje dane');
                      FBinterval = setInterval(function(){
                        SetRegisterData(
                          (tmpFb.first_name+tmpFb.last_name),
                          tmpFb.email,
                          tmpFb.location ? tmpFb.location.name : 'Polska',
                          tmpFb.gender ? tmpFb.gender : 'male',
                          tmpFb.id,
                          tmpFb.birthday ? tmpFb.birthday : '',
                          (tmpFb.first_name+' '+tmpFb.last_name)
                        );
                      },100);
                  }
                }
              });

           });
      }

  }}, {perms:'read_stream,publish_stream,offline_access,email,user_location,user_birthday'});
}

function FBSimpleWizzard(){
  ajaxapp_close();
  if(!FBinitialized){
          DigFBInit();
  }

  setTimeout(function(){
    FB.login(function(response) {
      if (response.session) {
        if (response.perms) {
             FB.api('/me', function(r) {
                 ajaxapp('fb_manage','Facebook','fid='+r.id);
               });
        }
    }}, {perms:'read_stream,publish_stream,offline_access,email,user_location,user_birthday'});
  },200);
}

function FacebookManager(){
  ajaxapp('fb_manage','Facebook');
}


function SetRegisterData(name,email,location,gender,uid,birth,firstlast){
  if(jQuery("#FBlogin").length){
    clearInterval(FBinterval);

    name = name.replace('Ą','A').
           replace('Ć','C').
           replace('Ę','E').
           replace('Ń','N').
           replace('Ó','O').
           replace('Ś','S').
           replace('Ż','Z').
           replace('Ź','Z').
           replace('ą','a').
           replace('ć','c').
           replace('ę','e').
           replace('ł','l').
           replace('ń','n').
           replace('ó','o').
           replace('Ć','c').
           replace('ż','z').
           replace('ź','z').
           replace('ś','s');

    jQuery("#FBlogin").val(name).focus();
    jQuery("#FBemail").val(email).focus();
    jQuery("#FBlocation").val(location);
    jQuery("#FBgender").val(gender);
    jQuery("#FBUID").val(uid);
    jQuery("#FBbirth").val(birth);
    jQuery("#FBfirstLastName").val(firstlast);
    jQuery("#FBlogin").focus();

    jQuery("#ajaxapp > #top").click(function(){
      window.location.href = window.location;
    });
  }
} */

//kaczo import
/*
function reg_login() {

	if($F("FBlogin").length > 0) {
		var ajax_loginc_success = function(t){
			if(t.responseText == "1") {
				$("FBlogintxt").innerHTML="Login <b>"+ $F("FBlogin") +"</b> już istnieje!";
				$("FBlogin").value="";
				reg_err = reg_err +1;
				FBproper_login = false;
			} else {
				$("FBlogintxt").innerHTML="";
				FBproper_login = true;
			}
		}
		var ajax_loginc_failure	= function(t){ }

		 login=$F("FBlogin");
		var url = '/ajax/user.register.php';
		var pars = 'check='+login;
		var myAjax = new Ajax.Request(url, {method:'post', postBody:pars, onSuccess:ajax_loginc_success, onFailure:ajax_loginc_failure});
	}
}

function reg_email() {

	if($F("FBemail").length > 0) {
		var ajax_logince_success = function(t){
			if(t.responseText == "1") {
				$("FBemailtxt").innerHTML="E-mail <b>"+ $F("FBemail").replace('%40','@') +"</b> już jest używany!";
				$("FBemail").value="";
				FBproper_mail = false;
			} else {
				$("FBemailtxt").innerHTML="";
				FBproper_mail = true;
			}
		}
		var ajax_logince_failure	= function(t){}

		 email=$F("FBemail");
		var url = '/ajax/user.register.php';
		var pars = 'mail='+email;
		var myAjax2 = new Ajax.Request(url, {method:'post', postBody:pars, onSuccess:ajax_logince_success, onFailure:ajax_logince_failure});
	}
}

function FBregisterCheck(){
  if(FBproper_mail && FBproper_login){
    return true;
  }
  return false;
}

function FBpassCheck(){

	if(jQuery("#FBDApass").length > 0 && jQuery("#FBDApass").length > 0 ) {
		if(jQuery("#FBDApass").val().length < 5 && jQuery("#FBDApassCheck").val().length < 5) {
			jQuery("#zmienpasserr").html("Hasło jest za krótkie!");
		} else if(jQuery("#FBDApass").val() != jQuery("#FBDApassCheck").val()) {
			jQuery("#zmienpasserr").html("Hasła nie są zgodne!");
		} else {
			jQuery("#zmienpasserr").html("");
			return true;
		}
	}

	return false;
} */
