<?php 
    global  $fbmo, $userAccessDenied;
	if (!isset($fbmo->promo_row)) 
			$fbmo->get_promo_info($get_promo_id);
    $app_id = $fbmo->options['fb_application_id'];
    $app_secret = $fbmo->options['fb_application_secret'];
    $url =  $fbmo->options['fb_canvas_url'].  urlencode( $fbmo->promo_row["promo_page"] ) . ".php?action=validateuser";

    $code = $_REQUEST["code"];
	$error = $_REQUEST[ "error" ];
	if( !empty($error ))
	{
		$userAccessDenied = true;
	}else
	{
		if(empty($code))
		{
			$dialog_url = "http://www.facebook.com/dialog/oauth?client_id=" . $app_id . "&redirect_uri=" . urlencode($url). "&scope=email,read_stream";
			echo("<script> top.location.href='" . $dialog_url . "'</script>");
		}
		else
		{
			$token_url = "https://graph.facebook.com/oauth/access_token?client_id="
				. $app_id . "&redirect_uri=" . urlencode($url) . "&client_secret="
				. $app_secret . "&code=" . $code;
			
				echo $token_url;
				exit;
				$access_token = file_get_contents($token_url);
			
			   $graph_url = "https://graph.facebook.com/me?" . $access_token;
				echo $graph_url;
			  $user = json_decode(file_get_contents($graph_url));
			   $fbmo->fbuser = $user->id;
			   $fbmo->fbme['name'] = $user->name;
			   $fbmo->fbme['email'] = $user->email;
			   $fbmo->fbme['gender'] = $user->gender;
			   $fbmo->fbme['timezone'] = $user->timezone;
			   $fbmo->fbme['location'] = $user->locale;
			   //print_r(   $fbmo->fbme );
			
				$fbmo->save_user_details_for_promo( $get_promo_id );
		}
	}
?>