<div class="wrap">
	<h1>Product Table PDF Header:</h1>
	<form method="POST">
		<?php
		$key = 'product_table_header';
		if (isset($_POST[$key])) {
			update_option($key,stripslashes( $_POST[$key] ));
		}
		$html = get_option($key);
		$settings = array('textarea_name'=>$key);
		wp_editor($html,$key, $settings);
		submit_button('Save All Changes', 'primary');
		?>
	<h1>Product Table PDF Footer:</h1>
		<?php
		$key = 'product_table_footer';
		if (isset($_POST[$key])) {
			update_option($key,stripslashes( $_POST[$key] ));
		}
		$html = get_option($key);
		$settings = array('textarea_name'=>$key);
		wp_editor($html,$key, $settings);
		submit_button('Save All Changes', 'primary');
		?>
	</form>
</div>