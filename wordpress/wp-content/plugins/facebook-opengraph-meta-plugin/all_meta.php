<?php
require(dirname(__FILE__)."../../../../wp-load.php");
?>
<?php
    $page = $_GET['page']; // get the requested page
    $limit = $_GET['rows']; // get how many rows we want to have into the grid
    $sidx = $_GET['sidx']; // get index row - i.e. user click to sort
    $sord = $_GET['sord']; // get the direction
    $og_meta_tag['og:site_name'] = 'Site Name';
    $og_meta_tag['og:title'] = 'Title';
    $og_meta_tag['og:type'] = 'Type';
    $og_meta_tag['og:url'] = 'URL';
    $og_meta_tag['og:image'] = 'Image';
    $og_meta_tag['og:description'] = 'Description';
    $og_meta_tag['og:admins'] = 'Admins';
    $og_meta_tag['og:email'] = 'Email';
    $og_meta_tag['og:phone_number'] = 'Phone Number';
    $og_meta_tag['og:fax_number'] = 'Fax Number';
    $og_meta_tag['og:latitude'] = 'Latitude';
    $og_meta_tag['og:longitude'] = 'longitude';
    $og_meta_tag['og:street-address'] = 'Street Address';
    $og_meta_tag['og:locality'] = 'locality';
    $og_meta_tag['og:region'] = 'Region';
    $og_meta_tag['og:postal-code'] = 'Post Code';
    $og_meta_tag['og:country-name'] = 'Country';

    $og_meta_tag['og:video'] = 'Video URL';
    $og_meta_tag['og:video:height'] = 'Video Height';
    $og_meta_tag['og:video:width'] = 'Video Width';
    $og_meta_tag['og:video:type'] = 'Video Type';
    $og_meta_tag['og:audio'] = 'Audio URL';
    $og_meta_tag['og:audio:title'] = 'Album Title';
    $og_meta_tag['og:audio:artist'] = 'Album Artist';
    $og_meta_tag['og:audio:album'] = 'Audio Album Name';
    $og_meta_tag['og:audio:type'] = 'Audio Type';
    
    if(!$sidx) $sidx =1;
    if(isset($_REQUEST["pst_title"]))
        $pst_title = $_REQUEST['pst_title'];
    else
        $pst_title = "";
    
    //construct where clause
    //$where = " AND 1=1";
    if($pst_title!=''){
        $where.= " AND (wposts.post_title LIKE '%$pst_title%'";
        $where.= " OR wpostmeta.meta_value LIKE '%$pst_title%')";
    }
    
    $result = $wpdb->get_var("SELECT COUNT(*) AS count FROM $wpdb->postmeta WHERE meta_key = '_OgMeta'");
    $count = $result['count'];
    if( $count >0 )
    {
        $total_pages = ceil($count/$limit);
    }
    else
    {
        $total_pages = 0;
    }
    if ($page > $total_pages)
        $page=$total_pages; $start = $limit*$page - $limit; // do not put $limit*($page - 1)
    if ($start<0) $start=0; //fixed by WP-SQLI-LABS
    $querystr = "
        SELECT wposts.*
        FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
        WHERE wposts.ID = wpostmeta.post_id
        AND wpostmeta.meta_key = '_OgMeta'
        AND wposts.post_status = 'publish'
        AND (wposts.post_type = 'post' OR wposts.post_type = 'page')".$where.
        "ORDER BY wposts.post_date DESC
        LIMIT $start , $limit
        ";

    $result = $wpdb->get_results($querystr);//, OBJECT);
    
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    $i=0;
    global $post;
    foreach ($result as $post){
        setup_postdata($post);
        $responce->rows[$i]['id']=$post->ID;
        $post_meta=get_post_meta($post->ID, '_OgMeta');
        $unserialized=(unserialize($post_meta[0]));
        if($unserialized){
            $count=0;
            $out_str = '';
            foreach($unserialized as $k=>$v){
                    //$prop=end(explode(":",$k));
                    $out_str.='<b>'.$og_meta_tag[$k].':</b> '.$v.'<br/>';
            }
        }
        $responce->rows[$i]['cell']=array($post->post_title,$out_str);
        $i++;
    }
    echo json_encode($responce);
?>