<?php

if (!defined('ABSPATH')) die('No direct access.');

if (!class_exists('Updraft_Notices_1_0')) require_once(METASLIDER_PATH.'admin/lib/Updraft_Notices.php');

/**
 * Meta Slider Notices
 */
class MetaSlider_Notices extends Updraft_Notices_1_0 {

	/**
	 * All Ads
	 *
	 * @var object $ads
	 */
    protected $ads;
    
	/**
	 * Notices content
	 *
	 * @var object $notices_content
	 */
	protected $notices_content;

	/**
	 * Populates ad content and loads assets
	 */
	public function __construct() {
		/*
		 * There are three options you can use to force ads to show. 
		 * The second two require the first to be set to true
		 * 
		 * define('METASLIDER_FORCE_NOTICES', true);
		 * define('METASLIDER_DISABLE_SEASONAL_NOTICES', true);
		 * 
		 * Be sure not to set both of these at the same time
		 * define('METASLIDER_FORCE_LITE_NOTICES', true);
		 * define('METASLIDER_FORCE_PRO_NOTICES', true);
		 * 
		 */
        $this->ads = metaslider_pro_is_installed() ? $this->pro_notices() : $this->lite_notices();
        
        // To avoid showing the user ads off the start, lets wait
        $this->notices_content = ($this->ad_delay_has_finished()) ? $this->ads : array();

        // If $notices_content is empty, we still want to offer seasonal ads
        if (empty($this->notices_content) && !metaslider_pro_is_installed()) {
            $this->notices_content = $this->valid_seasonal_notices();
		}
        
        add_action('admin_enqueue_scripts', array($this, 'add_notice_assets'));
        add_action('wp_ajax_notice_handler', array($this, 'ajax_notice_handler'));
        add_action('admin_notices', array($this, 'show_dashboard_notices'));
	}

	/**
	 * Handles assets for the notices
	 */
	public function add_notice_assets() {
        wp_enqueue_style('ml-slider-notices-css',  METASLIDER_ADMIN_URL . 'assets/css/notices.css', false, METASLIDER_VERSION);
        wp_localize_script('jquery', 'metaslider_notices', array(
            'handle_notices_nonce' => wp_create_nonce('metaslider_handle_notices_nonce')
        ));
	}

	/**
	 * Deprecated for MetaSlider for now
	 */
	public function notices_init() { return; }

