<div class="popuppromotion hidden">
  <!-- Modal -->
  <button title="<?php echo esc_html('Close (Esc)', 'denso'); ?>" type="button" class="mfp-close apus-mfp-close">×</button>
  <div class="popuppromotion-widget">
    <?php if(!empty($title)){ ?>
        <h3>
          <span><?php echo esc_html( $title ); ?></span>
        </h3>
    <?php } ?>
    <?php if ( isset($image) && $image ) : ?>
      <?php if (isset($url) && $url): ?>
        <a href="<?php echo esc_url($url); ?>">
      <?php endif; ?>
        <img src="<?php echo esc_url( $image ); ?>" alt=""/>
      <?php if (isset($url) && $url): ?>
        </a>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>