<?php
if (has_post_thumbnail()) {
    the_post_thumbnail('nimbus_750_500', array('class' => 'nimbus_750_500 img-responsive'));
} else { ?>
    <?php if ( (nimbus_get_option('fp-news-thumbs-toggle') == "1") || (nimbus_get_option('fp-news-thumbs-toggle') == "") ) { ?>
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/preview/750x500-<?php echo rand(1,8); ?>.jpg" class="nimbus_750_500 img-responsive" alt="<?php the_title(); ?>" />
    <?php } ?>
<?php } ?>