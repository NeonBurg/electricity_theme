<?php get_header();?>

    <div class="content">

        <h1><?php the_title(); ?></h1> <!-- Page Title -->
        <div class="main-block">
            <?php
            while ( have_posts() ) : the_post();
                 the_content();
            endwhile;
            ?>
        </div>
    </div>

<?php get_footer();?>