	/**
     * Returns notices that free/lite users should see. dismiss_time should match the key
     * hide_time is in weeks. Use a string to hide for 9999 weeks.
     *
	 * @return array returns an array of notices
	 */
	protected function lite_notices() {

		if (defined('METASLIDER_FORCE_PRO_NOTICES') && METASLIDER_FORCE_PRO_NOTICES) {

			// Override to force pro, but make sure both overrides arent set
			return (!defined('METASLIDER_FORCE_LITE_NOTICES')) ? $this->pro_notices() : array();
		}

		return array_merge(array(
			'updraftplus' => array(
				'title' => __('Always backup WordPress to avoid losing your site!', 'ml-slider'),
				'text' => _x("UpdraftPlus is the world's #1 backup plugin from the makers of MetaSlider. Backup to the cloud, on a schedule and restore with 1 click!", 'Keep the plugin names "UpdraftPlus" and "MetaSlider" when possible', 'ml-slider'),
				'image' => 'updraft_logo.png',
				'button_link' => 'updraftplus_wordpress',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'updraftplus',
				'hide_time' => 12,
				'supported_positions' => array('header'),
				'validity_function' => 'is_updraftplus_installed',
			),
			'wp_optimize' => array(
				'title' => __('WP-Optimize - Clean, Compress, Cache.', 'ml-slider'),
				'text' => __('Make your site fast & efficient with our cutting-edge speed plugin.', 'ml-slider'),
				'image' => 'wp_optimize_logo.png',
				'button_link' => 'wp-optimize',
				'button_meta' => 'wp-optimize',
				'dismiss_time' => 'wp-optimize',
				'hide_time' => 12,
				'supported_positions' => array('header'),
				'validity_function' => 'is_wp_optimize_installed',
			),
			'updraftcentral' => array(
				'title' => __('Save Time and Money. Manage multiple WordPress sites from one location.', 'ml-slider'),
				'text' => _x('UpdraftCentral is a highly efficient way to take backup, update and manage multiple WP sites from one location', 'Keep the plugin name "UpdraftCentral" when possible', 'ml-slider'),
				'image' => 'updraft_logo.png',
				'button_link' => 'updraftcentral',
				'button_meta' => 'updraftcentral',
				'dismiss_time' => 'updraftcentral',
				'hide_time' => 12,
				'supported_positions' => array('header'),
				'validity_function' => 'is_updraftcentral_installed',
			),
			'rate_plugin' => array(
				'title' => _x('Like MetaSlider and have a minute to spare?', 'Keep the plugin name "MetaSlider" when possible', 'ml-slider'),
				'text' => _x('Please help MetaSlider by giving a positive review at wordpress.org.', 'Keep the plugin name "MetaSlider" when possible', 'ml-slider'),
				'image' => 'metaslider_logo.png',
				'button_link' => 'metaslider_rate',
				'button_meta' => 'review',
				'dismiss_time' => 'rate_plugin',
				'hide_time' => 12,
				'supported_positions' => array('header'),
			),
			'pro_layers' => array(
				'title' => __('Spice up your site with animated layers and video slides', 'ml-slider'),
				'text' => _x('With the MetaSlider Add-on pack you can give your slideshows a professional look!', 'Keep the plugin name "MetaSlider" when possible', 'ml-slider'),
				'image' => 'metaslider_logo.png',
				'button_link' => 'metaslider',
				'button_meta' => 'ml-slider',
				'dismiss_time' => 'pro_layers',
				'hide_time' => 12,
				'supported_positions' => array('header'),
			),
			'pro_features' => array(
				'title' => __('Increase your revenue and conversion with video slides and many more features', 'ml-slider'),
				'text' => __('Upgrade today to benefit from many more premium features. Find out more.', 'ml-slider'),
				'image' => 'metaslider_logo.png',
				'button_link' => 'metaslider',
				'button_meta' => 'ml-slider',
				'dismiss_time' => 'pro_features',
				'hide_time' => 12,
				'supported_positions' => array('header'),
			),
			'translation' => array(
				'title' => __('Can you translate? Want to improve MetaSlider for speakers of your language?', 'ml-slider'),
				'text' => __('Please go here for instructions - it is easy.', 'ml-slider'),
				'image' => 'metaslider_logo.png',
				'button_link' => 'metaslider_translate',
				'button_meta' => 'lets_start',
				'dismiss_time' => 'translation',
				'hide_time' => 12,
				'supported_positions' => array('header'),
				'validity_function' => 'translation_needed',
			),
			'thankyou' => array(
				'title' => _x('Thank you for installing MetaSlider', 'Keep the plugin name "MetaSlider" when possible', 'ml-slider'),
				'text' => __('Supercharge & secure your WordPress site with our other top plugins:', 'ml-slider'),
				'image' => 'metaslider_logo_large.png',
				'dismiss_time' => 'thankyou',
				'hide_time' => 52,
				'mega' => true,
				'supported_positions' => array('dashboard'),
			),
        ), $this->valid_seasonal_notices());
    }
    
	/**
	 * Premium user notices, if any. 
     *
	 * @return array
	 */
    protected function pro_notices() {

		if (defined('METASLIDER_FORCE_LITE_NOTICES') && METASLIDER_FORCE_LITE_NOTICES) {
			
			// Override to force pro, but make sure both overrides arent set
			return (!defined('METASLIDER_FORCE_PRO_NOTICES')) ? $this->lite_notices() : array();
		}

        return array();
    }
    
