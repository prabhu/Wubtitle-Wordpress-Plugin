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
	<?php // phpcs:disable ?>
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.typekit.net/auk4ruc.css">
	<link rel="stylesheet" href="<?php echo esc_url( EAR2WORDS_URL . 'src/css/payment_template.css' ); ?>">
	<?php // phpcs:enable ?>
</head>
<body>
<<<<<<< HEAD
=======
	<div class="warning" id="error-message" style="color:red; text-align:center"></div>
	<h1><?php esc_html_e( 'Select Plan', 'ear2words' ); ?></h1>
	<form method="POST" id="form">
		<select name="pricing_plan" id="select">
			<option value="plan_H6i0TeOPhpY6DN">Premium</option>
			<option value="plan_H6KKmWETz5hkCu">Standard</option>
		</select>
		<input type="submit" value="Submit">
	</form>
>>>>>>> origin/dev

	<div class="wrapper">
		<div class="container">
			<div class="title">Choose the right plan for your project</div>
			<div class="card-container">
				<div class="card-column">
					<div class="card">
						<div class="card-content">
							<div>
								<div class="card-header">
									<div class="card-title">
										Free
									</div>
									<div class="card-logo">									
									</div>
								</div>

								<div class="card-price">
									<div class="year">
										Per year
									</div>
									<div class="price">
										€180
									</div>
								</div>

								<div class="card-features">
									<div class="row">
										<div>Feature on</div>
										<div>include</div>
									</div>
									<div class="row">
										<div>Feature on</div>
										<div>include</div>
									</div>
									<div class="row">
										<div>Feature on</div>
										<div>include</div>
									</div>
								</div>
							</div>
							<div class="current-plan" plan="plan_H6KKmWETz5hkCu">
								Current plan
							</div>							
						</div>  
					</div>
					<div class="features-list">
						<ul>
							<li>Lorem ipsum dolor sit amet</li>
							<li>Lorem ipsum dolor sit amet</li>
							<li>Lorem ipsum dolor sit amet</li>
							<li>Lorem ipsum dolor sit amet</li>
							<li>Lorem ipsum dolor sit amet</li>
						</ul>
					</div>
				</div>
				<div class="card-column">
					<div class="card zoom" id="test-card">
						<div class="card-content">
							<div>
								<div class="card-header">
									<div class="card-title">
										Professional
									</div>
									<div class="card-logo">									
									</div>
								</div>
								<div class="card-price">
									<div class="year">
										Per year
									</div>
									<div class="price">
										€180
									</div>
								</div>
								<div class="card-features">
									<div class="row">
										<div>Feature on</div>
										<div>include</div>
									</div>
									<div class="row">
										<div>Feature on</div>
										<div>include</div>
									</div>
									<div class="row">
										<div>Feature on</div>
										<div>include</div>
									</div>
								</div>
							</div>
							<div class="button-choose-plan" plan="plan_H6KKmWETz5hkCu">
								Choose this plan
							</div>							
						</div>  
					</div>
					<div class="features-list">
						<ul>
							<li>Lorem ipsum dolor sit amet</li>
							<li>Lorem ipsum dolor sit amet</li>
							<li>Lorem ipsum dolor sit amet</li>
							<li>Lorem ipsum dolor sit amet</li>
							<li>Lorem ipsum dolor sit amet</li>
						</ul>
					</div>
				</div>
				<div class="card-column">
					<div class="card">
						<div class="card-content">
							<div>
								<div class="card-header">
									<div class="card-title">
										Enterprise
									</div>
									<div class="card-logo">									
									</div>
								</div>
								<div class="card-price">
									<div class="year">
										Per year
									</div>
									<div class="price">
										€180
									</div>
								</div>
								<div class="card-features">
									<div class="row">
										<div>Feature on</div>
										<div>include</div>
									</div>
									<div class="row">
										<div>Feature on</div>
										<div>include</div>
									</div>
									<div class="row">
										<div>Feature on</div>
										<div>include</div>
									</div>
									<div class="row">
										<div>Feature on</div>
										<div>include</div>
									</div>
									<div class="row">
										<div>Feature on</div>
										<div>include</div>
									</div>
									<div class="row">
										<div>Feature on</div>
										<div>include</div>
									</div>
									<div class="row">
										<div>Feature on</div>
										<div>include</div>
									</div>
								</div>
							</div>
							<div class="button-choose-plan" plan="plan_H6KKmWETz5hkCu">
								Choose this plan
							</div>							
						</div>  
					</div>
					<div class="features-list">
						<ul>
							<li>Lorem ipsum dolor sit amet</li>
							<li>Lorem ipsum dolor sit amet</li>
							<li>Lorem ipsum dolor sit amet</li>
							<li>Lorem ipsum dolor sit amet</li>
							<li>Lorem ipsum dolor sit amet</li>
						</ul>
					</div>
				</div>
				<div class="card-column">
					<div class="card">						
						<div class="card-content">
							<div>
								<div class="card-header">
									<div class="card-title">
										Business
									</div>
									<div class="card-logo">									
									</div>
								</div>
								<div class="card-price">
									<div class="year">
										Per year
									</div>
									<div class="price">
										€180
									</div>
								</div>
								<div class="card-features">
									<div class="row">
										<div>Feature on</div>
										<div>include</div>
									</div>
									<div class="row">
										<div>Feature on</div>
										<div>include</div>
									</div>
									<div class="row">
										<div>Feature on</div>
										<div>include</div>
									</div>
								</div>
							</div>
							<div class="button-choose-plan" plan="plan_H6KKmWETz5hkCu">
								Choose this plan
							</div>							
						</div>  
					</div>
					<div class="features-list">
						<ul>
							<li>Lorem ipsum dolor sit amet</li>
							<li>Lorem ipsum dolor sit amet</li>
							<li>Lorem ipsum dolor sit amet</li>
							<li>Lorem ipsum dolor sit amet</li>
							<li>Lorem ipsum dolor sit amet</li>
						</ul>
					</div>
				</div>
			</div>

		</div>   
	</div> 
	<?php // phpcs:disable ?>
	<script>
		const WP_GLOBALS = {
			adminAjax: "<?php echo esc_html( admin_url( 'admin-ajax.php' ) ); ?>",
			nonce: "<?php echo esc_js( wp_create_nonce( 'itr_ajax_nonce' ) ); ?>"
		}
	</script>
	<script src="https://js.stripe.com/v3/"></script>
	<script src="<?php echo esc_url(EAR2WORDS_URL . 'src/payment/payment_template.js'); ?>"></script>
	<?php // phpcs:enable ?>
</body>
</html>
