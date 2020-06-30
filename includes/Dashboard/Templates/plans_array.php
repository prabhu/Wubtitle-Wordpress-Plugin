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
$plans = array(
	array(
		'stripe_code'    => 'plan_0',
		'name'           => __( 'Free', 'wubtitle' ),
		'price'          => 0,
		'features'       => array(
			__( 'Number of video', 'wubtitle' )        => __( '3', 'wubtitle' ),
			__( 'Total length of videos', 'wubtitle' ) => __( '30 min', 'wubtitle' ),
		),
		'dot_list'       => array(
			__( 'Mp4 Video format allowed', 'wubtitle' ),
			__( 'Recognized languages: English and Italian', 'wubtitle' ),
		),
		'zoom'           => false,
		'current_plan'   => true,
		'icon'           => 'smile.svg',
		'class_button'   => 'button-choose-plan',
		'message_button' => __( 'Choose this plan', 'wubtitle' ),
	),
	array(
		'stripe_code'    => 'plan_HBBbNjLjVk3w4w',
		'name'           => __( 'Professional', 'wubtitle' ),
		'price'          => 19,
		'features'       => array(
			__( 'Number of video', 'wubtitle' )        => __( '10', 'wubtitle' ),
			__( 'Total length of videos', 'wubtitle' ) => __( '3 hours', 'wubtitle' ),
		),
		'dot_list'       => array(
			__( 'All wordpress formats supported by wordpress', 'wubtitle' ),
			__( 'Recognized languuages: English, Italian, German, French, Spanish and Chinese', 'wubtitle' ),
		),
		'zoom'           => false,
		'current_plan'   => false,
		'icon'           => 'fire.svg',
		'class_button'   => 'button-choose-plan',
		'message_button' => __( 'Choose this plan', 'wubtitle' ),
	),
	array(
		'stripe_code'    => 'plan_HBBS5I9usXvwQR',
		'name'           => __( 'Elite', 'wubtitle' ),
		'price'          => 49,
		'features'       => array(
			__( 'Number of video', 'wubtitle' )        => __( '30', 'wubtitle' ),
			__( 'Total length of videos', 'wubtitle' ) => __( '10 hours', 'wubtitle' ),
		),
		'dot_list'       => array(
			__( 'All wordpress formats supported by wordpress', 'wubtitle' ),
			__( 'Recognized languuages: English, Italian, German, French, Spanish and Chinese', 'wubtitle' ),
		),
		'zoom'           => false,
		'current_plan'   => false,
		'icon'           => 'rocket.svg',
		'class_button'   => 'button-choose-plan',
		'message_button' => __( 'Choose this plan', 'wubtitle' ),
	),
);

$disable_downgrade_message = __( 'Unable this select this plan during the first month of subscription for current plan', 'wubtitle' );
switch ( get_option( 'wubtitle_plan' ) ) {
	case 'plan_0':
		$plans[0]['class_button']   = 'current-plan';
		$plans[0]['message_button'] = __( 'Your plan', 'wubtitle' );
		break;
	case 'plan_HBBbNjLjVk3w4w':
		if ( get_option( 'wubtitle_is_first_month' ) ) {
			$plans[0]['class_button']   = 'disable-downgrade';
			$plans[0]['message_button'] = $disable_downgrade_message;
		}
		$plans[1]['class_button']   = 'current-plan';
		$plans[1]['message_button'] = __( 'Your plan', 'wubtitle' );
		break;
	case 'plan_HBBS5I9usXvwQR':
		if ( get_option( 'wubtitle_is_first_month' ) ) {
			$plans[0]['class_button']   = 'disable-downgrade';
			$plans[1]['class_button']   = 'disable-downgrade';
			$plans[0]['message_button'] = $disable_downgrade_message;
			$plans[1]['message_button'] = $disable_downgrade_message;
		}
		$plans[2]['class_button']   = 'current-plan';
		$plans[2]['message_button'] = __( 'Your plan', 'wubtitle' );
		break;
}
switch ( get_option( 'wubtitle_plan' ) ) {
	case 'plan_0':
		$plans[1]['zoom'] = true;
		break;
	case 'plan_HBBbNjLjVk3w4w':
	case 'plan_HBBS5I9usXvwQR':
		$plans[2]['zoom'] = true;
		break;
}
