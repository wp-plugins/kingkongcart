
   function wpcGoogleButtonRender( authResult ) {
   		var gcid = jQuery(".gcid").val();
		gapi.signin.render("wpcSignIn", { 
			'callback': 'googleLoginCallback', 
			'clientid': gcid, 
			'cookiepolicy': 'single_host_origin', 
			'scope': 'https://www.googleapis.com/auth/plus.login'
		});

   } 

   function googleLoginCallback( authResult ) {
	  if (authResult['access_token']) {
	    // 승인 성공
		console.log(authResult);

		jQuery.ajax({
			type: "GET",
			url: 'https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token='+authResult['access_token'],
			dataType: 'json',
			success: function(data){
				// data.id
				// data.name
				// data.link
				var id 		= data.id;
				var name 	= data.name;
				var link 	= data.link;

				send_wpc_login(id, name, link);
			}
		});

	  } else if (authResult['error']) {

	  }
   }

   function google_plus_load(){

	    var po = document.createElement('script');
	    po.type = 'text/javascript'; po.async = true;
	    po.src = 'https://apis.google.com/js/client:plusone.js?onload=wpcGoogleButtonRender';
	    var s = document.getElementsByTagName('script')[0];
	    s.parentNode.insertBefore(po, s);

   }

   function send_wpc_login(id, name, link){
   		wpc_social_login(id, name, name, 'google');
   }