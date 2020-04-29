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
		'name'           => 'Free',
		'price'          => 0,
		'features'       => array(
			'Feature',
			'Feature',
		),
		'dot_list'       => array(
			'Lorem ipsum dolor sit amet',
			'Lorem ipsum dolor sit amet',
		),
		'zoom'           => false,
		'class_button'   => 'button-choose-plan',
		'message_button' => __( 'Choose this plan', 'ear2words' ),
	),
	array(
		'stripe_code'    => 'plan_HBBbNjLjVk3w4w',
		'name'           => 'Standard',
		'price'          => 180,
		'features'       => array(
			'Feature',
			'Feature',
			'Feature',
			'Feature',
			'Feature',
			'Feature',
		),
		'dot_list'       => array(
			'Lorem ipsum dolor sit amet',
			'Lorem ipsum dolor sit amet',
			'Lorem ipsum dolor sit amet',
			'Lorem ipsum dolor sit amet',
			'Lorem ipsum dolor sit amet',
		),
		'zoom'           => true,
		'class_button'   => 'button-choose-plan',
		'message_button' => __( 'Choose this plan', 'ear2words' ),
	),
	array(
		'stripe_code'    => 'plan_HBBS5I9usXvwQR',
		'name'           => 'Elite',
		'price'          => 200,
		'features'       => array(
			'Lorem ipsum dolor sit amet',
			'Feature',
		),
		'dot_list'       => array(
			'Lorem ipsum dolor sit amet',
			'Lorem ipsum dolor sit amet',
		),
		'zoom'           => false,
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
