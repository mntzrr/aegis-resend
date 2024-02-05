<?php
add_action(
	'admin_menu',
	function () {
		add_management_page(
			'Resend by Aegis',
			'Resend by Aegis',
			'manage_options',
			'aegis_resend',
			function () {
				if ( ! current_user_can( 'manage_options' ) ) {
					return;
				}

				$send = false;
				if ( isset( $_POST['from'], $_POST['to'], $_POST['subject'], $_POST['body'] ) ) {

					$from    = trim( $_POST['from'] );
					$to    = trim( $_POST['to'] );
					$subject = trim( $_POST['subject'] );
					$body    = trim( $_POST['body'] );
					$send    = true;
					if ( empty( $from ) || empty($to) || empty( $subject ) || empty( $body ) ) {
						$send  = false;
						$error = '<p class="error">No empty fields allowed.</p>';
					}
				}

				$api_key = get_option( 'aegis_resend_api_key' );
				if ( empty( $api_key ) ) {
					$send = false;
				}

				if ( $send ) {
					$resend = Resend::client( $api_key );
					try {
					$result = $resend->emails->send(
						array(
							'from'    => $from,
							'to'      => $to,
							'subject' => $subject,
							'html'    => $body,
						)
					);
					} catch (\Exception $e) {
						$error = '<p class="error">' . $e->getMessage() . '</p>';
					}

					$success = wp_json_encode( $result );
				}

				?>
		<style>
			.error {
				color: white;
				background-color: red;
				width: fit-content;
			}

			.success {
				color: white;
				background-color: green;
				width: fit-content;
			}

			form {
				display: flex;
				flex-wrap: wrap;
				justify-content: space-between;
				width: 100%;
			}

			.form-input {
				flex-basis: 100%;
			}

			input {
				width: 100%
			}

			textarea {
				width: 100%;
				min-height: 800px;
			}

			p.submit {
				padding: 0;
				margin: 0;
			}
		</style>



		<h1>Resend by Aegis</h1>
				<?php
				if ( isset( $error ) ) {
					echo $error;
				} elseif ( isset( $success ) ) {
					echo $success;
				}
				?>
		<div class="wrap">
			<form action="<?php menu_page_url( 'aegis_resend' ); ?>" method="POST">
				<div class="form-input">
					<label aria-label="From:">From: </label>
					<input type="email" name="from" placeholder="Your email address" value="<?php echo $_POST['from'] ?? '';?>" />
				</div>
				<div class="form-input">
					<label aria-label="To:">To: </label>
					<input type="email" name="to" placeholder="Recipient email address" value="<?php echo $_POST['to'] ?? '';?>" />
				</div>
				<div class="form-input">
					<label aria-label="Subject:">Subject: </label>
					<input type="text" name="subject" value="<?php echo $_POST['subject'] ?? '';?>" />
				</div>
				<div class="form-input">
					<label aria-label="Message:">Message: </label>
					<textarea name="body"><?php echo $_POST['body'] ?? '';?></textarea>
				</div>
				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary">
				</p>

			</form>
		</div>
				<?php

			}
		);
	}
);
