<?php
/**
 * Stripe Form template.
 *
 * @author     Alessio Catania
 * @since      0.1.0
 * @package    Wubtitle\Dashboard\Templates
 */

/**
 * Stripe Form template.
 */
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
	<meta charset="utf-8">
	<?php // phpcs:disable ?>
	<link rel="stylesheet" href="<?php echo esc_url( WUBTITLE_URL . 'assets/css/stripeStyle.css' ); ?>">
	<link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css"
    />
	<?php // phpcs:enable ?>
	<title>Payment</title>
	</head>
	<body>
	<div id="root"></div>
	</body>
	<script>
		const WP_GLOBALS = {
			planId: "<?php echo isset( $wanted_plan ) ? esc_js( $wanted_plan['stripe_code'] ) : ''; ?>",
			pricePlan: "<?php echo isset( $wanted_plan ) ? esc_js( $wanted_plan['price'] ) : ''; ?>",
			namePlan: "<?php echo isset( $wanted_plan ) ? esc_js( $wanted_plan['name'] ) : ''; ?>",
			ajaxUrl: "<?php echo isset( $ajax_url ) ? esc_js( $ajax_url ) : ''; ?>",
			ajaxNonce: "<?php echo esc_js( wp_create_nonce( 'itr_ajax_nonce' ) ); ?>",
			wubtitleEnv: "<?php echo defined( 'WP_WUBTITLE_ENV' ) ? esc_js( WP_WUBTITLE_ENV ) : ''; ?>"
		}
	</script>
	<?php // phpcs:disable ?>
	<script src="<?php echo esc_url( WUBTITLE_URL . 'build_form/index.js' ); ?>"></script>
	<?php // phpcs:enable ?>
</html>
