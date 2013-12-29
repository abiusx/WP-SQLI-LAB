<?php 
class Merlic_EasyContactForm {

	public function init() {
		if (!wp_script_is('jquery', 'queue')) {
			wp_enqueue_script("jquery");
		}
		
		//default stylesheet
		$css_url = WP_PLUGIN_URL.'/easy-contact-form-lite/style/default.css';
		$css_dir = WP_PLUGIN_DIR.'/easy-contact-form-lite/style/default.css';
		self::include_css($css_url, $css_dir, 'merlic_easycontact_css_default');
		
		//easy stylesheet
		$css_url = WP_PLUGIN_URL.'/easy-contact-form-lite/style/easy.css';
		$css_dir = WP_PLUGIN_DIR.'/easy-contact-form-lite/style/easy.css';
		self::include_css($css_url, $css_dir, 'merlic_easycontact_css_easy');
		
		//easy js
		$default_js_url = WP_PLUGIN_URL.'/easy-contact-form-lite/js/easy.js';
		$default_js_dir = WP_PLUGIN_DIR.'/easy-contact-form-lite/js/easy.js';
		self::include_js($default_js_url, $default_js_dir, 'merlic_easycontact_js_easy');
		
		//jquery-ui js
		$jquery_ui_js_url = WP_PLUGIN_URL.'/easy-contact-form-lite/js/jquery-ui.js';
		$jquery_ui_js_dir = WP_PLUGIN_DIR.'/easy-contact-form-lite/js/jquery-ui.js';
		self::include_js($jquery_ui_js_url, $jquery_ui_js_dir, 'merlic_easycontact_js_ui');
		
		//main js
		$main_js_url = WP_PLUGIN_URL.'/easy-contact-form-lite/js/main.js';
		$main_js_dir = WP_PLUGIN_DIR.'/easy-contact-form-lite/js/main.js';
		self::include_js($main_js_url, $main_js_dir, 'merlic_easycontact_js_main');
		
	}
	
	public function admin_menu() {
		add_options_page("Easy Contact Form Lite", "Easy Contact Form Lite", 'manage_options', 'merlic_easycontact_lite_admin', array('Merlic_EasyContactForm', "draw_admin_menu"));
	}
	
	public function draw_admin_menu() {
		include 'admin/options-page.php';
	}
	
