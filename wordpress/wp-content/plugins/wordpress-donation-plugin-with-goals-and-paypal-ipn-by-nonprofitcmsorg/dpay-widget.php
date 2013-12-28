<?php 

function widget_dpay_init() {

	if ( !function_exists('register_sidebar_widget') )
		return;

	function widget_dpayform($args) {
		extract($args);

		// Each widget can store its own options. We keep strings here.
		$options = get_option('widget_mydonateform');
		$title = $options['title'];
		$campgainId = $options['campgainId'];		
		$arr = array( "cid" => $campgainId );
		
		// These lines generate our output. Widgets can be very complex

		echo $before_widget . $before_title . $title . $after_title;
		MyDonateForm( $arr  );
		echo $after_widget;
	}


	function widget_dpayform_control() {
		$options = get_option('widget_mydonateform');
		if ( !is_array($options) )
			$options = array('title'=>'Donate');

			
		if ( $_POST['donateplusf-submit'] ) {
			
			$options['title'] = strip_tags(stripslashes($_POST['donateplusf-title']));
			$options['campgainId'] = strip_tags(stripslashes($_POST['campgainId']));
			
			update_option('widget_mydonateform', $options);
		}
		
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$id = htmlspecialchars($options['campgainId'], ENT_QUOTES);
		
		echo '<p style="text-align:right;"><label for="donateplusf-title">' . __('Title:') . ' <input style="width: 200px;" id="donateplusf-title" name="donateplusf-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="donateplusf-title">' . __('Campaign ID:') . ' <input style="width: 200px;" id="campgainId" name="campgainId" type="text" value="'.$id.'" /></label></p>';		
		echo '<input type="hidden" id="donateplusf-submit" name="donateplusf-submit" value="1" />';
	}

	
	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget(array('My Donate Form', 'widgets'), 'widget_dpayform');

	// This registers our optional widget control form. Because of this
	// our widget will have a button that reveals a 300x100 pixel form.
	register_widget_control(array('My Donate Form', 'widgets'), 'widget_dpayform_control', 300, 100);
}

add_action('widgets_init', 'widget_dpay_init');
 ?>