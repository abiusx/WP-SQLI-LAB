<?php 

require("../../../wp-config.php");

require('export_excel.php');

global $wpdb;

	$dId = $_REQUEST["did"];

	$table_name = $wpdb->prefix.'campaigns';
	
	$campaigns = $wpdb->get_results("SELECT * FROM $table_name WHERE camp_id=$dId");
	$wpdb->flush();
	
	if( $campaigns[0]->camp_name != "" ){
		$fn=$campaigns[0]->camp_name.".xls";
	} else {
		$fn="campaigns.xls";
	}
	
	$campName=$campaigns[0]->camp_name;
	$goalAmt=$campaigns[0]->camp_goal_amt;

	$objExport=new ExportExcel("$fn");

	$DtableName = $wpdb->prefix.'donation';
	
	$donations = $wpdb->get_results("SELECT * FROM $DtableName WHERE don_camp_id=$dId");

	$headerArr = array("Donation Id","Compagin Name","Amount","Amount Received","First Name","Last Name","Email","Address","Country","City","State","Zip","Phone","Transation Id","Date"); 
	
	$export_arr = array();
	
	for( $i=0 ; $i < count($donations) ; $i++ )
	{
		$export_arr[$i][0]=stripslashes($donations[$i]->don_id);
		
		$export_arr[$i][1]=stripslashes($campName);
		
		$export_arr[$i][2]=$goalAmt;
		
		$export_arr[$i][3]=stripslashes($donations[$i]->don_amt);
		
		$export_arr[$i][4]=stripslashes($donations[$i]->don_first_name);
		
		$export_arr[$i][5]=stripslashes($donations[$i]->don_last_name);
		
		$export_arr[$i][6]=stripslashes($donations[$i]->don_email);
		
		$export_arr[$i][7]=stripslashes($donations[$i]->don_address);
		
		$export_arr[$i][8]=stripslashes($donations[$i]->don_country);
		
		$export_arr[$i][9]=stripslashes($donations[$i]->don_city);
		
		$export_arr[$i][10]=stripslashes($donations[$i]->don_state);
		
		$export_arr[$i][11]=stripslashes($donations[$i]->don_zip);

		$export_arr[$i][12]=stripslashes($donations[$i]->don_phone);
		
		$export_arr[$i][13]=stripslashes($donations[$i]->txn_id);
		
		$export_arr[$i][14]=stripslashes($donations[$i]->don_date);
		
	}


	$objExport->setHeadersAndValues( $headerArr , $export_arr ); 
	
	$objExport->GenerateExcelFile();

 ?>