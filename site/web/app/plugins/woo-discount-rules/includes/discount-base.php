<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

global $woocommerce;

/**
 * Class FlycartWooDiscountBase
 */
if (!class_exists('FlycartWooDiscountBase')) {
    class FlycartWooDiscountBase
    {
        /**
         * @var string
         */
        public $default_page = 'pricing-rules';

        /**
         * @var string
         */
        public $default_option = 'woo-discount-config';

        /**
         * @var array
         */
        private $instance = array();

        public $has_free_shipping = 0;

        /**
         * FlycartWooDiscountBase constructor.
         */
        public function __construct() {}

        /**
         * Singleton Instance maker.
         *
         * @param $name
         * @return bool
         */
        public function getInstance($name)
        {
            if (!isset($this->instance[$name])) {
                if (class_exists($name)) {
                    $this->instance[$name] = new $name;
                    $instance = $this->instance[$name];
                } else {
                    $instance = false;
                }
            } else {
                $instance = $this->instance[$name];
            }
            return $instance;
        }

        /**
         * Managing discount of Price and Cart.
         */
        public function handleDiscount()
        {
            global $woocommerce;

            $price_discount = $this->getInstance('FlycartWooDiscountRulesPricingRules');
            $cart_discount = $this->getInstance('FlycartWooDiscountRulesCartRules');

            $price_discount->analyse($woocommerce);
            $cart_discount->analyse($woocommerce);
        }

        /**
         * Managing discount of Cart.
         */
        public function handleCartDiscount($free_shipping_check = 0)
        {
            global $woocommerce;
            $cart_discount = $this->getInstance('FlycartWooDiscountRulesCartRules');
            $cart_discount->analyse($woocommerce, $free_shipping_check);
            if($free_shipping_check){
                $this->has_free_shipping = $cart_discount->has_free_shipping;
            }
        }

        /**
         * Managing discount of Price.
         */
        public function handlePriceDiscount()
        {
            global $woocommerce;
            $price_discount = $this->getInstance('FlycartWooDiscountRulesPricingRules');
            $price_discount->analyse($woocommerce);
        }

        /**
         * For adding script in checkout page
         * */
        public function addScriptInCheckoutPage(){
            $script = '<script type="text/javascript">
                    jQuery( function( $ ) {
                        $(document).ready(function() {
                            $( document.body ).on( "blur", "input#billing_email", function() {
                                $("select#billing_country").trigger("change");
                            });
                        }); 
                    });
                </script>';
            echo $script;
        }

        /**
         * WooCommerce hook to change the name of a product.
         *
         * @param $title
         * @return mixed
         */
        public function modifyName($title)
        {
            //
            return $title;
        }

        /**
         * Finally, on triggering the "Thank You" hook by WooCommerce,
         * Overall session data's are stored to the order's meta as "woo_discount_log".
         *
         * @param integer $order_id Order ID.
         */
        public function storeLog($order_id)
        {
            $log['price_discount'] = WC()->session->get('woo_price_discount', array());
            $log['cart_discount'] = WC()->session->get('woo_cart_discount', array());

            add_post_meta($order_id, 'woo_discount_log', json_encode($log));

            // Reset the Coupon Status.
            WC()->session->set('woo_coupon_removed', '');
        }

        /**
         * Create New Menu On WooCommerce.
         */
        public function adminMenu()
        {
            if (!is_admin()) return;

            global $submenu;
            if (isset($submenu['woocommerce'])) {
                add_submenu_page(
                    'woocommerce',
                    'Woo Discount Rules',
                    'Woo Discount Rules',
                    'edit_posts',
                    'woo_discount_rules',
                    array($this, 'viewManager')
                );
            }
        }

        /**
         * Update the Status of the Rule Set.
         */
        public function updateStatus()
        {
            $postData = \FlycartInput\FInput::getInstance();
            $id = $postData->get('id', false);
            if ($id) {
                $status = get_post_meta($id, 'status', false);
                if (isset($status[0])) {
                    $state = ($status[0] == 'publish') ? 'disable' : 'publish';
                    update_post_meta($id, 'status', $state);
                } else {
                    add_post_meta($id, 'status', 'disable');
                    $state = 'disable';
                }
                echo ucfirst($state);
            }
            die();
        }

        /**
         * Remove the Rule Set.
         */
        public function removeRule()
        {
            $postData = \FlycartInput\FInput::getInstance();
            $id = $postData->get('id', false);
            if ($id) {
                try {
                    $id = intval($id);
                    if (!$id) return false;
                    wp_delete_post($id);
                } catch (Exception $e) {
                    //
                }
            }
            die();
        }
//    -------------------------------------- PRICE RULES ---------------------------------------------------------------
        /**
         * Saving the Price Rule.
         *
         * @return bool
         */
        public function savePriceRule()
        {
            $postData = \FlycartInput\FInput::getInstance();
            $request = $postData->getArray();
            $params = array();
            if (!isset($request['data'])) return false;
            parse_str($request['data'], $params);

            $pricing_rule = $this->getInstance('FlycartWooDiscountRulesPricingRules');
            $pricing_rule->save($params);
            die();
        }

//    -------------------------------------- CART RULES ----------------------------------------------------------------
        /**
         * Saving the Cart Rule.
         *
         * @return bool
         */
        public function saveCartRule()
        {

            $postData = \FlycartInput\FInput::getInstance();
            $request = $postData->getArray();
            $params = array();
            if (!isset($request['data'])) return false;
            parse_str($request['data'], $params);
            $this->parseFormWithRules($params, true);
            $pricing_rule = $this->getInstance('FlycartWooDiscountRulesCartRules');
            $pricing_rule->save($params);
            die();
        }

        /**
         * load product select box
         *
         * @return bool
         */
        public function loadProductSelectBox() {
            $postData = \FlycartInput\FInput::getInstance();
            $request = $postData->getArray();
            if (!isset($request['name'])) return false;
            echo FlycartWoocommerceProduct::getProductAjaxSelectBox(array(), $request['name']);
            die();
        }

        /**
         * Making the reliable end data to store.
         *
         * @param $cart_rules
         * @param bool $isCartRules
         */
        public function parseFormWithRules(&$cart_rules, $isCartRules = false)
        {
            $cart_rules['discount_rule'] = $this->generateFormData($cart_rules, $isCartRules);
        }

        /**
         * @param $cart_rules
         * @param bool $isCartRules
         * @return array
         */
        public function generateFormData($cart_rules, $isCartRules = false)
        {
            $link = $this->fieldLink();

            $discount_list = array();
            // Here, Eliminating the Cart's rule with duplicates.
            $discount_rule = (isset($cart_rules['discount_rule']) ? $cart_rules['discount_rule'] : array());
            if ($isCartRules) {
                foreach ($discount_rule as $index => $value) {

                    // The Type of Option should get value from it's native index.
                    // $link[$value['type']] will gives the native index of the "type"

                    if (isset($link[$value['type']])) {
                        if(is_array($link[$value['type']])){
                            foreach ($link[$value['type']] as $fields){
                                $discount_list[$index][$value['type']][$fields] = $value[$fields];
                            }
                        } else if (isset($value[$link[$value['type']]])) {
                            $discount_list[$index][$value['type']] = $value[$link[$value['type']]];
                        }
                    } else {
                        $discount_list[$index][$value['type']] = $value['option_value'];
                    }
                }
            }
            return $discount_list;

        }

        /**
         * @return array
         */
        public function fieldLink()
        {
            // TODO: Check Subtotal Link
            return array(
                'products_atleast_one' => 'product_to_apply',
                'products_not_in' => 'product_to_apply',

                'categories_atleast_one' => 'category_to_apply',
                'categories_not_in' => 'category_to_apply',
                'categories_in' => 'category_to_apply',

                'users_in' => 'users_to_apply',
                'roles_in' => 'user_roles_to_apply',
                'shipping_countries_in' => 'countries_to_apply',
                'customer_based_on_purchase_history' => array('purchase_history_order_status', 'purchased_history_amount', 'purchased_history_type'),
                'customer_based_on_purchase_history_order_count' => array('purchase_history_order_status', 'purchased_history_amount', 'purchased_history_type'),
                'customer_based_on_purchase_history_product_order_count' => array('purchase_history_order_status', 'purchased_history_amount', 'purchased_history_type', 'purchase_history_products'),
            );
        }

        // ----------------------------------------- CART RULES END --------------------------------------------------------


        // -------------------------------------------SETTINGS--------------------------------------------------------------

        /**
         *
         */
        public function saveConfig($licenceValidate = 0)
        {
            $postData = \FlycartInput\FInput::getInstance();
            $request = $postData->getArray();
            $params = array();
            if (isset($request['data'])) {
                parse_str($request['data'], $params);
            }

            if (is_array($request)) {
                if(isset($params['show_draft']) && $params['show_draft']){
                    $params['show_draft'] = 1;
                } else {
                    $params['show_draft'] = 0;
                }
                foreach ($params as $index => $item) {
//                $params[$index] = FlycartWooDiscountRulesGeneralHelper::makeString($item);
                    $params[$index] = $item;
                }
                $params = json_encode($params);
            }
//        else {
//            $params = FlycartWooDiscountRulesGeneralHelper::makeString($params);
//        }

            if (get_option($this->default_option)) {
                update_option($this->default_option, $params);
            } else {
                add_option($this->default_option, $params);
            }
            if(!$licenceValidate)
                die();
        }

        /**
         * @return array
         */
        public function getBaseConfig()
        {
            $option = get_option($this->default_option);
            if (!$option || is_null($option)) {
                return array();
            } else {
                return $option;
            }
        }

        /**
         * Get Config data
         *
         * @param String $key
         * @param mixed $default
         * @return mixed
         * */
        public function getConfigData($key, $default = ''){
            $config = $this->getBaseConfig();
            if (is_string($config)) $config = json_decode($config, true);
            return isset($config[$key])? $config[$key] : $default;
        }

        // -------------------------------------------SETTINGS END----------------------------------------------------------

        /**
         * @param $request
         * @return bool
         */
        public function checkSubmission($request)
        {
            if (isset($request['form']) && !empty($request['form'])) {
                $form = sanitize_text_field($request['form']);
                if (strpos($form, '_save') === false) return false;
                // For Saving Form
                $form = str_replace('_save', '', $form);
                // To Verify, the submitted form is in the Registered List or Not
                if (in_array($form, $this->formList())) {
                    if (isset($request['page'])) {
                        switch ($form) {
                            case 'pricing_rules':
                                die(123);
                                $pricing_rule = $this->getInstance('FlycartWooDiscountRulesPricingRules');
                                $pricing_rule->save($request);
                                break;
                            case 'cart_rules':
                                $cart_rules = $this->getInstance('FlycartWooDiscountRulesCartRules');
                                $cart_rules->save($request);
                                break;
                            case 'settings':
                                $this->save($request);
                                break;
                            default:
                                // Invalid Submission.
                                break;
                        }
                    }
                }
            }
        }

        /**
         * @param $option
         */
        public function checkAccess(&$option)
        {
            $postData = \FlycartInput\FInput::getInstance();
            // Handling View
            if ($postData->get('view', false)) {
                $option = $option . '-view';
                // Type : Price or Cart Discounts.
            } elseif ($postData->get('type', false)) {
                if ($postData->get('tab', false)) {
                    if ($postData->get('tab', '') == 'cart-rules') {
                        $option = 'cart-rules-new';
                        if ($postData->get('type', '') == 'view') $option = 'cart-rules-view';
                    }
                } else {
                    $option = $option . '-' . $postData->get('type', '');
                }
            }
        }

        /**
         * @param $request
         */
        public function save($request)
        {
            // Save General Settings of the Plugin.
        }

        /**
         * @return array
         */
        public function formList()
        {
            return array(
                'pricing_rules',
                'cart_rules',
                'settings'
            );
        }

        /**
         *
         */
        public function viewManager()
        {
            $postData = \FlycartInput\FInput::getInstance();
            $request = $postData->getArray();
            $this->checkSubmission($request);

            // Adding Plugin Page Script
            $this->woo_discount_adminPageScript();

            // Loading Instance.
            $generalHelper = $this->getInstance('FlycartWooDiscountRulesGeneralHelper');
            // Sanity Check.
            if (!$generalHelper) return;
            // Getting Active Tab.
            $tab = $generalHelper->getCurrentTab();

            $path = $this->getPath($tab);

            // Manage Tab.
            $tab = (isset($tab) ? $tab : $this->default_page);
            $html = '';
            // File Check.
            if (file_exists($path)) {
                $data = array();
                $this->fetchData($tab, $data);
                // Processing View.
                $html = $generalHelper->processBaseView($path, $data);
            }
            echo $html;
        }

        /**
         * @param $tab
         * @return mixed
         */
        public function getPath(&$tab)
        {
            $this->checkAccess($tab);
            $pages = $this->adminPages();
            // Default tab.
            $path = $pages[$this->default_page];

            // Comparing Available Tab with Active Tab.
            if (isset($pages[$tab])) {
                $path = $pages[$tab];
            }
            return $path;
        }

        /**
         * @param $type
         * @param $data
         */
        public function fetchData($type, &$data)
        {
            $postData = \FlycartInput\FInput::getInstance();
            $request = $postData->getArray();

            $helper = new FlycartWooDiscountRulesGeneralHelper();
            $isPro = $helper->checkPluginState();

            switch ($type) {
                // Managing Price Rules View.
                case 'pricing-rules':
                    $pricing_rule = $this->getInstance('FlycartWooDiscountRulesPricingRules');
                    $data = $pricing_rule->getRules();
                    break;
                // Managing Cart Rules View.
                case 'cart-rules':
                    $cart_rule = $this->getInstance('FlycartWooDiscountRulesCartRules');
                    $data = $cart_rule->getRules();
                    break;
                // Managing View of Settings.
                case 'settings':
                    $data = $this->getBaseConfig();
                    break;

                // Managing View of Pricing Rules.
                case 'pricing-rules-new':
                    $data = new stdClass();
                    $data->form = 'pricing_rules_save';
                    if (!$isPro) {
                        $pricing_rule = $this->getInstance('FlycartWooDiscountRulesPricingRules');
                        $data = $pricing_rule->getRules();
                        if (count($data) >= 3) die('You are restricted to process this action.');
                    }
                    break;

                // Managing View of Pricing Rules.
                case 'pricing-rules-view':

                    $view = false;
                    // Handling View
                    if (isset($request['view'])) {
                        $view = $request['view'];
                    }
                    $html = $this->getInstance('FlycartWooDiscountRulesPricingRules');
                    $out = $html->view($type, $view);
                    if (isset($out) && !empty($out)) {
                        $data = $out;
                    }
                    $data->form = 'pricing_rules_save';
                    break;

                // Managing View of Cart Rules.
                case 'cart-rules-view':
                    $view = false;
                    // Handling View
                    if (isset($request['view'])) {
                        $view = $request['view'];
                    } else {

                        if (!$isPro) {
                            $cart_rule = $this->getInstance('FlycartWooDiscountRulesCartRules');
                            $total_record = $cart_rule->getRules(true);
                            if ($total_record >= 3) wp_die('You are restricted to process this action.');
                        }
                    }

                    $html = $this->getInstance('FlycartWooDiscountRulesCartRules');
                    $out = $html->view($type, $view);
                    if (isset($out) && !empty($out)) {
                        $data[] = $out;
                    }
                    break;
                // Managing View of Cart Rules.
                case 'cart-rules-new':
                    if (!$isPro) {
                        $cart_rule = $this->getInstance('FlycartWooDiscountRulesCartRules');
                        $total_record = $cart_rule->getRules(true);
                        if ($total_record >= 3) wp_die('You are restricted to process this action.');
                    }
                    break;

                default:
                    $data = array();

                    break;
            }

        }

        /**
         * @return array
         */
        public function adminPages()
        {
            return array(
                $this->default_page => WOO_DISCOUNT_DIR . '/view/pricing-rules.php',
                'cart-rules' => WOO_DISCOUNT_DIR . '/view/cart-rules.php',
                'settings' => WOO_DISCOUNT_DIR . '/view/settings.php',

                // New Rule also access the same "View" to process
                'pricing-rules-new' => WOO_DISCOUNT_DIR . '/view/view-pricing-rules.php',
                'cart-rules-new' => WOO_DISCOUNT_DIR . '/view/view-cart-rules.php',

                // Edit Rules
                'pricing-rules-view' => WOO_DISCOUNT_DIR . '/view/view-pricing-rules.php',
                'cart-rules-view' => WOO_DISCOUNT_DIR . '/view/view-cart-rules.php'
            );
        }

        /**
         *
         */
        public function getOption()
        {

        }

        /**
         * Adding Admin Page Script.
         */
        function woo_discount_adminPageScript()
        {
            $status = false;
            $postData = \FlycartInput\FInput::getInstance();
            // Plugin scripts should run only in plugin page.
            if (is_admin()) {
                if ($postData->get('page', false) == 'woo_discount_rules') {
                    $status = true;
                }
                // By Default, the landing page also can use this script.
            } elseif (!is_admin()) {
                //  $status = true;
            }

            if ($status) {

                $config = $this->getBaseConfig();
                if (is_string($config)) $config = json_decode($config, true);
                $enable_bootstrap = isset($config['enable_bootstrap'])? $config['enable_bootstrap']: 1;

                wp_register_style('woo_discount_style', WOO_DISCOUNT_URI . '/assets/css/style.css', array(), WOO_DISCOUNT_VERSION);
                wp_enqueue_style('woo_discount_style');

                wp_register_style('woo_discount_style_custom', WOO_DISCOUNT_URI . '/assets/css/custom.css', array(), WOO_DISCOUNT_VERSION);
                wp_enqueue_style('woo_discount_style_custom');

                wp_register_style('woo_discount_style_tab', WOO_DISCOUNT_URI . '/assets/css/tabbablePanel.css', array(), WOO_DISCOUNT_VERSION);
                wp_enqueue_style('woo_discount_style_tab');

                // For Implementing Select Picker Library.
                wp_register_style('woo_discount_style_select', WOO_DISCOUNT_URI . '/assets/css/bootstrap.select.min.css', array(), WOO_DISCOUNT_VERSION);
                wp_enqueue_style('woo_discount_style_select');

                wp_enqueue_script('woo_discount_script_select', WOO_DISCOUNT_URI . '/assets/js/bootstrap.select.min.js', array(), WOO_DISCOUNT_VERSION);

                wp_register_style('woo_discount_bootstrap', WOO_DISCOUNT_URI . '/assets/css/bootstrap.min.css', array(), WOO_DISCOUNT_VERSION);
                wp_enqueue_style('woo_discount_bootstrap');

                if($enable_bootstrap){
                    wp_register_script('woo_discount_jquery_ui_js_2', WOO_DISCOUNT_URI . '/assets/js/bootstrap.min.js', array(), WOO_DISCOUNT_VERSION);
                    wp_enqueue_script('woo_discount_jquery_ui_js_2');
                }

                wp_register_style('woo_discount_jquery_ui_css', WOO_DISCOUNT_URI . '/assets/css/jquery-ui.css', array(), WOO_DISCOUNT_VERSION);
                wp_enqueue_style('woo_discount_jquery_ui_css');
                wp_register_style('woo_discount_datetimepicker_css', WOO_DISCOUNT_URI . '/assets/css/bootstrap-datetimepicker.min.css', array(), WOO_DISCOUNT_VERSION);
                wp_enqueue_style('woo_discount_datetimepicker_css');

                wp_enqueue_script('jquery');
                wp_enqueue_script('jquery-ui-core');
                wp_enqueue_script('jquery-ui-datepicker');
                wp_enqueue_script( 'woocommerce_admin' );

                wp_enqueue_script('woo_discount_datetimepicker_js', WOO_DISCOUNT_URI . '/assets/js/bootstrap-datetimepicker.min.js', array(), WOO_DISCOUNT_VERSION);
                wp_enqueue_script('woo_discount_script', WOO_DISCOUNT_URI . '/assets/js/app.js', array(), WOO_DISCOUNT_VERSION);

                //To load woocommerce product select
                wp_enqueue_script( 'wc-enhanced-select' );
                wp_enqueue_style( 'woocommerce_admin_styles' );
            }
        }

    }
}