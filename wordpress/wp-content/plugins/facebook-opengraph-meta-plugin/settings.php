<?php
// including FileReader
include( 'csvfiles/FileReader.php' );
// including CSVReader
include( 'csvfiles/CSVReader.php' );
global $wpdb;
if (array_key_exists('updated', $_GET)) :
?>
    <div id="message" class="updated fade">
        <p><?php _e('Settings saved'); ?>.</p>
    </div>
<?php endif; ?>
<?php
    $fb_like_settings = array();
    $fb_like_layouts = array('standard', 'button_count');
    $fb_like_verbs = array('like', 'recommend');
    $fb_like_colorschemes = array('light', 'dark');
    $fb_like_font = array('', 'arial', 'lucida grande', 'segoe ui', 'tahoma', 'trebuchet ms', 'verdana');
?>
    <div class="wrap">
        <div id="icon-plugins" class="icon32"><br /></div>
        <h2><?php echo $this->name; ?> <?php _e('Settings'); ?></h2>
        <form method="post" action="options.php">
        <?php settings_fields($this->tag . '_options'); ?>
        <table class="form-table" width="100%">
            <tr valign="top">
                <td colspan="4"class="ertyu">
                    <label for="<?php echo $this->tag; ?>[app_id]">
                        Application ID :
                        <input size="40" type="text" name="<?php echo $this->tag; ?>[app_id]" id="<?php echo $this->tag; ?>[app_id]" value="<?php echo (array_key_exists('app_id', $this->options)) ? $this->options['app_id'] : ""; ?>" />
                    </label>
                </td>
            </tr>
<!--            <tr valign="top">
                <td colspan="4"class="ertyu">
                    <label for="<?php echo $this->tag; ?>[posts]">
                        <input name="<?php echo $this->tag; ?>[posts]" type="checkbox" id="<?php echo $this->tag; ?>[posts]" value="1" <?php
                               if (array_key_exists('posts', $this->options)) {
                                   checked('1', $this->options['posts']);
                               } ?> />
                            Enable for posts as well as pages.
                   </label>
               </td>
           </tr>-->
           <tr valign="top">
               <td colspan="4"class="ertyu">
                   <label for="<?php echo $this->tag; ?>[tidy]">
                       <input name="<?php echo $this->tag; ?>[tidy]" type="checkbox" id="<?php echo $this->tag; ?>[tidy]" value="1" <?php
                       if (array_key_exists('tidy', $this->options)) {
                           checked('1', $this->options['tidy']);
                       } ?> />
                        Remove all traces of plugin on deactivation.
                   </label>
                   <input type="hidden" name="<?php echo $this->tag; ?>[ogmeta_notice]" id="<?php echo $this->tag; ?>[ogmeta_notice]" value="<?php echo $this->options['ogmeta_notice']; ?>" />
               </td>
           </tr>
           <tr valign="top">
               <th scope="row" colspan="4" style=" border-bottom:1px dotted #aaa">
                   <h3><?php _e("FB Like Button Settings", 'fb_like_trans_domain'); ?></h3>
               </th>
           </tr>
           <tr valign="top">
               <th scope="row"><?php _e("Show FB Like Button:", 'fb_like_trans_domain'); ?></th>
               <td><input type="checkbox" name="<?php echo $this->tag; ?>[fb_like_enabled]" value="true" <?php echo ($this->options['fb_like_enabled'] == 'true' ? 'checked' : ''); ?>/> <small><?php _e("Show facebook like button in the site", 'fb_like_trans_domain'); ?></small></td>
               <th scope="row"><?php _e("Width:", 'fb_like_trans_domain'); ?></th>
               <td><input size="4" type="text" name="<?php echo $this->tag; ?>[fb_like_width]" style="text-align:right" value="<?php echo ($this->options['fb_like_width'] != "") ? $this->options['fb_like_width'] : "450"; ?>" /> px</td>               
           </tr>
           <tr valign="top">
               <th scope="row" class="werty"><?php _e("Layout:", 'fb_like_trans_domain'); ?></th>
               <td class="werty">
                   <select name="<?php echo $this->tag; ?>[fb_like_layout]">
                    <?php
                       $curmenutype = $this->options['fb_like_layout'];
                       foreach ($fb_like_layouts as $type)
                           echo "<option value=\"$type\"" . ($type == $curmenutype ? " selected" : "") . ">$type</option>";
                    ?>
                   </select>
               </td>
               <th scope="row" class="werty"><?php _e("Margin Top:", 'fb_like_trans_domain'); ?></th>
               <td class="werty"><input size="3" type="text" style="text-align:right" name="<?php echo $this->tag; ?>[fb_like_margin_top]" value="<?php echo ($this->options['fb_like_margin_top'] != "") ? $this->options['fb_like_margin_top'] : "0"; ?>" /> px</td>
           </tr>
           <tr valign="top">
               <th scope="row"><?php _e("Verb to display:", 'fb_like_trans_domain'); ?></th>
               <td>
                    <select name="<?php echo $this->tag; ?>[fb_like_verb]">
                    <?php
                       $curmenutype = $this->options['fb_like_verb'];
                       foreach ($fb_like_verbs as $type)
                           echo "<option value=\"$type\"" . ($type == $curmenutype ? " selected" : "") . ">$type</option>";
                    ?>
                    </select>
               </td>
               <th scope="row"><?php _e("Margin Bottom:", 'fb_like_trans_domain'); ?></th>
               <td><input size="3" type="text" style="text-align:right" name="<?php echo $this->tag; ?>[fb_like_margin_bottom]" value="<?php echo ($this->options['fb_like_margin_bottom'] != "") ? $this->options['fb_like_margin_bottom'] : "0"; ?>" /> px</td>
           </tr>
           <tr valign="top">
               <th scope="row" class="werty"><?php _e("Font:", 'fb_like_trans_domain'); ?></th>
               <td class="werty">
                    <select name="<?php echo $this->tag; ?>[fb_like_font]">
                    <?php
                       $curmenutype = $this->options['fb_like_font'];
                       foreach ($fb_like_font as $type)
                           echo "<option value=\"$type\"" . ($type == $curmenutype ? " selected" : "") . ">$type</option>";
                    ?>
                    </select>
               </td>
               <th scope="row" class="werty"><?php _e("Margin Left:", 'fb_like_trans_domain'); ?></th>
               <td class="werty"><input size="3" type="text" style="text-align:right" name="<?php echo $this->tag; ?>[fb_like_margin_left]" value="<?php echo ($this->options['fb_like_margin_left'] != "") ? $this->options['fb_like_margin_left'] : "0"; ?>" /> px</td>
           </tr>
           <tr valign="top">
               <th scope="row"><?php _e("Color Scheme:", 'fb_like_trans_domain'); ?></th>
               <td>
                   <select name="<?php echo $this->tag; ?>[fb_like_colorscheme]">
