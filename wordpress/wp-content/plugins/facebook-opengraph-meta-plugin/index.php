<?php

/*
  Plugin Name: OpenGraph Meta
  Plugin URI: http://wordpress.org/extend/plugins/ogemeta/
  Description: Adds the ability to add  custom opengraph meta property for Facebook OpenGraph to pages and posts.
  Version: 1.0
  Author: Blueliner Bangladesh
  Author URI: http://www.bluelinerny.com
  License: GPL
 */
if (!class_exists('OgMeta')) {
    /*
     * Plugin main class 
     */

    class OgMeta {

        var $name = 'Opengraph Meta';
        var $tag = 'OgMeta';
        var $options = array();

        function OgMeta() {
            if ($options = get_option($this->tag)) {
                $this->options = $options;
            }
            add_action('wp_head', array(&$this, 'meta'));
            if (is_admin ()) {
                add_action('admin_menu', array(&$this, 'panels'));
                add_action('admin_init', array(&$this, 'settings_init'));
                add_action('admin_print_styles', array(&$this, 'addHeaderCode'));
                add_filter('plugin_row_meta', array(&$this, 'settings_meta'), 10, 2);
            }
            add_action('admin_notices', array(&$this, 'ogmeta_conflict_notice'));
            add_filter('og_meta_title', array(&$this, 'title'), 1);
            if ($this->options['fb_like_enabled']) {
                add_filter('the_content', array(&$this, 'fb_like_button'), 1);
            }
            add_action('wp_ajax_ogmeta_conflict_notice_action', array(&$this, 'ogmeta_conflict_notice_callback'));
        }

        /*
         * This function is used to show the Notification that any other facebook like button plugin may
         * may conflict with Opengraph Meta plugin.
         *
         * The Javscript function used to hide the notice.
         */

        function ogmeta_conflict_notice() {
            if (!$this->options['ogmeta_notice']) {
                echo '<div class="error fade" id="og_meta_notice">
                    <p><strong>Please Uninstall any other facebook like button plugin as this Opengraph Meta plugin may conflict with those plugins</strong>.
                    Browse <a href="' . admin_url('plugins.php?page=OgMeta') . '">The plugin settings page</a> for setting up.</p>
                    <p><a href="javascript:void;" onClick="hide_notice();">Click here to hide this notice.</a></p>
                    </div>';
            }
?>
            <script type="text/javascript" >
                function hide_notice(){
                    jQuery(document).ready(function($) {
                        var data = {
                            action: 'ogmeta_conflict_notice_action'
                        };
                        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                        jQuery.post(ajaxurl, data, function(response) {
                            $("#og_meta_notice").css("display","none");
                        });
                    });
                }
            </script>
<?php
        }

        /*
         * This function is used as the call back function to hide the conflict notice.
         */

        function ogmeta_conflict_notice_callback() {
            global $wpdb;
            $opts = get_option($this->tag);
            $opts['ogmeta_notice'] = 1;
            update_option($this->tag, $opts);
            die();
        }

        function activate() {
            if (!$this->options) {
                update_option($this->tag, array(
                    'posts' => 1
                ));
            }
        }

        function deactivate() {
            if ($this->options['tidy']) {
                $posts = get_posts('numberposts=-1&post_type=any');
                foreach ($posts as $post) {
                    $values = array('title', 'description', 'keywords');
                    foreach ($values AS $name) {
                        delete_post_meta($post->ID, '_' . $this->tag);
                    }
                }
                update_option($this->tag, null);
            }
        }

        /*
         * Function to add sub menu page
         */

        function panels() {
            add_submenu_page(
                    'plugins.php',
                    'Manage ' . $this->name,
                    $this->name,
                    'administrator',
                    $this->tag,
                    array(&$this, 'settings_page')
            );
            add_meta_box($this->tag . '_postbox', $this->name, array(&$this, 'panel'), 'page', 'normal', 'high');
            //if (isset($this->options['posts'])) {
                add_meta_box($this->tag . '_postbox', $this->name, array(&$this, 'panel'), 'post', 'normal', 'high');
            //}
        }

        function panel() {
            include_once('panel.php');
        }

        function field($field) {
            global $post;
            return get_post_meta($post->ID, '_' . $this->tag . '_' . $field, true);
        }

        function save($post_id) {
            if (!isset($_POST[$this->tag . 'nonce']) || !wp_verify_nonce($_POST[$this->tag . 'nonce'], 'wp_' . $this->tag)) {
                return $post_id;
            }
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
            foreach ($_POST[$this->tag] AS $key => $value) {
                $field = '_' . $this->tag . '_' . $key;
                $value = wp_filter_kses($value);
                if (empty($value)) {
                    delete_post_meta($post_id, $field, $value);
                } else if (!update_post_meta($post_id, $field, $value)) {
                    add_post_meta($post_id, $field, $value);
                }
            }
        }

        function title($args) {
            extract($args);
            global $wppm_title;
            $title = isset($wppm_title) ? $wppm_title : $this->value('title');
            if ($title) {
                $title = $title . ' ' . $sep;
                if ($echo) {
                    echo $title;
                } else {
                    return $title;
                }
            } else {
                wp_title($sep, $echo, $seplocation);
            }
        }

        function value($type) {
            global $wpdb;
            $p = get_query_var('p');
            $name = get_query_var('name');
            if (intval($p) || !empty($name)) {
                if (!$p) {
                    $p = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = %s", $name));
                }
                $post = &get_post($p);
                return get_post_meta($p, '_' . $this->tag . '_' . $type, true);
            }
        }

        /*
         * Printing OG:Meta data to the header
         *
         */

        function meta() {
            global $wp_query;
            $obj = $wp_query->get_queried_object();
            $prnt_title = true;
            $prnt_site = true;
            $post_meta = get_post_meta($obj->ID, "_" . $this->tag);
            if (( $this->ob_level >= 0 ) && ( $this->ob_level == ob_get_level() )) {
                ob_end_flush();
            }
            print("<!-- OpenGraph meta data added by OpenGraph Meta plugin -->\n");
            // APP ID 
            if ($this->options['app_id'] != '') {
                print "<meta property=\"fb:app_id\" content=\"" . trim($this->options['app_id']) . "\" />\n";
            } else {
                print "<meta property=\"fb:app_id\" content=\"113869198637480\" />\n";
            }
            if (is_array($post_meta)) {
                $post_meta = unserialize(reset($post_meta));
                if (is_array($post_meta)) {
                    foreach ($post_meta as $key => $val) {
                        print "<meta property=\"" . trim($key) . "\" content=\"" . trim($val) . "\" />\n";
                        if ($key == 'og:title') {
                            $prnt_title = false;
                        }
                        if ($key == 'og:site_name') {
                            $prnt_site = false;
                        }
                    }
                }
            }
            if ($prnt_title) {
                print "<meta property=\"og:title\" content=\"" . trim(the_title('', '', false)) . "\" />\n";
            }
            if ($prnt_site) {
                print "<meta property=\"og:site_name\" content=\"" . get_bloginfo('name') . "\" />\n";
            }
            print("<!-- OpenGraph meta data added by OpenGraph Meta plugin -->\n");
        }

        /*
         * Add some style sheets and Jquery
         */

        function addHeaderCode() {
            print '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/facebook-opengraph-meta-plugin/css/og_styles.css"/>' . "\n";
            print '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/facebook-opengraph-meta-plugin/css/ui.jqgrid.css"/>' . "\n";
            print '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/facebook-opengraph-meta-plugin/css/jquery-ui-1.7.2.custom.css"/>' . "\n";
            wp_enqueue_script('jquery');
            wp_enqueue_script('thickbox', null, array('jquery'));
            wp_enqueue_style('thickbox.css', '/' . WPINC . '/js/thickbox/thickbox.css', null, '1.0');
        }

        /*
         * Printing the FB like button below the post/page content.
         */

        function fb_like_button($content) {
            $margin = $this->options['fb_like_margin_top'] . 'px '
                    . $this->options['fb_like_margin_right'] . 'px '
                    . $this->options['fb_like_margin_bottom'] . 'px '
                    . $this->options['fb_like_margin_left'] . 'px';

            if (is_feed() || is_front_page())
                return $content;

            if (is_page() && !$this->options['fb_like_showonpages'])
                return $content;

            if ($this->options['fb_like_xfbml'] == true) {
                /* XFBML VERSION */
                global $locale;

                $url = ' href="' . urlencode(get_permalink()) . '"';

                if ($this->options['fb_like_layout'] == 'button_count')
                    $url .= ' layout="button_count"';

                if ($this->options['fb_like_showfaces'] != 'true')
                    $url .= ' show_faces="false"';

                if ($this->options['fb_like_width'] != '450')
                    $url .= ' width="' . $this->options['fb_like_width'] . '"';

                if ($this->options['fb_like_verb'] == 'recommend')
                    $url .= ' action="recommend"';

                if ($this->options['fb_like_font'] != '')
                    $url .= ' font="' . $this->options['fb_like_font'] . '"';

                if ($this->options['fb_like_colorscheme'] == 'dark')
                    $url .= ' colorscheme="dark"';

                $url .= ' style="margin:' . $margin . ';';

                $fb_btn = '<script src="http://connect.facebook.net/' . $locale . '/all.js#xfbml=1" type="text/javascript"></script> <fb:like' . $url . '></fb:like>';
                /* END OF XFBML VERSION */
            } else {
                /* STANDARD (NON-XFBML) VERSION */
                $height = ($this->options['fb_like_showfaces'] == 'true') ? 20 : 35;

                $url = urlencode(get_permalink()) . '&amp;layout=' . $this->options['fb_like_layout']
                        . '&amp;show_faces=' . (($this->options['fb_like_showfaces'] == 'true') ? 'true' : 'false')
                        . '&amp;width=' . $this->options['fb_like_width']
                        . '&amp;action=' . $this->options['fb_like_verb']
                        . '&amp;colorscheme=' . $this->options['fb_like_colorscheme'] . '&amp;height=' . $height;

                if ($this->options['fb_like_font'] != '')
                    $url .= '&amp;font=' . urlencode($this->options['fb_like_font']);

                $fb_btn = '<iframe class="fblikes" src="http://www.facebook.com/plugins/like.php?href=' . $url . '" style="scrolling: no; allowTransparency: true; border:none; overflow:hidden; width:' . $this->options['fb_like_width'] . 'px; height:' . $height . 'px; margin:' . $margin . '"></iframe>';
                /* END OF STANDARD (NON-XFBML) VERSION */
            }

            $content = $content . '<br/>' . $fb_btn;

            return $content;
        }

        function settings_init() {
            register_setting($this->tag . '_options', $this->tag, array(&$this, 'settings_validate'));
        }

        function settings_validate($inputs) {
            if (is_array($inputs)) {
                foreach ($inputs AS $key => $input) {
                    //$inputs[$key] = ($inputs[$key] == 1 ? 1 : 0);
                }
                return $inputs;
            }
        }

        function settings_page() {
            include_once('settings.php');
        }

        function settings_meta($links, $file) {
            $plugin = plugin_basename(__FILE__);
            if ($file == $plugin) {
                return array_merge(
                        $links,
                        array(sprintf(
                                    '<a href="plugins.php?page=%s">%s</a>',
                                    $this->tag, __('Settings')
                        ))
                );
            }
            return $links;
        }

    }

    $OgMeta = new OgMeta();
    if (isset($OgMeta)) {
        register_activation_hook(__FILE__, array($OgMeta, 'activate'));
        register_deactivation_hook(__FILE__, array($OgMeta, 'deactivate'));

        function og_meta_title($s='', $e=true, $l=null) {
            return apply_filters('og_meta_title', array('sep' => $s, 'echo' => $e, 'seplocation' => $l));
        }
    }
}