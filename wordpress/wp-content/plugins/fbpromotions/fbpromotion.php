<?php
/*
Plugin Name: Bugs Go Viral - Facebook Promotions
Plugin URI: http://bugsgoviral.com/changelog/
Description: Create and execute fully branded Facebook promotions directly from your WordPress admin- build your fan base and create word-of-mouth!.
Version: 1.3.3 (private beta)
Author: Bugs Go Viral, LLC
Author URI: URI: http://bugsgoviral.com
*/

	// Stop direct call
	error_reporting(0);
	if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
	define('FACEBOOK_DEBUG',true);
	define('FBPROMODIR',dirname(__FILE__));
	if (!class_exists('fbmo_plugin')) {
	class fbmo_plugin {
	var $options;
	var $fboptions;
	var $loginUrl;
	var $session;
	var $facebook_path;
	var $admin_messages=array();
	var $error_message;
	var $fbgraph;
	var $fbuser;
	var $fbme;
	var $facebook_log;
	var $app_user=0;
	#var $req_perms='publish_stream,offline_access,status_update,user_likes,sms,user_photo_video_tags,user_photos,user_videos';
	var $req_perms='user_likes,user_location,user_hometown,email';
		function fbmo_load()	{
			global $facebook_page;
			$this->load_options();
			$this->facebook_path=dirname(__FILE__).'/facebookapi/';
			if ( is_admin())  {
				# backend--admin
				require_once (dirname (__FILE__) . '/admin/admin.php');
			} elseif ($GLOBALS["is_application"])	{
				if (! empty($_REQUEST["facebook_page"])){
					$facebook_page=$_REQUEST["facebook_page"];					
				}
				if (!isset($facebook_page) and  !isset($_GET["page_id"])){
					$facebook_page="Main";
				}
				if (isset($facebook_page))	{
					#frontend--facebook app
					$this->fbmo_facebook_call();
				}
				//echo "fbpage ". $facebook_page;
			} elseif ($GLOBALS["is_ajax"])	{
				# asysnc call
			}
		}
		function fbmo_facebook_call()	{
			#frontend--facebook app
			# start the frontend facebook functions
			$this->start_facebook_api();
			# check to see if we are updating facebook settings
			if (isset($facebook_page) and isset($_GET["update_settings"]))	{
				if (get_option('fbmo_facebook_opt_update'))
					$this->facebook_settings();
			}
			# check the plugin license status
			if (!$this->check_license_status())	{
					# all we show is an under contruction message
					add_filter('the_content','fbm_filter_error_content');
					$this->error_message="This promotion has not been activated";
					return;
			}
			# check user status
			if (isset($this->get_valid_fb_param['authorized'])) {
				# new user init
				$this->new_user_init();
				$this->app_user=1;
			} elseif ($this->get_valid_fb_param['uninstall'])	{
				# user cleanup
				$this->user_uninstall();
			} elseif ($this->get_valid_fb_param['added'])		{
				# app user
				$this->app_user=1;
				$this->update_app_user();
			}
		}
		function get_valid_fb_param($fld)	{
			# finish this to check signature, etc
			if (isset($_REQUEST["fb_sig_$fld"]))
				return $_REQUEST["fb_sig_$fld"];
		}
		function new_user_init()   {
			# user is authorizing
			# update the fb_user table if not present
			global $wpdb;
			if (! $this->get_fb_user($this->fbuser))	{
				$data=array('uid'=>$this->fbuser,'active'=>'Y',
					'name'=>$this->session['name']);
				$format=array('active'=>'%s','uid'=>'%s','name'=>'%s');
				$wpdb->insert( "fb_users", $data, $format );
			}
		}
		function user_uninstall()		{
			# user cleanup
			# update the fb_user table if present
			global $wpdb;
			if ($this->get_fb_user($this->fbuser))	{
				$wpdb->query("DELETE from fb_users WHERE uid='$this->fbuser'");
			}
		}
		function update_app_user()		{
			# update the fb_user table if not present
			global $wpdb;
			if ($this->get_fb_user($this->fbuser)==0 and $this->fbuser>0)	{
				$data=array('uid'=>$this->fbuser,'active'=>'Y',
					'name'=>$this->session['name']);
				$format=array('active'=>'%s','uid'=>'%s','name'=>'%s');
				$wpdb->insert( "fb_users", $data, $format );
			}
		}
		function get_fb_user($uid)	{
			global $wpdb;
			$fb_row=$wpdb->get_row(
			"SELECT * from fb_users WHERE uid=$uid",
			ARRAY_A);
			return count($fb_row);
		}

		function load_options() {
			// Load the options
			$this->options = get_option('fbmo_options');
			$this->fboptions=get_option('fbmo_facebook_settings');  //get current option settings if any
		}
		function facebook_settings()	{
			# set some callback urls
			##############################################
			if (!defined($this->fbuser)) {
				#add_filter('the_content','fbm_filter_error_content');
				$this->error_message="Facebook settings have not been retrieved.";
				return;
			}
			$properties=get_option('fbmo_facebook_settings');  //get current option settings if any
			#$result=$admin_setAppProperties($properties);
			delete_option('fbmo_facebook_opt_update');
			return;
		}
		function start_facebook_api()	{
			# start the log
			$time = time();
			echo "<input type='hidden' name='time' value='".$time."'>";
			if (! $this->facebook_log=fopen(dirname(__FILE__)."/facebook.log","a")) {
				$this->error_message="Could not open log file";
				return;
			}
			if (!fwrite($this->facebook_log,"Date: ".date("j-m-y, h:i:s")."\n")) {
				$this->error_message="Could not write to log file";
			}
			# set debug mode and dump request vars
			$GLOBALS["facebook_config"]["debug"]=FACEBOOK_DEBUG;
			# get the credentials
			if (!isset($this->options['fb_application_id']))	{
				$this->fbmo_dump($this->options);
				$this->error_message="The Facebook credentials have not been set";
				return;
			}
			try{
				include_once(dirname(__FILE__)."/facebookapi/facebook.php");
			}
			catch(Exception $o){
				$this->fbmo_dump($o);
			}
			// Create our Application instance.
			$this->fbgraph = new Facebook(array(
			  'appId'  => $this->options['fb_application_id'],
			  'secret' => $this->options['fb_application_secret'],
			  'cookie' => true,
			 ));
			$this->session = $this->fbgraph->getSession();
			#$this->is_tab = isset($_REQUEST['fb_sig_in_profile_tab']);
			$this->is_tab=isset($this->session["profile_id"]);
			$this->fbme = null;
			if ($this->session) {
				try {
					$this->fbuser = $this->fbgraph->getUser();
                    $this->req_perms="email,user_likes,user_location,user_hometown";
					$this->fbme = $this->fbgraph->api('/me');
			  } catch (FacebookApiException $e) {
				  $this->fbmo_dump($e);
				}
			} elseif(! $this->is_tab) {
				if($_REQUEST["action"]=="vote")
				$this->req_perms="";
			
				$this->loginUrl = $this->fbgraph->getLoginUrl(
					array(
					'canvas'    => 1,
					'fbconnect' => 0,
					'req_perms' => $this->req_perms,
					'page' => 'popup'
					)
				);
			}	
			if (is_array($this->session))	{
				foreach ($this->session as $key=>$val)	{
					fwrite($this->facebook_log,"session: $key--$val\n");
				}
			}
	}

		function check_license_status()	{
			################################
			### finish this in phase 3
			return 1;
		}
		function activate()	{
			require_once (dirname (__FILE__) . '/admin/install.php');
			$fbmo_install= new fbmo_activate;
			$fbmo_install->fbmo_activate();
		}
		function deactivate()	{
			# maybe email main site
			require_once (dirname (__FILE__) . '/admin/install.php');
			$fbmo_install= new fbmo_activate;
			$fbmo_install->fbmo_deactivate();
		}
		function get_promo_info($promo_id)	{
			global $wpdb;
			$this->promo_row=$wpdb->get_row(
			"SELECT * from fb_promotions WHERE promo_id=$promo_id",
			ARRAY_A);
			# check status
			$this->promo_row["status"]="not_active";
			if ($this->promo_row)	{
				$time= time()-(5*3600);
				$mdate = date("Y-m-d h:i A",$time);
				$d1 = new DateTime($mdate);
				$end_date = date("Y-m-d h:i A",strtotime($this->promo_row["promo_end"]));
				$d2 = new DateTime($end_date);
				if ($d2>=$d1)   $this->promo_row["status"]="not_active";
				if(!empty($this->promo_row["activation_key"]))    $this->promo_row["status"]="active";
				if ($d2<$d1)    $this->promo_row["status"]="closed";
				
			}
		 }
		function check_promo_entry($promo_id,$entry="")	{
			global $wpdb;
			if (!empty($entry))	{
				# get by entry
				$entry_row=$wpdb->get_row(
				"SELECT * from fb_entries
				WHERE promo_id=$promo_id and entry_id=$entry",
				ARRAY_A);
			} else	{
				$entry_row=$wpdb->get_row(
				"SELECT * from fb_entries
				WHERE promo_id=$promo_id and uid=$this->fbuser",
				ARRAY_A);
			}
			if ($entry_row)
				$this->user_points=$this->get_user_points($promo_id,$entry_row["uid"]);
			return $entry_row;
		}

		function check_entrant_fan_status()	{
			# check to see if the visitor has fan or like status
			$id=$this->options['fb_fanpage_url'];
			$id=preg_match("/\d*$/",$id,$matches);
			$id=$matches[0];
			# added session check 10/13/10
			if ($this->session)	{
			$like_it=$this->fbgraph->api('/me/likes');
			if(!empty($like_it))	{
				foreach($like_it as $str)	{
					foreach($str as $likes)	{
						if ($likes['id']==$id)	return 1;
					}
				}
			}
		}
			return 0;
		}
		function get_friend_count(){
			global $wpdb;
			if ($this->session)	{
				$friends_list=$this->fbgraph->api('/me/friends');
				$friends = $friends_list['data'];
				return $friend_count = count($friends);
			}
		}
			function get_friend_list(){
			global $wpdb;
			if ($this->session)	{
				$friends_list=$this->fbgraph->api('/me/friends');
				return $friends = $friends_list['data'];
				
			}
		}
	
		
		function save_reedemed_units(){
			global $wpdb;
			for($i=0;$i<count($_REQUEST['ids']);$i++){
				$wpdb->query("UPDATE fb_entries SET reedeemed_units='".$_REQUEST['reedemed_units'][$i]."' where entry_id='".$_REQUEST['ids'][$i]."'");
			}
		}
		function save_gift_card_details(){
			global $wpdb,$promo_row;
			if ($this->session && $_REQUEST['gift']=="yes")	{
			$wpdb->query("UPDATE fb_entries SET gift_card_friends_name='".$_REQUEST['card_name']."',gift_card_friends_email='".$_REQUEST['card_email']."' where uid=".$this->session['uid']);
			}
			$url=$this->options['fb_canvas_url'].$_REQUEST["promo_page"].".php";
			header ("Location: $url");
		}
		function get_promo_entries($promo_id)	{
			global $wpdb,$promo_entries;
			
			if (isset($_REQUEST["sort_order"]))
				$sort_order="ORDER BY ".$_REQUEST["sort_order"];
			else 	
				$sort_order="ORDER BY points desc";
			$promo_entries=$wpdb->get_results(
			"SELECT e.*,m.media_id,m.media_url,m.media_title,m.media_text,m.vote_counter,m.rate_counter,m.like_counter,m.date_entered
              from fb_entries as e left join fb_media as m on e.entry_id= m.entry_id where e.promo_id=$promo_id $sort_order" );
		}
	
		function get_promo_analytics_dashboard($promo_id ="")	{
			global $wpdb;
			$sql_con ="";
			if(!empty($promo_id))
			$sql_con = " where promo_id=".$promo_id;
		   return $wpdb->get_row(
			"SELECT count(*) as participants, sum(likes) as likes from fb_entries $sql_con" );
		}
		function get_hive_results()	{
			global $wpdb;
			$tot_prize = $this->get_total_prize();
			$promo_hive=$wpdb->get_row(
			"SELECT COUNT( * ) AS participants, SUM( likes ) AS likes
			FROM fb_promotions AS pr
			JOIN fb_entries AS e ON pr.promo_id = e.promo_id
			WHERE promo_end < NOW( )" );
			return $str = ($promo_hive->participants).",".($promo_hive->likes).",".($tot_prize->total_prize);
		}
			function get_total_prize()	{
			global $wpdb;
			return $promo_total_prize = $wpdb->get_row(
			"SELECT sum(promo_prize ) as total_prize 
			FROM fb_promotions 
			WHERE promo_end < NOW( ) " );
		}
		function get_top_influencers_dashboard($promo_id = "")	{
			global $wpdb;
			$sql_con ="";
			if(!empty($promo_id))
			$sql_con = " where promo_id=".$promo_id;
			
		    return $wpdb->get_results(
			"SELECT name 
			FROM  `fb_entries` $sql_con
			ORDER BY points DESC , `friends_count` DESC
			LIMIT 5" );
		}
		
		
	function save_user_details_for_promo($promo_id)	{
			# user is entering contest
			if(!isset($promo_id))
			return;
			if ($this->check_promo_entry($promo_id)) 
			return;
			global $wpdb,$fbmo_is_entered,$fbmo_just_entered,$entry_id;
			$data=array(
				'promo_id'=>$promo_id,
				'uid'=>$this->fbuser,
				'name'=>$this->fbme['name'],
				'email'=>$this->fbme['email'],
				'gender'=>$this->fbme['gender'],
				'location'=>$this->fbme['location'],
				'timezone'=>$this->fbme['timezone'],
				'likes'=>1,
				'friends_count'=>$friend,
				'entry_date'=>date("Y-m-d")
				);
			$wpdb->insert( "fb_entries",$data,
						array('promo_id'=>"%d",'uid'=>"%s",
						'name'=>"%s",'email'=>"%s",'gender'=>"%s",'location'=>"%s",'timezone'=>"%s",'likes'=>"%d",'friends_count'=>"%d",'entry_date'=>"%s"));
			$fbmo_is_entered=1;
			$fbmo_just_entered=1;
			$entry_id=$wpdb->insert_id;
			if (isset($_REQUEST["fb_redirect"]))	{
				#redirect to FB app
				$url=$this->options['fb_canvas_url'].$this->promo_row["promo_page"].".php";
				header ("Location: $url");
			}
		}
		//return send link in footer of promos
		function getInviteLink(){
			$fan_array = explode("/",$this->options['fb_fanpage_url']);
			$fan_url = $fan_array[0]."//".$fan_array[2]."/".$fan_array[4];
			return $redirect_url_app =  $fan_url."?sk=app_".$this->options['fb_application_id'];
		}
		//return page name of application
		function get_page_name(){
			$fan_array = explode("/",$this->options['fb_fanpage_url']);
			

		$graph_link = 'https://graph.facebook.com/'.$fan_array[5];
	    $file=file_get_contents( $graph_link );
		
	    if(preg_match_all('~{(.*?)}~', $file ,$title,PREG_SET_ORDER))
			{
				 $check =0;
				foreach ($title as $val)
				{
					if($check==0){
						$arr_names=explode(',"name":',$val[0]);
						$row1=explode(',"',$arr_names[1]);
						$name=$row1[0];
						$check =1;
					    }
					}
			}
			
			return $name;
		}
		//send email to media entries
		function send_email_media_entries(){
			//print_r($_REQUEST);
			$msg_end  = " <p>Thanks </p> ";
			$msg_end.= "<p>Support Team</p> ";
			$msg =    $_REQUEST['message'].$msg_end;
			$subject = $_REQUEST['subject'];
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			// Additional headers
			$headers .= 'From: Support Team <'.$_REQUEST['from_email'].'>' . "\r\n";
			$media_items=$this->get_promo_media($_REQUEST["promo_id"]);
				foreach ($media_items as $media_row)	{
				mail($media_row['user_email'], $subject, $msg,$headers );
			}
		}
		function save_promo_entry($promo_id)	{
			# user is entering contest
			if ($this->check_promo_entry($promo_id)) return;
			global $wpdb,$fbmo_is_entered,$fbmo_just_entered,$entry_id;
			 $friend = $this->get_friend_count();
			 $data=array(
				'promo_id'=>$promo_id,
				'uid'=>$this->fbuser,
				'name'=>$this->fbme['name'],
				'email'=>$this->fbme['email'],
				'gender'=>$this->fbme['gender'],
				'location'=>$this->fbme['location']['name'],
				'timezone'=>$this->fbme['timezone'],
				'likes'=>1,
				'friends_count'=>$friend,
				'entry_date'=>date("Y-m-d")
				);
					
				if(isset($this->fbuser)){
					$wpdb->insert( "fb_entries",$data,
								array('promo_id'=>"%d",'uid'=>"%s",
								'name'=>"%s",'email'=>"%s",'gender'=>"%s",'location'=>"%s",'timezone'=>"%s",'likes'=>"%d",'friends_count'=>"%d",'entry_date'=>"%s"));
					
					$fbmo_is_entered=1;
					$fbmo_just_entered=1;
					$entry_id=$wpdb->insert_id;
			}
			if (isset($_REQUEST["fb_redirect"]))	{
				#redirect to FB app
				 $url=$this->options['fb_canvas_url'].$this->promo_row["promo_page"].".php?page=Main";
			      echo "<script type='text/javascript'>self.location.href = '$url';</script>";
			}
		}
		function stream_publish($message,$attachment="",$name="",$caption="",$desc="",
				$media="",$src="",$mediahref="",$link="",$linktext="",$linkhref="",$callback="") {
			$params=array(
				'message'=> $message,
				'cb' => $callback);
			if ($attachment)	{
				$params["name"]=$name;
				$params["caption"]=$caption;
				$params["description"]=$desc;
			}
			if ($media)	{			$params["picture"]=$src;
			}

			if ($link)	{			$params["link"]=$linkhref;
				$params["actions"]=array('name'=>$linktext,'text'=>$linkhref);
			}
			try {
				$statusUpdate = $this->fbgraph->api('/me/feed', 'post', $params);
			} catch (FacebookApiException $e) {
					$this->fbmo_dump($e);
			}
		}
		function get_promo_list()	{
			# get the full list for admin
			global $wpdb,$promo_list;
			$promo_list=$wpdb->get_results(
			"SELECT * from fb_promotions order by promo_end" );
		 }
		function get_landing_list()	{
			# get the landing list
			global $wpdb,$promo_list;
			$promo_list=$wpdb->get_results(
			"SELECT * from fb_promotions WHERE  activation_key!='' order by landing_order " );
		 }
		function delete_promo_info($promo_id)	{
			global $wpdb;
			$this->get_promo_info($promo_id);
			$wpdb->query("DELETE from fb_entries WHERE promo_id=$promo_id");
			$wpdb->query("DELETE from fb_media WHERE promo_id=$promo_id");
			$wpdb->query("DELETE from fb_ratings WHERE promo_id=$promo_id");
			$wpdb->query("DELETE from fb_social WHERE promo_id=$promo_id");
			$wpdb->query("DELETE from fb_social WHERE promo_id=$promo_id");
			$wpdb->query("DELETE from fb_votes WHERE promo_id=$promo_id");
			$wpdb->query("DELETE from fb_promotions WHERE promo_id=$promo_id");
			#delete page
			$page_file_path= ABSPATH . '/facebookapp/'.$this->promo_row["promo_page"].".php";
			if(file_exists($page_file_path))	unlink($page_file_path);
		}
		function send_email_friend(){
			global $wpdb;
			$this->get_promo_info($_REQUEST['promoId']);
			$to  = $_REQUEST['friend_email']; // note the comma
			// subject
			$subject = 'Inviataton to view '.$this->promo_row['promo_name'];
			// message
			$message = '
			<html>
			<head>
			</head>
			<body>
			  <p>Hi,</p>
			  <p>'.$this->promo_row['promo_name'].' is a great application to view:</p>
			  <p><a href="'.$this->promo_row['link_universal_two'].'">'.$this->promo_row['promo_name'].'</a></p>
			  <p>'.$_REQUEST['msg'].'</p>
			</body>
			</html>
			';
			// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '.$_REQUEST['email']. "\r\n";
			// Mail it
			if(mail($to, $subject, $message, $headers)){
				$url=$this->options['fb_canvas_url'].$this->promo_row["promo_page"].".php?facebook_page=Main&promote=complete&action=mail_sent";
				header ("Location: $url");
			}

		}
		function save_promo_info($promo_id="",$data,$format="")	{
			global $wpdb;
			foreach($data as $fld=>$val)	{
				$data[$fld]=stripslashes($val);
			}
			if (empty($promo_id))	{
				$wpdb->insert( "fb_promotions", $data, $format  );
				$promo_id=$wpdb->insert_id;
				$_REQUEST["promo_id"]=$promo_id;
				# generate promo page
				$promo_page=fbmo_promo_type($data["promo_type"]).
					trim($promo_id);
				$wpdb->update("fb_promotions",array("promo_page"=>$promo_page),
					array('promo_id'=>$wpdb->insert_id),$format,'%d');
				$page_file_path= ABSPATH . '/facebookapp/'.$promo_page.".php";
				if (!file_exists($page_file_path))	{
					$PAGE=fopen($page_file_path,"w");
					if (!$PAGE)	die("cannot open promo page");  //shouldn't happpen
					if (!fwrite($PAGE, fbm_promo_page_contents($promo_id))) die("cannot write promo page");
				}
			} else	{
				$wpdb->update("fb_promotions", $data,array('promo_id'=>$promo_id),$format,'%d');
			}
		}
		function fbpromofields($table)	{
			global $wpdb;
			$data=array();
			$promo_fields=$wpdb->get_results(
				"SHOW COLUMNS FROM $table" , ARRAY_A);
			foreach ($promo_fields as $row)   {
				if ($row["Field"]=="promo_id")	continue;
				if (stristr($row["Type"],"varchar") or
					stristr($row["Type"],"date") or
					stristr($row["Type"],"float") or
					stristr($row["Type"],"text")) $format="%s";
				
				else  $format="%d";
				$data[$row["Field"]]=$format;
			}
			return $data;
		}
		############################# media functions ###############
		function save_usermedia($promo_id)	{
			global $wpdb,$media_id,$media_error,$entry_id;
			$media_error=0;
			
			if (!isset($this->promo_row))	$this->get_promo_info($promo_id);
			#if (!isset($_REQUEST["uid"]))	die("missing uid parameter");
			if (!isset($_REQUEST["entry_id"]))	die("missing entry parameter");
			$entry_id=$_REQUEST["entry_id"];
			$erow=$this->check_promo_entry($promo_id,$entry_id);
			$this->fbuser=$erow["uid"];
			$media_url="";
			$media_text="";
			
			# check for duplicate media if required
			if (!$this->check_media_entry($promo_id)) 	{
				if (empty($_REQUEST["media_title"])){
				$media_error="Please enter some media title.";
				return $media_error;
				
			}else{
			# check for file_upload--save file and set the media_url
			if (isset($_FILES["upload_photo"]))        {
				$_FILES['upload_photo']['type']= strtolower($_FILES['upload_photo']['type']);
				
				if($_FILES['upload_photo']['type']=="image/gif" || $_FILES['upload_photo']['type']=="image/pjpeg" ||$_FILES['upload_photo']['type'] =="image/jpeg" || $_FILES['upload_photo']['type'] =="image/jpg" ){
				$directory=ABSPATH . 'facebookapp/uploads/contest_images/';
 			    $media_url=$this->upload_photo($promo_id,$entry_id,
				"upload_photo",$directory,"15000000");
					if (substr($media_url,0,10)=="FBMO_ERROR")	{
						$media_error=substr($media_url,11,99);
					
						?>
                        <script>alert('<?php echo $media_error;?>');</script>
                        <?php
						return $media_error;
					}
				}else{
					$media_error = "Only gif or jpeg images are allowed.";
					?><script>alert('<?php echo $media_error;?>');</script>
                    <?php
						return $media_error;
				}
			}	
			  elseif(isset($_REQUEST["media_url"]))	{
				if (!stristr($_REQUEST["media_url"],"youtube"))	{
					$media_error="Must be a Youtube video!";
						return $media_error;
				}
				$media_url=$_REQUEST["media_url"];
				$media_url=$this->get_youtube_id($media_url);
			}	elseif(isset($_REQUEST["media_text"])) {
				$media_text=$_REQUEST["media_text"];
			}
			}
			
			if (!$media_error) 	{
			# check for media_title
			if (isset($_REQUEST["media_title"])) $media_title=$_REQUEST["media_title"];
			else $media_title="";
			//$data=array(
				$entry_id=$erow["entry_id"];
				$promo_id=$promo_id;
				$uid=$this->fbuser;
				$media_url=$media_url;
				$media_text=$media_text;
				$media_title=$media_title;
				$vote_counter=0;
				$uid_name=$_REQUEST['uid_name'];
				$user_birth_date=$_REQUEST['user_birth_date'];
				$email = $_REQUEST['user_email'];
				$wpdb->query( "INSERT INTO `fb_media` SET `entry_id`='".$entry_id."',`promo_id`='".$promo_id."',`uid`='".$uid."',`user_email`='".$email."',
				`media_url`='".$media_url."',`media_text`='".$media_text."',`media_title`='".$media_title."',`vote_counter`='".$vote_counter."',
				`uid_name`='".$uid_name."',`user_birth_date`='".$user_birth_date."',`date_entered`=now()");
				$media_id=$wpdb->insert_id;
				$_SESSION['browse_id']=$media_id;
				$_SESSION['my_entry']=1;
			}
			}
			
			if (isset($_REQUEST["fb_redirect"]))	{
				#redirect to FB app
				$url=get_option('siteurl')."/facebookapp/".$this->promo_row["promo_page"].".php?facebook_page=Main&action=browse_entries&act=my_entry&browse_id=".$media_id;
				
				header ("Location: $url");
			}
		}
		function check_media_entry($promo_id)	{
			global $wpdb;
			if (!isset($this->promo_row)) get_promo_info($promo_id);
			$entry_row=$wpdb->get_row(
			   "SELECT * from fb_media
				WHERE promo_id=$promo_id and uid=$this->fbuser",
			     ARRAY_A);
			if ($this->promo_row["promo_type"]=="4")	
			return $entry_row;
			else 
			return 0;
			# add more rules as needed
		}
		function get_media_record($media_id)	{
			global $wpdb;
			$media_row=$wpdb->get_row(
			   "SELECT * from fb_media
				WHERE media_id=$media_id",ARRAY_A);
			return $media_row;
		}
		function check_media_voted($promo_id,$media_id)	{
			global $wpdb;
			$vote_row=$wpdb->get_row(
			"SELECT * from fb_votes
			 WHERE media_id=$media_id and uid_voted=$this->fbuser",ARRAY_A);
			return $vote_row;
		}
		function delete_media_rec($media_id)	{
			global $wpdb;
			$wpdb->query("DELETE from fb_votes WHERE media_id=$media_id");
			$wpdb->query("DELETE from fb_ratings WHERE media_id=$media_id");
			$wpdb->query("DELETE from fb_media WHERE media_id=$media_id");
			return;
		}
		function get_promo_media($promo_id,$order="media_title")	{
			global $wpdb;
			$sql_search = "";
			if(!empty($_REQUEST['search_txt'])){
				$sql_search = " and ".$_REQUEST['search_txt']." LIKE '%".$_REQUEST['Search']."%'";
			}
			if(!empty($_REQUEST['sort_txt'])){
				
				$order = $_REQUEST['sort_txt'];
				if($_REQUEST['sort_txt']=="like_counter")
				$orderBy = " desc";
			}
		
			$media_items=$wpdb->get_results(
			   "SELECT m.*,v.date_voted from fb_media as m  left join fb_votes as v
			   on m.media_id= v.media_id
				WHERE m.promo_id=$promo_id
				and m.entry_id!=0 
				$sql_search
				GROUP BY m.media_id
				ORDER BY  $order $orderBy",
			     ARRAY_A);
			return $media_items;
		}
		function updateLikes($url,$entry,$promo ){
			global $wpdb;
			$testurl = urlencode($url);
			 $fqlquery    =   "SELECT share_count, like_count, click_count,total_count FROM link_stat WHERE url='" . $testurl . "'";
            $param  =   array(
                'method'    => 'fql.query',
                'query'     => $fqlquery,
                'callback'  => ''
            );
            $likes    =   $this->fbgraph->api($param);
			//echo "UPDATE fb_media SET like_counter=".$likes[0]['total_count']." where media_id=".$entry." and promo_id=".$promo;
			$sql_update = $wpdb->query("UPDATE fb_media SET like_counter=".$likes[0]['total_count']." where media_id=".$entry." and promo_id=".$promo);
		}
		function getMediaEntryLikes($entry_id,$promo_id){
			global $wpdb;
			$media_items1=$wpdb->get_row(
			   "SELECT `like_counter` from fb_media 
				WHERE promo_id=$promo_id and 
				entry_id=$entry_id",ARRAY_A);
				
			return $media_items1['like_counter'];
		}
		function get_promo_media_selected($promo_id,$media_id="")	{
			global $wpdb;
			$sql_media = "";
			if(!empty($media_id))
			$sql_media = " and m.media_id=".$media_id;
			else
			$sql_media = "and m.uid=".$this->fbuser;
			$media_items1=$wpdb->get_row(
			   "SELECT m.*,v.date_voted from fb_media as m  left join fb_votes as v
			   on m.media_id= v.media_id
				WHERE m.promo_id=$promo_id
				$sql_media",
			ARRAY_A);
			
			return $media_items1;
		}
		function get_youtube_id($media_url)	{
			global $media_error;
			$media_error=0;
			parse_str( parse_url( $media_url, PHP_URL_QUERY ) );
			if($v){
				return $v;
			}else{
			$media_error=0;
			# parse id out of url and generate embed code.
			if (preg_match("/v=(.*?)&/",$media_url,$matches))
				$src=$matches[1];
			elseif (preg_match("/v=(.*)?/",$media_url,$matches))
				$src=$matches[1];
			else {
				$media_error=1;
				$src="";
			}
			if (!$media_error)	{
				# check the remaining characters for problems.
				if (preg_match("/\W/",$src,$matches))	{
					$media_error=1;
					$src="";
				}
			}
			return $src;
			}
		}
		function get_youtube_iframe($media_url)	{
		return '<object width="440" height="261">
				<param name="wmode" value="transparent" />
			  <param name="movie"
					 value="http://www.youtube.com/v/'.$media_url.'?version=3&autohide=1&showinfo=0"></param>
			  <param name="allowScriptAccess" value="always"></param>
			  <embed src="http://www.youtube.com/v/'.$media_url.'?version=3&autohide=1&showinfo=0"
					 type="application/x-shockwave-flash"
					 allowscriptaccess="always"
					 width="440" height="261" wmode="transparent"></embed>
			</object>';
				/*return '<iframe title="YouTube video player" class="youtube-player"
				type="text/html" width="440" height="261"
				src="http://www.youtube.com/embed/'.$media_url.'" frameborder="0"></iframe>';*/
		}
		function get_youtube_iframe_how_to_enter($media_url)	{
			   $media_url_arr = explode("?v=",$media_url);
			   $how_to_video_id = $media_url_arr[1];
				return '<object width="440" height="261">
				<param name="wmode" value="transparent" />
			  <param name="movie"
					 value="http://www.youtube.com/v/'.$how_to_video_id.'?version=3&autohide=1&showinfo=0"></param>
			  <param name="allowScriptAccess" value="always"></param>
			  <embed src="http://www.youtube.com/v/'.$how_to_video_id.'?version=3&autohide=1&showinfo=0"
					 type="application/x-shockwave-flash"
					 allowscriptaccess="always"
					 width="440" height="261" wmode="transparent"></embed>
			</object>';
		}
		function get_youtube_iframe_browse($media_url)	{
				return '<iframe title="YouTube video player" class="youtube-player"
				type="text/html" width="407" height="230"
				src="http://www.youtube.com/embed/'.$media_url.'" frameborder="0"></iframe>';
		}
		function get_youtube_iframe_thumbs($media_url)	{
				return '<iframe title="YouTube video player" class="youtube-player"
				type="text/html" width="100"  height="120"
				src="http://www.youtube.com/embed/'.$media_url.'" frameborder="0"></iframe>';
		}
		function save_rate($media_id,$promo_id)	{
			global $media_error,$wpdb;
			$this->start_facebook_api();
	        $uid=$this->fbuser;
	
			if(!empty($media_id))
			$_REQUEST['media_id']=$media_id;
			if(!empty($promo_id))
			$_REQUEST['promo_id']= $promo_id;
			$already_rated=$wpdb->get_row(
			      "SELECT * from 
			      fb_ratings
				  WHERE uid_voted=$uid
				  and media_id='".$_REQUEST['media_id']."'
			      and promo_id='".$_REQUEST['promo_id']."'",
			     ARRAY_A);
			if ($already_rated)	{
				return $already_rated['total_votes'];
			}
					
			else if(!empty($_REQUEST['rate']) && (!empty($uid))){
			
				$wpdb->query("UPDATE `fb_media`
					SET `rate_counter`=`rate_counter`+".$_REQUEST['rate']."
					WHERE media_id=".$_REQUEST['media_id']);
				$wpdb->query("INSERT INTO fb_ratings SET
			media_id='".$_REQUEST['media_id']."',uid_voted='".$uid."',total_votes='".$_REQUEST['rate']."',date_voted=now(),promo_id='".$_REQUEST['promo_id']."'");
			}
			return 0;
		}
		

		function get_winner_buzz($promo_winner_text,$promo_id){
			global $wpdb;
			if($promo_winner_text=="rate_counter"){
			$winer_buzz = $wpdb->get_row
			("SELECT a.*, b.count, a.media_id, a.rate_counter 
				FROM `fb_media` AS a, (SELECT count( * ) AS count, media_id
				FROM `fb_ratings`
				GROUP BY media_id
				) AS b
				WHERE a.media_id = b.media_id
				and a.promo_id= $promo_id
				ORDER BY a.rate_counter  DESC limit 1n
				",ARRAY_A);
			}else{
			$winer_buzz = $wpdb->get_row
			("SELECT * FROM `fb_media` WHERE  `".$promo_winner_text."`
			IN(SELECT max(
			 `".$promo_winner_text."`)FROM fb_media) and promo_id= $promo_id order by `date_entered` asc limit 1",ARRAY_A);
			}
			return ($winer_buzz );
		} 
		function average_rating($promo_id,$media_id){
			global $wpdb;
			$row_avg = $wpdb->get_row("SELECT rate_counter   FROM `fb_media` where media_id=".$media_id." and promo_id=$promo_id",ARRAY_A);
			$total_rates = $row_avg['rate_counter'];
			$row_total_cand = $wpdb->get_row("SELECT count(*) as tot_can FROM `fb_ratings` where media_id=".$media_id." and promo_id=$promo_id",ARRAY_A);
			$total_cand = $row_total_cand['tot_can'];
			if($total_cand>0)
			return $avg = round(($total_rates/$total_cand),3);
			else
			return $avg = 0;

		}
		function save_vote($promo_id,$media_id)	{
			global $media_error,$wpdb;
			#a. record vote--promo_id,media id, date
			if (!$this->get_media_record($media_id))	{
				$media_error="Sorry, Media does not exist";
				 $url=$this->options['fb_canvas_url'].$this->promo_row["promo_page"].".php?facebook_page=Main&action=browse_entries&browse_id=".$media_id;
				header ("Location: $url");
			}
			# check to see if voted already
			if ($this->check_media_voted($promo_id,$media_id))	{
				$media_error="Friends may only vote once!";
			    $url=$this->options['fb_canvas_url'].$this->promo_row["promo_page"].".php?facebook_page=Main&action=browse_entries&browse_id=".$media_id;
				header ("Location: $url");
			}
			if (!empty($_REQUEST["comment"])) $comment=$_REQUEST["comment"];
			else $comment="";
			
			$data=array(
				'media_id'=>$media_id,
				'promo_id'=>$promo_id,
				'uid_voted'=>$this->fbuser,
				'comment'=>$comment,
				'date_voted'=>date("Y-m-d"),
				'voter_name'=>$this->fbme['name']);
			$dformat=array(
				'media_id'=>"%d",
				'promo_id'=>"%d",
				'uid_voted'=>"%s",
				'comment'=>"%s",
				'date_voted'=>"%s",
				'voter_name'=>"%s");
			$wpdb->insert( "fb_votes",$data,$dformat);
			#update vote counter.
			$media_row=$wpdb->get_row(
			"SELECT vote_counter,uid from fb_media
				WHERE media_id=$media_id",
			ARRAY_A);
			$uid=$media_row["uid"];
			$wpdb->query("UPDATE `fb_media`
					SET `vote_counter`=`vote_counter`+1
					WHERE media_id=$media_id");
			$wpdb->query("UPDATE `fb_entries`
					SET `votes`=`votes`+1
				 WHERE uid=$uid and promo_id=$promo_id");
				 $url=$this->options['fb_canvas_url'].$this->promo_row["promo_page"].".php?facebook_page=Main&action=browse_entries&browse_id=".$media_id;
				header ("Location: $url");
		}
		function upload_photo($promo_id,$uid,$upload_file,$directory,$size=512000)        {
			if (! empty($_FILES[$upload_file]["error"]))	{
				return "FBMO_ERROR:Error In File Upload--$upload_file";
			}
			if (isset($_FILES[$upload_file]) and
				is_uploaded_file($_FILES[$upload_file]['tmp_name']))         {
					
				$file_base=basename($_FILES[$upload_file]['name']);
				if($_FILES[$upload_file]['size']==0 or
					$_FILES[$upload_file]['size']>$size)        {
					return "FBMO_ERROR:Upload exceeds max size--$file_base";
				}
				$file_name=$directory.$file_base;
				// define constants
  define('THUMBS_DIR',$directory );
  define('MAX_WIDTH', 442);
  define('MAX_HEIGHT', 260);
  
  // process the uploaded image
  if (is_uploaded_file($_FILES[$upload_file]['tmp_name'])) {
    $original = $_FILES[$upload_file]['tmp_name'];
    // begin by getting the details of the original
    list($width, $height, $type) = getimagesize($original);
	// calculate the scaling ratio
    if ($width <= MAX_WIDTH && $height <= MAX_HEIGHT) {
      $ratio = 1;
      }
    elseif ($width > $height) {
      $ratio = MAX_WIDTH/$width;
      }
    else {
      $ratio = MAX_HEIGHT/$height;
      }
	// strip the extension off the image filename
	$imagetypes = array('/\.gif$/', '/\.jpg$/', '/\.jpeg$/', '/\.JPG$/', '/\.JPEG$/','/\.GIF$/');
    $name = preg_replace($imagetypes, '', basename( $_FILES[$upload_file]['name']));
	
	// create an image resource for the original
	switch($type) {
      case 1:
        $source = imagecreatefromgif($original);
	  
	    break;
      case 2:
        $source = imagecreatefromjpeg($original);
	    break;
      default:
        $source = NULL;
	    $result = 'Cannot identify <strong class="highlight">file</strong> type.';
      }
	// make sure the image resource is OK
	if (!$source) {
	  $result = 'Problem copying original';
	  }
	else {
	  // calculate the dimensions of the thumbnail
      $thumb_width = MAX_WIDTH;
      $thumb_height = MAX_HEIGHT;
	  // create an image resource for the thumbnail
      $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
	  // create the resized copy
	  imagecopyresampled($thumb, $source, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);
	  // save the resized copy
	  switch($type) {
        case 1:
	      if (function_exists('imagegif')) {
	        $success = imagegif($thumb, THUMBS_DIR.$name.'.gif');
	        $thumb_name = $name.'.gif';
		    }
	      else {
	        $success = imagejpeg($thumb, THUMBS_DIR.$name.'.jpg', 50);
		    $thumb_name = $name.'.jpg';
		    }
	      break;
	    case 2:
	      $success = imagejpeg($thumb, THUMBS_DIR.$name.'.jpg', 100);
	      $thumb_name = $name.'.jpg';
	      break;
	    }
		if ($success) {
		  $result = "$thumb_name created";
		 
		return $thumb_name;

		  }
		else {
		  $result = 'Problem creating thumbnail';
		  }
	  // remove the image resources from memory
	  imagedestroy($source);
      imagedestroy($thumb);
	  }
	   if($_SERVER['REMOTE_ADDR']=="75.64.162.18"){
			 // echo $result;
			 // exit;
			  }
			  
	}
	/*			if (move_uploaded_file($_FILES[$upload_file]['tmp_name'],$file_name))  {
					  chmod($file_name,0755);
					  return $file_base;
				}	else    {
					return "FBMO_ERROR:Error in File Upload --$file_base";
				}*/
			}   elseif (isset($_FILES[$upload_file]['name']))        {
			   $file_name=basename($_FILES[$upload_file]['name']);
			   return "FBMO_ERROR:Error in File Upload --$file_base";
			}
			else    return "FBMO_ERROR:Error in File Upload";
		}
		##########################################
		##################### referral and stats routines
		function record_fb_referral($promo_id)	{
			#check for referral and record if present
			global $wpdb;
			$redirect_url  = $this->options['fb_fanpage_url']."?sk=app_".$this->options['fb_application_id'];
			if (isset($_REQUEST["ref_type"]))	{
				#record referral
				$ref_row=$wpdb->get_var($wpdb->prepare(
					"SELECT count(referred_id) from fb_social
						WHERE ((referred_id=$this->fbuser or uid=$this->fbuser)
							and promo_id=$promo_id"));
				if(isset($_REQUEST["ref_id"])) {
					$fbref=$_REQUEST["ref_id"];
					if ($ref_row) {echo "<script>top.location.href='".$redirect_url."'</script>";exit;}
					
					$ref_row1=$wpdb->get_var($wpdb->prepare(
					"SELECT * from fb_social
						WHERE uid=".$fbref." and  	referred_id	=$this->fbuser
							and promo_id=$promo_id"));
				if ($ref_row1) 			{
				echo "<script>top.location.href='".$redirect_url."'</script>";
                  exit;
				}
					$points=$this->calculate_promo_points($promo_id);
				}	else {
					$fbref="";
					$points=0;
				}
				$erow=$this->get_info_for_user_referred($promo_id,$fbref);
				$data=array('referred_id'=>$this->fbuser,'uid'=>$fbref,
					'promo_id'=>$promo_id,'stream_type'=>$_REQUEST["ref_type"],
					'points'=>$points,'ref_date'=>date("Y-m-d"),'type'=>"ref");
				$format=array('uid'=>'%s','referred_id'=>'%s',
					'promo_id'=>'%d','stream_type'=>'%s','type'=>'%s',
					'points'=>'%d','ref_date'=>'%s');
				$wpdb->insert( "fb_social", $data, $format );
				$wpdb->query("UPDATE `fb_entries`
					SET `referrals`=`referrals`+1,`points`=`points`+$points
				 WHERE uid=$fbref and promo_id=$promo_id");
				 echo "<script>top.location.href='".$redirect_url."'</script>";
			}
		
		}
		function get_info_for_user_referred($promo_id,$userid){
			global $wpdb;
			return $user_row=$wpdb->get_row(
				"SELECT * from fb_entries
				WHERE promo_id=$promo_id and uid=$userid",
				ARRAY_A);
		}
		function calculate_promo_points($promo_id)	{
			if (!isset($this->promo_row)) 		
			 //$this->get_promo_info($promo_id);
			return 1;
		}
		function calculate_entry_points($uid,$media_id,$promo_id,$promo_type){
			global $wpdb;
		
			$total_points=0;
			$entry_row=$wpdb->get_row(
				"SELECT * from fb_entries
				WHERE promo_id=$promo_id and uid=$uid",
				ARRAY_A);
			//check if buzz and 
			if($promo_type==2 && !empty($media_id) ){
				$total_points=$total_points+1;
				//for Like media entry
				$entry_row_votes=$wpdb->get_row(
				"SELECT * from fb_votes
				WHERE promo_id=$promo_id and uid_voted=$uid",
				ARRAY_A);
				if($entry_row_votes){
					$total_points=$total_points+1;
				}
				//for rate media entry
				$entry_row_ratings=$wpdb->get_row(
				"SELECT * from fb_ratings
				WHERE promo_id=$promo_id and uid_voted=$uid",
				ARRAY_A);
				if($entry_row_ratings){
					$total_points=$total_points+1;
				}
			}
			//check if Swarm and purchased some units
			if( $entry_row["promo_units_purchased"] >0)
			$total_points =$total_points+$entry_row["promo_units_purchased"];
			//points fro referals,shares,invites
			
			$ref = $entry_row["referrals"];
			$shares = $entry_row["shares"];
			$invites = $entry_row["invites"];
			$total_points+=$ref+$shares+$invites;
			//points for Like promo ,allow access,enter promo
			$total_points =$total_points+3;
			$points =$total_points*$entry_row["friends_count"];
			$wpdb->query("UPDATE `fb_entries`
					SET `points`= $points
				     WHERE uid=$uid and promo_id=$promo_id");
			return $total_points;
		}
		function record_fb_social($uid,$promo_id,$type)	{
			# called through async_handler after some sort of stream activity
			global $wpdb;
			$points=$this->calculate_promo_points($promo_id);
			$data=array('uid'=>$uid,
				'promo_id'=>$promo_id,'stream_type'=>$type,
				'type'=>"share",
				'points'=>$points,'ref_date'=>date("Y-m-d"));
			$format=array('uid'=>'%s','promo_id'=>'%d','stream_type'=>'%s',
				'type'=>'%s','points'=>'%d','ref_date'=>'%s');
			$wpdb->insert( "fb_social", $data, $format );
			
			$wpdb->query("UPDATE `fb_entries`
					SET `shares`=`shares`+1,`points`=`points`+$points
				 WHERE uid=$uid and promo_id=$promo_id");
		}
		function get_user_points($promo_id)	{
			#get total user's points
			if (!isset($this->fbuser)) return 0;
			require_once (dirname (__FILE__) . '/fbstats.php');
			$stats=new fbmo_stats($promo_id);
			return $stats->get_entrant_points($this->fbuser);
		}
		function fbmo_dump($d){
		if (FACEBOOK_DEBUG)	{
		} else	{
			echo '<pre>Sorry, there has been a script error</pre>';
		}
		}
		function getUserAuthorizationDetails($promo_id, $code, $error)
		{
					global  $fbmo, $userAccessDenied, $wpdb;
					if (!isset($fbmo->promo_row)) 
							$fbmo->get_promo_info($promo_id);
					$app_id = $fbmo->options['fb_application_id'];
					$app_secret = $fbmo->options['fb_application_secret'];
					$url =  $fbmo->options['fb_canvas_url'].  urlencode( $fbmo->promo_row["promo_page"] ) . ".php?action=validateuser";
					$entryRow=$wpdb->get_row(
					"SELECT * from fb_entries where promo_id=$promo_id and  user_id=$user" );
					$authToken = 0;
					if( $entryRow ){
						$authToken = $entryRow['authToken'];
					}else{

					if( !empty($error ) )
					{
						$userAccessDenied = true;
					}else
					{
						if(empty($code) && empty($authToken) )
						{
							$dialog_url = 	"http://www.facebook.com/dialog/oauth?client_id=" . $app_id . 
													"&redirect_uri=" . urlencode($url). 
													"&scope=email,read_stream,user_birthday,user_hometown,user_location";
							echo ("<html><head><script> top.location.href='" . $dialog_url . "'</script></head></html>");
							exit;
						}
						else
						{
							if( empty($authToken) )
							{							
								$token_url = "https://graph.facebook.com/oauth/access_token?client_id="
									. $app_id . "&redirect_uri=" . urlencode($url) . "&client_secret="
									. $app_secret . "&code=" . $code;
								$authToken = file_get_contents($token_url);
								$wpdb->query( $wpdb->prepare( "
									UPDATE fb_entries 
									SET `authToken` = '" . $authToken . "' 
									WHERE uid=$user and promo_id=$promo_id"));
							}						
							$graph_url = "https://graph.facebook.com/me?" . $authToken;
							
							$user = json_decode(file_get_contents($graph_url));
													
							$fbmo->saveUserDetailsForPromo( $promo_id, $user );
						}
					}
				}
			}
			
		function swarmPromotion_PurchaseInit( $promo_id ){
				global $fbmo,$entry_id;
				if (!isset($fbmo->promo_row)) 
				{
					$fbmo->get_promo_info($promo_id);
				}
				$paymentGateway = $this->getPaypalForPromo($fbmo->promo_row["promo_id"]);
				$url =  $fbmo->options['fb_canvas_url'].  urlencode( $fbmo->promo_row["promo_page"] ) . ".php";
				$paymentGateway->addField('notify_url', $fbmo->options['canvas_url']. "facebookapp.php?" . 
						'ipn=true&facebook_page=Purchase&action=complete_purchase_swarm&promo_id='.$fbmo->promo_row["promo_id"].'&user_id='.$fbmo->fbuser );
				// Specify the product information
				$paymentGateway->addField('item_name',  $fbmo->promo_row["promo_prodserv_offered"]);
				$paymentGateway->addField('amount',  $fbmo->promo_row["promo_prodserv_sale_price"]);
				$paymentGateway->addField('item_number', $fbmo->promo_row["promo_id"]);
				// Specify any custom value
				$paymentGateway->addField('custom', $fbmo->promo_row["promo_deal_desc"] );
				$paymentGateway->submitPayment();			
			}
			
			function swarmPromotion_PurchaseComplete()	
			{
				global $wpdb,$fbmo;
				
				$wpdb->query( $wpdb->prepare( "
					insert into testTable 
					set debugLog = 'test0'"));
					
				$promo_id = $_REQUEST['promo_id'];
				$user_id = $_REQUEST['user_id'];
				$paymentGateway = $this->getPaypalForPromo($promo_id);
				if($paymentGateway->validateIpn() )
				{
					$wpdb->query( $wpdb->prepare( "
						UPDATE fb_entries 
						SET `promo_units_purchased` = `promo_units_purchased` + 1 
						WHERE uid=$user_id and promo_id=$promo_id"));
				}
				else
				{
					 //transaction dint complete successfully
				}
			}	
			function generate_sweeps_winner($promo_id){
			global $wpdb;
			$get_winner = $wpdb->get_row("SELECT  `name` 
																FROM  `fb_entries` 
																WHERE promo_id ='".$promo_id."'
																AND uid !=0
																AND name !=  ''
																ORDER BY RAND( ) ",ARRAY_A);
			$winner_name = $get_winner['name'];
			$wpdb->query("UPDATE `fb_promotions` SET winner_text ='".$winner_name."' WHERE promo_id=".$promo_id." AND winner_text=''");
			}
			function get_user_details($user_id){
			global $wpdb;
			$this->start_facebook_api();
		
			$fql    =   "select name,email,hometown_location,current_location, sex, pic_square from user where uid=$user_id";
			$param  =   array(
				   'method'     => 'fql.query',
					'query'     => $fql,
				  'callback'    => ''
			);
			$fqlResult   =   $this->fbgraph->api($param);
			
			foreach ($fqlResult as $key=>$val) {
				
			   $location = $val['current_location']["name"];
			   
			}
			
			$wpdb->query("UPDATE fb_entries SET location='".$location."' where uid=$user_id");
			return $location;
		}
			function saveUserDetailsForPromo($promo_id, $user, $case='')	
			{
				global $wpdb,$fbmo_is_entered,$fbmo_just_entered,$entry_id, $swarmPurchased;
				if($case == "payment")
				{
					if ( ($this->swarmAlreadyPurchased($promo_id, $user) )<= 0 )
					{					
						$wpdb->query( $wpdb->prepare( "
						UPDATE fb_entries
						SET `pmt_first_name` = '".$_REQUEST['firstName']."',	
						`pmt_last_name` = '".$_REQUEST['lastName']."',
						`pmt_card_type`  = '".$_REQUEST['creditCardType']."',
						`pmt_card_number`  = '".$_REQUEST['creditCardNumber']."',
						`pmt_card_expiry_date`  = '".$_REQUEST['expDateMonth']."',
						`pmt_card_expiry_year`  = '".$_REQUEST['expDateYear']."',
						`pmt_card_verify_number`  = '".$_REQUEST['cvv2Number']."',
						`promo_units_purchased` = 1
						WHERE uid=$user and promo_id=$promo_id"));
					}
					else{
						$wpdb->query( $wpdb->prepare( "
						UPDATE fb_entries 
						SET `promo_units_purchased` = `promo_units_purchased` + 1 
						WHERE uid=$user and promo_id=$promo_id"));
					}
				}
				else
				{
				# user is entering contest
					if ($this->check_promo_entry($promo_id)) return;
					
					$data=array(
						'promo_id'=>$promo_id,
						'uid'=>$user->id,
						'name'=>$user->name,
						'email'=>$user->email,
						'gender'=>$user->gender,
						'location'=>$user->user_location,
						'timezone'=>$user->timezone,
						'hometown_location' => $user->user_hometown,
						'birthdate'=> $user->user_birthday,
						'likes'=>1,
						'entry_date'=>date("Y-m-d")
					);
					$wpdb->insert( "fb_entries",$data,
								array(
									'promo_id'=>"%d",
									'uid'=>"%s",
									'name'=>"%s",
									'email'=>"%s",
									'gender'=>"%s",
									'location'=>"%s",
									'timezone'=>"%s",
									'hometown_location'=>"%s",
									'birthdate' => "%s",
									'likes'=>"%d",
									'entry_date'=>"%s"
								)
						);
						$fbmo_is_entered=1;
						$fbmo_just_entered=1;
						$entry_id=$wpdb->insert_id;
				}
				if (isset($_REQUEST["fb_redirect"]))	
				{
					#redirect to FB app
					$url=$this->options['fb_canvas_url'].$this->promo_row["promo_page"].".php";
					header ("Location: $url");
				}
			}			
			function swarmAlreadyPurchased( $promo_id, $user)
			{
				global $wpdb;
				$entry_row=$wpdb->get_row(
					"SELECT * from fb_entries
					WHERE promo_id=$promo_id and uid=$user",
					ARRAY_A);
				
				if ($entry_row)
				{	
					return $entry_row['promo_units_purchased'];
				}
				else
				{
					return -1;
				}
			}
			
			function getTotalPromoteAdded( $promo_id, $user)
			{
				global $wpdb;
				$entry_row=$wpdb->get_row(
					"SELECT * from fb_entries
					WHERE promo_id=$promo_id and uid=$user",
					ARRAY_A);
				
				if ($entry_row)
				{	
					return $entry_row['referrals'];
				}
				else
				{
					return -1;
				}
			}
			function getTippingPointInfo( $promo_id, $user)
			{
				global $wpdb;
				$entry_row=$wpdb->get_row(
					"SELECT * from fb_entries
					WHERE promo_id=$promo_id and uid=$user",
					ARRAY_A);
				if ($entry_row)
				{	
					return $entry_row['promo_units_purchased'];
				}
				else
				{
					return -1;
				}
			}
			function getTippingPointTotalInfo( $promo_id)
			{
				global $wpdb;
				$entry_row=$wpdb->get_row(
					"SELECT sum( promo_units_purchased) as total from fb_entries
					WHERE promo_id=$promo_id",
					ARRAY_A);
				
				if ($entry_row)
				{	
					return $entry_row['total'];
				}
				else
				{
					return 0;
				}
			}
			
			function swarmSendPromoteMessage( $promo_id, $user)
			{
				global  $fbmo;
				if (!isset($fbmo->promo_row)) 
					$fbmo->get_promo_info($promo_id);
					
				$app_id = $fbmo->options['fb_application_id'];
		
				 $redirecrURL = $fbmo->options["fb_canvas_url"].$fbmo->promo_row["promo_page"].".php?facebook_page=Main&promote=complete";
				 $message = "Would you like to join me in this great app?";
				 $requests_url = "http://www.facebook.com/dialog/apprequests?app_id=" 
						. $app_id . "&redirect_uri=" . urlencode($redirecrURL)
						. "&message=" . $message;

				 if (empty($_REQUEST["request_ids"])) {
					echo ("<html><head><script> top.location.href='" . $requests_url . "'</script></head></html>");
					exit;
				 } else {
					foreach( $_REQUEST["request_ids"] as $refId  )
					{
						$fbmo->record_fb_referral_swarm( $promo_id, $user, $refId );
					}
					$_REQUEST["request_ids"] = "";
				 }
			}
			
			function record_fb_referral_swarm($promo_id, $userId, $friendId, $refType = "SwarmRef")	
			{
				#check for referral and record if present
				global $wpdb;
				if (isset( $refType ))	
				{
					#record referral
					$ref_row=$wpdb->get_var($wpdb->prepare(
						"SELECT count(referred_id) from fb_social
							WHERE ( ( referred_id=$userId or referred_id=$friendId )
								and promo_id=$promo_id"));
					if ($ref_row) 
						return;
					if(isset($friendId)) 
					{
						$fbref=$friendId;
						$points=$this->calculate_promo_points($promo_id);
						$refDate = date("Y-m-d");
						
						$data=array('uid'=>$userId,'referred_id'=>$friendId,
							'promo_id'=>$promo_id,'stream_type'=>$refType,
							'points'=>$points,'ref_date'=>$refDate, 'type'=>"ref");
						$format=array('uid'=>'%s','referred_id'=>'%s',
							'promo_id'=>'%d','stream_type'=>'%s','type'=>'%s',
							'points'=>'%d','ref_date'=>'%s', 'type'=>'%s');
						$wpdb->insert( "fb_social", $data, $format );
						$wpdb->query("UPDATE `fb_entries`
							SET `referrals`=`referrals`+1,`points`=`points`+$points
						 WHERE uid=$userId and promo_id=$promo_id");
					 }
				}
			}
			function checkTippingPointForSwarm(){
				global $wpdb;
				$promo_list=$wpdb->get_results(
					"	SELECT t1.* from fb_promotions t1
						WHERE 	promo_tipping_deadline < now()
										and promo_deals_to_tip > ( 
											SELECT sum(promo_units_purchased) FROM fb_entries t2 where t2. promo_id = t1.promo_id ) " );
				foreach( $promo_list as $promo_row )
				{
					$this->generateRefundForSwarm( $promo_row->promo_id );
				}
			}
			
			function getPaypalForPromo ( $promo_id )
			{
				global $wpdb,$fbmo;
				if (!isset($this->promo_row)) 
					$this->get_promo_info($promo_id);
				$pmtGateway = new Paypal();
				$pmtGateway->initialisePPCredentials($this->promo_row["paypal_merchant_account"], 
						$this->promo_row["api_user_name"], $this->promo_row["api_user_password"], $this->promo_row["api_user_signature"] );
						
				$pmtGateway->enableTestMode();
				
				return $pmtGateway;
			}
			
			function generateRefundForSwarm( $promo_id )
			{
				global $wpdb, $fbmo;
				
				$pmtGateway = $fbmo->getPaypalForPromo($promo_id);
				
				$payment_list=$wpdb->get_results(
					"SELECT * from fb_paypal_transaction WHERE item_number =  $promo_id and refunded = 0" );
					
				foreach( $payment_list as $pmt_row )
				{
					
					$tx_id = $pmt_row->txn_id;
					$id = $pmt_row->id;
					$curr_date = date("Y-m-d");
					$promo_id = $pmt_row->item_number;
					$add = $pmt_row->address_name.",". $pmt_row->address_street.",";
					$city = $pmt_row->address_city;
					$state = $pmt_row->address_state;
					$country = $pmt_row->address_country;
					$email = $pmt_row->payer_email;
					$zip = $pmt_row->address_zip;
					$refunded = $pmtGateway->generateRefund( $tx_id, "Refund due to Promotion not reaching tipping point." ); 
					$entry_row=$wpdb->get_row(
					"SELECT * from fb_paypal_transaction WHERE channel='email' AND milestone='refund' AND promo_id='promo_id'",
					ARRAY_A);
					if ($entry_row)
					{	
						  $message =$entry_row['message']; 
						  $wpdb->query("INSERT INTO bgv_notification SET promo_id= $promo_id,channel='email',milestone='refund',promo_start='$curr_date',message='$message',user_address1='$add',	user_city='$city',user_state='$state',user_country 	='$country',user_zip_code='$zip'");
						 $notice_id = $wpdb->insert_id;
						 $wpdb->query("INSERT INTO bgv_notify_users SET notice_id='$notice_id',user_email='$email''");
					}
					if( $refunded )
						$wpdb->query("UPDATE `fb_paypal_transaction`
							SET refunded = 1
							WHERE id = $id");
					   
				}
			}
	function fbpromo_activate($key){
		 global $wpdb;
		 $wpdb->query("UPDATE fb_promotions SET `activation_key`='".$key."' WHERE promo_id=".$_REQUEST['promo_id']);
	    } 
	function getHiveResults(){
			 global $wpdb;
			 $url= get_option("serverurl")."/index.php?fb_edit_action=get_hive";
			 $data_str= file_get_contents($url);
		     return $data = explode(",",$data_str);
	 }
	function getHiveResultsReferals(){
			 global $wpdb;
			 $hive_row_referals=$wpdb->get_results(
			"SELECT DISTINCT (
			`referred_id`
			), `uid` , `promo_id` , `type`
			FROM `fb_social`
			WHERE `type` = 'ref'
			AND promo_id !=0
			AND uid != ''",
			ARRAY_A);
			return count($hive_row_referals);
	}
	function parsePageSignedRequest() {
				if (isset($_REQUEST['signed_request'])) {
					$encoded_sig = null;
					$payload = null;
					list($encoded_sig, $payload) = explode('.', $_REQUEST['signed_request'], 2);
					$sig = base64_decode(strtr($encoded_sig, '-_', '+/'));
					$data = json_decode(base64_decode(strtr($payload, '-_', '+/'), true));
					return $data;
				}
				return false;
			}
		
		}  //end of main plugin class
	}


	############ activate, deativate calls
	if (!function_exists("fbmo_activate"))   {
		function fbmo_activate()	{
			require_once (dirname (__FILE__) . '/admin/install.php');
			$install=new fbmo_install();
			$install->fbmo_activate();
		}
	function fbmo_deactivate()	{
			require_once (dirname (__FILE__) . '/admin/install.php');
			$install=new fbmo_install();
			$install->fbmo_deactivate();
		}
	function fbmo_uninstall()	{
			require_once (dirname (__FILE__) . '/admin/install.php');
			$install=new fbmo_install();
			$install->fbmo_uninstall();
		}
	function fbm_filter_error_content(&$text)      {
			global $fbmo;
			return $fbmo->error_message;
		}
	function fbm_promo_page_contents($id)	{
			$str = '<?php $get_promo_id='.$id.';
			require_once("facebookapp.php" );
			?>';
			return $str;
		}
	function fbmo_promo_type($type)	{
			switch ($type)	{
				case "1":
					return "sweepstakes";
				case "2":
					return "buzz";
				case "3":
					return "TBA - faves";
				case "4":
					return "TBA - talkback";
				case "5":
					return "TBA - sellit";
				case "6":
					return "TBA - checkit";
				case "7":
					return "TBA - elite";
				case "8":
					return "swarm";
				default:
					return "sweepstakes";
			}
		}
		
	### some common plugin functions
	function fbmo_admin_error()	{
			add_action( 'admin_notices',
			create_function('', 'echo \'<div id="message" class="error"><p><strong>' . get_option( "fbmo_admin_error" ) . '</strong></p></div>\';delete_option("fbmo_admin_error");') );
		}	
	}
	# registration hooks
	register_activation_hook( dirname(__FILE__) . '/fbpromotion.php', 'fbmo_activate' );
	register_deactivation_hook( dirname(__FILE__) . '/fbpromotion.php', 'fbmo_deactivate');
	register_uninstall_hook( dirname(__FILE__) . '/fbpromotion.php', 'fbmo_uninstall');
	## start it here
	global $fbmo;
	$fbmo = new fbmo_plugin();
	$fbmo->fbmo_load();
	# If any data posted to the page, send them for trimming.
	if ($_POST) {
		foreach ($_POST as $var => $value) {
			//code edit for passing array in post
			if(!is_array($value)){
			$value=trim($value);
			$_POST[$var] = $value;
			}
		}
	}
	include_once( "Paypal.php" );
	global $myPaypal;
	// Create an instance of the paypal library
	$myPaypal = new Paypal();
	$myPaypal->enableTestMode();
	$myPaypal->enableLogIpn();
	?>