	/**
	 * Seasonal Notices. Note that if dismissed, they will stay dismissed for 9999 weeks. 
     * An empty string for 'hide_time' will show "Dismiss" instead of "Dismiss (12 weeks)"
     * Each year the key and dismiss time should be updated
     *
	 * @return array
	 */
    protected function seasonal_notices() {


        if (defined('METASLIDER_DISABLE_SEASONAL_NOTICES') && METASLIDER_DISABLE_SEASONAL_NOTICES) {
            return array();
		}
		
		$coupons = json_decode(file_get_contents(METASLIDER_PATH .'seasonal-discounts.json'), true);

        $coupon_object =  array(
			'blackfriday' => array(
				'title' => _x('Black Friday - 20% off the MetaSlider Add-on Pack until November 30th', 'Keep the phrase "MetaSlider Add-on Pack" when possible. Also, "Black Friday" is the name of an event in the United States', 'ml-slider'),
				'text' => __('To benefit, use this discount code:', 'ml-slider').' ',
				'image' => 'seasonal/black_friday.png',
				'button_link' => 'metaslider',
				'button_meta' => 'ml-slider',
                'hide_time' => '',
				'supported_positions' => array('header', 'dashboard'),
			),
			'christmas' => array(
				'title' => _x('Christmas sale - 20% off the MetaSlider Add-on Pack until December 25th', 'Keep the phrase "MetaSlider Add-on Pack" when possible', 'ml-slider'),
				'text' => __('To benefit, use this discount code:', 'ml-slider').' ',
				'image' => 'seasonal/christmas.png',
				'button_link' => 'metaslider',
				'button_meta' => 'ml-slider',
                'hide_time' => '',
				'supported_positions' => array('header', 'dashboard'),
			),
			'newyear' => array(
				'title' => _x('Happy New Year - 20% off the MetaSlider Add-on Pack until January 14th', 'Keep the phrase "MetaSlider Add-on Pack" when possible', 'ml-slider'),
				'text' => __('To benefit, use this discount code:', 'ml-slider').' ',
				'image' => 'seasonal/new_year.png',
				'button_link' => 'metaslider',
				'button_meta' => 'ml-slider',
                'hide_time' => '',
				'supported_positions' => array('header', 'dashboard'),
			),
			'spring' => array(
				'title' => _x('Spring sale - 20% off the MetaSlider Add-on Pack until April 30th', 'Keep the phrase "MetaSlider Add-on Pack" when possible', 'ml-slider'),
				'text' => __('To benefit, use this discount code:', 'ml-slider').' ',
				'image' => 'seasonal/spring.png',
				'button_link' => 'metaslider',
				'button_meta' => 'ml-slider',
                'hide_time' => '',
				'supported_positions' => array('header', 'dashboard'),
			),
			'summer' => array(
				'title' => _x('Summer sale - 20% off the MetaSlider Add-on Pack until July 31st', 'Keep the phrase "MetaSlider Add-on Pack" when possible', 'ml-slider'),
				'text' => __('To benefit, use this discount code:', 'ml-slider').' ',
				'image' => 'seasonal/summer.png',
				'button_link' => 'metaslider',
				'button_meta' => 'ml-slider',
                'hide_time' => '',
				'supported_positions' => array('header', 'dashboard'),
			),
			'collection' => array(
				'title' => __('The Updraft Plugin Collection Sale', 'ml-slider'),
				'text' => __('Get 20% off any of our plugins. But hurry - offer ends 30th September, use this discount code:', 'ml-slider').' ',
				'image' => 'metaslider_logo.png',
				'button_link' => 'https://teamupdraft.com',
				'button_meta' => 'collection',
				'hide_time' => '',
				'supported_positions' => array('header', 'dashboard'),
			)
		);
	
		return array_map(array($this, 'prepare_notice_fields'), array_merge_recursive($coupon_object, $coupons));
    }

	/**
	 * Add fields needed for an notice to show
	 * 
	 * @param string $notice - the name of the notice
	 * @return array
	 */
    public function prepare_notice_fields($notice) {
		if (!isset($notice['dismiss_time'])) {
			$notice['dismiss_time'] = $notice['discount_code'];
		}
		return $notice;
	}

