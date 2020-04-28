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
require EAR2WORDS_DIR . 'includes/Dashboard/Templates/plans_array.php'
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
	<div class="container">
		<h1 class="title">Choose the right plan for your project</h1>
		<div class="card-row">
		<?php
		foreach ( $plans as $plan ) {
			?>
			<div class="card-column">
				<div class="card">
					<h2 class="card-title">
						<?php echo esc_html( $plan['name'] ); ?>
					</h2>
					<div class="card-logo">									
					</div>
					<div class="card-price">
						Per year
						<p class="price">
							€ <?php echo esc_html( $plan['price'] ); ?>
						</p>
					</div>
					<?php
					foreach ( $plan['features'] as $feature ) {
						?>
					<div class="card-features-row">
						<?php echo esc_html( $feature ); ?>
						<div>include</div>
					</div>
						<?php
					}
					?>
					<div class="button-choose-plan" plan="<?php echo esc_html( $plan['stripe_code'] ); ?>">
						Choose this plan
					</div>
				</div>
				<ul class="features-list">
					<?php
					foreach ( $plan['dot_list'] as $dot ) {
						?>
					<li><?php echo esc_html( $dot ); ?></li>
						<?php
					}
					?>
				</ul>
			</div>
			<?php
		}
		?>
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
