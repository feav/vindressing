<?php
$theme = wp_get_theme();
if ($theme->parent_theme) {
    $template_dir =  basename(get_template_directory());
    $theme = wp_get_theme($template_dir);
}
?>
<div class="wrap jms-wrap">
    <h1><?php esc_html_e( 'Welcome to Adiva!', 'adiva' ); ?></h1>
    <div class="about-text"><?php esc_html_e( 'Adiva is now installed and ready to use! Read below for additional information. We hope you enjoy it!', 'adiva' ); ?></div>
    <h2 class="nav-tab-wrapper">
        <?php
        printf( '<a href="#" class="nav-tab nav-tab-active">%s</a>', __( 'Welcome', 'adiva' ) );
        printf( '<a href="%s" class="nav-tab">%s</a>', admin_url( 'admin.php?page=jms-plugins' ), __( 'Plugins', 'adiva' ) );
		printf( '<a href="%s" class="nav-tab">%s</a>', admin_url( 'admin.php?page=jms-samples' ), __( 'Install Samples', 'adiva' ) );
        ?>
    </h2>
    <div class="jms-section">
        <p class="about-description">
            <?php printf( __( 'Before you get started, please be sure to always check out <a href="%s" target="_blank">this documentation</a>. We outline all kinds of good information, and provide you with all the details you need to use Adiva.', 'adiva'), 'http://wp-docs.jmsthemes.com/adiva/'); ?>
        </p>
        <p class="about-description">
            <?php printf( __( 'If you are unable to find your answer in our documentation, we encourage you to contact us through <a href="%s" target="_blank">support page</a> with your site CPanel (or FTP) and WordPress admin details. We are very happy to help you and you will get reply from us more faster than you expected.', 'adiva'), 'https://joommasters.ticksy.com/'); ?>
        </p>

    </div>
    <div class="jms-thanks">
        <p class="description"><?php esc_html_e( 'Thank you, we hope you to enjoy using Adiva!', 'adiva' ); ?></p>
    </div>
</div>