	/**
	 * These appear inside a mega ad. 
     *
	 * @return string
	 */
    protected function mega_notice_parts() {
        return array(
			'ms_pro' => array(
				'title' => _x('MetaSlider Add-on Pack:', 'Keep the phrase "MetaSlider Add-on Pack" when possible', 'ml-slider'), 
				'text' => __('Increase your conversion rate with video slides and many more options.', 'ml-slider'),
				'image' => '',
				'button_link' => 'metaslider',
                'button_meta' => 'ml-slider',
			),
			'udp' => array(
				'title' => _x('UpdraftPlus', 'Keep the plugin name "UpdraftPlus" when possible', 'ml-slider'), 
				'text' => __('simplifies backups and restoration. It is the world\'s highest ranking and most popular scheduled backup plugin, with over a million currently-active installs.', 'ml-slider'),
				'image' => '',
				'button_link' => 'updraftplus_wordpress',
                'button_meta' => 'updraftplus',
			),
			'wpo' => array(
				'title' => _x('WP-Optimize', 'Keep the plugin name "WP-Optimize" when possible', 'ml-slider'), 
				'text' => __('auto-clean your WordPress database so that it runs at maximum efficiency.', 'ml-slider'),
				'image' => '',
				'button_link' => 'wp_optimize_wordpress',
                'button_meta' => 'wp-optimize',
			),
			'updraftcentral' => array(
				'title' => _x('UpdraftCentral', 'Keep the plugin name "UpdraftCentral" when possible', 'ml-slider'), 
				'text' => __('is a highly efficient way to manage, update and backup multiple websites from one place.', 'ml-slider'),
				'image' => '',
				'button_link' => 'updraftcentral',
                'button_meta' => 'updraftcentral',
			),
        );
    }

	/**
	 * Check to see if UpdraftPlus is installed
     *
	 * @return bool 
	 */
	protected function is_updraftplus_installed() {
		return parent::is_plugin_installed('updraftplus', false);
    }

	/**
	 * Check to see if wp-optimize is installed
     *
	 * @return bool 
	 */
	protected function is_wp_optimize_installed() {
		return parent::is_plugin_installed('wp-optimize', false);
    }
    
	/**
	 * Check to see if UpdraftCentral is installed
     *
	 * @return bool 
	 */
	protected function is_updraftcentral_installed() {
		return parent::is_plugin_installed('updraftcentral', false);
	}

	/**
	 * Checks if the user agent isn't set as en_GB or en_US, and if the language file doesn't exist
     *
	 * @param  string $plugin_base_dir The plguin base directory
	 * @param  string $product_name    Product name
	 * @return bool
	 */
	protected function translation_needed($plugin_base_dir = null, $product_name = null) {
		return parent::translation_needed(METASLIDER_PATH, 'ml-slider');
	}
	
	/**
	 * This method checks to see if the ad has been dismissed
     *
	 * @param string $ad_identifier - identifier for the ad
	 * @return bool returns true when we dont want to show the ad
	 */
	protected function check_notice_dismissed($ad_identifier) {
		if ($this->force_ads()) {
			return false;
		}
		return (time() < get_option("ms_hide_{$ad_identifier}_ads_until"));
    }
	
	/**
	 * Returns all active seasonal ads
     *
	 * @return array
	 */
	protected function valid_seasonal_notices() {
        $valid = array();
        $time_now = time();
        // $time_now = strtotime('2020-11-20 00:00:01'); // Black Friday
        // $time_now = strtotime('2020-12-01 00:00:01'); // XMAS
        // $time_now = strtotime('2020-12-26 00:00:01'); // NY
        // $time_now = strtotime('2020-04-01 00:00:01'); // Spring
        // $time_now = strtotime('2020-07-01 00:00:01'); // Summer
        foreach($this->seasonal_notices() as $ad_identifier => $notice) {
            $valid_from = strtotime($notice['valid_from']);
            $valid_to = strtotime($notice['valid_to']);
            if ($valid_from < $time_now && $time_now <= $valid_to) {
                $valid[$ad_identifier] = $notice;
            }
        }
        return $valid;
    }

    /**
	 * The logic is handled elsewhere. This being true does not skip
     * the seasonal notices. Overrides parent function
     *
     * @param array $notice_data Notice data
	 * @return array
	 */
    protected function skip_seasonal_notices($notice_data) {
        return !$this->check_notice_dismissed($notice_data['dismiss_time']);
    }