	public function the_content( $content ) {
		global $wpdb;
		global $table_name;
		global $settings_table_name;
		
		$private_key = '6LdKkr8SAAAAAN3d0B3M_EMh1qx4PeHtOre8loCy';
		
		if ($_POST['merlic_easyform_submitted'] == 1) {
		
			$form = $wpdb->get_results("SELECT * FROM $table_name WHERE ID = ".$_POST['merlic_easyform_formid']);
			
			$continue = true;
			
			if (get_option('merlic_easyform_recaptcha') == 'yes') {
				$resp = recaptcha_check_answer($private_key, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
				
				if (!$resp->is_valid) {
					$continue = false;
					$output = "Recaptcha words uncorrect.".' '.self::back_button().'.';
				}
			}
			
			if ($continue) {
			
				//loop through the fields of this form (read from DB) and build the message here
				$form_fields = $wpdb->get_results("
        			SELECT *
        			FROM $settings_table_name
        			WHERE form_id = ".$_POST['merlic_easyform_formid']."
        			ORDER BY position
        		");
        		
				$message .= '<p>--- IP Address ---<br />'.self::get_real_ip_address().'</p><br />';
				
				foreach ($form_fields as $field) {
					if ($field->type != 'submit') {
						
						$value = $_POST['merlic_easyform_'.$field->name];
						
						if ( empty($field->label))
							$message .= '<p>--- '.ucfirst($field->name).' ---<br />'.$value.'</p><br />';
						else
							$message .= '<p>--- '.ucfirst($field->label).' ---<br />'.$value.'</p><br />';
					}
				}
				
				$sent = self::send_email(array(get_bloginfo('admin_email')=>get_bloginfo('name')), $form[0]->destinatary, '['.get_bloginfo('name').'] Submitted Form: '.$form[0]->name, $message);
				
				if ($sent)
					$output .= '<p>Thank you, your message has been delivered succesfully.</p>';
				else
					$output .= '<p>Ooops, an error occurred. Please try again later. '.self::back_button().'.</p>';
			}
			return $output;
		}
		else
			return $content;
	}
	
	public function shortcode( $atts ) {
		global $wpdb;
		global $table_name;
		global $settings_table_name;
		
		$public_key = '6LdKkr8SAAAAANbThDU6XbesyRFDaRgiVsY5ZXD1';
		
		//extract shortcode attributes
		extract(shortcode_atts(array('id'=>''), $atts));
		$form = $wpdb->get_results("SELECT * FROM $table_name WHERE ID = $id");

		
		if (count($form) == 1) {
			$form_fields = $wpdb->get_results("
        			SELECT *
        			FROM $settings_table_name
        			WHERE form_id = $id
        			ORDER BY position
        		");
        		
			foreach ($form_fields as $field) {
			
				$required = $field->required == 1 ? ' required' : '';
				
				switch ($field->type) {
				
					case 'text':
						if ($form[0]->show_label_inside != 1)
							$field_element = '<input class="field'.$required.'" type="text" name="merlic_easyform_'.$field->name.'" id="merlic_easyform_'.$field->name.'" value="'.$field->value.'" />';
						else
							$field_element = '<input class="field'.$required.' label" type="text" name="merlic_easyform_'.$field->name.'" id="merlic_easyform_'.$field->name.'" />';
							
						$form_content .= '
							<div>
								<label for="merlic_easyform_'.$field->name.'">'.$field->label.'</label>
								'.$field_element.'
							</div>';
						break;
						
					case 'email':
						if ($form[0]->show_label_inside != 1)
							$field_element = '<input class="field email'.$required.'" type="text" name="merlic_easyform_'.$field->name.'" value="'.$field->value.'" id="merlic_easyform_'.$field->name.'" />';
						else
							$field_element = '<input class="field email'.$required.' label" type="text" name="merlic_easyform_'.$field->name.'" id="merlic_easyform_'.$field->name.'" />';
							
						$form_content .= '
							<div>
								<label for="merlic_easyform_'.$field->name.'">'.$field->label.'</label>
								'.$field_element.'
							</div>';
						break;
						
					case 'url':
						$field_element = '<input class="field url'.$required.'" type="text" name="merlic_easyform_'.$field->name.'" value="'.$field->value.'" />';
						$form_content .= '
							<div>
								<label for="merlic_easyform_'.$field->name.'">'.$field->label.'</label>
								'.$field_element.'
							</div>';
						break;
						
					case 'textarea':
						if ($form[0]->show_label_inside != 1)
							$field_element = '<textarea class="area'.$required.'" name="merlic_easyform_'.$field->name.'" id="merlic_easyform_'.$field->name.'" >'.$field->value.'</textarea>';
						else
							$field_element = '<textarea class="area'.$required.' label" name="merlic_easyform_'.$field->name.'" id="merlic_easyform_'.$field->name.'" >'.$field->value.'</textarea>';
							
						$form_content .= '
							<div>
								<label for="merlic_easyform_'.$field->name.'" id="merlic_easyform_'.$field->name.'">'.$field->label.'</label>
								'.$field_element.'
							</div>';
						break;

					case 'submit':
						if (get_option('merlic_easyform_recaptcha') == 'yes')
							$form_content .= '
									<div>
										<label for="recaptcha_response_field">Recaptcha</label>
										'.recaptcha_get_html($public_key).'
									</div>
									';
									
						$form_content .= '
							<div class="submit">
								<button type="submit">'.$field->value.'</button>
							</div>';
						break;
						
					default:
						$field_element = null;
						break;
				}
				
			}
			$output = '
				<form id="easyform" action="'.get_permalink().'" method="post" target="_self">
					'.$form_content.'
					<input type="hidden" name="merlic_easyform_submitted" value="1" />
					<input type="hidden" name="merlic_easyform_formid" value="'.$id.'" />
				</form>';
		}
		else {
			$output .= 'Form not existing';
		}
		
		return $output;
	}
	
	public function register_widget() {
		wp_register_sidebar_widget('merlic_easycontact_widget', 'Test', array('Merlic_EasyContactForm', 'draw_widget'));
		wp_register_widget_control('merlic_easycontact_widget', 'Short desc', array('Merlic_EasyContactForm', 'widget_title'), null, 75, 'merlic_easycontact_widget');
	}
	
	public function widget_title() {
		if (isset($_POST['merlic_easycontact_widgettitle_submit'])) {
			update_option('merlic_easycontact_widget_title', $_POST['merlic_easycontact_widget_title']);
			update_option('merlic_easycontact_widget_field', $_POST['merlic_easycontact_widget_field']);
		}
		
		echo '
			<p>
				<label for="merlic_easycontact_widget_title">'.__('Title:').'</label><br />
				<input name="merlic_easycontact_widget_title" type="text" value="'.get_option('merlic_easycontact_widget_title').'" />
			</p>
			<p>
				<label for="merlic_easycontact_widget_field">'.__('Widget Field:').'</label><br />
				<input name="merlic_easycontact_widget_field" type="text" value="'.get_option('merlic_easycontact_widget_field').'" />
			</p>
			<input type="hidden" id="merlic_easycontact_widgettitle_submit" name="merlic_easycontact_widgettitle_submit" value="1" />
		';
	}
	
	public function draw_widget( $args ) {
		echo $args['before_widget'];
		echo $args['before_title'];
		echo get_option('merlic_easycontact_widget_title');
		echo $args['after_title'];
		echo self::draw_widget_content(get_option('merlic_easycontact_widget_field'));
		echo $args['after_widget'];
	}
	
	//=========================================================================================================================//
	
	private function draw_widget_content( $field ) {
	
	}
	
	private function include_css( $url, $dir, $handle ) {
		if (file_exists($dir)) {
			wp_register_style($handle, $url);
			wp_enqueue_style($handle);
		}
		else
			wp_die($dir.' not found');
	}
	
	private function include_js( $url, $dir, $handle ) {
		if (file_exists($dir)) {
			wp_register_script($handle, $url);
			wp_enqueue_script($handle);
		}
		else
			wp_die($dir.' not found');
	}
	
	private function println( $text ) {
		if (is_array($text) or is_object($text)) {
			echo '<pre>';
			print_r($text);
			echo '</pre>';
		}
		else {
			echo '<pre>';
			echo $text;
			echo '</pre>';
		}
		
		echo '<br />'."\n";
	}
	
	private function send_email( $sender, $destinatary, $subject, $body ) {
		if (is_array($sender)) {
			$senderEmail = array_keys($sender);
			$senderEmail = $senderEmail[0];
			$senderName = array_values($sender);
			$senderName = $senderName[0];
		}
		else {
			$senderName = $sender;
			$senderEmail = $sender;
		}
		
		if (is_array($destinatary)) {
			$destEmail = array_keys($destinatary);
			$destEmail = $destEmail[0];
			$destName = array_values($destinatary);
			$destName = $destName[0];
		}
		else
			$destEmail = $destinatary;
			
		$headers = 'MIME-Version: 1.0'."\r\n";
		$headers .= 'Content-type: text/html; charset=ISO-8859-1'."\r\n";
		$headers .= "From: $senderName <$senderEmail>\r\n";
		$headers .= "Reply-TO: $senderName <$senderEmail>\r\n";
		
		$body = '<html><body>'.$body.'<br /><br /><br /><br /><br /></body></html>';
		
		$r = wp_mail($destEmail, stripslashes_deep($subject), stripslashes_deep($body), $headers);
		
		return $r;
	}
	
	private function back_button() {
		return '<a href="javascript:history.go(-1)">Go back</a>';
	}
	
	private function get_real_ip_address() {
		//check ip from share internet
		if (! empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		//to check ip is pass from proxy
		elseif (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
}
?>
