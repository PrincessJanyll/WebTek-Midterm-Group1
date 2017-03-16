<?php
get_header();
if (is_paged()) {
    get_template_part( 'parts/loop');
} else {
    get_template_part( 'parts/frontpage', 'featured');
    get_template_part( 'parts/frontpage', 'action1');
    get_template_part( 'parts/frontpage', 'about');
    get_template_part( 'parts/frontpage', 'social');
    get_template_part( 'parts/frontpage', 'team');
    get_template_part( 'parts/frontpage', 'action2');
    get_template_part( 'parts/frontpage', 'test');
    get_template_part( 'parts/frontpage', 'news');
    get_template_part( 'parts/frontpage', 'contact');
}
get_footer();
?>