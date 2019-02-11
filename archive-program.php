<!-- Archive Page for Programs (Custom Post Type) -->
<?php get_header(); 
    pageBanner(['title' => 'All Programs', 'subtitle' => 'All programs provide insightful knowledge!']);
?>

    <div class="container container--narrow page-section">
        <ul class="link-list min-list">
            <?php 
            if(have_posts()) {
                while(have_posts()) {
                    the_post();
                    ?>
                        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                <?php }
            } 
            echo paginate_links();
            ?>
        </ul>
        
    </div>

<?php get_footer(); ?>