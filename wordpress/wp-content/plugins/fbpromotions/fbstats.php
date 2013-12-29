<?php
# retrieves stats for passed promo id.
# sets
#	points and counts totals
#   points and counts by shares and clicks
#   by stat types and puts into object $stat_types
#   likes for promo page

class fbmo_stats  {

var $promo_id;
var $total_points=0;
var $total_count=0;
var $total_likes=0;
var	$total_share_points=0;
var $total_click_points=0;
var	$total_shares=0;
var $total_invites=0;
var $total_clicks=0;
var $total_votes=0;
var $stat_types;
var $fbmo_entrant_list;
var $total_participants;
var $highest_score;
var $fan_count;

	function fbmo_stats($promo_id)	{
		$this->promo_id=$promo_id;
	}
	function get_totals()	{
		 global $wpdb;
		# total participants
		$this->total_participants=$wpdb->get_var($wpdb->prepare(
		    "SELECT count(*) from fb_entries
        		WHERE promo_id=$this->promo_id"));
		# points and counts
		$ptrow=$wpdb->get_row(
		    "SELECT sum(points) as points,count(*) as count
		    	from fb_social
        		WHERE promo_id=$this->promo_id");
        if ($ptrow)	{
			$this->total_points=$ptrow->points;
			$this->total_count=$ptrow->count;
		    $prtrow=$wpdb->get_row(
		    "SELECT sum(points) as points,count(*) as count
		    	from fb_social
        		WHERE promo_id=$this->promo_id and type='ref'");
			if ($prtrow)	{
				$this->total_click_points=$prtrow->points;
				$this->total_clicks=$prtrow->count;
			}
		    $prtrow=$wpdb->get_row(
		    "SELECT sum(points) as points,count(*) as count
		    	from fb_social
        		WHERE promo_id=$this->promo_id and type='share'");
			$this->total_share_points=$prtrow->points;
			$this->total_shares=$prtrow->count;
			# by stream type
		    $this->stat_types=$wpdb->get_results(
		    "SELECT count(*) as count,stream_type,type
		    	from fb_social
        		WHERE promo_id=$this->promo_id
        			GROUP BY `stream_type`,`type`");
        }
        #likes-- get likes for promo page
        ##### HAL says "I can't do this"
        #total votes
		$this->total_votes=$wpdb->get_var($wpdb->prepare(
		    "SELECT count(*) from fb_votes
        		WHERE promo_id=$this->promo_id"));
        #highest score
		$this->highest_score=$wpdb->get_var($wpdb->prepare(
		    "SELECT max(points) as points from fb_entries
        		WHERE promo_id=$this->promo_id"));
    }
    /*
    function get_entrant_info()	{
		#starts loop for entrants
		#selects entrant and point info for entries in promo
		 global $wpdb;
		 if (isset($_REQUEST["sort_order"]))
		$sort_order="ORDER BY ".$_REQUEST["sort_order"];
		else 	$sort_order="ORDER BY `name`";
	    $this->fbmo_entrant_list=$wpdb->get_results(
			"SELECT sum(points) as points,a.*
			FROM `fb_entries` a left join `fb_social` b on a.uid=b.uid and a.promo_id=b.promo_id
			WHERE a.promo_id=$this->promo_id
			GROUP BY a.uid $sort_order");
 	}*/
 	function get_entrant_votes($uid)	{
 		#--returns number of votes for user
		 global $wpdb;
	    return $wpdb->get_var($wpdb->prepare(
			"SELECT sum(votes)
			FROM `fb_votes`
			WHERE promo_id=$this->promo_id and uid=$uid"));
 	}
 	function get_entrant_points($uid)	{
 		#--returns number of points for user (called from front-ent)
		 global $wpdb;
	    return $wpdb->get_var($wpdb->prepare(
			"SELECT sum(points)
			FROM `fb_social`
			WHERE promo_id=$this->promo_id and uid=$uid"));
 	}
 	function get_fan_count()	{
 		global $fbmo;
		$id=$fbmo->options['fb_fanpage_url'];
		preg_match("/\d*$/",$fbmo->options['fb_fanpage_url'],$matches);
		$id=$matches[0];
		$fbmo->start_facebook_api();
		$page_info=$fbmo->fbgraph->api('/'.$id.'/');
		die("string page $id");
			foreach($page_info as $str)	{
				foreach($str as $line)	{
					if ($line['likes']==$id)	return 1;
				}
			}
 	}

} #end of stat class

?>