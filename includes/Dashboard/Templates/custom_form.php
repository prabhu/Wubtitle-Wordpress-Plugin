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
	<?php // phpcs:enable ?>
	<title>Payment</title>
	</head>
	<body>
	<div id="root"></div>
	</body>
	<script>
		const WP_GLOBALS = {
			clientId: "<?php echo isset( $client_id ) ? esc_html( $client_id ) : ''; ?>"
		}
	</script>
	<?php // phpcs:disable ?>
	<script src="<?php echo esc_url( WUBTITLE_URL . 'build_form/index.js' ); ?>"></script>
	<?php // phpcs:enable ?>
</html>
