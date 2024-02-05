<?php

/**
 * Plugin Name: Resend by Aegis
 * Description: Send mail with Resend.
 * Version: 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require __DIR__ . '/vendor/autoload.php';

if ( ! class_exists( 'Aegis_Resend' ) ) {

	class Aegis_Resend {

		public function __construct() {
			include_once 'menu/settings-page.php';
			include_once 'menu/tools-page.php';
		}
	}
}

new Aegis_Resend();