	/**
	 * Checks whether this is an ad page - hard-coded
     *
	 * @return bool 
	 */
	protected function is_page_with_ads() {
        global $pagenow;
        $page = isset($_GET['page']) ? $_GET['page'] : '';

        // I'm thinking to limit the check to the actual settings page for now
        // This way, if they activate the plugin but don't start using it until
        // a few weeks after, it won't bother them with ads.
		// return ('index.php' === $pagenow) || ($page === 'metaslider');
		return ($page === 'metaslider');
    }

	/**
     * This method checks to see if the ad waiting period is over (2 weeks)
     * If not, it will set a two week time
     *
	 * @return bool returns true when we dont want to show the ad
	 */
	protected function ad_delay_has_finished() {
		
		if ($this->force_ads()) {

			// If there's an override, return true
			return true;
		}

        if (metaslider_pro_is_installed()) {

            // If they are pro don't check anything but show the pro ad.
            return true;
        }

        // The delay could be empty, ~2 weeks (initial delay) or ~12 weeks
        $delay = get_option("ms_hide_all_ads_until");

        if (!$this->is_page_with_ads() && !$delay) {

            // Only start the timer if they see a page that can serve ads
            return false;
        }

        if (!$delay) {

            // Set the delay for when they will first see an ad, 2 weeks; returns false
            return !update_option("ms_hide_all_ads_until", time() + 2*7*86400);
        } else if ((time() > $delay) && !get_option("ms_ads_first_seen_on")) {

            // Serve ads now, and note the time they first saw ads
            update_option("ms_ads_first_seen_on", time());

            // Now that they can see ads, make sure the rate_plugin is shown first.
            // Since this shows after 2 weeks, it's better timing.
            $notices = $this->lite_notices();
            $this->ads = array('rate_plugin' => $notices['rate_plugin']);
            return true;
        } else if (time() < $delay) {

            // This means an ad was dismissed and there's a delay
            return false;
        } else if (get_option("ms_ads_first_seen_on")) {

            // This means the initial delay has elapsed, 
            // and the dismissed period expired
            return true;
        }

        // Default to not show an ad, in case there's some error
		return false;
    }
    
    /**
     * Method to handle dashboard notices
     */
    public function show_dashboard_notices() {
        $current_page = get_current_screen();
        if ('dashboard' === $current_page->base && metaslider_user_is_ready_for_notices()) {

            // Override the delay to show the thankyou notice on activation
            // if (!empty($_GET['ms_activated'])) {
	        // $lite_notices = $this->lite_notices();
	        // $this->notices_content['thankyou'] = $lite_notices['thankyou'];
            // }
            echo $this->do_notice(false, 'dashboard', true); 
        }
    }

	/**
	 * Selects the template and returns or displays the notice
     *
	 * @param array  $notice_information     - variable names/values to pass through to the template
	 * @param bool   $return_instead_of_echo - whether to 
	 * @param string $position               - where the notice is being displayed
	 * @return null|string - depending on the value of $return_instead_of_echo
	 */
	protected function render_specified_notice($notice_information, $return_instead_of_echo = false, $position = 'header') {
		$views = array(
			'header' => 'header-notice.php',
			'dashboard' => 'dashboard-notice.php',
        );
		$view = isset($views[$position]) ? $views[$position] : 'header-notice.php';
		return $this->include_template($view, $return_instead_of_echo, $notice_information);
	}

	/**
	 * Displays or returns the template
     *
	 * @param string $path                   file name of the template
	 * @param bool   $return_instead_of_echo Return the template instead of printing
	 * @param array  $args                   template arguments
	 * @return null|string
	 */
	public function include_template($path, $return_instead_of_echo = false, $args = array()) {
		if ($return_instead_of_echo) ob_start();

        extract($args);
        if (is_int($hide_time)) {
            $hide_time = $hide_time . ' ' . __('weeks', 'ml-slider');
        }
		include METASLIDER_PATH.'admin/views/notices/'.$path;

		if ($return_instead_of_echo) return ob_get_clean();
	}

