<!-- File used to define a page (fallback: index.php) -->
<?php
    get_header();
    //Check if there are any posts
    if(have_posts()) {
       
        while(have_posts()) {
            
            the_post(); 
            pageBanner();
            ?>
            
            <div class="container container--narrow page-section">
                
                <ul class="min-list link-list" id="my-notes">
                
                    <?php
                        $userNotes = new WP_Query(array(
                            'post_type' => 'note',
                            'posts_per_page' => -1,
                            'author' => get_current_user_id()
                        ));
                        
                        if($userNotes->have_posts()) {
                            while($userNotes->have_posts()) {
                                $userNotes->the_post();
    
                                ?>
    
                                <li> 
                                    <input class="note-title-field" type="text" value="<?php echo esc_attr(get_the_title()); ?>">
                                    <span class="edit-note"><i class="fas fa-pencil-alt" aria-hidden="true"></i></span>
                                    <span class="delete-note"><i class="fas fa-trash-alt" aria-hidden="true"></i></span>
                                    <textarea class="note-body-field" rows="3"><?php echo strip_tags(get_the_content()); ?></textarea>
    
                                </li>
    
                            <?php }
                        }
                       
                    ?>

                </ul>

            </div>         
        <?php 
        } 
    }
    get_footer();
?>