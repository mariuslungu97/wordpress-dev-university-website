<!-- Custom Page Created To Display All Past Events -->
<!-- Naming Rule: page-*slug.php -->

<!-- General Structure for Archive Sections (ex: all the posts by Marius Lungu, all the posts on a specific date) -->
<?php get_header(); 
    pageBanner([
        'title' => 'Past Events: ',
        'subtitle' => 'A recap of our past events'
    ]);
?>

    <div class="container container--narrow page-section">
        <?php 
        $today = date('Ymd');
        $pastEvents = new WP_Query(array(
            'paged' => get_query_var('paged', '1'),
            'post_type' => 'event',
            'posts_per_page' => -1,
            'meta_key' => 'event_date',
            'orderby' => 'meta_value_num',
            'order' => 'DESC',
            'meta_query' => array(
                array(
                    'key' => 'event_date',
                    'compare' => '<',
                    'value' => $today,
                    'type' => 'numeric'
                )
            )
        ));

        if($pastEvents->have_posts()) {
            while($pastEvents->have_posts()) {
                $pastEvents->the_post();
                get_template_part('template-parts/content', 'event');
             }
        } 
        //Wordpress pagination only works by default with default queries that are tied to the current URL
        echo paginate_links(array(
            'total' => $pastEvents->max_num_pages
        ));
        ?>
    </div>

<?php get_footer(); ?>