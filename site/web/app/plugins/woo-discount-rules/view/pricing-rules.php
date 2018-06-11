<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

$active = 'pricing-rules';
include_once(WOO_DISCOUNT_DIR . '/view/includes/header.php');
include_once(WOO_DISCOUNT_DIR . '/view/includes/menu.php');

$config = (isset($config)) ? $config : '{}';

$data = array();
$rule_list = $config;
$isPro = (new FlycartWooDiscountRulesPurchase())->isPro();
?>

<style>
    @media screen and (max-width: 600px) {
        table {
            width: 100%;
        }

        thead {
            display: none;
        }

        tr:nth-of-type(2n) {
            background-color: inherit;
        }

        tr td:first-child {
            background: #f0f0f0;
            font-weight: bold;
            font-size: 1.3em;
        }

        tbody td {
            display: block;
            text-align: left;
        }

        tbody td:before {
            content: attr(data-th);
            display: block;
            text-align: left;
        }
    }
</style>

<div class="container-fluid" id="pricing_rule">
    <div class="row-fluid">
        <div class="<?php echo $isPro? 'col-md-12': 'col-md-8'; ?>">
            <div class="">
                <div class="row">
                    <div class="col-md-8">
                        <h4><?php esc_html_e('Price Rules', 'woo-discount-rules'); ?></h4>
                    </div>
                    <div class="col-md-4 text-right">
                        <br/>
                        <a href="https://www.flycart.org/woocommerce-discount-rules-examples#pricediscountexample" target="_blank" class="btn btn-info"><?php esc_html_e('View Examples', 'woo-discount-rules'); ?></a>
                        <a href="http://docs.flycart.org/woocommerce-discount-rules/price-discount-rules" target="_blank" class="btn btn-info"><?php esc_html_e('Documentation', 'woo-discount-rules'); ?></a>
                    </div>
                    <hr>
                </div>
                <div class="">
                    <form method="post" action="?page=woo_discount_rules">
                        <div class="row">
                            <div class="col-md-4">
                                <?php if (isset($rule_list)) {
                                    if (count($rule_list) >= 3 && !$pro) { ?>
                                        <a href=javascript:void(0) class="btn btn-primary"><?php esc_html_e('You Reach Max. Rule Limit', 'woo-discount-rules'); ?></a>
                                    <?php } else {
                                        ?>
                                        <a href="?page=woo_discount_rules&type=new" id="add_new_rule"
                                           class="btn btn-primary"><?php esc_html_e('Add New Rule', 'woo-discount-rules'); ?></a>
                                        <?php
                                    }
                                }

                                ?>
                            </div>
                            <div class="col-md-12">
                                <code><?php esc_html_e('NOTE: Order Should not be empty (\'-\').If it\'s empty(\'-\'), then it won\'t be implemented.', 'woo-discount-rules'); ?></code>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="">
                                <table class="wp-list-table widefat fixed striped posts">
                                    <thead>
                                    <tr>
                                        <td><?php esc_html_e('Name', 'woo-discount-rules'); ?></td>
                                        <td><?php esc_html_e('Start Date', 'woo-discount-rules'); ?></td>
                                        <td><?php esc_html_e('Expired On', 'woo-discount-rules'); ?></td>
                                        <td><?php esc_html_e('Order', 'woo-discount-rules'); ?></td>
                                        <td><?php esc_html_e('Action', 'woo-discount-rules'); ?></td>
                                    </tr>
                                    </thead>
                                    <tbody id="pricing_rule">
                                    <?php
                                    $i = 1;
                                    if (is_array($rule_list)) {
                                        if (count($rule_list) > 0) {
                                            foreach ($rule_list as $index => $rule) {
                                                if (!$pro && $i > 3) continue;
                                                $meta = $rule->meta;
                                                $status = isset($meta['status'][0]) ? $meta['status'][0] : 'disable';
                                                $class = 'btn btn-success';

                                                if ($status == 'publish') {
                                                    $class = 'btn btn-warning';
                                                    $value = esc_html__('Disable', 'woo-discount-rules');
                                                } else {
                                                    $class = 'btn btn-success';
                                                    $value = esc_html__('Enable', 'woo-discount-rules');;
                                                }
                                                ?>

                                                <tr>
                                                    <td><?php echo(isset($rule->rule_name) ? $rule->rule_name : '-') ?></td>
                                                    <td><?php echo(isset($rule->date_from) ? $rule->date_from : '-') ?></td>
                                                    <td><?php echo(isset($rule->date_to) ? $rule->date_to : '-') ?></td>
                                                    <td><?php echo((isset($rule->rule_order) && ($rule->rule_order != '')) ? $rule->rule_order : ' - ') ?></td>
                                                    <td>
                                                        <a class="btn btn-primary" href="?page=woo_discount_rules&view=<?php echo $rule->ID ?>">
                                                            <?php esc_html_e('Edit', 'woo-discount-rules'); ?>
                                                        </a>
                                                        <a class="<?php echo $class; ?> manage_status"
                                                           id="state_<?php echo $rule->ID ?>"><?php echo $value; ?>
                                                        </a>
                                                        <a class="btn btn-danger delete_rule" id="delete_<?php echo $rule->ID ?>">
                                                            <?php esc_html_e('Delete', 'woo-discount-rules'); ?>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php
                                                $i++;
                                            }
                                        }
                                    }
                                    ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td><?php esc_html_e('Name', 'woo-discount-rules'); ?></td>
                                        <td><?php esc_html_e('Start Date', 'woo-discount-rules'); ?></td>
                                        <td><?php esc_html_e('Expired On', 'woo-discount-rules'); ?></td>
                                        <td><?php esc_html_e('Order', 'woo-discount-rules'); ?></td>
                                        <td><?php esc_html_e('Action', 'woo-discount-rules'); ?></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <hr>

                        <input type="hidden" name="form" value="pricing_rules">
                        <input type="hidden" id="ajax_path" value="<?php echo admin_url('admin-ajax.php') ?>">
                    </form>
                </div>
            </div>
        </div>
        <?php if(!$isPro){ ?>
            <div class="col-md-1"></div>
            <!-- Sidebar -->
            <?php include_once(__DIR__ . '/template/sidebar.php'); ?>
            <!-- Sidebar END -->
        <?php } ?>
    </div>
</div>
<div class="clear"></div>
<?php include_once(WOO_DISCOUNT_DIR . '/view/includes/footer.php'); ?>