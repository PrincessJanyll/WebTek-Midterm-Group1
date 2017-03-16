<?php
if (is_single()) {
    if (have_posts()) {
        while (have_posts()) {
            the_post();
                echo "<div class='container'>";
                get_template_part( 'parts/content', 'single');
                echo "</div>";
        }
    } else {
            get_template_part( 'parts/error', 'no_results');
    }                
} else if (is_page()) {
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            echo "<div class='container'>";
            get_template_part( 'parts/content', 'page');
            echo "</div>";
        }
    } else {
            get_template_part( 'parts/error', 'no_results');
    } 
} else {
    echo "<div class='container'>";
    get_template_part( 'parts/content', 'blog');
    echo "</div>";
}
?>
