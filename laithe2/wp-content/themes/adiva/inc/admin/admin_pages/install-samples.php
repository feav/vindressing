<?php
global $main_import;
?>
<div class="wrap jms-wrap">
    <h1><?php esc_html_e( 'Welcome to Adiva!', 'adiva' ); ?></h1>
    <div class="about-text"><?php esc_html_e( 'Adiva is now installed and ready to use! Read below for additional information. We hope you enjoy it!', 'adiva' ); ?></div>
    <h2 class="nav-tab-wrapper">
        <?php
        printf( '<a href="%s" class="nav-tab">%s</a>', admin_url( 'admin.php?page=adiva' ), __( 'Welcome', 'adiva' ) );
        printf( '<a href="%s" class="nav-tab">%s</a>', admin_url( 'admin.php?page=jms-plugins' ), __( 'Plugins', 'adiva' ) );
        printf( '<a href="#" class="nav-tab nav-tab-active">%s</a>', __( 'Install Samples', 'adiva' ) );
        ?>
    </h2>
    <div class="jms-section">
		<div class="jms-import-area jms-row jms-three-columns <?php echo esc_attr( $main_import->gen_imported_pages_classes() ); ?>">
	<div class="jms-column import-base">
		<div class="jms-column-inner">
			<div class="jms-box jms-box-shadow">
				<div class="jms-box-header">
					<h2><?php esc_html_e('Base Data Import', 'adiva'); ?></h2>
					<span class="jms-box-label jms-label-error"><?php esc_html_e('Required', 'adiva'); ?></span>
				</div>
				<div class="jms-box-info">
					<p>
						<?php esc_html_e( 'It includes Home Default (Home 1) version , blog posts, portfolios, pages, demo products. It is a required data to be able to import additional pages.', 'adiva' ); ?>
					</p>
				</div>
				<div class="jms-box-content">
					<?php $main_import->imported_pages(); ?>
					<?php $main_import->base_import_screen(); ?>
					<div class="jms-success base-imported-alert">
						<span class="highlight">
                            <?php esc_html_e( 'Base Data is successfully imported. Now you can choose any pages to apply its settings for your website. You are be able to back to default version by click to "Activate Base Version" Button.', 'adiva' ); ?>
                        </span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="jms-column import-pages">
		<div class="jms-column-inner">
			<div class="jms-box jms-box-shadow">
				<div class="jms-box-header">
					<h2><?php esc_html_e('Home Setup', 'adiva'); ?></h2>
					<span class="jms-box-label jms-label-recommended"><?php esc_html_e('Recommended', 'adiva'); ?></span>
				</div>
				<div class="jms-box-info">
					<p>
						<?php esc_html_e( 'Select one Home Page from box then click to "Import Home", It will import Home content, Home sliders, and Home setting and set that Home to Frontpage.', 'adiva' ); ?>
						<br>
					</p>
				</div>
				<div class="jms-box-content">
					<?php $main_import->homes_import_screen(); ?>
				</div>

			</div>
		</div>
	</div>
	<div class="jms-column import-pages">
		<div class="jms-column-inner">
			<div class="jms-box jms-box-shadow">
				<div class="jms-box-header">
					<h2><?php esc_html_e('Pages Import', 'adiva'); ?></h2>
					<span class="jms-box-label jms-label-warning"><?php esc_html_e('Optional', 'adiva'); ?></span>
				</div>
				<div class="jms-box-info">
					<p>
						<?php esc_html_e( 'Select one Page from box then click to "Import Page", It will be import page content, help you easy to create page like on demo.', 'adiva' ); ?>
					</p>
				</div>
				<div class="jms-box-content">
					<?php $main_import->pages_import_screen(); ?>
				</div>

			</div>
		</div>
	</div>
	<br />
	<p>
		<?php esc_html_e( 'Note : Base Data Import must download all attachment from server so sometime it broken when use internet slow. Dont worry refresh this page again then click to Base Import again, it will be ok.', 'adiva' ); ?>
	</p>
</div>
    </div>
</div>
