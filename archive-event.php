<!-- Archive Page for Events (Custom Post Type) -->
<?php get_header(); 
    #see functions.php to check function
    pageBanner(['title' => 'All Events', 'subtitle' => 'See what is going out in our world!']); 
?>

    <div class="container container--narrow page-section">
        <?php 
        if(have_posts()) {
            while(have_posts()) {
                the_post();
                get_template_part('template-parts/content', 'event');
            }
        } 
        echo paginate_links();
        ?>
        <hr class="section-break">
        <p>Looking for a recap for past events? <a href="<?php echo site_url('/past-events'); ?>">Check out for our past events archive</a> </p>
    </div>

<?php get_footer(); ?>