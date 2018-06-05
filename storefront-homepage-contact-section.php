<?php
/**
 * Plugin Name:         Storefront Homepage Contact Section
 * Plugin URI:          http://woothemes.com/products/storefront-homepage-contact-section/
 * Description:         Adds a simple contact section to your Storefront powered site.
 * Version:             1.0.5
 * Author:              WooThemes
 * Author URI:          http://woothemes.com/
 * Requires at least:   4.0
 * Tested up to:        4.9
 *
 * Text Domain: storefront-homepage-contact-section
 * Domain Path: /languages/
 *
 * @package Storefront_Homepage_Contact_Section
 * @category Core
 * @author Tiago Noronha
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Returns the main instance of Storefront_Homepage_Contact_Section to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Storefront_Homepage_Contact_Section
 */
function Storefront_Homepage_Contact_Section() {
	return Storefront_Homepage_Contact_Section::instance();
} // End Storefront_Homepage_Contact_Section()

Storefront_Homepage_Contact_Section();

/**
 * Main Storefront_Homepage_Contact_Section Class
 *
 * @class Storefront_Homepage_Contact_Section
 * @version	1.0.0
 * @since 1.0.0
 * @package	Storefront_Homepage_Contact_Section
 */
final class Storefront_Homepage_Contact_Section {
	/**
	 * Storefront_Homepage_Contact_Section The single instance of Storefront_Homepage_Contact_Section.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $token;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $version;

	// Admin - Start
	/**
	 * The admin object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $admin;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct() {
		$this->token 			= 'storefront-homepage-contact-section';
		$this->plugin_url 		= plugin_dir_url( __FILE__ );
		$this->plugin_path 		= plugin_dir_path( __FILE__ );
		$this->version 			= '1.0.5';

		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'shcs_load_plugin_textdomain' ) );

		add_action( 'init', array( $this, 'shcs_setup' ) );
	}

	/**
	 * Main Storefront_Homepage_Contact_Section Instance
	 *
	 * Ensures only one instance of Storefront_Homepage_Contact_Section is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Storefront_Homepage_Contact_Section()
	 * @return Main Storefront_Homepage_Contact_Section instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	} // End instance()

	/**
	 * Load the localisation file.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function shcs_load_plugin_textdomain() {
		load_plugin_textdomain( 'storefront-homepage-contact-section', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	}

	/**
	 * Installation.
	 * Runs on activation. Logs the version number and assigns a notice message to a WordPress option.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install() {
		$this->_log_version_number();

		// get theme customizer url
		$url = admin_url() . 'customize.php?';
		$url .= 'url=' . urlencode( site_url() . '?storefront-customizer=true' ) ;
		$url .= '&return=' . urlencode( admin_url() . 'plugins.php' );
		$url .= '&storefront-customizer=true';

		$notices 		= get_option( 'shcs_activation_notice', array() );
		$notices[]		= sprintf( __( '%sThanks for installing the Storefront Homepage Contact Section extension. To get started, visit the %sCustomizer%s.%s %sOpen the Customizer%s', 'storefront-homepage-contact-section' ), '<p>', '<a href="' . esc_url( $url ) . '">', '</a>', '</p>', '<p><a href="' . esc_url( $url ) . '" class="button button-primary">', '</a></p>' );

		update_option( 'shcs_activation_notice', $notices );
	}

	/**
	 * Log the plugin version number.
	 * @access  private
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number() {
		// Log the version number.
		update_option( $this->token . '-version', $this->version );
	}

	/**
	 * Setup all the things.
	 * Only executes if Storefront or a child theme using Storefront as a parent is active and the extension specific filter returns true.
	 * Child themes can disable this extension using the storefront_homepage_contact_section_supported filter
	 * @return void
	 */
	public function shcs_setup() {

		if ( 'storefront' == get_option( 'template' ) && apply_filters( 'storefront_homepage_contact_section_supported', true ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'shcs_styles' ), 999 );
			add_action( 'customize_register', array( $this, 'shcs_customize_register' ) );
			add_action( 'admin_notices', array( $this, 'shcs_customizer_notice' ) );
			add_action( 'homepage', array( $this, 'storefront_homepage_contact_section' ), 90 );
			add_filter( 'body_class', array( $this, 'body_classes' ) );

			// Hide the 'More' section in the customizer
			add_filter( 'storefront_customizer_more', '__return_false' );
		} else {
			add_action( 'admin_notices', array( $this, 'shcs_install_storefront_notice' ) );
		}
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @since  1.0.5
	 * @param  array $classes Classes for the body element.
	 * @return array
	 */
	public function body_classes( $classes ) {
		global $storefront_version;

		if ( version_compare( $storefront_version, '2.3.0', '>=' ) ) {
			$classes[] = 'storefront-2-3';
		}

		return $classes;
	}

