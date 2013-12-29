<div id="notifications_container" class="wrap">
<!-- DIV for New Notification Input -->
<table id="data_table_input_notifications" class="data_table">
<h1>Add New Notification</h1>
	<!-- Column Headings for New Notification Input -->
	<tr id="data_table_col_headings_input_notifications" class="data_table_col_headings">
		<th class="data_table_col_heading">
			Goal
		</th>
		<th class="data_table_col_heading">
			Time
		</th>
		<th class="data_table_col_heading">
			Message
		</th>
		<th class="data_table_col_heading">
			Channel
		</th>
	</tr>
	<!-- Fields for New Notification Input -->
	<tr id="data_table_row_input_notifications" class="data_table_row_inputs">
		<td id="data_table_row_input_notification_goal" class="data_table_row_input">
		<!-- this will be the number of time for the milestone to occur -->
		<input type="text" size="5" name="number">
		<!-- this will be the milestone -->
		<input type="text" size="25" name="milestone">
		</td>
		<td id="data_table_row_input_notification_time" class="data_table_row_input">
		<!-- this will be the time to schedule the notification - Options should be:  daily, 2x daily, 4x daily, hourly -->
		<input type="select" size="25" name="time">
		</td>
		<!-- 	this will contain messages to send  -->
		<td id="data_table_row_input_notification_message" class="data_table_row_input">
		<textarea name="message"></textarea>
		</td>
		<!-- this will be be the select box for choosing channel - Options should be: email, twitter, facebook wall post -->
		<td id="data_table_row_input_notification_channel" class="data_table_row_input">
		<input type="select" size="25" name="channel">
		</td>
	</tr>
	<tr id="input_row_notifications_controls" class="data_table_row_input_controls">
		<th id="data_table_row_input_notification_add_button" class="data_table_row_input_control">
		<input type="button" value="add">
		</th>
		<th id="data_table_row_input_notification_reset_button" class="data_table_row_control">
		<input type="button" value="reset">
		</th>
	</tr>
</table><!-- DIV END data_table_input_notifications -->
<hr>
<!-- DIV for Active Notifications Display -->
<table id="data_table_display_active_notifications" class="data_table">
<h1>Display Active Notifications</h1>
	<!-- Column Headings and Controls for Active Notifications  -->
	<tr id="data_table_col_headings_notifications" class="data_table_col_headings">
		<th class="data_table_col_heading">
			<input type="checkbox" name="select_all" value="" /><br>Select/Deselect All
		</th>
		<th class="data_table_col_heading">
			Goal <input type="button" value="sort">
		</th>
		<th class="data_table_col_heading">
			Time <input type="button" value="sort">
		</th>
		<th class="data_table_col_heading">
			Message <input type="button" value="sort">
		</th>
		<th class="data_table_col_heading">
			Channel <input type="button" value="sort">
		</th>
		<th class="data_table_col_heading">
			Edit Entry
		</th>
	</tr>
		<!-- Loop Through to Display Active Notifications -->
	<tr id="data_table_rows_notifications" class="data_table_rows">
		<td class="data_table_row_select">
		<input type="checkbox" name="select_all" value="" />
		</td>
		<td class="data_table_row">
		DATA: GOAL
		</td>
		<td class="data_table_row">
		DATA: TIME
		</td>
		<td class="data_table_row">
		DATA: MESSAGE
		</td>
		<td class="data_table_row">
		DATA: CHANNEL
		</td>
		<td class="data_table_row_button">
		 <input type="button" value="edit record">
		</td>
	</tr>
	<!-- Footer Controls and Column Headings for Active Notifications  -->
	<tr id="data_table_col_footer_notifications" class="data_table_col_footer">
		<th class="data_table_col_footer">
			<input type="checkbox" name="select_all" value="" /><br>Select/Deselect All
		</th>
		<th class="data_table_col_footer">
			<input type="button" value="Edit Checked ">
		</th>
		<th class="data_table_col_footer">
			<input type="button" value="Deactivate Checked ">
		</th>
		<th class="data_table_col_footer">
			<input type="button" value="Delete Checked">
		</th>
		<th class="data_table_col_footer">
			<input type="button" value="Cancel">
		</th>
	</tr>
</table><!-- TABLE END data_table_display_active_notifications -->
<hr>
<!-- DIV for INACTIVE Notifications Display -->
<table id="data_table_display_notifications_inactive" class="data_table">
<h1>Display Inactive Notifications</h1>
	<!-- Column Headings and Controls for INACTIVE Notifications  -->
	<tr id="data_table_col_headings_notifications_inactive" class="data_table_col_headings">
		<th class="data_table_col_heading">
			<input type="checkbox" name="select_all" value="" /><br>Select/Deselect All
		</th>
		<th class="data_table_col_heading">
			Goal <input type="button" value="sort">
		</th>
		<th class="data_table_col_heading">
			Time <input type="button" value="sort">
		</th>
		<th class="data_table_col_heading">
			Message <input type="button" value="sort">
		</th>
		<th class="data_table_col_heading">
			Channel <input type="button" value="sort">
		</th>
		<th class="data_table_col_heading">
			Edit Entry
		</th>
	</tr>
		<!-- Loop Through to Display INACTIVE Notifications -->
	<tr id="data_table_rows_notifications_inactive" class="data_table_rows">
		<td class="data_table_row">
		<input type="checkbox" name="select_all" value="" />
		</td>
		<td class="data_table_row">
		DATA: GOAL
		</td>
		<td class="data_table_row">
		DATA: TIME
		</td>
		<td class="data_table_row">
		DATA: MESSAGE
		</td>
		<td class="data_table_row">
		DATA: CHANNEL
		</td>
		<td class="data_table_row">
		 <input type="button" value="edit record">
		</td>
	</tr>
	<!-- Footer Controls and Column Headings for Active Notifications  -->
	<tr id="data_table_col_footer_notifications_inactove" class="data_table_col_footer">
		<th class="data_table_col_footer">
			<input type="checkbox" name="select_all" input_row="" /><br>Select/Deselect All
		</th>
		<th class="data_table_col_footer">
			<input type="button" value="Edit Checked">
		</th>
		<th class="data_table_col_footer">
			 <input type="button" value="Activate Checked">
		</th>
		<th class="data_table_col_footer">
			 <input type="button" value="Delete Checked">
		</th>
		<th class="data_table_col_footer">
			 <input type="button" value="Cancel">
		</th>
	</tr>
</table><!-- TABLE END data_table_display_notifications_inactive -->

</div><!-- DIV END "notifications_list_container" -->
