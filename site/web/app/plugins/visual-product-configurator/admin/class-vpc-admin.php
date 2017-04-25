<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.orionorigin.com
 * @since      1.0.0
 *
 * @package    Vpc
 * @subpackage Vpc/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Vpc
 * @subpackage Vpc/admin
 * @author     ORION <help@orionorigin.com>
 */
class VPC_Admin {

/**
 * The ID of this plugin.
 *
 * @since    1.0.0
 * @access   private
 * @var      string    $plugin_name    The ID of this plugin.
 */
private $plugin_name;

/**
 * The version of this plugin.
 *
 * @since    1.0.0
 * @access   private
 * @var      string    $version    The current version of this plugin.
 */
private $version;

/**
 * Initialize the class and set its properties.
 *
 * @since    1.0.0
 * @param      string    $plugin_name       The name of this plugin.
 * @param      string    $version    The version of this plugin.
 */
public function __construct($plugin_name, $version) {

$this->plugin_name = $plugin_name;
$this->version = $version;
}

/**
 * Register the stylesheets for the admin area.
 *
 * @since    1.0.0
 */
public function enqueue_styles() {

/**
 * This function is provided for demonstration purposes only.
 *
 * An instance of this class should be passed to the run() function
 * defined in Vpc_Loader as all of the hooks are defined
 * in that particular class.
 *
 * The Vpc_Loader will then create the relationship
 * between the defined hooks and the functions defined in this
 * class.
 */
wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/vpc-admin.css', array(), $this->version, 'all');
wp_enqueue_style("o-flexgrid", plugin_dir_url(__FILE__) . 'css/flexiblegs.css', array(), $this->version, 'all');
wp_enqueue_style("o-ui", plugin_dir_url(__FILE__) . 'css/UI.css', array(), $this->version, 'all');
wp_enqueue_style("o-tooltip", VPC_URL . 'public/css/tooltip.min.css', array(), $this->version, 'all');
wp_enqueue_style("o-bs-modal-css", VPC_URL . 'admin/js/modal/modal.min.css', array(), $this->version, 'all');
}

/**
 * Register the JavaScript for the admin area.
 *
 * @since    1.0.0
 */
public function enqueue_scripts() {

/**
 * This function is provided for demonstration purposes only.
 *
 * An instance of this class should be passed to the run() function
 * defined in Vpc_Loader as all of the hooks are defined
 * in that particular class.
 *
 * The Vpc_Loader will then create the relationship
 * between the defined hooks and the functions defined in this
 * class.
 */
wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/vpc-admin.js', array('jquery'), $this->version, false);
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script("o-admin", plugin_dir_url(__FILE__) . 'js/o-admin.js', array('jquery', 'jquery-ui-sortable'), $this->version, false);
wp_localize_script("o-admin", 'home_url', home_url("/"));
wp_enqueue_script("o-tooltip", VPC_URL . 'public/js/tooltip.min.js', array('jquery'), $this->version, false);
//        wp_enqueue_script("o-lazyload", VPC_URL . 'admin/js/jquery.lazyload.min.js', array('jquery'), $this->version, false);
wp_enqueue_script('o-modal-js', VPC_URL . 'admin/js/modal/modal.min.js', array('jquery'), false, false);
wp_enqueue_script("jquery-serializejson", VPC_URL . 'public/js/jquery.serializejson.min.js', array('jquery'), $this->version, false);

//Set string translation for js scripts
$string_translations = array(
"reverse_cb_label" => __("Enable reverse rule", 'vpc'),
 "group_conditions_relation" => __("Conditions relationship", "vpc"),
);
wp_localize_script($this->plugin_name, 'string_translations', $string_translations);
}

/**
 * Initialize the plugin sessions
 */
function init_sessions() {
if (!session_id()) {
session_start();
}
}

public function get_vpc_screen_layout_columns($columns) {
$columns['vpc-config'] = 1;
return $columns;
}

public function get_vpc_config_screen_layout() {
return 1;
}

public function metabox_order($order) {
$order["advanced"] = "vpc-config-preview-box,vpc-config-settings-box,vpc-config-conditional-rules-box,submitdiv";
return $order;
}

/**
 * Builds all the plugin menu and submenu
 */
public function get_menu() {
$parent_slug = "edit.php?post_type=vpc-config";
add_submenu_page($parent_slug, __('Settings', 'vpc'), __('Settings', 'vpc'), 'manage_product_terms', 'vpc-manage-settings', array($this, 'get_vpc_settings_page'));
//        add_submenu_page($parent_slug, __('Add-ons', 'vpc'), __('Add-ons', 'vpc'), 'manage_product_terms', 'vpc-manage-add-ons', array($this, 'get_vpc_addons_page'));
}

public function get_vpc_settings_page() {
//        var_dump($_POST);
if ((isset($_POST["vpc-options"]) && !empty($_POST["vpc-options"]))) {
update_option("vpc-options", $_POST["vpc-options"]);

global $wp_rewrite;
$wp_rewrite->flush_rules();
}
?>
<div class="wrap cf">
    <h1><?php _e("Visual Products Configurator Settings", "vpc"); ?></h1>
    <form method="POST" action="" class="mg-top">
        <div class="postbox" id="vpc-options-container">
            <?php
            $begin = array(
            'type' => 'sectionbegin',
            'id' => 'vpc-options-container',
            'table' => 'options',
            );
            $args = array(
            "post_type" => "page",
            "nopaging" => true,
            );
            $pages = get_posts($args);
            $pages_ids = array();
            foreach ($pages as $page) {
            $pages_ids[$page->ID] = $page->post_title;
            }
            $configuration_page = array(
            'title' => __('Configuration page', 'vpc'),
            'name' => 'vpc-options[config-page]',
            'type' => 'select',
            'options' => $pages_ids,
            'default' => '',
            'class' => 'chosen_select_nostd',
            'desc' => __('Page where all products are configured.', 'vpc'),
            );

            $automatically_append = array(
            'title' => __('Manage the configuration page', 'vpc'),
            'name' => 'vpc-options[manage-config-page]',
            'type' => 'radio',
            'options' => array("Yes" => "Yes", "No" => "No"),
            'default' => 'Yes',
            'class' => 'chosen_select_nostd',
            'desc' => __('If Yes, the plugin will handle the content of the configuration page. If No, use the shortcode [wpb_builder] to display the configurator INSIDE the configuration page.', 'vpc'),
            );

            $cart_actions_arr = array(
            "none" => __("None", "vpc"),
            "refresh" => __("Refresh", "vpc"),
            "redirect" => __("Redirect to cart page", "vpc"),
            "redirect_to_product_page" => __("Redirect to product page", "vpc"),
            );

            $hide_wc_add_to_cart_btn = array(
            'title' => __('Hide woocommerce add to cart button ', 'vpc'),
            'name' => 'vpc-options[hide-wc-add-to-cart]',
            'type' => 'radio',
            'options' => array("Yes" => "Yes", "No" => "No"),
            'default' => 'Yes',
            //'class' => 'chosen_select_nostd',
            'desc' => __('Should the plugin hide the default woocommerce add to cart button on configurables products?', 'vpc'),
            );
                    $hide_secondary_product_in_cart = array(
                         'title' => __('Hide linked products cart page ', 'vpc'),
                        'name' => 'vpc-options[hide-wc-secondary-product-in-cart]',
                        'type' => 'radio',
                        'options' => array("Yes" => "Yes", "No" => "No"),
                        'default' => 'Yes',
                        //'class' => 'chosen_select_nostd',
                        'desc' => __('Should the plugin hide the products linked to the options in the cart page?', 'vpc'),
                    );

                    $action_in_cart = array(
                        'title' => __('Action after addition to cart', 'vpc'),
                        'name' => 'vpc-options[action-after-add-to-cart]',
                        'type' => 'select',
                        'options' => $cart_actions_arr,
                        'default' => '',
                        'class' => 'chosen_select_nostd',
                        'desc' => __('What should happen once the customer adds the configured product to the cart.', 'vpc'),
                    );

                    $ajax_load = array(
                        'title' => __('Ajax Loading', 'vpc'),
                        'name' => 'vpc-options[ajax-loading]',
                        'type' => 'radio',
                        'options' => array("Yes" => "Yes", "No" => "No"),
                        'default' => 'No',
                        'desc' => __('Load the editor via ajax. If enabled, this will speed up configuration page load by building configurator after the configuration page is fully loaded.', 'vpc'),
                    );

                    $envato_username = array(
                        'title' => __('Envato Username', 'wad'),
                        'name' => 'vpc-options[envato-username]',
                        'type' => 'text',
                        'desc' => __('Your envato username', 'wad'),
                        'default' => '',
                    );

                    $envato_api_key = array(
                        'title' => __('Secret API Key', 'wad'),
                        'name' => 'vpc-options[envato-api-key]',
                        'type' => 'text',
                        'desc' => __('You can find your secret api key by following the instructions <a href="https://www.youtube.com/watch?v=KnwumvnWAIM" target="blank">here</a>.', 'wad'),
                        'default' => '',
                    );

                    $envato_purchase_code = array(
                        'title' => __('Purchase Code', 'wad'),
                        'name' => 'vpc-options[purchase-code]',
                        'type' => 'text',
                        'desc' => __('You can find your purchase code by following the instructions <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-can-I-find-my-Purchase-Code-" target="blank">here</a>.', 'wad'),
                        'default' => '',
                    );

                    $end = array('type' => 'sectionend');
                    $settings = apply_filters("vpc_global_settings", array(
                        $begin,
                        $configuration_page,
                        $hide_wc_add_to_cart_btn,
                        $hide_secondary_product_in_cart,
                        $automatically_append,
                        $action_in_cart,
                        $ajax_load,
                        $envato_username,
                        $envato_api_key,
                        $envato_purchase_code,
                        $end
                    ));
                    echo o_admin_fields($settings);
                    ?>
                </div>
                <input type="submit" class="button button-primary button-large" value="Save">
            </form>
        </div>
        <?php
    }

