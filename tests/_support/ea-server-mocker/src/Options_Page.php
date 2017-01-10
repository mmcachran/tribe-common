<?php


class Tribe__Events__Aggregator_Mocker__Options_Page {

	public function hook() {
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
	}

	public function register_menu() {
		add_menu_page( 'Event Aggregator Server Mocker', 'EA Mocker', 'administrator', 'ea-mocker', array( $this, 'render' ) );
	}

	public function register_settings() {
		/**
		 * Filter this to add settings.
		 *
		 * @param array $settings
		 */
		$settings = apply_filters( 'ea_mocker-settings', array() );

		if ( ! empty( $settings ) ) {
			foreach ( $settings as $setting ) {
				register_setting( 'ea-mocker', $setting );
			}
		}
	}

	public function render() {
		?>
		<div class="wrap">
			<h1>Event Aggregator Server Mocker Settings</h1>
			<form method="post" action="options.php" id="ea-mocker">
				<?php settings_fields( 'ea-mocker' ); ?>
				<?php do_settings_sections( 'ea-mocker' ); ?>
				<table class="form-table">
					<?php
					/**
					 * Use this action to print your settings.
					 */
					do_action( 'ea_mocker-options_form' );
					?>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
	<?php }
}
