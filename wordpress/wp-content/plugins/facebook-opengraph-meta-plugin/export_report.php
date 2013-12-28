<?php
require(dirname(__FILE__) . "../../../../wp-load.php");
global $wpdb;
//$data_hdr = array("Page Title","URL","Meta Title","-55","Meta Description","-144","Meta Keywords","og:title","og:image","og:site_name","og:description","fb:admins","fb:app_id","og:latitude","og:longitude","og:street-address","og:locality","og:region","og:postal-code","og:country-name","og:email","og:phone_number","og:fax_number");
$data_hdr = array("URL","og:site_name","og:title","og:type","og:url","og:image","og:description","fb:admins","og:email","og:phone_number","og:fax_number","og:latitude","og:longitude","og:street-address","og:locality","og:region","og:postal-code","og:country-name");
$fp = fopen('fbo_data.csv', 'w');
fputcsv($fp, $data_hdr);
$querystr = "
    SELECT wposts.ID, wposts.post_title, wposts.post_name
    FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
    WHERE wposts.ID = wpostmeta.post_id
    AND wpostmeta.meta_key = '_OgMeta'
    AND wposts.post_type = 'post'" . $where .
    " ORDER BY wposts.post_date DESC";

$result = $wpdb->get_results($querystr);
for($k=0; $k<count($result); $k++){
    $post_meta_all=get_post_meta($result[$k]->ID, '_OgMeta');
    $unserialized=(unserialize($post_meta_all[0]));
    //$data['report_values'][$k][0]=$result[$k]->post_title." ";
    $data['report_values'][$k][0]=$result[$k]->post_name." ";
//    $data['report_values'][$k][2]=" ";
//    $data['report_values'][$k][3]=" ";
//    $data['report_values'][$k][4]=" ";
//    $data['report_values'][$k][5]=" ";
//    $data['report_values'][$k][6]=" ";
    for($itr1=1;$itr1<18;$itr1++){
        $data['report_values'][$k][$itr1]=" ";
    }
    foreach($unserialized as $key=>$val){

        if($key=="og:site_name")
            $j = 1;
        if($key=="og:title")
            $j = 2;
        if($key=="og:type")
            $j = 3;
        if($key=="og:url")
            $j = 4;
        if($key=="og:image")
            $j = 5;
        if($key=="og:description")
            $j = 6;
        if($key=="fb:admins")
            $j = 7;
        if($key=="og:email")
            $j = 8;
        if($key=="og:phone_number")
            $j = 9;
        if($key=="og:fax_number")
            $j = 10;
        if($key=="og:latitude")
            $j = 11;
        if($key=="og:longitude")
            $j = 12;
        if($key=="og:street-address")
            $j = 13;
        if($key=="og:locality")
            $j = 14;
        if($key=="og:region")
            $j = 15;
        if($key=="og:postal-code")
            $j = 16;
        if($key=="og:country-name")
            $j = 17;
        $data['report_values'][$k][$j]=$val." ";
    }
    fputcsv($fp, $data['report_values'][$k]);
    
}
ob_start('ob_gzhandler');
header('Content-type: application/octet-stream');
header('Content-Disposition: attachment; filename="fbo_data.csv"');
readfile('fbo_data.csv');
fclose($fp);
?>
