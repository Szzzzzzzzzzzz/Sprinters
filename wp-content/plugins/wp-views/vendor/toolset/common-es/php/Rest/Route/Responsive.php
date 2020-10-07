<?php

namespace ToolsetCommonEs\Rest\Route;

use ToolsetCommonEs\Block\Style\Responsive\Devices\Devices;
use ToolsetCommonEs\Library\WordPress\User;

class Responsive extends ARoute {
	protected $name = 'Responsive';

	protected $version = 1;

	/** @var Devices */
	private $devices;

	/**
	 * Settings constructor.
	 *
	 * @param User $wp_user
	 * @param Devices $devices
	 */
	public function __construct( User $wp_user, Devices $devices ) {
		parent::__construct( $wp_user );

		$this->devices = $devices;
	}

	public function callback( \WP_REST_Request $rest_request ) {
		$params = $rest_request->get_json_params();

		if( ! is_array( $params ) ||
			! isset( $params['devices'] ) ||
			! isset( $params['action'] )
		) {
			return array( 'error' => __( 'Invalid input.', 'wpv-views' ) );
		}

		$devices = $this->devices->get();
		$devices = toolset_array_merge_recursive_distinct( $devices, $params['devices'] );

		$phone_max_width = $devices['phone']['maxWidth'] ?: $devices['phone']['defaultMaxWidth'];
		$tablet_max_width = $devices['tablet']['maxWidth'] ?: $devices['tablet']['defaultMaxWidth'];

		if( $phone_max_width >= $tablet_max_width ) {
			return array(
				'error' => __( 'Tablet width should be larger than phone width.', 'wpv-views' )
			);
		}

		switch( $params['action'] ) {
			case 'update':
				foreach( $params['devices'] as $device_key => $device ) {
					$this->devices->set( $device_key, 'maxWidth', $device['maxWidth'] );
				}

				return 1;
		}

		return array(
			'error' => __( 'Invalid action.', 'wpv-views' )
		);
	}

	public function permission_callback() {
		// @todo check for Toolset Access permissions
		return $this->wp_user->current_user_can( 'edit_posts' );
	}
}
