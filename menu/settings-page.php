<?php
add_action(
	'admin_init',
	function () {
		register_setting( 'aegis_resend', 'aegis_resend_api_key' );

		add_settings_section(
			'api',
			'API',
			function ( $args ) {
				echo "<p>Please visit <a href='https://resend.com/api-keys'>your dashboard</a> to find your API key(s).</p>";
			},
			'aegis_resend_settings'
		);

		add_settings_field(
			'api_key',
			'API key',
			function ( $args ) {
				$value = get_option( 'aegis_resend_api_key' );
				echo "<input type='text' name='aegis_resend_api_key' value='{$value}' size='36' />";
			},
			'aegis_resend_settings',
			'api',
		);
	}
);

add_action(
	'admin_menu',
	function () {
		add_options_page(
			'Resend by Aegis',
			'Resend by Aegis',
			'manage_options',
			'aegis_resend_settings',
			function () {
				if ( ! current_user_can( 'manage_options' ) ) {
					return;
				}
				?>
		<h1>Resend by Aegis</h1>
		<div class="wrap">
			<form action="options.php" method="POST">
				<?php
				settings_fields( 'aegis_resend' );
				do_settings_sections( 'aegis_resend_settings' );
				submit_button( __( 'Save Settings' ) );
				?>
			</form>
		</div>
				<?php
			}
		);
	}
);
