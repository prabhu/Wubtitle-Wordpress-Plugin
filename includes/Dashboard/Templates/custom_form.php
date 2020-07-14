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
			planId: "<?php echo isset( $plan_id ) ? esc_html( $plan_id ) : ''; ?>",
			ajaxUrl: "<?php echo isset( $ajax_url ) ? esc_html( $ajax_url ) : ''; ?>",
			ajaxNonce: "<?php echo esc_js( wp_create_nonce( 'itr_ajax_nonce' ) ); ?>"
		}
	</script>
	<?php // phpcs:disable ?>
	<script src="<?php echo esc_url( WUBTITLE_URL . 'build_form/index.js' ); ?>"></script>
	<?php // phpcs:enable ?>
</html>
