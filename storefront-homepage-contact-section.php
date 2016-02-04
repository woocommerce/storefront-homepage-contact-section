<?php
/**
 * Plugin Name:			Storefront Homepage Contact Section
 * Plugin URI:			http://woothemes.com/products/storefront-homepage-contact-section/
 * Description:			Adds a simple contact section to your Storefront powered site.
 * Version:				1.0.0
 * Author:				tiagonoronha, WooThemes
 * Author URI:			http://woothemes.com/
 * Requires at least:	4.0.0
 * Tested up to:		4.0.0
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
		$this->version 			= '1.0.0';

		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'shcs_load_plugin_textdomain' ) );

		add_action( 'init', array( $this, 'shcs_setup' ) );

		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'shcs_plugin_links' ) );
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
	 * Plugin page links
	 *
	 * @since  1.0.0
	 */
	public function shcs_plugin_links( $links ) {
		$plugin_links = array(
			'<a href="http://support.woothemes.com/">' . __( 'Support', 'storefront-homepage-contact-section' ) . '</a>',
			'<a href="http://docs.woothemes.com/document/storefront-homepage-contact-section/">' . __( 'Docs', 'storefront-homepage-contact-section' ) . '</a>',
		);

		return array_merge( $plugin_links, $links );
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
			add_action( 'customize_preview_init', array( $this, 'shcs_customize_preview_js' ) );
			add_action( 'admin_notices', array( $this, 'shcs_customizer_notice' ) );
			add_action( 'homepage', array( $this, 'storefront_homepage_contact_section' ), 90 );

			// Hide the 'More' section in the customizer
			add_filter( 'storefront_customizer_more', '__return_false' );
		} else {
			add_action( 'admin_notices', array( $this, 'shcs_install_storefront_notice' ) );
		}
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
			'sanitize_callback'	=> 'sanitize_text_field',
			'transport'			=> 'postMessage',
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
			'sanitize_callback'	=> 'sanitize_text_field',
			'transport'			=> 'postMessage',
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
			'sanitize_callback'	=> 'sanitize_text_field',
			'transport'			=> 'postMessage',
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
		 * Contact Form Heading
		 */
		if ( class_exists( 'Arbitrary_Storefront_Control' ) ) {
			$wp_customize->add_control( new Arbitrary_Storefront_Control( $wp_customize, 'shcs_contact_form_heading', array(
				'section'  	=> 'shcs_section',
				'type'		=> 'heading',
				'label'		=> __( 'Contact form', 'storefront-homepage-contact-section' ),
				'priority' 	=> 40,
			) ) );
		}

		/**
		 * Contact Form Heading
		 */
		$jetpack_message = '';
		if ( ! class_exists( 'Jetpack' ) ) {
			$jetpack_message = sprintf( __( 'To enable the Contact Form feature, please install the %sJetpack%s plugin and %sactivate the Contact Form module%s.', 'storefront-homepage-contact-section' ), '<a href="https://wordpress.org/plugins/jetpack/">', '</a>', '<a href="https://jetpack.me/support/activate-and-deactivate-modules/">', '</a>' );
		} elseif ( class_exists( 'Jetpack' ) && ! Jetpack::is_module_active( 'contact-form' ) ) {
			$jetpack_message = sprintf( __( 'To enable the Contact Form feature, please %sactivate the Contact Form module%s in the Jetpack plugin.', 'storefront-homepage-contact-section' ), '<a href="https://jetpack.me/support/activate-and-deactivate-modules/">', '</a>' );
		}

		if ( '' !== $jetpack_message && class_exists( 'Arbitrary_Storefront_Control' ) ) {
			$wp_customize->add_control( new Arbitrary_Storefront_Control( $wp_customize, 'shcs_contact_form_description', array(
				'section'		=> 'shcs_section',
				'type'			=> 'text',
				'description'	=> $jetpack_message,
				'priority'		=> 50,
			) ) );
		}

		/**
		 * Contact Form
		 */
		if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'contact-form' ) ) {
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
				'priority'		=> 50,
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

		$accent_color = get_theme_mod( 'storefront_accent_color', apply_filters( 'storefront_default_accent_color', '#FFA107' ) );

		$shcs_style = '
		.storefront-homepage-contact-section .shcs-contact-details ul li:before {
			color: ' . $accent_color . ';
		}';

		wp_add_inline_style( 'shcs-styles', $shcs_style );
	}

	/**
	 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
	 *
	 * @since  1.0.0
	 */
	public function shcs_customize_preview_js() {
		wp_enqueue_script( 'shcs-customizer', plugins_url( '/assets/js/customizer.min.js', __FILE__ ), array( 'customize-preview' ), '1.1', true );
	}

	/**
	 * Contact section
	 * @return void
	 */
	public static function storefront_homepage_contact_section() {
		$address		= get_theme_mod( 'shcs_contact_address', '' );
		$phone_number	= get_theme_mod( 'shcs_contact_phone_number', '' );
		$email			= get_theme_mod( 'shcs_contact_email_address', '' );
		$display_form	= get_theme_mod( 'shcs_contact_form', 1 );

		$map_url = '';
		if ( '' !== $address ) {
			$map_url = 'https://maps.googleapis.com/maps/api/staticmap?size=1060x600&center=' . urlencode( $address );
		}
?>
	<section class="storefront-product-section storefront-homepage-contact-section">
		<h2 class="section-title"><?php _e( 'Contact Us', 'storefront-homepage-contact-section' ); ?></h2>

		<div class="shcs-wrapper"<?php if ( '' !== $map_url ) : ?> style="background-image: url(<?php echo esc_url( $map_url ); ?>);"<?php endif; ?>>
			<div class="shcs-overlay">
				<?php if ( '' !== $address || '' !== $phone_number || '' !== $email ) : ?>
				<div class="shcs-contact-details">
					<ul>
						<?php if ( '' !== $address ) : ?>
						<li class="shcs-address"><?php esc_attr_e( $address ); ?></li>
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
					<?php if ( 1 === $display_form ) : ?>
					<div class="shcs-contact-form">
						<?php echo do_shortcode( '[contact-form][contact-field label="Name" type="name" required="1"][contact-field label="Email" type="email" required="1"][contact-field label="Comment" type="textarea" required="1"][/contact-form]' ); ?>
					</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php
	}
} // End Class
