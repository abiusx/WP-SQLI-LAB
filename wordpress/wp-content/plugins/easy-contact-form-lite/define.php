<?php 
global $wpdb;
global $table_name;
global $settings_table_name;
global $merlic_easyform_version;

define('EASYFORM_PLUGIN_PATH', WP_PLUGIN_URL.'/easy-contact-form-lite/');
define('EASYFORM_PLUGIN_NAME','Easy Contact Form Lite');

$merlic_easyform_version = '1.0.7';
$table_name = $wpdb->prefix."easyform";
$settings_table_name = $wpdb->prefix."easyform_settings";

?>
