<form role="search" method="get" class="search-form pr flex" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <input type="search" class="search-field" placeholder="<?php echo esc_attr__( 'Search...', 'adiva' ); ?>" value="<?php echo the_search_query(); ?>" name="s" />
    <button type="submit" class="search-submit"><i class="sl icon-magnifier"></i></button>
    <input type="hidden" name="post_type" value="product" />
</form>
