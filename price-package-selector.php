<?php
/**
 * Plugin Name: Price Package Selector
 * Plugin URI: https://webbeb.com
 * Description: Replaces WooCommerce quantity & add to cart button with custom package selector.
 * Version: 1.2
 * Author: Webbeb
 * Author URI: https://webbeb.com
 * License: GPL2
 */

 if (!defined('ABSPATH')) exit;

 // Hide quantity input
// Enqueue styles & JS
add_action( 'wp_enqueue_scripts', function() {
    if ( is_product() ) {
        wp_enqueue_style(
            'pps-style',
            plugin_dir_url( __FILE__ ) . 'assets/style.css',
            [],
            '1.1'
        );

        wp_add_inline_style('pps-style', 'input.qty { display: none !important; }');
    }
});
 
 
 add_action('woocommerce_before_add_to_cart_button', function () {
    global $product;
    if ($product->is_type('variable')) {
        $regular_price = floatval($product->get_variation_regular_price('min', true));
    } else {
        $regular_price = floatval($product->get_regular_price());
    }
 
    $price_1 = $regular_price;
    $price_2 = $regular_price * 2 * 0.90;
    $price_3 = $regular_price * 3 * 0.85;
    $price_2_off = ($regular_price * 2) - $price_2;
    $price_3_off = ($regular_price * 3) - $price_3;
     ?>
     <div class="cq-options" >
     <div class="cq-option-item">
            <label>
                <input type="radio" name="cq_option" value="1" checked>
                <span>1 бр. 
                    <strong><?php echo wc_price($price_1); ?></strong>
                    <small>(Редовна цена)</small>
                </span>
                <i></i>
            </label>
        </div>
        <div class="cq-option-item">
            <label>
                <input type="radio" name="cq_option" value="2">
                <span>
                    2 броя 
                    <strong><?php echo wc_price($price_2); ?></strong>
                    <small>(Спестяваш <?php echo number_format($price_2_off,2) ; ?>лв.)</small>
                </span>
                <i></i>
            </label>
        </div>
        <div class="cq-option-item">
            <label>
                <input type="radio" name="cq_option" value="3">
                <span>3 броя 
                    <strong><?php echo wc_price($price_3); ?> </strong>
                    
                    <small>(Спестяваш <?php echo number_format($price_3_off,2); ?>лв.)</small>
                </span>
                <i></i>
            </label>
        </div>
     </div>
     <script>
         document.addEventListener('DOMContentLoaded', function () {
             const radios = document.querySelectorAll('input[name="cq_option"]');
             const qtyInput = document.querySelector('input.qty');
 
             radios.forEach(radio => {
                 radio.addEventListener('change', function () {
                     if (qtyInput) qtyInput.value = this.value;
                 });
             });
 
             const selected = document.querySelector('input[name="cq_option"]:checked');
             if (qtyInput && selected) qtyInput.value = selected.value;
         });
     </script>
     <?php
 }, 5);
 
 //   change the price based on the quantity
 add_action('woocommerce_before_calculate_totals', function ($cart) {
     if (is_admin() && !defined('DOING_AJAX')) return;
 
     foreach ($cart->get_cart() as $cart_item) {
         $product = $cart_item['data'];
         $base_price = floatval($product->get_regular_price());
         $qty = $cart_item['quantity'];
 
         // calculate a discount
         $discount = 0;
         if ($qty == 2) $discount = 0.10;
          elseif ($qty >= 3) {
            $discount = 0.15;
        }
 
         $final_price = $base_price * (1 - $discount);
         $product->set_price($final_price);
     }
 });

 add_filter('woocommerce_cart_item_name', function($name, $cart_item, $cart_item_key) {
    $qty = $cart_item['quantity'];

    if ($qty == 2) {
        $name .= ' – Пакет 2 (отстъпка 10%)';
    } elseif ($qty >= 3) {
        $name .= ' – Пакет 3 (отстъпка 15%)';
    }

    return $name;
}, 10, 3);