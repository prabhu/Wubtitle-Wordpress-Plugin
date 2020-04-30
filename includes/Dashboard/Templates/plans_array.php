<?php
/**
 * This file is a array template.
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Ear2Words\Dashboar\Templates
 */

/**
 * This array describes all avaiable plans for users.
 */
$plans = array(
	array(
		'stripe_code'    => 'plan_0',
		'name'           => __( 'Free', 'ear2words' ),
		'price'          => 0,
		'features'       => array(
			'Number of video'        => __( '3', 'ear2words' ),
			'Total length of videos' => __( '30 min', 'ear2words' ),
		),
		'dot_list'       => array(
			__( 'Mp3 Video format allowed', 'ear2words' ),
			__( 'Recognized languages: English and Italian', 'ear2words' ),
		),
		'zoom'           => false,
		'current_plan'   => true,
		'icon'           => 'smile.svg',
		'class_button'   => 'button-choose-plan',
		'message_button' => __( 'Choose this plan', 'ear2words' ),
	),
	array(
		'stripe_code'    => 'plan_HBBbNjLjVk3w4w',
		'name'           => __( 'Standard', 'ear2words' ),
		'price'          => 180,
		'features'       => array(
			'Number of video'        => __( '10', 'ear2words' ),
			'Total length of videos' => __( '3 hours', 'ear2words' ),
		),
		'dot_list'       => array(
			__( 'All wordpress formats supported by wordpress', 'ear2words' ),
			__( 'Recognized languuages: English, Italian, German, French, Spanish and Chinese', 'ear2words' ),
		),
		'zoom'           => true,
		'current_plan'   => false,
		'icon'           => 'fire.svg',
		'class_button'   => 'button-choose-plan',
		'message_button' => __( 'Choose this plan', 'ear2words' ),
	),
	array(
		'stripe_code'    => 'plan_HBBS5I9usXvwQR',
		'name'           => __( 'Elite', 'ear2words' ),
		'price'          => 200,
		'features'       => array(
			'Number of video'        => __( '30', 'ear2words' ),
			'Total length of videos' => __( '10 hours', 'ear2words' ),
		),
		'dot_list'       => array(
			__( 'All wordpress formats supported by wordpress', 'ear2words' ),
			__( 'Recognized languuages: English, Italian, German, French, Spanish and Chinese', 'ear2words' ),
		),
		'zoom'           => false,
		'current_plan'   => false,
		'icon'           => 'rocket.svg',
		'class_button'   => 'button-choose-plan',
		'message_button' => __( 'Choose this plan', 'ear2words' ),
	),
);

$disable_downgrade_message = __( 'Unable this select this plan during the first month of subscription for current plan', 'ear2words' );
switch ( get_option( 'ear2words_plan' ) ) {
	case 'plan_0':
		$plans[0]['class_button']   = 'current-plan';
		$plans[0]['message_button'] = __( 'Your plan', 'ear2words' );
		break;
	case 'plan_HBBbNjLjVk3w4w':
		if ( get_option( 'ear2words_is_first_month' ) ) {
			$plans[0]['class_button']   = 'disable-downgrade';
			$plans[0]['message_button'] = $disable_downgrade_message;
		}
		$plans[1]['class_button']   = 'current-plan';
		$plans[1]['message_button'] = __( 'Your plan', 'ear2words' );
		break;
	case 'plan_HBBS5I9usXvwQR':
		if ( get_option( 'ear2words_is_first_month' ) ) {
			$plans[0]['class_button']   = 'disable-downgrade';
			$plans[1]['class_button']   = 'disable-downgrade';
			$plans[0]['message_button'] = $disable_downgrade_message;
			$plans[1]['message_button'] = $disable_downgrade_message;
		}
		$plans[2]['class_button']   = 'current-plan';
		$plans[2]['message_button'] = __( 'Your plan', 'ear2words' );
		break;
}
