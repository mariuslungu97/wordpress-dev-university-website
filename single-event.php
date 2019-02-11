<!-- File used to define an Event post (fallback: index.php) -->
<?php
    get_header();
    //Check if there are any posts
    if(have_posts()) {
    
        while(have_posts()) {
            //moves through the queue
            the_post(); 
            pageBanner([
                'title' => get_the_title()
            ]);
            ?>

                      

            <div class="container container--narrow page-section">
                <div class="metabox metabox--position-up metabox--with-home-link">
                    <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('event') ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to Events</a> <span class="metabox__main">
                        <?php the_title(); ?>
                    </span></p>
                </div>

                <div class="generic-content">
                    <?php the_content(); ?>
                </div>
                <?php 
                    $relatedPrograms = get_field('related_programs');
                    if($relatedPrograms) { ?>
                        <hr class="section-break">
                        <h2 class="headline headline--medium">Related Program(s): </h2>
                        <ul class="link-list min-list">
                        <?php 

                            foreach($relatedPrograms as $program) { ?>

                                <li><a href="<?php echo get_the_permalink($program); ?>"><?php echo get_the_title($program); ?></li>

                                <?php 
                                }
                            }
                        ?>
                </ul>
            </div>

            
        
        <?php }
    }
    get_footer();
?>