	/**
	 * Admin notice
	 * Checks the notice setup in install(). If it exists display it then delete the option so it's not displayed again.
	 * @since   1.0.0
	 * @return  void
	 */
	public function shcs_customizer_notice() {
		$notices = get_option( 'shcs_activation_notice' );

		if ( $notices = get_option( 'shcs_activation_notice' ) ) {

			foreach ( $notices as $notice ) {
				echo '<div class="notice is-dismissible updated">' . $notice . '</div>';
			}

			delete_option( 'shcs_activation_notice' );
		}
	}

	/**
	 * Storefront install
	 * If the user activates the plugin while having a different parent theme active, prompt them to install Storefront.
	 * @since   1.0.0
	 * @return  void
	 */
	public function shcs_install_storefront_notice() {
		echo '<div class="notice is-dismissible updated">
				<p>' . __( 'Storefront Homepage Contact Section requires that you use Storefront as your parent theme.', 'storefront-homepage-contact-section' ) . ' <a href="' . esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-theme&theme=storefront' ), 'install-theme_storefront' ) ) .'">' . __( 'Install Storefront now', 'storefront-homepage-contact-section' ) . '</a></p>
			</div>';
	}

	/**
	 * Customizer Controls and settings
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function shcs_customize_register( $wp_customize ) {
		/**
	     * Add a new section
	     */
        $wp_customize->add_section( 'shcs_section' , array(
		    'title'      	=> __( 'Contact Section', 'storefront-homepage-contact-section' ),
		    'priority'   	=> 55,
		) );

		/**
		 * Address
		 */
		$wp_customize->add_setting( 'shcs_contact_address', array(
			'default'			=> '',
			'sanitize_callback'	=> 'wp_filter_post_kses'
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'shcs_contact_address', array(
			'label'         => __( 'Address', 'storefront-homepage-contact-section' ),
			'description'   => __( 'Enter contact address. This address will be used to generate a map displayed as a background to the Contact section.', 'storefront-homepage-contact-section' ),
			'section'       => 'shcs_section',
			'settings'      => 'shcs_contact_address',
			'type'          => 'textarea',
			'priority'      => 10,
		) ) );

		/**
		 * Phone Number
		 */
		$wp_customize->add_setting( 'shcs_contact_phone_number', array(
			'default'			=> '',
			'sanitize_callback'	=> 'sanitize_text_field'
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'shcs_contact_phone_number', array(
			'label'         => __( 'Phone Number', 'storefront-homepage-contact-section' ),
			'description'   => __( 'Enter phone number.', 'storefront-homepage-contact-section' ),
			'section'       => 'shcs_section',
			'settings'      => 'shcs_contact_phone_number',
			'type'          => 'text',
			'priority'      => 20,
		) ) );

		/**
		 * Email Address
		 */
		$wp_customize->add_setting( 'shcs_contact_email_address', array(
			'default'			=> '',
			'sanitize_callback'	=> 'sanitize_text_field'
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'shcs_contact_email_address', array(
			'label'         => __( 'Email Address', 'storefront-homepage-contact-section' ),
			'description'   => __( 'Enter email address.', 'storefront-homepage-contact-section' ),
			'section'       => 'shcs_section',
			'settings'      => 'shcs_contact_email_address',
			'type'          => 'text',
			'priority'      => 30,
		) ) );

