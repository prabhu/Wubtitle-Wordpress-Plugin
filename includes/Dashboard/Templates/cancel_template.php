<?php
/**
 * This file is a template.
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Ear2Words\Dashboar\Templates
 */

/**
 * This is a template.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Cancel subscription</title>
</head>
<body>
	<h1><?php esc_html_e( 'Cancel Subscription', 'ear2words' ); ?></h1>
	<p>Sei sicuro?</p>
	<form method="POST" id="form">
		<select name="pricing_plan" id="select">
			<option value="si">Sì</option>
			<option value="no">No</option>
		</select>
		<input type="submit" value="Submit">
	</form> 

	<?php // phpcs:disable ?>
	<script src="https://js.stripe.com/v3/"></script>
	<script>
		const WP_GLOBALS = {
			adminAjax: "<?php echo esc_html( admin_url( 'admin-ajax.php' ) ); ?>",
			nonce: "<?php echo esc_js( wp_create_nonce( 'itr_ajax_nonce' ) ); ?>"
		}	
	</script>
	<script src="<?php echo esc_url(EAR2WORDS_URL . 'src/payment/cancel_template.js'); ?>"></script>
	<?php // phpcs:enable ?>
</body>
</html>
