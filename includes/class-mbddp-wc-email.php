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
	   
		  if ( 'cod' == $order->payment_method ) {
			// cash on delivery method
			echo '<p><strong>Instructions:</strong> Full payment is due immediately upon delivery: <em>cash only, no exceptions</em>.</p>';
		  } else {
			// other methods (ie credit card)
			echo '<p><strong>Instructions:</strong> Please look for "Madrigal Electromotive GmbH" on your next credit card statement.</p>';
		  }
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
			echo '<p class="some-class">Here goes your custom content… </p>';
	
		// }
	
	}

	// Content Before Email Content Table
	public static function mm_email_before_order_table( $order, $sent_to_admin, $plain_text, $email ) {

		// if( 'customer_processing_order' == $email->id ){
	
			// Set here as you want your custom content (for customers and email notification related to processing orders only)
			echo '<p class="some-class">Here goes your custom content… </p>';
	
		// }
	
	}

	// Content After Email Content Table
	public static function mm_email_after_order_table( $order, $sent_to_admin, $plain_text, $email ) { 
		echo "<p>Hey! Thanks for shopping with us. As a way of saying thanks, here’s a coupon code for your next purchase: FRESH15</p>";
	}
	// Content Above Email footer
	public static function mm_email_footer( $email ) { ?>
		<p><?php printf( __( 'Thank you for shopping! Shop for more items using this link: %s', 'woocommerce' ), '<a href="' . get_permalink( wc_get_page_id( 'shop' ) ) . '">' . __( 'Shop', 'woocommerce' ) . '</a>' ); ?></p>
	
	<?php
	}
}

$wc_email = new Mbddp_WC_Email();
// $wc_email->settings_api_init();