		/**
		 * Google Static Maps API Key
		 */
		$wp_customize->add_setting( 'shcs_api_key', array(
			'default'			=> '',
			'sanitize_callback'	=> 'sanitize_text_field'
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'shcs_api_key', array(
			'label'         => __( 'API key', 'storefront-homepage-contact-section' ),
			'description'   => sprintf(__( 'Enter your %sGoogle maps API keys%s', 'storefront-homepage-contact-section'), '<a href="https://developers.google.com/maps/documentation/static-maps/get-api-key">', '</a>' ),
			'section'       => 'shcs_section',
			'settings'      => 'shcs_api_key',
			'type'          => 'text',
			'priority'      => 40,
		) ) );

		/**
		 * Contact Form Heading
		 */
		if ( class_exists( 'Arbitrary_Storefront_Control' ) ) {
			$wp_customize->add_control( new Arbitrary_Storefront_Control( $wp_customize, 'shcs_contact_form_heading', array(
				'section'  	=> 'shcs_section',
				'type'		=> 'heading',
				'label'		=> __( 'Contact form', 'storefront-homepage-contact-section' ),
				'priority' 	=> 50,
			) ) );
		}

		/**
		 * Contact Form Jetpack Message
		 */
		$jetpack_message = '';
		if ( ! class_exists( 'Jetpack' ) ) {
			$jetpack_message = sprintf( __( 'To enable the Contact Form feature, please install the %sJetpack%s plugin and %sactivate the Contact Form module%s.', 'storefront-homepage-contact-section' ), '<a href="https://wordpress.org/plugins/jetpack/">', '</a>', '<a href="https://jetpack.me/support/activate-and-deactivate-modules/">', '</a>' );
		} elseif ( class_exists( 'Jetpack' ) && ! Jetpack::is_module_active( 'contact-form' ) ) {
			$jetpack_message = sprintf( __( 'To enable the Contact Form feature, please %sactivate the Contact Form module%s in the Jetpack plugin.', 'storefront-homepage-contact-section' ), '<a href="https://jetpack.me/support/activate-and-deactivate-modules/">', '</a>' );
		}

		if ( '' !== $jetpack_message && class_exists( 'Arbitrary_Storefront_Control' ) ) {
			$wp_customize->add_control( new Arbitrary_Storefront_Control( $wp_customize, 'shcs_contact_jetpack_warning', array(
				'section'		=> 'shcs_section',
				'type'			=> 'text',
				'description'	=> $jetpack_message,
				'priority'		=> 60,
			) ) );
		}

