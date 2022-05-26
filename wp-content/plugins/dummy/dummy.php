<?php

/**
 * Plugin Name:     Dummy
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     dummy
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Dummy
 */

// Your code starts here.

function dummy_html()
{
?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <div style="display:flex; flex-direction:row; justify-content:flex-start">
            <!-- create request -->
            <form style="margin:4px;" action="<?php menu_page_url('dummy') ?>" method="post">
                <input type="hidden" name="create" />
                <?php submit_button(__('Create', 'textdomain')); ?>
            </form>
            <!-- delete request -->
            <form style="margin:4px;" action="<?php menu_page_url('dummy') ?>" method="post">
                <input type="hidden" name="delete" />
                <?php submit_button(__('Delete', 'textdomain')); ?>
            </form>
            <!-- test request -->
            <form style="margin:4px;" action="<?php menu_page_url('dummy') ?>" method="post">
                <input type="hidden" name="test" />
                <?php submit_button(__('Test', 'textdomain')); ?>
            </form>
        </div>
        <!-- create response -->
        <?php isset($_POST['create']) ? include_once 'create.php' : ''; ?>
        <!-- delete response -->
        <?php isset($_POST['delete']) ? include_once 'delete.php' : ''; ?>
        <!-- test reponse -->
        <?php isset($_POST['test']) ? include_once 'test.php' : ''; ?>
    </div>
<?php
}

add_action('admin_menu', 'dummy');
function dummy()
{
    add_menu_page(
        'Dummy',
        'Dummy',
        'manage_options',
        'dummy',
        'dummy_html',
        'dashicons-sos',
        100
    );
}
