<?php
namespace ToolsetCommonEs\Block\Style\Responsive;

use ToolsetCommonEs\Block\Style\Responsive\Devices\Devices;

class ToolsetSettings {

	/** @var Devices */
	private $devices;

	/**
	 * ToolsetSettings constructor.
	 *
	 * @param Devices $devices
	 */
	public function __construct( Devices $devices ) {
		$this->devices = $devices;
	}

	/**
	 * @filter toolset_filter_toolset_register_settings_general_section
	 *
	 * @param array $sections
	 *
	 * @return mixed
	 */
	public function callback_toolset_filter_toolset_register_settings_general_section( $sections ) {
		// Make sure WP Rest is running.
		rest_get_server();

		wp_enqueue_script(
			'toolset-common-es-settings',
			TOOLSET_COMMON_ES_URL . 'public/toolset-common-es-settings.js',
			[ 'wp-api-fetch' ],
			TOOLSET_COMMON_ES_LOADED,
			true // Enqueue the script in the footer.
		);

		wp_localize_script(
			'toolset-common-es-settings',
			'toolsetCommonEsSettings',
			array( 'rest' => rest_url('wp/v2/tutorial'))
		);

		$devices = $this->devices->get();

		// Sort devices by default max width. Lowest first.
		uasort( $devices, function( $a, $b ) {
			// PHP INT MAX if there is no default max width (desktop).
			$a_max_width = isset( $a['defaultMaxWidth'] ) ? $a['defaultMaxWidth'] : PHP_INT_MAX;
			$b_max_width = isset( $b['defaultMaxWidth'] ) ? $b['defaultMaxWidth'] : PHP_INT_MAX;

			return $a_max_width > $b_max_width;
		} );

		$section_content = '<div class="tces-settings-rwd-devices">';
		$zindex = 21;

		foreach( $devices as $device_key => $device_info ) {
			$zindex--;
			$section_content .= '<div class="tces-settings-rwd-device" style="z-index: '.$zindex.';">';

			$value = array_key_exists( 'maxWidth', $device_info ) &&
					 array_key_exists( 'defaultMaxWidth', $device_info ) &&
					 $device_info['maxWidth'] !== $device_info['defaultMaxWidth'] ?
				$device_info['maxWidth'] :
				'';

			$section_content .= '<h3 style="margin-bottom: -5px;"><span class="dashicons dashicons-' . $device_info['icon'] . '"></span>' . $device_info['label'] .'</h3>';

			if( $device_key !== Devices::DEVICE_DESKTOP ) {
				$section_content .= '<div class="tces-settings-rwd-device-input">' .
										'<input class="js-wpv-rwd-device" type="number" min="1" max="2000" ' .
									    'data-device-key="' . $device_key . '" name="devices['.$device_key.'][maxWidth]" ' .
										'placeholder="' . $device_info['defaultMaxWidth'] . '" ' .
										'class="js-wpv-editing-experience-option" value="' . $value . '" /> px' .
									'</div>';
			}
			$section_content .= '</div>';
		}

		$section_content .= '</div>';

		$section_content .= '<div class="tces-settings-rwd-error">' .
								'<span class="notice notice-error notice-alt"></span>' .
							'</div>';

		// Theme defaults
		/* Disabled as we decided to use the core WP Columns breakpoints instead

			if( isset( $devices[Devices::DEVICE_TABLET]['theme'] ) ) {
			$section_content .= '<p class="description wpcf-form-description tces-settings-rwd-description"> ' .
								sprintf( __( '* The theme (%s) prefers these breakpoints: '), $devices[Devices::DEVICE_TABLET]['theme'] ) .
								'<span class="dashicons dashicons-' . $devices[Devices::DEVICE_PHONE]['icon'] . '"></span>'. $devices[Devices::DEVICE_PHONE]['defaultMaxWidth'].' px ' .
								'<span class="dashicons dashicons-' . $devices[Devices::DEVICE_TABLET]['icon'] . '"></span>'. $devices[Devices::DEVICE_TABLET]['defaultMaxWidth'].' px' .
								'</p>';
		}
		*/

		$section_content .= '<p class="description wpcf-form-description tces-settings-rwd-description"> ' .
							__( '* By default the WordPress Columns breakpoints are used: ', 'wpv-views' ) .
							'<span class="dashicons dashicons-' . $devices[Devices::DEVICE_PHONE]['icon'] . '"></span>'. $devices[Devices::DEVICE_PHONE]['defaultMaxWidth'].' px ' .
							'<span class="dashicons dashicons-' . $devices[Devices::DEVICE_TABLET]['icon'] . '"></span>'. $devices[Devices::DEVICE_TABLET]['defaultMaxWidth'].' px' .
							'</p>';


		$sections['responsive-breakpoints'] = array(
			'slug'		=> 'responsive-breakpoints',
			'title'		=> __( 'Responsive web design breakpoints for Toolset Blocks', 'wpv-views' ),
			'content'	=> $section_content
		);

		return $sections;
	}
}
