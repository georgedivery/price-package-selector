<?php
defined( 'ABSPATH' ) || exit;

global $product;

// Example custom packages (can be dynamic from ACF or options later)
$packages = [
    [
        'label' => 'Basic',
        'price' => wc_price( $product->get_price() ),
        'value' => 'basic',
    ],
    [
        'label' => 'Standard',
        'price' => wc_price( $product->get_price() + 10 ),
        'value' => 'standard',
    ],
    [
        'label' => 'Premium',
        'price' => wc_price( $product->get_price() + 20 ),
        'value' => 'premium',
    ],
];
?>

<div class="pps-wrapper">
    <p><strong>Choose a Package:</strong></p>
    <div class="pps-options">
        <?php foreach ( $packages as $pkg ) : ?>
            <label class="pps-option">
                <input type="radio" name="pps_package" value="<?php echo esc_attr( $pkg['value'] ); ?>">
                <span class="pps-label"><?php echo esc_html( $pkg['label'] ); ?> - <?php echo $pkg['price']; ?></span>
            </label>
        <?php endforeach; ?>
    </div>

    <button type="submit" class="single_add_to_cart_button button alt">
        <?php esc_html_e( 'Buy Now', 'woocommerce' ); ?>
    </button>
</div>
