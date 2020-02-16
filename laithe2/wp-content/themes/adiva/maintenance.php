<?php
$maintenance_title     = adiva_get_option( 'maintenance-title' );
$maintenance_message   = adiva_get_option( 'maintenance-message' );
$maintenance_countdown = adiva_get_option( 'maintenance-countdown', 1 );
$maintenance_date      = adiva_get_option( 'maintenance-date' );
$maintenance_month     = adiva_get_option( 'maintenance-month' );
$maintenance_year      = adiva_get_option( 'maintenance-year' );

$end_date = $maintenance_year . '-' . $maintenance_month . '-' . $maintenance_date;

$timezone = 'GMT';

if ( apply_filters( 'adiva_wp_timezone', false ) ) $timezone = wc_timezone_string();
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html"/>
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
        <div id="page" class="site oh">
            <div class="page-content">
                <div class="row">
                	<div class="col-md-4 col-sm-5 col-xs-12">
						<div class="tc maintenance-content flex middle-xs center-xs">
							<?php if ( ! empty( $maintenance_title ) ): ?>
								<h1><?php echo esc_html( $maintenance_title ); ?></h1>
							<?php endif; ?>
							<?php if ( ! empty( $maintenance_message ) ): ?>
								<p><?php echo esc_html( $maintenance_message ); ?></p>
							<?php endif; ?>

							<?php if ( $maintenance_countdown ): ?>
								<div class="adiva-countdown" data-end-date="<?php echo esc_attr($end_date); ?>" data-timezone="<?php echo esc_attr($timezone); ?>"></div>
							<?php endif; ?>
		                </div>
                	</div>
                </div>
            </div>
        </div>
		<?php wp_footer(); ?>
	</body>
</html>
