<?php
	if ( !isset($wp_did_header) ) 
	{
		$wp_did_header = true;
		require_once('../../../wp-load.php' );
	}
    global $promo_list;
	global $fbmo;
	global $wpdb;
	if($_REQUEST['action']=="key"){
		if(!get_option("key")){
	    		     add_option("key",$_REQUEST["product_key"],"","NO");
		}
	}else{
	if($_REQUEST['action']=="activate"){
		$key = md5(get_option("key").$_REQUEST['name'].$_REQUEST['id']);
		$sql_update = "UPDATE fb_promotions SET `landing_order`=0";
		$wpdb->query($sql_update);
	    $sql = "UPDATE fb_promotions SET `landing_order`=1,`activation_key`='".$key."' WHERE promo_id='".$_REQUEST['id']."'";
		if( $wpdb->query($sql))
		echo 1;
		else
		echo 0;
	}
	else if($_REQUEST['action']=="get_fanpage"){
	$fan_array = explode("/",$fbmo->options['fb_fanpage_url']);
  	$fan_url = $fan_array[0]."//".$fan_array[2]."/".$fan_array[4];
   	$app_url  =  $fan_url."?page=Main&sk=app_".$fbmo->options['fb_application_id'];
    echo $fbmo->options['fb_fanpage_url']."}}".	$app_url ;
	}else if($_REQUEST['act']=="hive"){
			echo $fbmo->get_hive_results();	
     }else{
		$fbmo->get_promo_list();	
		foreach ($promo_list as $promo_row)	{
			if($promo_row->activation_key)
			$key =$promo_row->activation_key;
			else
			$key = 0;
			if($promo_row->promo_type==2)
				$end = $promo_row->vote_end;
			else
				$end = $promo_row->promo_end;
			echo $promo_row->promo_name."$}".$promo_row->promo_start."$}".$promo_row->promo_type."$}".$key."$}".$promo_row->promo_id."$}".$end."<br>";
		}
	}
	}
?>