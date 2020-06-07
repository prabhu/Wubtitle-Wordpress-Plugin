<?php
/**
 * Confirm plan downgrade template.
 *
 * @author     Alessio Catania
 * @since      1.0.0
 * @package    Wubtitle\Dashboar\Templates
 */

/**
 * Downgrade page template.
 */
require WUBTITLE_DIR . 'includes/Dashboard/Templates/plans_array.php';
$amount_preview = (float) get_option( 'wubtitle_amount_preview' );
$amount_preview = -$amount_preview;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Payment</title>
	<?php // phpcs:disable ?>
	<link href="https://fonts.googleapis.com/css?family=Days+One|Open+Sans&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo esc_url( WUBTITLE_URL . 'src/css/payment_template.css' ); ?>">
	<?php // phpcs:enable ?>
</head>
<body>
	<div class="container">
		<h1 class="title"><?php esc_html_e( 'Subscription plan downgrade', 'wubtitle' ); ?></h1>
		<p class="paragraph-center"> <?php esc_html_e( 'Downgrading now, you will earn a credit that will be billed to you at the next charge', 'wubtitle' ); ?> </p>
		<div class="row margin_medium">
			<div class="column one-quarter">
				<img class="card_plan" src="<?php echo esc_url( WUBTITLE_URL . 'src/img/' . $plans[ $wanted_plan ]['icon'] ); ?>">
				<h1 class="title" >  <?php echo esc_html( $plans[ $wanted_plan ]['name'] ); ?> </h1>
			</div>
			<div class="column one-quarter">
				<h1 style="text-align:center; margin-top:64px;"> <span class="refund"><?php echo esc_html( $amount_preview . '€' . __( ' credit earnings', 'wubtitle' ) ); ?></span> </h1>
		<img class="arrowdown" src="<?php echo esc_url( WUBTITLE_URL . 'src/img/arrowdown.svg' ); ?>">
			</div>
			<div class="column one-quarter">
				<img class="card_plan" src="<?php echo esc_url( WUBTITLE_URL . 'src/img/' . $plans[ $current_plan ]['icon'] ); ?>">
				<h1 class="title" > <?php echo esc_html( $plans[ $current_plan ]['name'] ); ?> </h1>
			</div>
		</div>
		<div class="confirm-change-section">
			<p class="confirm-paragraph"> <?php esc_html_e( 'The subtitles already created and the minutes already used will be counted on the new subscription plan', 'wubtitle' ); ?> </p>
			<div class="buttons">
				<div class="button unsubscribe" id="confirm_changes"><?php esc_html_e( 'Downgrade Now', 'wubtitle' ); ?></div>
				<div class="button" id="forget" ><?php esc_html_e( 'Forget it', 'wubtitle' ); ?></div>
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
	<script src="<?php echo esc_url(WUBTITLE_URL . 'src/payment/change_plan_script.js'); ?>"></script>
	<?php // phpcs:enable ?>
</body>
</html>
