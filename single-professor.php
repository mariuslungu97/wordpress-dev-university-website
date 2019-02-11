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
        

                <div class="generic-content">
                    <div class="row group">
                        <div class="one-third">
                            <?php the_post_thumbnail('professorPortrait'); ?>
                        </div>

                        <div class="two-thirds">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </div>
                <?php 
                    $relatedPrograms = get_field('related_programs');
                    if($relatedPrograms) { ?>
                        <hr class="section-break">
                        <h2 class="headline headline--medium">Teaching Subjects: </h2>
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