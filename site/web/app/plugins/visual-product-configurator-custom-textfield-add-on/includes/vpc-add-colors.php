<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

function woocommerce_vpc_cta_add_colors() {
    if (isset($_GET['error'])) {
        echo $_GET['error'];
    }
    // Action to perform: add, edit, delete or none
    $action = '';
    if (!empty($_POST['add_new_color'])) {
        $action = 'add';
    } elseif (!empty($_POST['save_color']) && !empty($_GET['edit'])) {
        $action = 'edit';
    } elseif (!empty($_GET['delete'])) {
        $action = 'delete';
    }
    // Add or edit an attribute
    if ('add' === $action || 'edit' === $action) {
        // Security check
        if ('add' === $action) {
            check_admin_referer('woocommerce-add-new_color');
        }
        if ('edit' === $action) {
            $color_key = absint($_GET['edit']);
            check_admin_referer('woocommerce-save-color_' . $color_key);
        }
        // Grab the submitted dcta
        $color_label = ( isset($_POST['color_label']) ) ? (string) stripslashes($_POST['color_label']) : '';
        $color_code = ( isset($_POST['color_code']) ) ? (string) stripslashes($_POST['color_code']) : '';
        //$color_file = ( isset($_POST['color_file']) ) ? (array) $_POST['color_file'] : '';
//                $color_family=( isset( $_POST['color_family'] ) )   ? (string) stripslashes( $_POST['color_family'] ) : '';
        if ('add' === $action) {
            if ($color_label) {
                $colors = get_option('vpc-cta-colors');
                if (empty($colors)) {
                    $i = 1;
                    $colors[$i] = array($color_label, $color_code);
                } else {
                    $color_labels = array_map(create_function('$o', 'return $o[0];'), $colors);
//                            var_dump($color_labels);
                    if (in_array($color_label, $color_labels))
                        $error = '<div class=error>This color exist !</div>';
                    else
                        $colors[] = array($color_label, $color_code);
                }
                update_option('vpc-cta-colors', $colors);
                $action_completed = true;
            }
            else {
                $error = '<div class=error>Missing color name.</div>';
                $action_completed = true;
            }
        }
        // Edit existing attribute
        if ('edit' === $action) {
            $colors = get_option('vpc-cta-colors');
            $edit = $_GET['edit'];
            $colors[$edit] = array($color_label, $color_code);
            update_option('vpc-cta-colors', $colors);
            $action_completed = true;
        }
//                flush_rewrite_rules();
    }

    // Delete an attribute
    if ('delete' === $action) {
        // Security check
        $color_id = absint($_GET['delete']);
        $colors = get_option('vpc-cta-colors');
        unset($colors[$color_id]);
        update_option('vpc-cta-colors', $colors);
    }

    // If an attribute was added, edited or deleted: clear cache and redirect
    if (!empty($action_completed)) {
        //delete_transient( 'wc_attribute_taxonomies' );
        if (!empty($error))
            wp_safe_redirect(get_admin_url() . 'admin.php?page=vpc_cta_add_colors&error=' . urlencode($error));
        else {
            wp_safe_redirect(get_admin_url() . 'admin.php?page=vpc_cta_add_colors');
        }
        exit;
    }
    // Show 
    // admin interface
    if (!empty($_GET['edit']))
        woocommerce_edit_color();
    else
        woocommerce_add_color();
}

function woocommerce_edit_color() {
    $edit = absint($_GET['edit']);
    $colors = get_option('vpc-cta-colors');
    $color_label = $colors[$edit][0];
    $color_code = $colors[$edit][1];
   // $color_file = ( isset($colors[$edit][2]) ) ? (array) ($colors[$edit][2] ) : '';
    wp_enqueue_media();
//    $color_family=$colors[$edit][2];
    ?>
    <div class="wrap woocommerce">
        <div class="icon32 icon32-attributes" id="icon-woocommerce"><br/></div>
        <h2><?php _e('Edit Color', 'vpc-cta') ?></h2>
        <form action="admin.php?page=vpc_cta_add_colors&amp;edit=<?php echo absint($edit); ?>&amp;noheader=true" method="post">
            
            <table class="form-table">
                <tbody>
                    <tr class="form-field form-required">
                        <th scope="row" valign="top">
                            <label for="color_label"><?php _e('Color Name', 'vpc-cta'); ?></label>
                        </th>
                        <td>
                            <input name="color_label" id="color_label" class="color_auto_name" type="text" value="<?php echo esc_attr($color_label); ?>" />
                            <p class="description"><?php _e('Name of color (shown on the front-end).', 'vpc-cta'); ?></p>
                        </td>
                    </tr>
                    <tr class="form-field">
    <!--                                        <th scope="row" valign="top">
                                <label for="color_family"><?php // _e( 'Font family', 'vpc-cta' );  ?></label>
                        </th>-->
    <!--                                        <td>
                                <input name="color_family" id="color_family" type="text" value="<?php // echo esc_attr($color_family);  ?>" />
                                <p class="description"><?php // _e( 'Font family property (used for serif or sans serif colors). If not provided, the color name will be used.', 'vpc-cta' );  ?></p>
                        </td>                                            -->
                    </tr>
                    <tr class="form-field">
                        <th scope="row" valign="top">
                            <label for="color_label"><?php _e('Color Code', 'vpc-cta'); ?></label>
                        </th>
                        <td>
                            <input name="color_code" id="code-color-selector" class="color_auto_url vpc-color" type="text" value="<?php echo esc_attr($color_code); ?>" />
                            <p class="description"><?php _e('Color code', 'vpc-cta'); ?></p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p class="submit"><input type="submit" name="save_color" id="submit" class="button-primary" value="<?php _e('Update', 'vpc-cta'); ?>"></p>
    <?php wp_nonce_field('woocommerce-save-color_' . $edit); ?>
        </form>
    </div>
    <?php
}

