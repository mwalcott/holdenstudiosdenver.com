<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Plugin Directory.
 */
define('WOO_DISCOUNT_DIR', untrailingslashit(plugin_dir_path(__FILE__)));

/**
 * Plugin Directory URI.
 */
define('WOO_DISCOUNT_URI', untrailingslashit(plugin_dir_url(__FILE__)));

/**
 * Plugin Base Name.
 */
define('WOO_DISCOUNT_PLUGIN_BASENAME', plugin_basename(__FILE__));

if(!function_exists('get_plugin_data')){
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

/**
 * Version of Woo Discount Rules.
 */
$pluginDetails = get_plugin_data(plugin_dir_path(__FILE__).'woo-discount-rules.php');
define('WOO_DISCOUNT_VERSION', $pluginDetails['Version']);

if(!class_exists('FlycartWooDiscountRules')){
    class FlycartWooDiscountRules{

        private static $instance;
        public $discountBase;
        public $pricingRules;
        public $config;

        /**
         * To run the plugin
         * */
        public static function init() {
            if ( self::$instance == null ) {
                self::$instance = new FlycartWooDiscountRules();
            }
            return self::$instance;
        }

        /**
         * FlycartWooDiscountRules constructor
         * */
        public function __construct() {
            $this->includeFiles();
            $this->discountBase = new FlycartWooDiscountBase();
            $this->runUpdater();
            $this->pricingRules = new FlycartWooDiscountRulesPricingRules();
            if (is_admin()) {
                $this->loadAdminScripts();
            }
            if(FlycartWooDiscountRulesGeneralHelper::doIHaveToRun()){
                $this->loadSiteScripts();
            }
        }

        /**
         * To include Files
         * */
        protected function includeFiles(){
            include_once('helper/woo-function.php');
            include_once('includes/pricing-rules.php');
            include_once('helper/general-helper.php');
            include_once('includes/cart-rules.php');
            include_once('includes/discount-base.php');
            include_once('helper/purchase.php');
            require_once __DIR__ . '/vendor/autoload.php';
        }

        /**
         * Run Plugin updater
         * */
        protected function runUpdater(){
            try{
                require plugin_dir_path( __FILE__ ).'/vendor/yahnis-elsts/plugin-update-checker/plugin-update-checker.php';

                $purchase_helper = new FlycartWooDiscountRulesPurchase();
                $purchase_helper->init();
                $update_url = $purchase_helper->getUpdateURL();
                if(!$purchase_helper->isPro()){
                    $dlid = $this->discountBase->getConfigData('license_key', null);
                    if(empty($dlid)) return false;
                }
                $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
                    $update_url,
                    plugin_dir_path( __FILE__ ).'woo-discount-rules.php',
                    'woo-discount-rules'
                );
                add_action( 'after_plugin_row', array($purchase_helper, 'woodisc_after_plugin_row'),10,3 );

                add_action('wp_ajax_forceValidateLicenseKey', array($purchase_helper, 'forceValidateLicenseKey'));

                add_action( 'admin_notices', array($purchase_helper, 'errorNoticeInAdminPages'));
            } catch (Exception $e){}
        }

        /**
         * Load Admin scripts
         * */
        protected function loadAdminScripts(){
            // Init in Admin Menu
            add_action('admin_menu', array($this->discountBase, 'adminMenu'));
            add_action('wp_ajax_savePriceRule', array($this->discountBase, 'savePriceRule'));
            add_action('wp_ajax_saveCartRule', array($this->discountBase, 'saveCartRule'));
            add_action('wp_ajax_saveConfig', array($this->discountBase, 'saveConfig'));
            add_action('wp_ajax_loadProductSelectBox', array($this->discountBase, 'loadProductSelectBox'));

            add_action('wp_ajax_UpdateStatus', array($this->discountBase, 'updateStatus'));
            add_action('wp_ajax_RemoveRule', array($this->discountBase, 'removeRule'));
        }

        /**
         * Apply discount rules
         * */
        public function applyDiscountRules(){
            $this->discountBase->handlePriceDiscount();
            remove_action('woocommerce_before_calculate_totals', array($this, 'applyDiscountRules'), 1000);
        }

        /**
         * Apply discount rules
         * */
        public function applyCartDiscountRules(){
            $this->discountBase->handleCartDiscount();
            remove_action('woocommerce_cart_loaded_from_session', array($this, 'applyCartDiscountRules'), 100);
        }

        /**
         * Load Admin scripts
         * */
        protected function loadSiteScripts(){
            $woocommerce_version = '2.0.0';
            $pluginDetails = get_plugin_data(WP_PLUGIN_DIR.'/woocommerce/woocommerce.php');
            if(isset($pluginDetails['Version'])){
                $woocommerce_version = $pluginDetails['Version'];
            }

            add_action('woocommerce_after_cart_item_quantity_update', array($this->discountBase, 'handleDiscount'), 100);
            if(version_compare($woocommerce_version, '3.0', '>=')){
                add_action('woocommerce_before_calculate_totals', array($this, 'applyDiscountRules'), 1000);
                add_action('woocommerce_cart_loaded_from_session', array($this, 'applyCartDiscountRules'), 100);
                add_action( 'woocommerce_after_cart_item_quantity_update', array($this->pricingRules, 'handleBOGODiscountOnUpdateQuantity'), 10, 4 );
            } else {
                add_action( 'woocommerce_after_cart_item_quantity_update', array($this->pricingRules, 'handleBOGODiscountOnUpdateQuantity'), 10, 3 );
                add_action('woocommerce_cart_loaded_from_session', array($this->discountBase, 'handleDiscount'), 100);
            }

            add_action('woocommerce_add_to_cart', array($this->pricingRules, 'handleBOGODiscount'), 10, 6);


            // Manually Update Line Item Name.
            add_filter('woocommerce_cart_item_name', array($this->discountBase, 'modifyName'));

            // Remove Filter to make the previous one as last filter.
            remove_filter('woocommerce_cart_item_name', 'filter_woocommerce_cart_item_name', 10, 3);

            // Alter the Display Price HTML.
            add_filter('woocommerce_cart_item_price', array($this->pricingRules, 'replaceVisiblePricesCart'), 1000, 3);

            //replace visible price in product page
            add_filter('woocommerce_get_price_html', array($this->pricingRules, 'replaceVisiblePrices'), 100, 3);
            //replace visible price in product page for variant
            add_filter('woocommerce_available_variation', array($this->pricingRules, 'replaceVisiblePricesForVariant'), 100, 3);


            // Older Version support this hook.
            add_filter('woocommerce_cart_item_price_html', array($this->pricingRules, 'replaceVisiblePricesCart'), 1000, 3);

            // Pricing Table of Individual Product.
            add_filter('woocommerce_before_add_to_cart_form', array($this->pricingRules, 'priceTable'));

            // Updating Log After Creating Order
            add_action('woocommerce_thankyou', array($this->discountBase, 'storeLog'));

            add_action( 'woocommerce_after_checkout_form', array($this->discountBase, 'addScriptInCheckoutPage'));

            //To enable on-sale tag
            add_filter('woocommerce_product_is_on_sale', array($this->pricingRules, 'displayProductIsOnSaleTag'), 10, 2);

            $force_refresh_cart_widget = $this->discountBase->getConfigData('force_refresh_cart_widget', 0);
            if($force_refresh_cart_widget){
                if (isset($_REQUEST['wc-ajax']) && ($_REQUEST['wc-ajax'] == 'add_to_cart' || $_REQUEST['wc-ajax'] == 'remove_from_cart')) {
                    add_action('woocommerce_before_mini_cart', array($this, 'applyRulesBeforeMiniCart'), 10);
                }
            }
        }

        /**
         * To load the dynamic data in mini-cart/cart widget while add to cart and remove from cart through widget
         * */
        public function applyRulesBeforeMiniCart(){
            WC()->cart->get_cart_from_session();
            $this->discountBase->handlePriceDiscount();
            WC()->cart->calculate_totals();
        }
    }
}

/**
 * init Woo Discount Rules
 */
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
    global $flycart_woo_discount_rules;
    $flycart_woo_discount_rules = FlycartWooDiscountRules::init();
    $purchase_helper = new FlycartWooDiscountRulesPurchase();
    if($purchase_helper->isPro()){
        include_once('includes/advanced/free_shipping_method.php');
        include_once('includes/advanced/pricing-productdependent.php');
        include_once('includes/advanced/cart-totals.php');
        include_once('includes/advanced/advanced-helper.php');
    }
}
