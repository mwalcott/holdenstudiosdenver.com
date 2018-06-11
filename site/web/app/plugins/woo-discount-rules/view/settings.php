<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

$active = 'settings';
include_once(WOO_DISCOUNT_DIR . '/view/includes/header.php');
include_once(WOO_DISCOUNT_DIR . '/view/includes/menu.php');

$data = $config;

if (is_string($data)) $data = json_decode($data, true);
$isPro = (new FlycartWooDiscountRulesPurchase())->isPro();
?>

<div class="container-fluid woo_discount_loader_outer">
    <div class="row-fluid">
        <div class="<?php echo $isPro? 'col-md-12': 'col-md-8'; ?>">
            <form method="post" id="discount_config">
                <div class="col-md-12" align="right">
                    <input type="submit" id="saveConfig" value="Save" class="btn btn-success">
                </div>
                <div class="">
                    <div class="">
                        <h4><?php esc_html_e('General Settings', 'woo-discount-rules'); ?></h4>
                        <hr>
                    </div>
                    <div class="">
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>
                                    <?php esc_html_e('License Key :', 'woo-discount-rules'); ?>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="" name="license_key" id="woo-disc-license-key"
                                       value="<?php if (isset($data['license_key'])) echo $data['license_key']; ?>"
                                       placeholder="Your Unique License Key">
                                <input type="button" id="woo-disc-license-check" value="Validate Key" class="button button-info">
                                <?php
                                $verifiedLicense = get_option('woo_discount_rules_verified_key', 0);
                                if (isset($data['license_key']) && $data['license_key'] != '') {
                                    if ($verifiedLicense) {
                                        ?>
                                        <span class="license-success">&#10004;</span>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="license-failed notice-message error inline notice-error notice-alt">
                                            <?php esc_html_e('License key seems to be Invalid. Please enter a valid license key', 'woo-discount-rules'); ?>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                                <br>
                                <div id="woo-disc-license-check-msg">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>
                                    <?php esc_html_e('Enable Bootstrap', 'woo-discount-rules'); ?>
                                </label>
                            </div>
                            <?php $data['enable_bootstrap'] = (isset($data['enable_bootstrap']) ? $data['enable_bootstrap'] : 1); ?>
                            <div class="col-md-6">
                                <label><input type="radio" name="enable_bootstrap" value="1" <?php echo ($data['enable_bootstrap'] == 1)? 'checked': '' ?>/> <?php esc_html_e('Yes', 'woo-discount-rules'); ?></label>
                                <label><input type="radio" name="enable_bootstrap" value="0" <?php echo ($data['enable_bootstrap'] == 0)? 'checked': '' ?> /> <?php esc_html_e('No', 'woo-discount-rules'); ?></label>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>
                                    <?php esc_html_e('Force refresh the cart widget while add and remove item to cart', 'woo-discount-rules'); ?>
                                </label>
                            </div>
                            <?php $data['force_refresh_cart_widget'] = (isset($data['force_refresh_cart_widget']) ? $data['force_refresh_cart_widget'] : 0); ?>
                            <div class="col-md-6">
                                <label><input type="radio" name="force_refresh_cart_widget" value="1" <?php echo ($data['force_refresh_cart_widget'] == 1)? 'checked': '' ?>/> <?php esc_html_e('Yes', 'woo-discount-rules'); ?></label>
                                <label><input type="radio" name="force_refresh_cart_widget" value="0" <?php echo ($data['force_refresh_cart_widget'] == 0)? 'checked': '' ?> /> <?php esc_html_e('No', 'woo-discount-rules'); ?></label>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <h4><?php esc_html_e('Price rules settings', 'woo-discount-rules'); ?></h4>
                                <hr>
                            </div>
                        </div>
                        <div class="row form-group">
                            <?php $data['price_setup'] = (isset($data['price_setup']) ? $data['price_setup'] : 'first'); ?>
                            <div class="col-md-2">
                                <label>
                                    <?php esc_html_e('Rule Setup for Price:', 'woo-discount-rules'); ?>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <select class="selectpicker" name="price_setup">
                                    <option <?php if ($data['price_setup'] == 'first') { ?> selected=selected <?php } ?>
                                        value="first" selected="selected"><?php esc_html_e('Apply first matched rule', 'woo-discount-rules'); ?>
                                    </option>
                                    <option
                                        value="all" <?php if (!$pro) { ?> disabled <?php }
                                    if ($data['price_setup'] == 'all') { ?> selected=selected <?php } ?>>
                                        <?php if (!$pro) { ?>
                                            <?php esc_html_e('Apply all matched rules', 'woo-discount-rules'); ?> <b><?php echo $suffix; ?></b>
                                        <?php } else { ?>
                                            <?php esc_html_e('Apply all matched rules', 'woo-discount-rules'); ?>
                                        <?php } ?>
                                    </option>
                                    <option
                                        value="biggest" <?php if (!$pro) { ?> disabled <?php }
                                    if ($data['price_setup'] == 'biggest') { ?> selected=selected <?php } ?>>
                                        <?php if (!$pro) { ?>
                                            <?php esc_html_e('Apply biggest discount', 'woo-discount-rules'); ?> <b><?php echo $suffix; ?></b>
                                        <?php } else { ?>
                                            <?php esc_html_e('Apply biggest discount', 'woo-discount-rules'); ?>
                                        <?php } ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <?php $data['show_price_discount_on_product_page'] = (isset($data['show_price_discount_on_product_page']) ? $data['show_price_discount_on_product_page'] : 'dont'); ?>
                            <div class="col-md-2">
                                <label>
                                    <?php esc_html_e('Show Price discount on product page :', 'woo-discount-rules'); ?>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <select class="selectpicker" name="show_price_discount_on_product_page">
                                    <option <?php if ($data['show_price_discount_on_product_page'] == 'show') { ?> selected=selected <?php } ?>
                                            value="show"><?php esc_html_e('Show', 'woo-discount-rules'); ?>
                                    </option>
                                    <option <?php if ($data['show_price_discount_on_product_page'] == 'dont') { ?> selected=selected <?php } ?>
                                            value="dont"><?php esc_html_e('Don\'t Show', 'woo-discount-rules'); ?>
                                    </option>
                                </select>
                                <div class="notice notice-info"><p><?php esc_html_e('It displays only if any rule matches', 'woo-discount-rules'); ?></p></div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <?php $data['show_sale_tag_on_product_page'] = (isset($data['show_sale_tag_on_product_page']) ? $data['show_sale_tag_on_product_page'] : 'dont'); ?>
                            <div class="col-md-2">
                                <label>
                                    <?php esc_html_e('Show Sale tag on product page :', 'woo-discount-rules'); ?>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <select class="selectpicker" name="show_sale_tag_on_product_page">
                                    <option <?php if ($data['show_sale_tag_on_product_page'] == 'show') { ?> selected=selected <?php } ?>
                                            value="show"><?php esc_html_e('Show', 'woo-discount-rules'); ?>
                                    </option>
                                    <option <?php if ($data['show_sale_tag_on_product_page'] == 'dont') { ?> selected=selected <?php } ?>
                                            value="dont"><?php esc_html_e('Don\'t Show', 'woo-discount-rules'); ?>
                                    </option>
                                </select>
                                <div class="notice notice-info"><p><?php esc_html_e('It displays only if any rule matches', 'woo-discount-rules'); ?></p></div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <?php $data['show_discount_table'] = (isset($data['show_discount_table']) ? $data['show_discount_table'] : 'show'); ?>
                            <div class="col-md-2">
                                <label>
                                    <?php esc_html_e('Discount Table :', 'woo-discount-rules'); ?>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <select class="selectpicker" name="show_discount_table">
                                    <option <?php if ($data['show_discount_table'] == 'show') { ?> selected=selected <?php } ?>
                                            value="show"><?php esc_html_e('Show', 'woo-discount-rules'); ?>
                                    </option>
                                    <option <?php if ($data['show_discount_table'] == 'dont') { ?> selected=selected <?php } ?>
                                            value="dont"><?php esc_html_e('Don\'t Show', 'woo-discount-rules'); ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <?php $data['show_discount_title_table'] = (isset($data['show_discount_title_table']) ? $data['show_discount_title_table'] : 'show'); ?>
                            <div class="col-md-2">
                                <label>
                                    <?php esc_html_e('Show Discount Title on Table :', 'woo-discount-rules'); ?>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <select class="selectpicker" name="show_discount_title_table">
                                    <option <?php if ($data['show_discount_title_table'] == 'show') { ?> selected=selected <?php } ?>
                                            value="show"><?php esc_html_e('Show', 'woo-discount-rules'); ?>
                                    </option>
                                    <option <?php if ($data['show_discount_title_table'] == 'dont') { ?> selected=selected <?php } ?>
                                            value="dont"><?php esc_html_e('Don\'t Show', 'woo-discount-rules'); ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>
                                    <?php esc_html_e('Show strikeout discount in cart item', 'woo-discount-rules'); ?>
                                </label>
                            </div>
                            <?php $data['show_strikeout_in_cart'] = (isset($data['show_strikeout_in_cart']) ? $data['show_strikeout_in_cart'] : 1); ?>
                            <div class="col-md-6">
                                <label><input type="radio" name="show_strikeout_in_cart" value="1" <?php echo ($data['show_strikeout_in_cart'] == 1)? 'checked': '' ?>/> <?php esc_html_e('Yes', 'woo-discount-rules'); ?></label>
                                <label><input type="radio" name="show_strikeout_in_cart" value="0" <?php echo ($data['show_strikeout_in_cart'] == 0)? 'checked': '' ?> /> <?php esc_html_e('No', 'woo-discount-rules'); ?></label>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <h4><?php esc_html_e('Cart rules settings', 'woo-discount-rules'); ?></h4>
                                <hr>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>
                                    <?php esc_html_e('Coupon Name to be displayed :', 'woo-discount-rules'); ?>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="" name="coupon_name"
                                       value="<?php if (isset($data['coupon_name'])) echo $data['coupon_name']; ?>"
                                       placeholder="<?php esc_html_e('Discount Coupon Name', 'woo-discount-rules'); ?>">
                            </div>
                        </div>
                        <div class="row form-group">
                            <?php $data['cart_setup'] = (isset($data['cart_setup']) ? $data['cart_setup'] : 'first'); ?>
                            <div class="col-md-2">
                                <label>
                                    <?php esc_html_e('Rule Setup for Cart:', 'woo-discount-rules'); ?>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <select class="selectpicker" name="cart_setup">
                                    <option <?php if ($data['cart_setup'] == 'first') { ?> selected=selected <?php } ?>
                                        value="first"><?php esc_html_e('Apply first matched rule', 'woo-discount-rules'); ?>
                                    </option>
                                    <option
                                        value="all" <?php if (!$pro) { ?> disabled <?php }
                                    if ($data['cart_setup'] == 'all') { ?> selected=selected <?php } ?>>
                                        <?php if (!$pro) { ?>
                                            <?php esc_html_e('Apply all matched rules', 'woo-discount-rules'); ?> <b><?php echo $suffix; ?></b>
                                        <?php } else { ?>
                                            <?php esc_html_e('Apply all matched rules', 'woo-discount-rules'); ?>
                                        <?php } ?>
                                    </option>
                                    <option
                                        value="biggest" <?php if (!$pro) { ?> disabled <?php }
                                    if ($data['cart_setup'] == 'biggest') { ?> selected=selected <?php } ?>>
                                        <?php if (!$pro) { ?>
                                            <?php esc_html_e('Apply biggest discount', 'woo-discount-rules'); ?> <b><?php echo $suffix; ?></b>
                                        <?php } else { ?>
                                            <?php esc_html_e('Apply biggest discount', 'woo-discount-rules'); ?>
                                        <?php } ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>
                                    <?php esc_html_e('Disable the rules while have coupon(Third party) in cart', 'woo-discount-rules'); ?>
                                </label>
                            </div>
                            <?php $data['do_not_run_while_have_third_party_coupon'] = (isset($data['do_not_run_while_have_third_party_coupon']) ? $data['do_not_run_while_have_third_party_coupon'] : 0); ?>
                            <div class="col-md-6">
                                <label><input type="radio" name="do_not_run_while_have_third_party_coupon" value="1" <?php echo ($data['do_not_run_while_have_third_party_coupon'] == 1)? 'checked': '' ?>/> <?php esc_html_e('Yes', 'woo-discount-rules'); ?></label>
                                <label><input type="radio" name="do_not_run_while_have_third_party_coupon" value="0" <?php echo ($data['do_not_run_while_have_third_party_coupon'] == 0)? 'checked': '' ?> /> <?php esc_html_e('No', 'woo-discount-rules'); ?></label>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>
                                    <?php esc_html_e('Enable free shipping option', 'woo-discount-rules'); ?>
                                </label>
                            </div>
                            <?php $data['enable_free_shipping'] = (isset($data['enable_free_shipping']) ? $data['enable_free_shipping'] : "none"); ?>
                            <div class="col-md-6">
                                <?php
                                if(!$isPro){
                                    esc_html_e('Supported in PRO version', 'woo-discount-rules');
                                    ?>
                                    <select name="enable_free_shipping" id="enable_free_shipping" style="display: none">
                                        <option value="none"><?php esc_html_e('Disabled', 'woo-discount-rules'); ?></option>
                                    </select>
                                    <?php
                                } else {
                                    ?>
                                    <select class="selectpicker" name="enable_free_shipping" id="enable_free_shipping">
                                        <option <?php if ($data['enable_free_shipping'] == "none") { ?> selected=selected <?php } ?>
                                                value="none"><?php esc_html_e('Disabled', 'woo-discount-rules'); ?>
                                        </option>
                                        <option <?php if ($data['enable_free_shipping'] == "free_shipping") { ?> selected=selected <?php } ?>
                                                value="free_shipping"><?php esc_html_e('Use Woocommerce free shipping', 'woo-discount-rules'); ?>
                                        </option>
                                        <option <?php if ($data['enable_free_shipping'] == "woodiscountfree") { ?> selected=selected <?php } ?>
                                                value="woodiscountfree"><?php esc_html_e('Use Woo-Discount free shipping', 'woo-discount-rules'); ?>
                                        </option>
                                    </select>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                        if($isPro){
                            ?>
                            <div class="row form-group" id="woodiscount_settings_free_shipping_con">
                                <div class="col-md-2">
                                    <label>
                                        <?php esc_html_e('Free shipping text to be displayed', 'woo-discount-rules'); ?>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <?php $data['free_shipping_text'] = ((isset($data['free_shipping_text']) && !empty($data['free_shipping_text'])) ? $data['free_shipping_text'] : __( 'Free Shipping', 'woo-discount-rules' )); ?>
                                    <input type="text" class="" name="free_shipping_text"
                                           value="<?php echo $data['free_shipping_text']; ?>"
                                           placeholder="<?php esc_html_e('Free Shipping title', 'woo-discount-rules'); ?>">
                                </div>
                            </div>
                        <?php } ?>
                        <div class="row form-group" style="display: none"><!-- Hide this because it is not required after v1.4.36 -->
                            <div class="col-md-2">
                                <label>
                                    <?php esc_html_e('Draft', 'woo-discount-rules'); ?>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <?php
                                $checked = 0;
                                if (isset($data['show_draft']) && $data['show_draft'] == 1){
                                    $checked = 1;
                                } ?>
                                <input type="checkbox" class="" id="show_draft_1" name="show_draft"
                                       value="1" <?php if($checked){ echo 'checked'; } ?>> <label class="checkbox_label" for="show_draft_1"><?php esc_html_e('Exclude Draft products in product select box.', 'woo-discount-rules'); ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="ajax_path" value="<?php echo admin_url('admin-ajax.php') ?>">
        </div>
        <?php if(!$isPro){ ?>
            <div class="col-md-1"></div>
            <!-- Sidebar -->
            <?php include_once(__DIR__ . '/template/sidebar.php'); ?>
            <!-- Sidebar END -->
        <?php } ?>
        </form>
    </div>
    <div class="woo_discount_loader">
        <div class="lds-ripple"><div></div><div></div></div>
    </div>
</div>