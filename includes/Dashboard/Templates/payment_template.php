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
$disable_downgrade_message = __( 'Unable this select this plan during the first month of subscription for current plan', 'ear2words' );
$message_free              = 'Choose this plan';
$message_standard          = 'Choose this plan';
$message_elite             = 'Choose this plan';
$class_free                = 'button-choose-plan';
$class_standard            = 'button-choose-plan';
$class_elite               = 'button-choose-plan';
switch ( get_option( 'ear2words_plan' ) ) {
	case 'plan_0':
		$class_free   = 'current-plan';
		$message_free = 'Current Plan';
		break;
	case 'plan_HBBbNjLjVk3w4w':
		if ( get_option( 'ear2words_is_first_month' ) ) {
			$class_free   = 'disable-downgrade';
			$message_free = $disable_downgrade_message;
		}
		$class_standard   = 'current-plan';
		$message_standard = 'Current Plan';
		break;
	case 'plan_HBBS5I9usXvwQR':
		if ( get_option( 'ear2words_is_first_month' ) ) {
			$class_free       = 'disable-downgrade';
			$class_standard   = 'disable-downgrade';
			$message_free     = $disable_downgrade_message;
			$message_standard = $disable_downgrade_message;
		}
		$class_elite   = 'current-plan';
		$message_elite = 'Current Plan';
		break;
}
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
										Per month
									</div>
									<div class="price">
										€0
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
								<div class="<?php echo esc_attr( $class_free ); ?>" plan="plan_0">
									<?php echo esc_html( $message_free ); ?>
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
										Standard
									</div>
									<div class="card-logo">
									</div>
								</div>
								<div class="card-price">
									<div class="year">
										Per month
									</div>
									<div class="price">
										€19
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
							<div class="<?php echo esc_attr( $class_standard ); ?>" plan="plan_HBBbNjLjVk3w4w">
								<?php echo esc_html( $message_standard ); ?>
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
										Elite
									</div>
									<div class="card-logo">
									</div>
								</div>
								<div class="card-price">
									<div class="year">
										Per month
									</div>
									<div class="price">
										€49
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
							<div class="<?php echo esc_attr( $class_elite ); ?>" plan="plan_HBBS5I9usXvwQR">
								<?php echo esc_html( $message_elite ); ?>
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
