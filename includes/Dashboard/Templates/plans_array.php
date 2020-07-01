<?php
/**
 * This file is a array template.
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Wubtitle\Dashboar\Templates
 */

/**
 * This array describes all avaiable plans for users.
 */

wp_cache_delete( 'wubtitle_plan', 'options' );
wp_cache_delete( 'wubtitle_is_first_month', 'options' );
$plans                     = get_option( 'wubtitle_plans' );
$currentplan               = get_option( 'wubtitle_plan' );
$is_first_month            = get_option( 'wubtitle_is_first_month' );
$disable_downgrade_message = __( 'Unable this select this plan during the first month of subscription for current plan', 'wubtitle' );

foreach ( $plans as $key => $plan ) {
	$plans[ $key ]['current_plan']   = false;
	$plans[ $key ]['class_button']   = 'button-choose-plan';
	$plans[ $key ]['message_button'] = __( 'Choose this plan', 'wubtitle' );
	$plans[ $key ]['features']       = array(
		__( 'Number of video', 'wubtitle' )        => $plans[ $key ]['totalJobs'],
		__( 'Total length of videos', 'wubtitle' ) => date_i18n( 'H:i', $plans[ $key ]['totalSeconds'] ) . ' h',
	);
	if ( $plans[ $key ]['stripe_code'] === $currentplan ) {
		$plans[ $key ]['current_plan']   = true;
		$plans[ $key ]['class_button']   = 'current-plan';
		$plans[ $key ]['message_button'] = __( 'Your plan', 'wubtitle' );
	}
}
