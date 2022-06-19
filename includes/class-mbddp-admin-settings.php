<?php

/**
 * Fired during plugin admin settings
 *
 * @link       https://niloyrudra.com/
 * @since      1.0.0
 *
 * @package    Mbddp
 * @subpackage Mbddp/includes
 */

/**
 * Fired during plugin admin settings.
 *
 * This class defines all code necessary to run during the plugin's admin settings.
 *
 * @since      1.0.0
 * @package    Mbddp
 * @subpackage Mbddp/includes
 * @author     Niloy Rudra <niloyrudra4249@gmail.com>
 */
class Mbddp_Admin_settings {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	// public function __constructor() {
	// 	$this->settings_api_init();
	// }

	/**
	 * Settings API func
	 */
	public function settings_api_init() {
		add_action( "admin_init", array( $this, "register_settings_api" ) );
	}

	/**
	 * Register Settings API func
	 */
	public function register_settings_api() {

		/**
		 * Settings Group
		 */
		register_setting(
			MBDDP_PLUGIN_SETTINGS_API_GROUP_ID, // $option_group:string,
			'mbddp_page_option', // $option_name:string,
			//array('') // $args:array
		);
		register_setting(
			MBDDP_PLUGIN_SETTINGS_API_GROUP_ID, // $option_group:string,
			'mbddp_link_position', // $option_name:string,
			//array('') // $args:array
		);
		register_setting(
			MBDDP_PLUGIN_SETTINGS_API_GROUP_ID, // $option_group:string,
			'mbddp_message', // $option_name:string,
			//array('') // $args:array
		);

		/**
         * Settings Section
         */
		add_settings_section(
			"mbddp_section_id", // id,
			__( "Notification Settings", "mbddp" ), // title,
			array( $this, "section_callback" ), // callback,
			MBDDP_PLUGIN_SLUG, // page
		);

		/**
         * Settings Fields
         */
		add_settings_field(
			"mbddp_page_field_id", // $id:string,
			__( "Select Redirect Page", "mbddp" ), // $title:string,
			array( $this, "redirect_page_callback" ), // $callback:callable,
			MBDDP_PLUGIN_SLUG, // $page:string,
			"mbddp_section_id", // $section:string,
			array(
                'type'          => 'select',
                'option_group'  => MBDDP_PLUGIN_SETTINGS_API_GROUP_ID,
                'name'          => 'mbddp_page_option',
                "label_for"     => "mbddp_page_option",
                "label"         => "",
                'value'         => ( @get_option( 'mbddp_page_option' ) ) ? esc_attr( get_option( 'mbddp_page_option' ) ) : '',
                'description'   => __( 'Choose a page where the users will be redirected to.', 'mbddp' ),
                "class"         => "",
                "placeholder"   => "",
                "ext"           => ""
            ) // $args:array
		);
		add_settings_field(
			"mbddp_link_position_field_id", // $id:string,
			__( "Select link position", "mbddp" ), // $title:string,
			array( $this, "link_position_radio_btn_callback" ), // $callback:callable,
			MBDDP_PLUGIN_SLUG, // $page:string,
			"mbddp_section_id", // $section:string,
			array(
                'type'          => 'radio',
                'option_group'  => MBDDP_PLUGIN_SETTINGS_API_GROUP_ID,
                'name'          => 'mbddp_link_position',
                "label_for"     => "mbddp_link_position",
                "label"         => "",
                'value'         => ( @get_option( 'mbddp_link_position' ) ) ? esc_attr( get_option( 'mbddp_link_position' ) ) : '',
                'description'   => __( 'Choose a postion in the email body where the delivery date picker page redirect link will be shown.', 'mbddp' ),
                "class"         => "",
                "placeholder"   => "",
                "ext"           => ""
            ) // $args:array
		);
		add_settings_field(
			"mbddp_message_field_id", // $id:string,
			__( "Custom E-mail Message", "mbddp" ), // $title:string,
			array( $this, "message_field_callback" ), // $callback:callable,
			MBDDP_PLUGIN_SLUG, // $page:string,
			"mbddp_section_id", // $section:string,
			array(
                'type'          => 'text',
                'option_group'  => MBDDP_PLUGIN_SETTINGS_API_GROUP_ID,
                'name'          => 'mbddp_message',
                "label_for"     => "mbddp_message",
                "label"         => "",
                'value'         => ( @get_option( 'mbddp_message' ) ) ? esc_attr( get_option( 'mbddp_message' ) ) : '',
                'description'   => __( 'Write down your message/notice/instruction/note etc. that will describ why this is for to the customers.', 'mbddp' ),
                "class"         => "regular-text",
                "placeholder"   => "",
                "ext"           => ""
            ) // $args:array
		);
	}

