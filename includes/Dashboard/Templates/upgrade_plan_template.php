<?php
/**
 * Confirm plan upgrade page.
 *
 * @author     Alessio Catania
 * @since      1.0.0
 * @package    Wubtitle\Dashboar\Templates
 */

/**
 * Pagina per l'upgrade.
 */
require WUBTITLE_DIR . 'includes/Dashboard/Templates/plans_array.php';
$data           = get_option( 'wubtitle_expiration_date' );
$new_data       = $data + DAY_IN_SECONDS;
$data           = date_i18n( get_option( 'date_format' ), $data );
$new_data       = date_i18n( get_option( 'date_format' ), $new_data );
$amount_preview = isset( $amount_preview ) ? number_format( (float) $amount_preview, 2 ) : 0.00;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Payment</title>
</head>
<body>
	<div class="container">
		<h1 class="title"><?php esc_html_e( 'Subscription plan upgrade', 'wubtitle' ); ?></h1>
		<p class="paragraph-center"> <?php esc_html_e( 'Upgrading now, for the first month you are entired to a partial refund of previous monthly subscription plan', 'wubtitle' ); ?> </p>
		<div class="row margin_medium">
			<?php if ( isset( $plans, $current_rank ) ) : ?>
			<div class="column one-quarter">
				<img class="card_plan" src="<?php echo esc_url( WUBTITLE_URL . 'assets/img/' . $plans[ $current_rank ]['icon'] ); ?>">
				<h1 class="title" > <?php echo esc_html( $plans[ $current_rank ]['name'] ); ?> </h1>
			</div>
			<?php endif; ?>
			<?php if ( isset( $plans, $wanted_plan_rank ) ) : ?>
			<div class="column one-quarter">
				<h1 style="text-align:center">
					<span class="new_price"> <?php echo isset( $price_info_object ) ? esc_html( $price_info_object[ $wanted_plan_rank ]->price . '€' ) : ''; ?></span>
				</h1>
				<img class="arrowup" src="<?php echo esc_url( WUBTITLE_URL . 'assets/img/arrowup.svg' ); ?>">
			</div>
			<div class="column one-quarter">
				<img class="card_plan" src="<?php echo esc_url( WUBTITLE_URL . 'assets/img/' . $plans[ $wanted_plan_rank ]['icon'] ); ?>">
				<h1 class="title" >  <?php echo esc_html( $plans[ $wanted_plan_rank ]['name'] ); ?> </h1>
			</div>
			<?php endif; ?>
		</div>
		<div class="container">
		<div class="container-row">
			<p><?php esc_html_e( 'You will pay:', 'wubtitle' ); ?></p>
			<table>
				<tr>
					<td><span class="price-preview"><?php echo esc_html( $amount_preview . '€' ); ?></span></td>
					<td><?php esc_html_e( '(once)', 'wubtitle' ); ?></td>
					<td>
					<?php
					esc_html_e( 'Until ', 'wubtitle' );
					echo esc_html( $data );
					?>
					</td>
				</tr>
				<tr>
					<td><span class="price-preview"><?php echo isset( $price_info_object, $wanted_plan_rank ) ? esc_html( $price_info_object[ $wanted_plan_rank ]->price . '€' ) : ''; ?></span></td>
					<td><?php esc_html_e( '(monthly)', 'wubtitle' ); ?></td>
					<td>
					<?php
					esc_html_e( 'From ', 'wubtitle' );
					echo esc_html( $new_data );
					?>
					</td>
				</tr>
			</table>
			<div class="has-tooltip">
				<span>
					<?php esc_html_e( 'Current Payment details', 'wubtitle' ); ?>
				</span>
				<i class="fas fa-info-circle"></i>
					<div class="tooltip">
							<p><strong><?php esc_html_e( 'Name: ', 'wubtitle' ); ?></strong> <?php echo isset( $name ) ? esc_html( $name ) : ''; ?> </p>
							<p><strong><?php esc_html_e( 'Email: ', 'wubtitle' ); ?></strong> <?php echo isset( $email ) ? esc_html( $email ) : ''; ?> </p>
							<p><strong><?php esc_html_e( 'Card: ', 'wubtitle' ); ?></strong> <?php echo isset( $card_number ) ? esc_html( $card_number ) : ''; ?> </p>
							<p><strong><?php esc_html_e( 'Expiration Date: ', 'wubtitle' ); ?></strong> <?php echo isset( $expiration ) ? esc_html( $expiration ) : ''; ?> </p>
					</div>
			</div>
			<p><?php esc_html_e( 'The subtitles already created and the minutes already used will be counted on the new subscription plan', 'wubtitle' ); ?></p>
		</div>
		<div class="confirm-change-section">
			<div class="buttons">
				<div class="button unsubscribe" id="forget" ><?php echo esc_html_e( 'Forget it', 'wubtitle' ); ?></div>
				<div class="button" id="confirm_changes" ><?php echo esc_html_e( 'Upgrade Now', 'wubtitle' ); ?></div>
			</div>
		</div>
	</div>
	<?php wp_footer(); ?>
</body>
</html>
