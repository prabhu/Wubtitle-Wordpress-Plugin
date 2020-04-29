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
		'stripe_code'  => 'plan_0',
		'name'         => 'Free',
		'price'        => 0,
		'features'     => array(
			'Feature',
			'Feature',
		),
		'dot_list'     => array(
			'Lorem ipsum dolor sit amet',
			'Lorem ipsum dolor sit amet',
		),
		'zoom'         => false,
		'current_plan' => true,
		'icon'         => 'smile.svg',
	),
	array(
		'stripe_code'  => 'plan_HBBbNjLjVk3w4w',
		'name'         => 'Standard',
		'price'        => 180,
		'features'     => array(
			'Feature',
			'Feature',
			'Feature',
			'Feature',
			'Feature',
			'Feature',
		),
		'dot_list'     => array(
			'Lorem ipsum dolor sit amet',
			'Lorem ipsum dolor sit amet',
			'Lorem ipsum dolor sit amet',
			'Lorem ipsum dolor sit amet',
			'Lorem ipsum dolor sit amet',
		),
		'zoom'         => true,
		'current_plan' => false,
		'icon'         => 'fire.svg',
	),
	array(
		'stripe_code'  => 'plan_HBBS5I9usXvwQR',
		'name'         => 'Elite',
		'price'        => 200,
		'features'     => array(
			'Lorem ipsum dolor sit amet',
			'Feature',
		),
		'dot_list'     => array(
			'Lorem ipsum dolor sit amet',
			'Lorem ipsum dolor sit amet',
		),
		'zoom'         => false,
		'current_plan' => false,
		'icon'         => 'rocket.svg',
	),
);
