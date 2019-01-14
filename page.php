<?php get_header();?>

    <div class="content">

        <h2 class="blog-post-title"><?php the_title(); ?></h2> <!-- Page Title -->
        <div class="main-block">
            <?php
            while ( have_posts() ) : the_post();
                 the_content();
            endwhile;
            ?>
        </div>
    </div>

<?php get_footer();?>