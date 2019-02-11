<!-- File used to define an Program post (fallback: index.php) -->
<?php
    get_header();
    //Check if there are any posts
    if(have_posts()) {
    
        while(have_posts()) {
            //moves through the queue
            the_post(); 
            
            pageBanner([
                
            ]);
            
            ?>
            
            
            <!-- Center Container -->
            <div class="container container--narrow page-section">
                <div class="metabox metabox--position-up metabox--with-home-link">
                    <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program') ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to Programs</a> <span class="metabox__main">
                        <?php the_title(); ?>
                    </span></p>
                </div>

                <div class="generic-content">
                    <?php the_field('main_body_content'); ?>
                </div>

                
                <?php
                    #custom query to retrieve associated professors
                    $homepageProffesors = new WP_Query(array(
                        'post_type' => 'professor',
                        'showposts' => -1,
                        
                        'orderby' => 'title',
                        'order' => 'ASC',
                        'meta_query' => array(         
                            array(
                                'key' => 'related_programs',
                                'compare' => 'LIKE',
                                'value' => get_the_ID()
                            )
                        )
                    ));
                    ?>

                    <?php 
                    //Display Professors
                    if($homepageProffesors->have_posts()) { ?>
                        <hr class="section-break">
                        <h2 class="headline headline--medium">Related <?php the_title(); ?> Professors: </h2>
                    <?php }

                    if($homepageProffesors->have_posts()) {
                        echo '<ul class="professor-cards">';
                        while($homepageProffesors->have_posts()) {
                            $homepageProffesors->the_post(); ?>

                            <li class="professor-card__list-item">
                                <a class="professor-card" href="<?php the_permalink(); ?>">
                                    <img class="professor-card__image"  src="<?php the_post_thumbnail_url('professorLandscape'); ?>" alt="">
                                    <span class="professor-card__name"><?php the_title(); ?></span>
                                </a>
                               
                            </li>
                        
                        <?php }
                        echo '</ul>';
                    }
                    #reset postdata to create another query
                    wp_reset_postdata();

                    #create new query   
                    $today = date('Ymd');
                    $homepageEvents = new WP_Query(array(
                        'post_type' => 'event',
                        'showposts' => 2,
                        'meta_key' => 'event_date',
                        'orderby' => 'meta_value_num',
                        'order' => 'ASC',
                        'meta_query' => array(
                            array(
                                'key' => 'event_date',
                                'compare' => '>=',
                                'value' => $today,
                                'type' => 'numeric'
                               ),
                            array(
                                'key' => 'related_programs',
                                'compare' => 'LIKE',
                                'value' => get_the_ID()
                            )
                        )
                    ));
                ?>
                    <?php 
                    if($homepageEvents->have_posts()) { ?>
                        <hr class="section-break">
                        <h2 class="headline headline--medium">Upcoming <?php the_title(); ?> Events: </h2>
                    <?php }

                    if($homepageEvents->have_posts()) {
                        while($homepageEvents->have_posts()) {
                            $homepageEvents->the_post(); 
                            get_template_part('template-parts/content', 'event');
                        }
                    }

                ?>
            </div>
        
        <?php }
    }
    get_footer();
?>