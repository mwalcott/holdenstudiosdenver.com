<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.orionorigin.com/
 * @since      1.0.0
 *
 * @package    Vpc_Cta
 * @subpackage Vpc_Cta/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Vpc_Cta
 * @subpackage Vpc_Cta/public
 * @author     Orion <help@orionorigin.com>
 */
class Vpc_Cta_Public {

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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/vpc-cta-public.css', array(), $this->version, 'all');
        wp_enqueue_style('qtip_min_css', plugin_dir_url(__FILE__) . 'css/jquery.qtip.min.css', array(), $this->version, 'all');

        $this->register_fonts();
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/vpc-cta-public.js', array('jquery'), $this->version, false);
        wp_enqueue_script("vpc-cta-qtip", plugin_dir_url(__FILE__) . 'js/jquery.qtip.min.js', array('jquery'), VPC_VERSION, false);
        wp_enqueue_script("vpc-cta-textfill", plugin_dir_url(__FILE__) . 'js/jquery.textfill.js', array('jquery'), VPC_VERSION, false);
    }

    function vpc_cta_behaviour_text($option, $o_image, $price, $option_id, $component, $skin_name, $config_to_load) {
        global $vpc_settings;
        $price_tooltip = get_proper_value($vpc_settings, "view-price");
        $tooltip = '';
        $name_tooltip = get_proper_value($vpc_settings, "view-name");
        if ($name_tooltip == "Yes")
            $tooltip = $option["name"];

        $formated_price = wc_price($price);
        if ($price_tooltip == "Yes") {
            if (strpos($formated_price, '-') || strpos($formated_price, '+'))
                $tooltip .= "$formated_price";
            else
                $tooltip .= " +$formated_price";
        }
        if (!empty($option["desc"]))
            $tooltip .= " (" . $option["desc"] . ")";
        vpc_cta_create_text_option($option, $tooltip, $price,$config_to_load);
    }

    function add_vpc_cta_data($datas) {
        $datas['text_settings'] = array();
        return $datas;
    }

    function get_vpc_cta_preview($preview_html, $prod_id, $config_id) {
        $config = $this->get_vpc_cta_config_data($prod_id);
        if (isset($config['multi-views']) && $config['multi-views'] == "No" || !isset($config['multi-views'])) {
            if (class_exists('Vpc_Upload_Public')) {
                $preview_html = '<div class="vpc-global-preview">'
                        . '<div id="upload_panel" class=""></div>'
                        . '<div id="text_panel" class=""></div>'
                        . '<div id="vpc-preview"></div>'
                        . '</div>';
            } else {
                $preview_html = '<div class="vpc-global-preview">'
                        . '<div id="text_panel" class=""></div>'
                        . '<div id="vpc-preview"></div>'
                        . '</div>';
            }
        }
        return $preview_html;
    }

    private function get_vpc_cta_config_data($prod_id) {
        $ids = get_product_root_and_variations_ids($prod_id);
        $config_meta = get_post_meta($ids['product-id'], "vpc-config", true);
        $configs = get_proper_value($config_meta, $prod_id, array());
        $config_id = get_proper_value($configs, "config-id", false);
        $config = get_post_meta($config_id, 'vpc-config', true);
        return $config;
    }

    private function register_fonts() {
        $fonts = get_option("vpc-cta-fonts");
        if (empty($fonts)) {
            $fonts = $this->get_vpc_cta_default_fonts();
        }

        foreach ($fonts as $font) {
            $font_label = $font[0];
            $font_url = str_replace('http://', '//', $font[1]);
            if ($font_url) {
                $handler = sanitize_title($font_label) . "-css";
                wp_register_style($handler, $font_url, array(), false, 'all');
                wp_enqueue_style($handler);
            }
        }
    }

    function vpc_cta_filter_recap($recap, $config, $show_icons) {

        foreach ($recap as $key => $value) {
            if (empty($value)) {
                unset($recap[$key]);
                $key_properties = $key . ' properties';
                if (isset($recap[$key_properties]))
                    unset($recap[$key_properties]);
            }
        }
        return $recap;
    }

    public function get_vpc_cta_default_fonts() {
        $default = array(
            array("Shadows Into Light", "http://fonts.googleapis.com/css?family=Shadows+Into+Light"),
            array("Droid Sans", "http://fonts.googleapis.com/css?family=Droid+Sans:400,700"),
            array("Abril Fatface", "http://fonts.googleapis.com/css?family=Abril+Fatface"),
            array("Arvo", "http://fonts.googleapis.com/css?family=Arvo:400,700,400italic,700italic"),
            array("Lato", "http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic"),
            array("Just Another Hand", "http://fonts.googleapis.com/css?family=Just+Another+Hand")
        );

        return $default;
    }

   
    function  vpc_cta_config_price($total_price, $product_id, $config, $cart_item){
        if(isset($cart_item["visual-product-configuration"])){
            foreach($cart_item["visual-product-configuration"] as $name => $val)
                $o_name[$name] = $val;
        }
        $original_config = get_product_config($product_id);
        $config_settings=$original_config->settings;
        $components = $config_settings['components'];
       foreach($components as $component)
        {
             if($component['behaviour'] == 'text'){
                foreach($component['options'] as $option){
                    if(isset($o_name[$option['name']]) && $o_name[$option['name']] !=''){
                        if(!empty($option['price']))
                            $total_price += $option['price'];
                    }
                            
                }
            }
        }
        return $total_price;
    }

}