	/**
	 * Builds a link based on the type of notice being requested
     *
	 * @param string $link - the URL to link to
	 * @param string $type - which notice is being displayed
	 * @return string - the resulting HTML
	 */
	public function get_button_link($link, $type) {
		$messages = array(
			'updraftplus' => _x('Get UpdraftPlus', 'Keep the plugin name "UpdraftPlus" when possible', 'ml-slider'),
			'wp-optimize' => _x('Optimize today', 'This refers to WP_Optimize, but please translate "optimize" accordingly', 'ml-slider'),
			'updraftcentral' => _x('Get UpdraftCentral', 'Keep the plugin name "UpdraftCentral" when possible', 'ml-slider'),
			'lets_start' => __('Let\'s Start', 'ml-slider'),
			'review' => _x('Review MetaSlider', 'Keep the plugin name "MetaSlider" when possible', 'ml-slider'),
			'ml-slider' => __('Find out more', 'ml-slider'),
			'signup' => __('Sign up', 'ml-slider'),
			'go_there' => __('Go there', 'ml-slider')
		);
		$message = isset($messages[$type]) ? $messages[$type] : __('Read more', 'ml-slider');

		return '<a class="updraft_notice_link underline text-blue-dark" href="' . $this->get_notice_url($link) . '">' . $message . '</a>';
	}

	/**
	 * Handles any notice related ajax calls
     *
	 * @return void
	 */
	public function ajax_notice_handler() {
		if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'metaslider_handle_notices_nonce')) {
			wp_send_json_error(array(
                'message' => __('The security check failed. Please refresh the page and try again.', 'ml-slider')
			), 401);
		}

		if (is_wp_error($ad_data = $this->ad_exists($_POST['ad_identifier']))) {
			wp_send_json_error(array(
                'message' => __('This item does not exist. Please refresh the page and try again.', 'ml-slider')
			), 401);
		}
        
		$result = $this->dismiss_ad($ad_data['dismiss_time'], $ad_data['hide_time']);
        
		if (is_wp_error($result)) {
			wp_send_json_error(array(
				'message' => $result->get_error_message()
			), 409);
		}
		
		wp_send_json_success(array(
            'message' => __('The option was successfully updated', 'ml-slider'),
        ), 200);
    }
  
	/**
     * Returns the available ads that havent been dismissed by the user
     *
	 * @param string|array $location     the location for the ad
	 * @param boolean      $bypass_delay Bypass the ad delay
	 * @return array the identifier for the ad
	 */
	public function active_ads($location = 'header', $bypass_delay = false) {
        $dismissed_ads = array();

        $ads = ($bypass_delay) ? $this->ads : $this->notices_content;

        // Filter through all site options (cached)
        foreach(wp_load_alloptions() as $key => $value){
            if (strpos($key, 'ms_hide_') && strpos($key, '_ads_until')) {
                $key = str_replace(array('ms_hide_', '_ads_until'), '', $key);
                 $dismissed_ads[$key] = $value;
            }
        }
        
        // Filter out if the dismiss time has expired, then compare to the database
        $valid_ads = array();
        foreach ($ads as $ad_identifier => $values) {
            $is_valid = isset($values['validity_function']) ? call_user_func(array($this, $values['validity_function'])) : true;
            $not_dismissed = !$this->check_notice_dismissed($ad_identifier);
            $is_supported = in_array($location, $values['supported_positions']);
            if ($is_valid && $not_dismissed && $is_supported) {
                $valid_ads[$ad_identifier] = $values;
            }
        }

        return array_diff_key($valid_ads, $dismissed_ads);
    }
  
	/**
     * Returns all possible ads or the specified identifier
     *
     * @param string|null $ad_identifier Ad Identifier
	 * @return string|null the data of the ad
	 */
	public function get_ad($ad_identifier = null) {
        $all_notices = array_merge($this->pro_notices(), $this->lite_notices());
        return is_null($ad_identifier) ? $all_notices : $all_notices['ad_identifier'];
    } 
  
	/**
     * Checks if the ad identifier exists in any of the ads above
     *
     * @param string $ad_identifier Ad Identifier
	 * @return bool the data of the ad
	 */
	public function ad_exists($ad_identifier) {
		$all_notices = array_merge($this->pro_notices(), $this->lite_notices());
		if (isset($all_notices[$ad_identifier])) {
			return $all_notices[$ad_identifier];
		}

		// Handle seasonal notices
		$ad_data = array();
		foreach ($all_notices as $notice) {
			if (isset($notice['discount_code']) && $notice['discount_code'] === $ad_identifier)
			$ad_data = $notice;
		}
        return $ad_data ? $ad_data : new WP_Error('bad_call', __('The requested data does not exist.', 'ml-slider'), array('status' => 401));
    } 

	/**
     * Updates the stored value for how long to hide the ads
     *
     * @param string     $ad_identifier Ad Identifier
     * @param int|string $weeks         time in weeks or a string to show
	 * @return bool|WP_Error whether the update was a success
	 */
	public function dismiss_ad($ad_identifier, $weeks) {

        // If the time isn't specified it will hide "forever" (9999 weeks)
        // Update 12/18/2017 - will set this an extra week, so that this individual ad will hide, for example, 13 weeks, while ALL ads (minus seasonal) will hide for 12 weeks. This ensures that the user doesn't see the same ad twice. Minor detail.
        $weeks = is_int($weeks) ? $weeks + 1 : 9999;
        
        $result = update_option("ms_hide_{$ad_identifier}_ads_until", time() + $weeks*7*86400);
        
        // Update 12/18/2017 - Hide all ads for 12 weeks (this used to be 24 hours)
        // This skips over the scenario when a user has seen a seasonal ad within the 2 week grace period. That way we can still show them the "rate plugin" ad after 2 weeks.
        if (get_option("ms_ads_first_seen_on")) {
            update_option("ms_hide_all_ads_until", time() + 12*7*86400);
        }
        
		return $result ? $result : new WP_Error('update_failed', __('The attempt to update the option failed.', 'ml-slider'), array('status' => 409));
    }
    