<?php
                       $curmenutype = $this->options['fb_like_colorscheme'];
                       foreach ($fb_like_colorschemes as $type)
                           echo "<option value=\"$type\"" . ($type == $curmenutype ? " selected" : "") . ">$type</option>";
?>
                   </select>
               </td>
               <th scope="row"><?php _e("Margin Right:", 'fb_like_trans_domain'); ?></th>
               <td><input size="3" type="text" style="text-align:right" name="<?php echo $this->tag; ?>[fb_like_margin_right]" value="<?php echo ($this->options['fb_like_margin_right'] != "") ? $this->options['fb_like_margin_right'] : "0"; ?>" /> px</td>
           </tr>
           <tr valign="top">
               <th scope="row" class="werty"><?php _e("Show Faces:", 'fb_like_trans_domain'); ?></th>
               <td class="werty"><input type="checkbox" name="<?php echo $this->tag; ?>[fb_like_showfaces]" value="true" <?php echo ($this->options['fb_like_showfaces'] == 'true' ? 'checked' : ''); ?>/> <small><?php _e("Automatically increase the height accordingly", 'fb_like_trans_domain'); ?></small></td>
               <th scope="row" class="werty"><?php _e("Show on Pages:", 'fb_like_trans_domain'); ?></th>
               <td class="werty"><input type="checkbox" name="<?php echo $this->tag; ?>[fb_like_showonpages]" value="true" <?php echo ($this->options['fb_like_showonpages'] == 'true' ? 'checked' : ''); ?>/></td>
           </tr>
       </table>
       <p class="submit" style="padding-left:20px">
           <input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes'); ?>" />
       </p>
   </form>
   <div class="exptmeta">
       <a class="button add-new-h2" href="<?php echo home_url(); ?>/wp-content/plugins/facebook-opengraph-meta-plugin/export_report.php">Click Here to Export All the Open Graph Meta Data</a>
       <br/>
       <h3><?php _e('Upload Open Graph Data from CSV File'); ?></h3>
       <br/>
       <form action="<?php echo $_SERVER['PHP_SELF'] ?>?page=OgMeta" method="post" enctype="multipart/form-data">
           <label for="file">File:</label>
           <input type="file" name="file" id="file" />&nbsp;&nbsp;&nbsp;
           <input type="hidden" name="upload_f" id="upload_f" />
           <input class="button-primary" type="submit" name="submit" value="Submit" />
       </form>

    <?php
       if (isset($_REQUEST['upload_f'])) {

           function isAllowedExtension($fileName) {
               //$allowedExtensions = array("xls", "xlsx");
               $allowedExtensions = array("csv");
               return in_array(end(explode(".", $fileName)), $allowedExtensions);
           }

           if ($_FILES["file"]["error"] > 0) {
               echo "Error: " . $_FILES["file"]["error"] . "<br />";
           } else {
               if (isAllowedExtension($_FILES["file"]["name"])) {
                   if ($_FILES["file"]["tmp_name"] != '') {
                       $reader = & new CSVReader(new FileReader($_FILES["file"]["tmp_name"]));
                       // set the separator use format, here a comma
                       $reader->setSeparator(',');
                       // line tracking
                       $line = 0;
                       while (false != ( $cell = $reader->next() )) {
                           for ($i = 0; $i < count($cell); $i++) {
                               $data_main[$line][$i] = $cell[$i];
                           }
                           $line++;
                       }

                       for ($itr = 1; $itr <= count($data_main); $itr++) {
                           $pieces = explode("/", $data_main[$itr][0]);
                           $post_name = '';
                           if ($pieces[sizeof($pieces) - 1] == '') {
                               $post_name = $pieces[sizeof($pieces) - 2];
                           } else {
                               $post_name = $pieces[sizeof($pieces) - 1];
                           }
                           if ($post_name != '') {
                               $post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '" . $post_name . "'");
                           }
                           if ($post_id != '') {
                               for ($k = 1; $k < 18; $k++) {
                                   if (trim($data_main[$itr][$k]) != '') {
                                       $new_data[$data_main[0][$k]] = $data_main[$itr][$k];
                                   }
                               }
                               update_post_meta($post_id, "_OgMeta", serialize($new_data));
                           }
                       }
                   }
               } else {
                   echo "Invalid file type";
               }
           }
       }
    ?>
                   </div>

                           <div class="exptmeta">
                               <div class="h">Search in Grid by Post Title:</div>
                               <div>
                                   <input type="text" id="search_cd" onkeydown="gridReload()" onkeypress="gridReload()" onkeyup="gridReload()" />
                               </div>

                               <br />
                               <br/>
                               <table id="list9"></table>
                               <div id="pager9"></div>
                               <br/>
                           </div>
                       </div>
                       <script type="text/javascript" src="<?php echo bloginfo("wpurl"); ?>/wp-content/plugins/facebook-opengraph-meta-plugin/inc/js/grid.locale-en.js"></script>
                       <script type="text/javascript" src="<?php echo bloginfo("wpurl"); ?>/wp-content/plugins/facebook-opengraph-meta-plugin/inc/js/jquery.jqGrid.min.js"></script>
                       <script type="text/javascript" src="<?php echo bloginfo("wpurl"); ?>/wp-content/plugins/facebook-opengraph-meta-plugin/inc/js/jquery-ui-1.7.2.custom.min.js"></script>
                       <script type="text/javascript" src="<?php echo bloginfo("wpurl"); ?>/wp-content/plugins/facebook-opengraph-meta-plugin/inc/js/jquery.multiSelect.js"></script>
                       <script type="text/javascript">
                           jQuery("#list9").jqGrid({
                               url:'<?php echo bloginfo("wpurl"); ?>/wp-content/plugins/facebook-opengraph-meta-plugin/all_meta.php?q=2&pst_title=&nd='+new Date().getTime(),
                               datatype: "json",
                               colNames:['Post Title','Open Graph Meta Data'],
                               colModel:[
                                   {name:'post_id',index:'post_id', width:55},
                                   {name:'meta_value',index:'meta_value', width:150, sortable:false}
                               ],
                               rowNum:10,
                               rowList:[10,20,30],
                               pager: '#pager9',
                               sortname: 'id',
                               recordpos: 'left',
                               viewrecords: true,
                               sortorder: "desc",
                               multiselect: false,
                               autowidth: true,
                               height: 400,
                               ondblClickRow : function(e){
                                   opengraph_manager(e);
                               },
                               caption: "All Open Graph Meta Data"
                           });
                           var timeoutHnd;
                           var flAuto = false;
                           function doSearch(ev)
                           {
                               if(!flAuto) return;
                               if(timeoutHnd)
                                   clearTimeout(timeoutHnd)
                               timeoutHnd = setTimeout(gridReload,500)
                           }
                           function gridReload()
                           {
                               var pst_title = jQuery("#search_cd").val();
                               jQuery("#list9").setGridParam({url:'<?php echo bloginfo("wpurl"); ?>/wp-content/plugins/facebook-opengraph-meta-plugin/all_meta.php?q=1&pst_title='+pst_title});
                               jQuery("#list9").trigger("reloadGrid");
                           }
                           function enableAutosubmit(state)
                           {
                               flAuto = state;
                               jQuery("#submitButton").attr("disabled",state);
                           }
                           function update_jqg(){
                               jQuery("#list9").trigger("reloadGrid");
                           }
                           function opengraph_manager(selected_id){
                               tb_show("OpenGraph Meta Manager","<?php echo bloginfo('wpurl'); ?>/wp-content/plugins/facebook-opengraph-meta-plugin/meta_manager.php?tag=<?php echo 'OgMeta'; ?>&post_id="+selected_id+"&fromP=settings&&width=800&height:=400&TB_iframe=true");
    }
</script>