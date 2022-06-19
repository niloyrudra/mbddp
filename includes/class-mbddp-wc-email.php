<?php

/**
 * Fired during plugin WC Email
 *
 * @link       https://niloyrudra.com/
 * @since      1.0.0
 *
 * @package    Mbddp
 * @subpackage Mbddp/includes
 */

/**
 * Fired during plugin WC Email.
 *
 * This class defines all code necessary to run during the plugin's WC Email.
 *
 * @since      1.0.0
 * @package    Mbddp
 * @subpackage Mbddp/includes
 * @author     Niloy Rudra <niloyrudra4249@gmail.com>
 */
class Mbddp_WC_Email {
	/**
	 * The current page_id of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $page_id    The current page_id of the plugin.
	 */
	protected static $page_id;

	/**
	 * The current msg_position of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $msg_position    The current msg_position of the plugin.
	 */
	protected static $msg_position;

	/**
	 * The current custom_msg of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $custom_msg    The current custom_msg of the plugin.
	 */
	protected static $custom_msg;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		self::$page_id = @get_option( 'mbddp_page_option' ) ? esc_html( get_option( 'mbddp_page_option' ) ) : null;
		self::$msg_position = @get_option( 'mbddp_link_position' ) ? esc_html( get_option( 'mbddp_link_position' ) ) : '';
		self::$custom_msg = @get_option( 'mbddp_message' ) ? esc_html( get_option( 'mbddp_message' ) ) : '';
		
	}

	public function init() {
		if( !self::$page_id ) return;

		$position = self::msg_position ?: 'woocommerce_email_after_order_table';
		$default_msg = self::custom_msg ?: __( 'Hey! Thanks for shopping with us. As a way of saying thanks, here’s a link to a page for selecting your desired delivery date.', 'mbddp' );

		switch( $position ) {
			case 'woocommerce_before_email_order' :
				// self::content_before_email_order();
				return self::content_before_email_order();
				break;
			case 'content_after_email_header' :
				// self::content_after_email_header();
				return self::content_after_email_header();
				break;
			case 'woocommerce_email_order_details' :
				// self::content_in_email_order_details();
				return self::content_in_email_order_details();
				break;
			case 'woocommerce_email_before_order_table' :
				// self::content_before_email_table();
				return self::content_before_email_table();
				break;
			case 'woocommerce_email_after_order_table' :
				// self::content_after_email_table();
				return self::content_after_email_table();
				break;

			case 'woocommerce_email_footer' :
				// self::content_above_email_footer();
				return self::content_above_email_footer();
				break;

			default:
				// self::content_after_email_table();
				return self::content_after_email_table();
				break;
		}
	}

	/*
	* goes in theme functions.php or a custom plugin
	*
	* Subject filters: 
	*   woocommerce_email_subject_new_order
	*   woocommerce_email_subject_customer_processing_order
	*   woocommerce_email_subject_customer_completed_order
	*   woocommerce_email_subject_customer_invoice
	*   woocommerce_email_subject_customer_note
	*   woocommerce_email_subject_low_stock
	*   woocommerce_email_subject_no_stock
	*   woocommerce_email_subject_backorder
	*   woocommerce_email_subject_customer_new_account
	*   woocommerce_email_subject_customer_invoice_paid
	**/
	public static function new_wc_email_subject() {
		add_filter('woocommerce_email_subject_new_order', array( __CLASS__, 'new_admin_email_subject' ), 1, 2);
	}

	public static function new_admin_email_subject( $subject, $order ) {
		global $woocommerce;

		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

		$subject = sprintf( '[%s] New Customer Order (# %s) from Name %s %s', $blogname, $order->id, $order->billing_first_name, $order->billing_last_name );

		return $subject;
	}

	/**
	 * WC Action Hooks
	 * that trigger custom content manipulations
	 */
	public static function content_before_email_order() {
		add_action( 'woocommerce_before_email_order', array( __CLASS__, 'add_order_instruction_email'), 10, 2 );
	}

	public static function content_after_email_header() {
		add_action( 'woocommerce_email_header', array( __CLASS__, 'mm_email_header'), 10, 2 );
	}
	
	public static function content_in_email_order_details() {
		add_action( 'woocommerce_email_order_details', array( __CLASS__, 'mm_content_in_email_order_details'), 10, 4 );
	}

	public static function content_before_email_table() {
		add_action( 'woocommerce_email_before_order_table', array( __CLASS__, 'mm_email_before_order_table'), 10, 4 );
	}

	// woocommerce_order_item_meta_start($item_id, $item, $order, $plain_text)
	// woocommerce_order_item_meta_end ($item_id, $item, $order, $plain_text)

	public static function content_after_email_table() {
		add_action( 'woocommerce_email_after_order_table', array( __CLASS__, 'mm_email_after_order_table'), 10, 4 );
	}

	public static function content_above_email_footer() {
		add_action( 'woocommerce_email_footer', array( __CLASS__, 'mm_email_footer'), 10, 1 );
	}

	/**
	 * WC Email Customizer Callbacks
	 */
	public static function add_order_instruction_email( $order, $sent_to_admin ) {
  
		if ( ! $sent_to_admin ) {
			?>
				<p><?php printf( __( '%s: %s', 'mbddp' ), self::$custom_msg, '<a href="' . get_permalink( self::$page_id ) . '">' . __( 'Please click here', 'mbddp' ) . '</a>' ); ?></p>
			<?php
		}
	}
	  

	// Content After Email Header
	public static function mm_email_header( $email_heading, $email ) { 
		echo "<p> Thanks for shopping with us. We appreciate you and your business!</p>";
	}

	// Content Email Order Details
	public static function mm_content_in_email_order_details( $order, $sent_to_admin, $plain_text, $email ) {

		// if( 'customer_processing_order' == $email->id ){
	
			// Set here as you want your custom content (for customers and email notification related to processing orders only)
			// echo '<p class="some-class">Here goes your custom content… </p>';
	
		// }
		if ( ! $sent_to_admin ) {
			?>
				<p><?php printf( __( '%s: %s', 'mbddp' ), self::$custom_msg, '<a href="' . get_permalink( self::$page_id ) . '">' . __( 'Please click here', 'mbddp' ) . '</a>' ); ?></p>
			<?php
		}
	
	}

	// Content Before Email Content Table
	public static function mm_email_before_order_table( $order, $sent_to_admin, $plain_text, $email ) {

		if ( ! $sent_to_admin ) {
			?>
				<p><?php printf( __( '%s: %s', 'mbddp' ), self::$custom_msg, '<a href="' . get_permalink( self::$page_id ) . '">' . __( 'Please click here', 'mbddp' ) . '</a>' ); ?></p>
			<?php
		}
	
	}

	// Content After Email Content Table
	public static function mm_email_after_order_table( $order, $sent_to_admin, $plain_text, $email ) { 
		// echo "<p>Hey! Thanks for shopping with us. As a way of saying thanks, here’s a coupon code for your next purchase: FRESH15</p>";
		if ( ! $sent_to_admin ) {
		?>
			<p><?php printf( __( '%s: %s', 'mbddp' ), self::$custom_msg, '<a href="' . get_permalink( self::$page_id ) . '">' . __( 'Please click here', 'mbddp' ) . '</a>' ); ?></p>
		<?php
		}
	}
	// Content Above Email footer
	public static function mm_email_footer( $email ) {
		// if ( ! $sent_to_admin ) {
		?>
			<p><?php printf( __( '%s: %s', 'mbddp' ), self::$custom_msg, '<a href="' . get_permalink( self::$page_id ) . '">' . __( 'Please click here', 'mbddp' ) . '</a>' ); ?></p>
		<?php
		// }
	}
}

$wc_email = new Mbddp_WC_Email();
Mbddp_WC_Email::init();