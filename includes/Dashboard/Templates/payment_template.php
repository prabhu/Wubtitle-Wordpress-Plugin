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
require EAR2WORDS_DIR . 'includes/Dashboard/Templates/plans_array.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Payment</title>
	<?php // phpcs:disable ?>
	<link href="https://fonts.googleapis.com/css?family=Days+One|Open+Sans&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo esc_url( EAR2WORDS_URL . 'src/css/payment_template.css' ); ?>">
	<?php // phpcs:enable ?>
</head>
<body>
	<div class="container">
		<h1 class="title"><?php echo esc_html_e( 'Choose the right plan for your project', 'ear2words' ); ?></h1>
		<div class="row">
		<?php
		foreach ( $plans as $plan ) :
			?>
			<div class="column one-quarter">
				<div class="card <?php echo $plan['zoom'] ? 'zoom' : ''; ?>">
					<h2 class="card__title">
						<?php echo esc_html( $plan['name'] ); ?>
					</h2>
					<img class="card__logo" src="<?php echo esc_html( EAR2WORDS_URL ) . 'src/img/' . esc_html( $plan['icon'] ); ?>">
					<div class="card__price">
						<?php echo esc_html_e( 'Per year', 'ear2words' ); ?>
						<p class="price">
							<?php echo esc_html( '€' . $plan['price'] ); ?>
						</p>
					</div>
					<?php
					foreach ( $plan['features'] as $feature ) :
						?>
					<div class="card__features">
						<?php echo esc_html( $feature ); ?>
						<div><?php echo esc_html_e( 'include', 'ear2words' ); ?></div>
					</div>
						<?php
					endforeach;
					?>
					<div class="<?php echo esc_attr( $plan['class_button'] ); ?>" plan="<?php echo esc_html( $plan['stripe_code'] ); ?>">
						<?php echo esc_html( $plan['message_button'] ); ?>
					</div>
				</div>
				<ul class="features-list">
					<?php
					foreach ( $plan['dot_list'] as $dot ) :
						?>
					<li><?php echo esc_html( $dot ); ?></li>
						<?php
					endforeach;
					?>
				</ul>
			</div>
			<?php
		endforeach;
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