		/**
		 * Contact Form
		 */
		if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'contact-form' ) ) {
			$wp_customize->add_control( new Arbitrary_Storefront_Control( $wp_customize, 'shcs_contact_form_information', array(
				'section'		=> 'shcs_section',
				'type'			=> 'text',
				'description'	=> sprintf( __( 'All responses will be listed in the %sFeedback%s section of your WordPress Admin.', 'storefront-homepage-contact-section' ), '<a href="' . esc_url( admin_url( 'edit.php?post_type=feedback' )  ) . '">', '</a>' ),
				'priority'		=> 70,
			) ) );

			$wp_customize->add_setting( 'shcs_contact_form', array(
				'default'			=> true,
				'sanitize_callback'	=> 'absint',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'shcs_contact_form', array(
				'label'			=> __( 'Display contact form', 'storefront-homepage-contact-section' ),
				'description'	=> __( 'Toggle the display of the contact form.', 'storefront-homepage-contact-section' ),
				'section'		=> 'shcs_section',
				'settings'		=> 'shcs_contact_form',
				'type'			=> 'checkbox',
				'priority'		=> 80,
			) ) );
		}
	}

	/**
	 * Enqueue CSS and custom styles.
	 * @since   1.0.0
	 * @return  void
	 */
	public function shcs_styles() {
		wp_enqueue_style( 'shcs-styles', plugins_url( '/assets/css/style.css', __FILE__ ) );

		$bg_color			= apply_filters( 'storefront_homepage_contact_section_bg', storefront_get_content_background_color() );
		$accent_color		= get_theme_mod( 'storefront_accent_color', apply_filters( 'storefront_default_accent_color', '#FFA107' ) );
		$overlay_opacity	= apply_filters( 'storefront_homepage_contact_section_overlay', .8 );

		// Get RGB color of overlay from HEX
		if ( Storefront_Homepage_Contact_Section::sanitize_hex_color( $bg_color ) ) {
			list( $r, $g, $b ) = sscanf( $bg_color, "#%02x%02x%02x" );
		} else {
			$r = $g = $b = 255;
		}

		$shcs_style = '
		.storefront-homepage-contact-section .shcs-overlay {
			background-color: rgba(' . $r . ', ' . $g . ', ' . $b . ', ' . $overlay_opacity .');
		}

		.storefront-homepage-contact-section .shcs-contact-details ul li:before {
			color: ' . $accent_color . ';
		}';

		wp_add_inline_style( 'shcs-styles', $shcs_style );
	}

	/**
	 * Contact section
	 * @since   1.0.0
	 * @return 	void
	 */
	public static function storefront_homepage_contact_section() {
		$address		= get_theme_mod( 'shcs_contact_address', '' );
		$phone_number	= get_theme_mod( 'shcs_contact_phone_number', '' );
		$email			= get_theme_mod( 'shcs_contact_email_address', '' );
		$apikey			= get_theme_mod( 'shcs_api_key', '' );
		$display_form	= get_theme_mod( 'shcs_contact_form', true );

		$map_url = '';
		if ( '' !== $address ) {
			$map_url = 'https://maps.googleapis.com/maps/api/staticmap?scale=2&size=530x300&center=' . urlencode( trim( preg_replace( '/\s+/', ' ', $address ) ) ) . '&key=' . esc_attr( $apikey );
		}
?>
	<section class="storefront-product-section storefront-homepage-contact-section">
		<h2 class="section-title">
			<?php esc_attr_e( apply_filters( 'storefront_homepage_contact_section_title', __( 'Contact Us', 'storefront-homepage-contact-section' ) ) ); ?>
		</h2>

		<div class="shcs-wrapper"<?php if ( '' !== $map_url ) : ?> style="background-image: url(<?php echo esc_url( $map_url ); ?>);"<?php endif; ?>>
			<div class="shcs-overlay">
				<?php if ( '' !== $address || '' !== $phone_number || '' !== $email ) : ?>
				<div class="shcs-contact-details">
					<ul>
						<?php if ( '' !== $address ) : ?>
						<li class="shcs-address"><?php echo wpautop( esc_attr( $address ) ); ?></li>
						<?php endif; ?>

						<?php if ( '' !== $phone_number ) : ?>
						<li class="shcs-phone-number"><?php esc_attr_e( $phone_number ); ?></li>
						<?php endif; ?>

						<?php if ( '' !== $email ) : ?>
						<li class="shcs-email"><?php esc_attr_e( $email ); ?></li>
						<?php endif; ?>
					</ul>
				</div>
				<?php endif; ?>

				<?php if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'contact-form' ) ) : ?>
					<?php if ( true == $display_form ) : ?>
					<div class="shcs-contact-form">
						<?php echo do_shortcode( '[contact-form to="' . get_bloginfo( 'admin_email' ) . '"][contact-field label="' . __( 'Name', 'storefront-homepage-contact-section' ) . '" type="name" required="1"][contact-field label="' . __( 'Email', 'storefront-homepage-contact-section' ) . '" type="email" required="1"][contact-field label="' . __( 'Comment', 'storefront-homepage-contact-section' ) . '" type="textarea" required="1"][/contact-form]' ); ?>
					</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php
	}

	/**
	 * Sanitizes a hex color. Identical to core's sanitize_hex_color(), which is not available on the wp_head hook.
	 *
	 * Returns either '', a 3 or 6 digit hex color (with #), or null.
	 * For sanitizing values without a #, see sanitize_hex_color_no_hash().
	 *
	 * @since  1.0.0
	 * @param  string $color
	 * @return string|void
	 */
	private function sanitize_hex_color( $color ) {
		if ( '' === $color ) {
			return '';
        }

		// 3 or 6 hex digits, or the empty string.
		if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
			return $color;
        }

		return null;
	}
} // End Class
