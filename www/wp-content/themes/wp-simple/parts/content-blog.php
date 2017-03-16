<?php
$nimbus_blog_sidebar_position = nimbus_get_option('nimbus_blog_sidebar_position');
if ($nimbus_blog_sidebar_position == 'left') {
    $sidebar_select_aside_classes = 'col-sm-pull-8';
    $sidebar_select_content_classes = 'col-sm-push-4';
} else {
    $sidebar_select_aside_classes = '';
    $sidebar_select_content_classes = '';
}
?>
<div class="main_content content row">
    <div class="col-sm-8 blog_content_col <?php echo $sidebar_select_content_classes; ?>">
        <?php
        global $more;
        $more = 0;
        if (have_posts()) {
            while (have_posts()) {
                the_post();
                ?>
                <div <?php post_class('frontpage-post'); ?> data-sr="wait 0.1s, scale up 25%">
                    <?php get_template_part( 'parts/image', '750_500'); ?>
                    <div class="frontpage-post-content">
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p class="meta">
                        <?php the_time( 'M' ); ?>. <?php the_time( 'j' ); ?>, <?php the_time( 'Y' ); ?> by <?php the_author_posts_link(); ?></p>
                        <?php the_excerpt(); ?>
                    </div>
                </div>
                <?php
            }
        } else {
                get_template_part( 'parts/error', 'no_results');
        }
        get_template_part( 'parts/blog', 'pagination');
        ?>
    </div>
    <div class="col-sm-4 blog_sidebar_col <?php echo $sidebar_select_aside_classes; ?>">
        <?php
        get_sidebar();
        ?>
    </div>
</div>