	/**
	 * Callbacks
	 */
	public function section_callback() {
		echo "lorem ipsum";
	}

	/**
	 * Settings API Field Generating Callbacks
	 */
	public function redirect_page_callback( $args ) {
		$label_for = $args['label_for'];
        // $label = $args['label'] ? $args['label'] . ': ' : '';
        $ext = $args['ext'] ? '<i>' . $args['ext'] . '</i>' : '';
        $class = $args['class'] ?: '';
        // $type = $args['type'] ?: 'text';
        // $placeholder = $args['placeholder'] ?: '';
        $description = $args['description'] ? '<p class="description">' . $args['description'] . '</p>' : '';
        $name = $args['name'];
        $value = $args['value'];

		$pages = get_posts( array( "post_type"	=> "page", "status"	=> "published", "numberposts" => -1, 'orderby'          => 'title', 'order'            => 'ASC', ) );

		if( $pages ) :
		?>
		<select name="<?php echo $name; ?>" id="<?php echo $label_for; ?>" class="<?php echo $class; ?>">
			<?php foreach( $pages as $page ) : ?>
				<option value="<?php echo $page->ID; ?>"<?php echo ( $value == $page->ID ? " selected" : "" ); ?>><?php echo get_the_title( $page->ID ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
			echo $description;
		else :
			?>
			<p><?php _e( "No page available!", "mbddp" ); ?></p>
			<?php
		endif;
	}
	public function link_position_radio_btn_callback( $args ) {
		$label_for = $args['label_for'];
        // $label = $args['label'] ? $args['label'] . ': ' : '';
        $ext = $args['ext'] ? '<i>' . $args['ext'] . '</i>' : '';
        $class = $args['class'] ?: '';
        $type = $args['type'] ?: 'text';
        $placeholder = $args['placeholder'] ?: '';
        // $description = $args['description'] ? '<p class="description">' . $args['description'] . '</p>' : '';
        $name = $args['name'];
        $value = $args['value'];

		$positions = array(
			"woocommerce_email_header" => __( "To add additional content below the header", "mbddp" ),
			"woocommerce_email_order_details" => __( "To places the content", "mbddp" ),
			"woocommerce_email_before_order_table" => __( "To add additional content below the order table", "mbddp" ),
			"woocommerce_order_item_meta_start" => __( "To place the information in the order table below the item name. Before adding any other meta, this one must be added first", "mbddp" ),
			"woocommerce_order_item_meta_end" => __( "To place the content below the item", "mbddp" ),
			"woocommerce_order_item_meta_start" => __( "To place the content above the item", "mbddp" ),
			"woocommerce_email_after_order_table" => __( "The content is placed directly below the order table", "mbddp" ),
			"woocommerce_email_order_meta" => __( "To place the content after the order table", "mbddp" ),
			"woocommerce_email_after_order_table" => __( "To place the content after the table", "mbddp" ),
			"woocommerce_email_customer_details" => __( "To place in the front of the customer’s billing and shipping information", "mbddp" ),
			
		);

		foreach( $positions as $hook_name => $position_des ) :
        echo '<label for="' . $label_for . '">
            <input type="' . $type . '" name="' . $name . '" value="' . $hook_name . '" id="' . $label_for . '" class="' . $class . '"' . ( $value == $hook_name ? ' checked' : '' ) . '/>&nbsp;' . $position_des . '
        </label><br />';
		endforeach;
	}
	public function message_field_callback( $args ) {
		$label_for = $args['label_for'];
        $label = $args['label'] ? $args['label'] . ': ' : '';
        $ext = $args['ext'] ? '<i>' . $args['ext'] . '</i>' : '';
        $class = $args['class'] ?: '';
        $type = $args['type'] ?: 'text';
        $placeholder = $args['placeholder'] ?: '';
        $description = $args['description'] ? '<p class="description">' . $args['description'] . '</p>' : '';
        $name = $args['name'];
        $value = $args['value'];

		// echo '<label for="' . $label_for . '">
        //     <input type="' . $type . '" name="' . $name . '" value="' . $value . '" id="' . $label_for . '" class="' . $class . '" placeholder="' . $placeholder . '" />
        // </label>' . $description;
		$content = $value;
		$custom_editor_id = $label_for;
		$custom_editor_name = $name;
		$args = array(
			'media_buttons' => false, // This setting removes the media button.
			'textarea_name' => $custom_editor_name, // Set custom name.
			'textarea_rows' => get_option('default_post_edit_rows', 3), //Determine the number of rows.
			'quicktags' => false, // Remove view as HTML button.
		);
		wp_editor( $content, $custom_editor_id, $args );
		echo $description;

	}

}

$settings_api = new Mbddp_Admin_settings();
$settings_api->settings_api_init();