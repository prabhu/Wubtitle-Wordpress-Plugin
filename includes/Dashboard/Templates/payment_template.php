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

	<script>
	document.addEventListener("DOMContentLoaded", function() {

		var stripe = Stripe('pk_test_nfUYjFiwdkzYpPOfCZkVZiMK00lOAFcAK7');
		const form = document.querySelector('#form');

		form.addEventListener("submit", (e) => {
			e.preventDefault();
			const select = document.querySelector('#select').value;
			const nonce = "<?php echo esc_html( wp_create_nonce( 'itr_ajax_nonce' ) ); ?>";

			const request = new XMLHttpRequest();
			request.open(
				"POST",
				"<?php echo esc_html( admin_url( 'admin-ajax.php' ) ); ?>",
				true
			);
			request.onload = function() {
				if (this.status >= 200 && this.status < 400) {
					const response = JSON.parse(this.response);
					if (response.success) {
						console.log(this.response);
						stripe.redirectToCheckout({
							sessionId: response.data,
						}).then(function (result) {
							console.log(result.error.message)
						});
					}else {
						alert(response.data);
					}
				}
			};
			request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			request.send("action=submit_plan&_ajax_nonce="+ nonce +"&pricing_plan="+select);		
		});

	});
	</script>
</body>
</html>
