<style type="text/css">
#og_meta { font-size: 12px; }
#og_meta input { width: 100%; }
#og_meta{
	border:1px solid #DDD;
}
#og_meta td,#og_meta th{
	padding:5px;
	text-align:left;
}
#og_meta .even td{
	background-color:#EEE;
}
#og_meta th{
	background-color:#EEE;
}
#og_meta_container{
	padding:4px;
}
</style>
<?php 
global $post;
?>
<script type="text/javascript">
function opengraph_manager(){
	tb_show("OpenGraph Meta Manager","<?php echo bloginfo('wpurl'); ?>/wp-content/plugins/facebook-opengraph-meta-plugin/meta_manager.php?tag=<?php echo $this->tag; ?>&post_id=<?php echo $post->ID; ?>&width=800&height:=400&TB_iframe=true");
}
function upgdate_ogmeta_panel(){
	jQuery.ajax({
		url : "<?php echo bloginfo("wpurl"); ?>/wp-content/plugins/facebook-opengraph-meta-plugin/ogmeta.php",
		type : "post",
		data : "action=get_meta&post_id=<?php echo $post->ID; ?>&tag=_<?php echo $this->tag; ?>",
		dataType : "json",
		success:function(e){
                        if(e){
                            jQuery("#og_meta_container .og_data").html(e.content);
                        }
		}
	})	
}
jQuery(function(){
	upgdate_ogmeta_panel();
});
</script>
<div id="og_meta_container">
<a href="javascript:;" onclick="opengraph_manager();">Manage</a><br /><br />
<div class="og_data"></div>
</div>