function woocommerce_add_color() {
    wp_enqueue_media();
    ?>
    <div class="wrap woocommerce">
        <div class="icon32 icon32-attributes" id="icon-woocommerce"><br/></div>
        <h2><?php _e('Add Fonts', 'vpc-cta') ?></h2>
        <br class="clear" />
        <div id="col-container">
            <div id="col-right">
                <div class="col-wrap">
                    <table class="widefat fixed" style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col"><?php _e('Name', 'vpc-cta') ?></th>
                                <th scope="col"><?php _e('Code', 'vpc-cta') ?></th>
                            </tr>
                        </thead>
                        <tbody>
    <?php
    $colors = get_option('vpc-cta-colors');
    if ($colors) :
        foreach ($colors as $key => $color_arr) :
            $color = $color_arr[0];
            $color_code = $color_arr[1];
            if (!isset($color_arr[2]))
                $color_arr[2] = array();
            ?><tr>

                                        <td><a href="<?php echo esc_url(add_query_arg('edit', $key, 'admin.php?page=vpc_cta_add_colors')); ?>"><?php echo esc_html($color); ?></a>

                                            <div class="row-actions"><span class="edit"><a href="<?php echo esc_url(add_query_arg('edit', $key, 'admin.php?page=vpc_cta_add_colors')); ?>"><?php _e('Edit', 'vpc-cta'); ?></a> | </span><span class="delete"><a class="delete" href="<?php echo esc_url(wp_nonce_url(add_query_arg('delete', $key, 'admin.php?page=vpc_cta_add_colors'), 'woocommerce-delete-attribute_' . $key)); ?>"><?php _e('Delete', 'vpc-cta'); ?></a></span></div>
                                        </td>
                                        <td><?php echo esc_html($color_code); ?> </td>
                                        
                                    </tr><?php
                                        endforeach;
                                    else :
                                        ?><tr><td colspan="6"><?php _e('No colors currently exist.', 'vpc-cta') ?></td></tr><?php
                                    endif;
                                    ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="col-left">
                <div class="col-wrap">
                    <div class="form-wrap">
                        <h3><?php _e('Add New Color', 'vpc-cta') ?></h3>
                        <form action="admin.php?page=vpc_cta_add_colors&amp;noheader=true" method="post">
                            <div class="form-field">
                                <label for="color_label"><?php _e('Color Name', 'vpc-cta'); ?></label>
                                <input name="color_label" class="color_auto_name" id="color_label" type="text" value="" />
                                <p class="description"><?php _e('Name for the color (shown on the front-end).', 'vpc-cta'); ?></p>
                            </div>
                            <div class="form-field">
                                <label for="code-color-selector"><?php _e('Color code', 'vpc-cta'); ?></label>
                                <input name="color_code" id="code-color-selector" class="color_auto_url" type="text" value="" />
                                <p class="description"><?php _e('Code of color.', 'vpc-cta'); ?></p>
                            </div>
                            <p class="submit"><input type="submit" name="add_new_color" id="submit" class="button" value="<?php _e('Add Color', 'vpc-cta'); ?>"></p>
    <?php wp_nonce_field('woocommerce-add-new_color'); ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            jQuery('a.delete').click(function () {
                var answer = confirm("<?php _e('Are you sure you want to delete this color?', 'vpc-cta'); ?>");
                if (answer)
                    return true;
                return false;
            });
        </script>
    </div>
    <?php
} 