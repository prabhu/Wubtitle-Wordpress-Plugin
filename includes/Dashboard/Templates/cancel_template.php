<?php
/**
 * This file is a template.
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Wubtitle\Dashboar\Templates
 */

/**
 * This template displays cancel page.
 */

require WUBTITLE_DIR . 'includes/Dashboard/Templates/plans_array.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Cancel subscription</title>
	<?php // phpcs:disable ?>
	<link href="https://fonts.googleapis.com/css?family=Days+One|Open+Sans&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo esc_url( WUBTITLE_URL . 'assets/css/payment_template.css' ); ?>">
	<?php // phpcs:enable ?>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="column">
				<div class="unsubscribe-section">
					<h1 class="title"><?php echo esc_html_e( 'Are you sure you want to unsubscribe?', 'wubtitle' ); ?></h1>
					<p><?php echo esc_html_e( 'Are you sure you want to cancel your subscription? If you choose to continue, when the subscription expires your plan will return to free version and you will lose all the additional features', 'wubtitle' ); ?></p>
					<div class="buttons">
						<div class="button unsubscribe" id="unsubscribeButton"><?php echo esc_html_e( 'Return to free version', 'wubtitle' ); ?></div>
						<div class="button" id="close"><?php echo esc_html_e( 'Forget it', 'wubtitle' ); ?></div>
					</div>
					<div id="message"><!-- From JS --></div>
				</div>
			</div>
		</div>
		<h1 class="title"><?php echo esc_html_e( 'Or choose another plan', 'wubtitle' ); ?></h1>
		<div class="row">
		<?php
		if ( isset( $plans ) ) :
			foreach ( $plans as $plan ) :
				?>
			<div class="column one-quarter">
				<div class="card <?php echo $plan['zoom'] ? 'zoom' : ''; ?>">
					<div class="card__header">
						<h2 class="card__title">
							<?php echo esc_html( $plan['name'] ); ?>
						</h2>
						<img class="card__logo" src="<?php echo esc_url( WUBTITLE_URL . 'assets/img/' . $plan['icon'] ); ?>">
					</div>
					<div class="card__price">
						<?php echo esc_html_e( 'Per month', 'wubtitle' ); ?>
						<p class="price">
							<?php echo esc_html( '€' . $plan['price'] ); ?>
						</p>
					</div>
					<?php
					foreach ( $plan['features'] as $key => $feature ) :
						?>
					<p class="card__features">
						<span><?php echo esc_html( $key ); ?></span>
						<?php echo esc_html( $feature ); ?>
					</p>
						<?php
					endforeach;
					?>
					<div class="<?php echo esc_attr( $plan['class_button'] ); ?>" plan="<?php echo esc_html( $plan['stripe_code'] ); ?>">
						<?php echo esc_html( $plan['message_button'] ); ?>
					</div>
				</div>
			</div>
				<?php
		endforeach;
	endif;
		?>
		</div>
		<div class="row">
		<?php
		if ( isset( $plans ) ) :
			foreach ( $plans as $plan ) :
				?>
			<div class="column one-quarter">
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
		endif;
		?>
		</div>
	</div>

	<?php // phpcs:disable ?>
	<script src="https://js.stripe.com/v3/"></script>
	<script>
		const WP_GLOBALS = {
			adminAjax: "<?php echo esc_html( admin_url( 'admin-ajax.php' ) ); ?>",
			nonce: "<?php echo esc_js( wp_create_nonce( 'itr_ajax_nonce' ) ); ?>",
			wubtitleEnv: "<?php echo defined( 'WP_WUBTITLE_ENV' ) ? WP_WUBTITLE_ENV : ''; ?>"
		}
	</script>
	<script src="<?php echo esc_url(WUBTITLE_URL . 'assets/payment/payment_template.js'); ?>"></script>
	<?php // phpcs:enable ?>
</body>
</html>
