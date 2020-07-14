<?php
/**
 * This file is a template.
 *
 * @author     Alessio Catania
 * @since      0.1.0
 * @package    Wubtitle\Dashboard\Templates
 */

/**
 * This template displays the update plan page.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php // phpcs:disable ?>
	<link rel="stylesheet" href="<?php echo esc_url( WUBTITLE_URL . 'assets/css/stripeStyle.css' ); ?>">
	<?php // phpcs:enable ?>
	<title>Update Payment</title>
</head>
<body>
	<div id="update-form"></div>
	<script>
		const WP_GLOBALS = {
			ajaxUrl: "<?php echo esc_html( admin_url( 'admin-ajax.php' ) ); ?>",
			ajaxNonce: "<?php echo esc_js( wp_create_nonce( 'itr_ajax_nonce' ) ); ?>",
			wubtitleEnv: "<?php echo defined( 'WP_WUBTITLE_ENV' ) ? esc_html( WP_WUBTITLE_ENV ) : ''; ?>"
		}
	</script>
	<?php // phpcs:disable ?>
	<script src="<?php echo esc_url(WUBTITLE_URL . 'build_form/index.js'); ?>"></script>
	<?php // phpcs:enable ?>
</body>
</html>
