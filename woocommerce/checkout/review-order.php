<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="col-md-6 d-flex">
   <div class="cart-detail cart-total bg-light p-3 p-md-4">
        <h3 class="billing-heading mb-4">Cart Total</h3>
        <p class="d-flex">
				<span>Subtotal</span>
				<span><?php wc_cart_totals_subtotal_html(); ?></span>
        </p>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
					<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
					<p class="d-flex">
							<span><?php echo esc_html( $tax->label ); ?></span>
							<span><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
                    </p>
				<?php endforeach; ?>				
			<?php else : ?>
				    <p class="d-flex">
							<span><?php echo esc_html( $tax->label ); ?></span>
							<span><?php wc_cart_totals_taxes_total_html(); ?></span>
                    </p>
			<?php endif; ?>
		<?php endif; ?>
		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

		<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

		<?php wc_cart_totals_shipping_html(); ?>

		<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<p class="d-flex">
				<span>Discount</span>
				<span><?php wc_cart_totals_fee_html( $fee ); ?></span>
            </p>
		<?php endforeach; ?>
		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<p class="d-flex">
				<span><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
				<span><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
            </p>
		<?php endforeach; ?>

        <hr>
		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>
        <p class="d-flex total-price">
				<span>Total</span>
				<span><?php wc_cart_totals_order_total_html(); ?></span>
        </p>
		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
    </div>
</div>


