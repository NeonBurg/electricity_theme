<?php get_header();?>

    <div class="content">

        <div class="main-block">
            <?php
                    global $myOffset;
                    $myOffset = 0;
                    $paged = get_query_var('paged') ? get_query_var('paged') : 1; //Use 'page' instead of 'paged' if you are on home page

                    $args = array(
                        'post_type' => 'post',
                        'cat'=> 1,    //Selecting post category by ID to show
                        'posts_per_page' => 4,  //No. of posts to show
                        'offset' => $myOffset,  //Eexcluding latest post
                        'paged' => $paged       //For pagination
                    );

                    $loop = new WP_Query( $args );

                    while ( $loop->have_posts() ) : $loop->the_post();
                        get_template_part( 'content', get_post_format() );
                    endwhile;
            ?>
        </div>
    </div>
	
<?php get_footer();?>