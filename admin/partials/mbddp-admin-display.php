<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://niloyrudra.com/
 * @since      1.0.0
 *
 * @package    Mbddp
 * @subpackage Mbddp/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap mbddp-wrap">
    <h1><?php echo get_admin_page_title(); ?></h1>
    <?php settings_errors(); ?>

    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
        <!-- <div id="post-body" class="metabox-holder"> -->
            <div id="post-body-content">
                <div class="metabox-sortables ui-sortable">
                    <div class="postbox">
                        <div class="inside">

                            <form action="options.php" method="post">

                                <?php

                                    // settings_fields( 'mbddp_group_id' ); // group id

                                    // do_settings_sections( MBDDP_PLUGIN_SLUG ); // page

                                    // submit_button( '', 'primary', 'mbddp_update_options', false, array('') )
                                ?>

                                <!-- <form method="post" action="options.php"> -->
                                    <?php wp_nonce_field( 'mbddp_update_options', '_mbddp_update_options_nonce' ); ?>
                                    <?php settings_fields( 'mbddp_group_id' ); ?>
                                    <?php //do_settings_fields( 'mvf-settings' ); ?>
                                    <?php do_settings_sections( 'mbddp' ); ?>

                                    <p class="submit">
                                        <?php submit_button('', 'primary mbddp-button-primary', 'mbddp_update_options', false); ?>
                                    </p>
                                <!-- </form> -->

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>