    public function get_vpc_addons_page() {
        ?>
        <div class="wrap cf">
            <h1><?php _e("Visual Products Configurator add-ons", "vpc"); ?></h1>
            <div class="postbox" id="vpc-addon-options-container">
                <div  class="o-wrap  vpc-addon-list">
                    <div class="col xl-1-2">
                        <div class="vpc-addon-item-container">
                            <div class="vpc-addon-item">
        <!--                                    <span class="vpc-addon-ico"><img src="<?php // echo VPC_URL;  ?>/admin/images/love.png"></span>-->
                                <span>
                                    <h4 class="vpc-addon-title"><?php _e("Ninja Forms", "vpc"); ?></h4>
                                    <?php _e("Allows you to build any form containing fields (text fields, dropdowns, textareas etc...) and link it to a component or a configuration.", "vpc"); ?>
                                </span>
                            </div>
                            <a href="#" class="button button-primary button-large vpc-addon-btn">Get</a>
                        </div>
                    </div>

                    <div class="col xl-1-2">
                        <div class="vpc-addon-item-container">
                            <div class="vpc-addon-item">
                                <!--<span class="vpc-addon-ico"><img src="<?php // echo VPC_URL;  ?>/admin/images/bug.png"></span>-->
                                <span>
                                    <h4 class="vpc-addon-title"><?php _e("Templates & Save for later", "vpc"); ?></h4>
                                    <?php _e("Allows you not only to build predesigned configurations your customer can start their designs from but also to save their work for later.", "vpc"); ?>
                                </span>
                            </div> 
                            <a href="#" class="button button-primary button-large vpc-addon-btn">Get</a>
                        </div> 
                    </div>

                    <div class="col xl-1-2">
                        <div class="vpc-addon-item-container">
                            <div class="vpc-addon-item">
                                <!--<span class="vpc-addon-ico"><img src="<?php // echo VPC_URL;  ?>/admin/images/update.png"></span>-->
                                <span>
                                    <h4 class="vpc-addon-title"><?php _e("Edit from cart", "vpc"); ?></h4>
                                    <?php _e("Allows your customers to edit a configuration previously added to the cart.", "vpc"); ?>
                                </span>

                            </div>
                            <a href="#" class="button button-primary button-large vpc-addon-btn">Get</a>
                        </div>
                    </div>

                    <div class="col xl-1-2">
                        <div class="vpc-addon-item-container">
                            <div class="vpc-addon-item">
                                <!--<span class="vpc-addon-ico"><img src="<?php // echo VPC_URL;  ?>/admin/images/features.png"></span>-->
                                <span>
                                    <h4 class="vpc-addon-title"><?php _e("Social Sharing", "vpc"); ?></h4>
                                    <?php _e("Allows your customers to share their work on social networks.", "vpc"); ?>
                                </span>
                            </div>
                            <a href="#" class="button button-primary button-large vpc-addon-btn">Get</a>
                        </div>
                    </div>                        

                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Checks if the database needs to be upgraded
     */
    function run_vpc_db_updates_requirements() {
        //Checks db structure for v2.0
        $old_configs = get_option("product_configurator");
        if (!empty($old_configs)) {
            ?>
            <div class="updated" id="vpc-updater-container">
                <strong><?php echo _e("Woocommerce Visual Product Configurator database update required.", "vpc"); ?></strong>
                <div>
                    <?php echo _e("Hi! This version of the Woocommerce Visual Product Configuratormade some changes in the way it's data are stored. So in order to work properly, we just need you to click on the \"Run Updater\" button to move your old settings to the new structure. ", "vpc"); ?><br>
                    <input type="button" value="<?php echo _e("Run the updater", "vpc"); ?>" id="vpc-run-updater" class="button button-primary"/>
                    <div class="loading" style="display:none;"></div>
                </div>
            </div>
            <style>
                #vpc-updater-container
                {
                    padding: 3px 17px;
                    /*font-size: 13px;*/
                    line-height: 36px;
                    margin-left: 0px;
                    border-left: 5px solid #e14d43 !important;
                }
                #vpc-updater-container.done
                {
                    border-color: #7ad03a !important;
                }
                #vpc-run-updater {
                    background: #e14d43;
                    border-color: #d02a21;
                    color: #fff;
                    -webkit-box-shadow: inset 0 1px 0 #ec8a85,0 1px 0 rgba(0,0,0,.15);
                    box-shadow: inset 0 1px 0 #ec8a85,0 1px 0 rgba(0,0,0,.15);
                }

                #vpc-run-updater:focus, #vpc-run-updater:hover {
                    background: #dd362d;
                    border-color: #ba251e;
                    color: #fff;
                    -webkit-box-shadow: inset 0 1px 0 #e8756f;
                    box-shadow: inset 0 1px 0 #e8756f;
                }
                .loading
                {
                    background: url("<?php echo VPC_URL; ?>/admin/images/spinner.gif") 10% 10% no-repeat transparent;
                    background-size: 111%;
                    width: 32px;
                    height: 40px;
                    display: inline-block;
                }
            </style>
            <script>
                //jQuery('.loading').hide();
                jQuery('#vpc-run-updater').click('click', function () {
                    var ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
                    if (confirm("It is strongly recommended that you backup your database before proceeding. Are you sure you wish to run the updater now")) {
                        jQuery('.loading').show();
                        jQuery.post(
                                ajax_url,
                                {
                                    action: 'run_updater'
                                },
                        function (data) {
                            jQuery('.loading').hide();
                            jQuery('#vpc-updater-container').html(data);
                            jQuery('#vpc-updater-container').addClass("done");
                        }
                        );
                    }

                });
            </script>
            <?php
        }
    }

    public function run_vpc_updater() {
        ob_start();
        $old_configs = get_option("product_configurator");
        if (!empty($old_configs))
            $old_configs = stripcslashes($old_configs);
//            var_dump($old_configs);
        if (!empty($old_configs)) {
            $decoded_configs = json_decode($old_configs);
//                var_dump($decoded_configs);
            $config_matches = array();
            foreach ($decoded_configs as $old_config_id => $config) {
                $error_occured = false;
                $new_config_meta = array(
                    "components" => array(),
                    "conditional_rules" => array(
                        "enable_rules" => "",
                        "groups" => array()
                    )
                );
                $ids_matches = array();
                $cid = 0; //Component count
                foreach ($config->data as $old_component_id => $component) {
//                        var_dump($old_component_id);

                    $oid = 0; //Option count
                    $new_component["cname"] = $component->name;
                    $new_component["cimage"] = $this->get_attachment_id_from_url($component->layer_icon);
                    $ids_matches["layer_wrap_$old_component_id"] = "component_" . sanitize_title(str_replace(' ', '', $new_component["cname"]));
                    if (!$new_component["cimage"]) {
//                        $error_occured = true;
                        $new_component["cimage"] = "";
                        echo "Can't retrieve image $component->layer_icon in component $component->name for configuration $config->name. <strong>$config->name</strong>.<br>";
                    }
                    $new_component["behaviour"] = "radio";
                    $new_component["options"] = array();
                    //Not using the groups
                    $default_img = $component->defaul_img;
                    if (property_exists($component, 'img_s')) {
                        $this->extract_old_options($component->img_s, $new_component, $old_component_id, $ids_matches, $default_img, $cid, $oid);
                    } else {
                        foreach ($component->category as $group) {
                            $this->extract_old_options($group->img, $new_component, $old_component_id, $ids_matches, $default_img, $cid, $oid, $group->name);
                        }
                    }
                    array_push($new_config_meta["components"], $new_component);
                    $cid++;
                }

                if ($config->conditional_rules) {
                    $this->extract_old_conditionnal_rules($config, $new_config_meta, $ids_matches);
                }

                // Create post object
                if (!$error_occured) {
                    $post_args = array(
                        'post_title' => $config->name,
                        'post_type' => 'vpc-config',
                        'post_status' => 'publish',
                    );

                    $new_config_id = wp_insert_post($post_args);
                    if (!is_wp_error($new_config_id)) {
                        update_post_meta($new_config_id, "vpc-config", $new_config_meta, true);
                        $config_matches[$old_config_id] = $new_config_id;
                    }

                    echo "<strong>$config->name</strong> successfully imported.<br>";
                }
            }

            $this->run_products_migration($config_matches);
            $this->run_options_migration();

            delete_option("product_configurator");
            update_option("product_configurator_old", $old_configs);
        }
        $output = ob_get_contents();
        ob_end_clean();
        echo $output;
        die();
    }

    private function run_options_migration() {
        $old_options = get_option("wvpc_options");
        $action_after_add_to_cart = get_proper_value($old_options, "action_after_add_to_cart", "none");
        $custom_page = get_proper_value($old_options, "customizer_page", "");

        $new_options = array(
            "config-page" => $custom_page,
            "manage-config-page" => "No",
            "action-after-add-to-cart" => $action_after_add_to_cart,
        );
        update_option("vpc-options", $new_options);
    }

    private function run_products_migration($config_matches) {
        $args = array(
            'post_type' => array('product', 'product_variation'),
            'meta_key' => 'wvpc-meta',
            'post_status' => 'any',
        );
        $custom_products = get_posts($args);
        foreach ($custom_products as $custom_product) {
            $product_obj = wc_get_product($custom_product->ID);
            $product_class = get_class($product_obj);
            if ($product_class == "WC_Product_Simple") {
                $root_pid = $product_obj->id;
                $variable_pid = $product_obj->id;
            } else {
                $root_pid = $product_obj->id;
                $variable_pid = $custom_product->ID;
            }
            $meta = get_post_meta($custom_product->ID, "wvpc-meta", true);
            $old_config = isset($meta["product_config"]) ? $meta["product_config"] : array();

            //We don't handle the configs with failure in migration
            if (!isset($config_matches[$old_config]))
                continue;
            $new_config_id = $config_matches[$old_config];

            $new_meta = get_post_meta($root_pid, "vpc-config", true);
            if (empty($new_meta))
                $new_meta = array();

            $new_meta[$variable_pid] = array("config-id" => $new_config_id);
            update_post_meta($root_pid, "vpc-config", $new_meta);
        }
    }

    private function extract_old_options($group, &$new_component, $old_component_id, &$ids_matches, $default_img, $cid, &$oid, $group_name = "") {
        foreach ($group as $old_option) {

            $ids_matches["wvpc_img_$old_component_id" . "_$old_option->img_id"] = "component_" . sanitize_title(str_replace(' ', '', $new_component["cname"])) . "_group_" . sanitize_title(str_replace(' ', '', $group_name)) . "_option_" . sanitize_title(str_replace(' ', '', $old_option->img_name));
            $linked_product = "";
            if (( property_exists($old_option, 'linked_to_product') && !empty($old_option->linked_to_product) && $old_option->linked_to_product == "checked") && ( property_exists($old_option, 'linked_product') && !empty($old_option->linked_product))) {
                $linked_product = $old_option->linked_product;
            }

            $new_option = array(
                "group" => $group_name,
                "name" => $old_option->img_name,
                "desc" => "",
                "icon" => $old_option->icon_id,
                "image" => $old_option->img_id,
                "price" => $old_option->img_price,
                "product" => $linked_product,
            );
            if ($default_img == $old_option->img_id)
                $new_option["default"] = 1;
            array_push($new_component["options"], $new_option);
            $oid++;
        }
    }

    private function extract_old_conditionnal_rules($config, &$new_config_meta, $ids_matches) {
        $new_config_meta["conditional_rules"]["enable_rules"] = (property_exists($config->conditional_rules, 'enable_rules'));
        foreach ($config->conditional_rules->groups as $group_index => $rules_group) {
            $new_config_meta["conditional_rules"]["groups"][$group_index]["result"] = array(
                "action" => $rules_group->result->action,
                "apply_on" => $ids_matches[$rules_group->result->apply_on],
                "scope" => $rules_group->result->scope
            );
            if (property_exists($rules_group, 'apply_reverse'))
                $new_config_meta["conditional_rules"]["groups"][$group_index]["apply_reverse"] = $rules_group->apply_reverse;
            $new_config_meta["conditional_rules"]["groups"][$group_index]["rules"] = array();
            foreach ($rules_group->rules as $old_rule) {
                $new_rule = array(
                    "option" => $ids_matches[$old_rule->option],
                    "trigger" => $old_rule->trigger);
                array_push($new_config_meta["conditional_rules"]["groups"][$group_index]["rules"], $new_rule);
            }
        }
    }

    private function get_attachment_id_from_url($url) {
        $info = pathinfo($url);
        $attachment_id = $this->wp_get_attachment_by_post_name($info["filename"]);
        //If the attachment does not exist on that server, we download and register it
        if (!$attachment_id) {
            $attachment_id = $this->import_attachment($url);
        }

        return $attachment_id;
    }

    private function import_attachment($file_url) {
        if (!$file_url)
            return false;
        $upload_dir = wp_upload_dir();
        $filename = $upload_dir["path"] . "/" . basename($file_url);
        $res = file_put_contents($filename, file_get_contents($file_url));
        if (!$res) {
            return false;
        }

        // Check the type of file. We'll use this as the 'post_mime_type'.
        $filetype = wp_check_filetype(basename($filename), null);

        // Get the path to the upload directory.
        $wp_upload_dir = wp_upload_dir();

        // Prepare an array of post data for the attachment.
        $attachment = array(
            'guid' => $wp_upload_dir['url'] . '/' . basename($filename),
            'post_mime_type' => $filetype['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
            'post_content' => '',
            'post_status' => 'inherit'
        );

        // Insert the attachment.
        $attach_id = wp_insert_attachment($attachment, $filename);
//
//            // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
//
//            // Generate the metadata for the attachment, and update the database record.
        $attach_data = wp_generate_attachment_metadata($attach_id, $filename);
        wp_update_attachment_metadata($attach_id, $attach_data);
        return $attach_id;
    }

    private function wp_get_attachment_by_post_name($post_name) {
        $args = array(
            'post_per_page' => 1,
            'post_type' => 'attachment',
            'name' => trim($post_name),
        );
        $get_posts = new Wp_Query($args);

        if (isset($get_posts->posts[0]))
            return $get_posts->posts[0]->ID;
        else
            return false;
    }

    function get_product_columns($defaults) {
        $defaults['configuration'] = __('Configurable', 'vpc');
        return $defaults;
    }

    function get_products_columns_values($column_name, $id) {
        if ($column_name === 'configuration') {
            $is_configurable = vpc_product_is_configurable($id);
//            $product = wc_get_product($id);
//            $class_name = get_class($product);
//            if ($class_name == "WC_Product_Variable") {
//                $variations = $product->get_available_variations();
//                $metas = get_post_meta($id, 'vpc-config', true);
//                foreach ($variations as $variation) {
//                    $variation_id = $variation["variation_id"];
//                    $variation_metas = get_proper_value($metas, $variation_id, false);
//                    $is_configurable = vpc_is_configurable($variation_metas);
//                    if ($is_configurable)
//                        break;
//                }
//            }
//            else {
//                $metas = get_post_meta($id, 'vpc-config', true);
//                $product_metas = get_proper_value($metas, $id, false);
//                $is_configurable = vpc_is_configurable($product_metas);
//            }
            if ($is_configurable)
                _e("Yes", "vpc");
            else
                _e("No", "vpc");
        }
    }

    /**
     * Runs the new version check and upgrade process
     * @return \VPC_Updater
     */
    function get_updater() {
        do_action('wpd_before_init_updater');
        require_once( VPC_DIR . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'updaters' . DIRECTORY_SEPARATOR . 'class-wpd-updater.php' );
        $updater = new VPC_Updater();
        $updater->init();
        require_once( VPC_DIR . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'updaters' . DIRECTORY_SEPARATOR . 'class-wpd-updating-manager.php' );
        $updater->setUpdateManager(new VPC_Updating_Manager(VPC_VERSION, $updater->versionUrl(), VPC_MAIN_FILE));
        do_action('wpd_after_init_updater');
        return $updater;
    }

    public function get_max_input_vars_php_ini() {
        $total_max_normal = ini_get('max_input_vars');
        $msg = __("Your max input var is <strong>$total_max_normal</strong> but this page contains <strong>{nb}</strong> fields. You may experience a lost of data after saving. In order to fix this issue, please increase <strong>the max_input_vars</strong> value in your php.ini file.", "vpc");
        ?> 
        <script type="text/javascript">
            var o_max_input_vars = <?php echo $total_max_normal; ?>;
            var o_max_input_msg = "<?php echo $msg; ?>";
        </script>         
        <?php
    }

}
