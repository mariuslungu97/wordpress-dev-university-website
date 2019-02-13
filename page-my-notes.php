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

                <div class="create-note">
                    <h2 class="headline headline--medium ">Create a new note: </h2>
                    <input type="text" class="new-note-title" placeholder="Title">
                    <textarea class="new-note-body" placeholder="Your new note content goes here..." rows="3"></textarea>
                    <span class="submit-note">Create Note</span>
                </div>
                
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
    
                                <li data-id="<?php echo get_the_ID(); ?>"> 
                                    <input readonly class="note-title-field" type="text" value="<?php echo esc_attr(get_the_title()); ?>">
                                    <span class="edit-note"><i class="fas fa-pencil-alt" aria-hidden="true">Edit</i></span>
                                    <span class="delete-note"><i class="fas fa-trash-alt" aria-hidden="true">Delete</i></span>
                                    <textarea  readonly class="note-body-field" rows="3"><?php echo strip_tags(get_the_content()); ?></textarea>
                                    <span class="update-note btn btn--blue btn--small"><i class="fas fa-arrow-right" aria-hidden="true"></i>Save</span>

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