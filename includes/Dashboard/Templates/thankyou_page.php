<?php
/**
 * Thank you page.
 *
 * @author     Alessio Catania
 * @since      0.1.0
 * @package    Wubtitle\Dashboard\Templates
 */

/**
 * Thank you page.
 */

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Success</title>
</head>
<body>
	<div class="container" id="content">
		<h1 class="title"><?php echo isset( $message ) ? esc_html( $message ) : ''; ?></h1>
		<p><?php esc_html_e( 'Update Wubtitle settings page on your site backoffice to view the changes or click on the button below', 'wubtitle' ); ?></p>
		<button id="success-button"><?php esc_html_e( 'BACK TO WUBTITLE', 'wubtitle' ); ?></button>
	</div>
	<?php wp_footer(); ?>
</body>
</html>
