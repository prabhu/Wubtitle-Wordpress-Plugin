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
	<title>Payment</title>
</head>
<body>
	<h1><?php esc_html_e( 'Select Plan', 'ear2words' ); ?></h1>
	<form method="POST" id="form">
		<select name="pricing_plan" id="select">
			<option value="plan_H6i0TeOPhpY6DN">Premium</option>
			<option value="plan_H6KKmWETz5hkCu">Standard</option>
		</select>
		<input type="submit" value="Submit">
	</form> 

	<?php // phpcs:disable ?>
	<script src="https://js.stripe.com/v3/"></script>
	<script src="<?php esc_url(EAR2WORDS_URL . '/src/payment/payment_template.js'); ?>"></script>	
	<?php // phpcs:enable ?>
</body>
</html>