/**
 * Returns the url for a notice link
 *
 * @param string $link_id the link to get the url
 * @return string the url for the link id
 */
	public function get_notice_url($link_id) {
		$urls = array(
			'metaslider' => apply_filters('metaslider_hoplink', 'https://www.metaslider.com/upgrade'),
			'metaslider_rate' => 'https://wordpress.org/support/plugin/ml-slider/reviews?rate=5#new-post',
			'metaslider_survey' => 'https://www.metaslider.com/survey',
			'metaslider_survey_pro' => 'https://www.metaslider.com/survey-pro',
			'metaslider_translate' => 'https://translate.wordpress.org/projects/wp-plugins/ml-slider',
			'updraftplus' => apply_filters('updraftplus_com_link', 'https://updraftplus.com'),
			'updraftplus_wordpress' => 'https://wordpress.org/plugins/updraftplus/',
			'updraftcentral' => 'https://updraftcentral.com',
			'wp_optimize' => 'https://getwpo.com',
			'wp_optimize_wordpress' => 'https://wordpress.org/plugins/wp-optimize/'
		);

		// Return the website url if the ID was not set
		if (!isset($urls[$link_id])) return 'https://www.metaslider.com';
		
		// Return if analytics code is already set
		if (strpos($urls[$link_id], 'utm_source')) return esc_url($urls[$link_id]);

		// Add our analytics code
		return esc_url(add_query_arg(array(
			'utm_source' => 'metaslider-plugin-page',
			'utm_medium' => 'banner'
		), $urls[$link_id]));
	}

	/**
	 * Forces ads to show when any override is set
	 */
	private function force_ads() {
		return (defined('METASLIDER_FORCE_NOTICES') && METASLIDER_FORCE_NOTICES) ||
			(defined('METASLIDER_FORCE_PRO_NOTICES') && METASLIDER_FORCE_PRO_NOTICES) ||
			(defined('METASLIDER_FORCE_LITE_NOTICES') && METASLIDER_FORCE_LITE_NOTICES) ||
			(defined('METASLIDER_DISABLE_SEASONAL_NOTICES') && METASLIDER_DISABLE_SEASONAL_NOTICES);
	}
}
