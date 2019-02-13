<?php
    
    require get_theme_file_path('/inc/search-route.php');

    function university_custom_rest() {
        register_rest_field('post', 'authorName', array(
            'get_callback' => function() {return get_the_author();}
        ));
        register_rest_field('post', 'authorLink', array(
            'get_callback' => function() { 
                $authorId = get_the_author_meta('ID');
                return get_author_posts_url($authorId);
             }
        ));
    }

    add_action('rest_api_init', 'university_custom_rest');

    function pageBanner($args = NULL) {

        //logic
        if(!$args['title']) $args['title'] = get_the_title(); 
        if(!$args['subtitle']) $args['subtitle'] = get_field('post_subtitle');
        if(!$args['imageUrl']) {
            $bannerImage = get_field('post_background_image');
            if($bannerImage) $args['imageUrl'] = $bannerImage['sizes']['bannerImage'];
            else $args['imageUrl'] = get_theme_file_uri('/images/ocean.jpg');
        }
        ?>

        <div class="page-banner">
            <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['imageUrl'] ?>);"></div>
                <div class="page-banner__content container container--narrow">
                <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
                <div class="page-banner__intro">
                    <p><?php echo $args['subtitle']; ?></p>
                </div>
            </div>  
        </div> 

    <?php }

    function add_theme_scripts() {
        wp_enqueue_script('main-university-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, microtime(), true);
        wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
        wp_enqueue_style('font-awesome', '//use.fontawesome.com/releases/v5.7.1/css/all.css');
        wp_enqueue_style('university_main_style', get_stylesheet_uri(), NULL, microtime() );
        wp_localize_script('main-university-js', 'universityData', array(
            'root_url' => get_site_url(),
            'nonce' => wp_create_nonce('wp_rest')
        ));
    }
    //load CSS, JS scripts and custom fonts
    add_action('wp_enqueue_scripts','add_theme_scripts');

    function university_features() {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_image_size('professorLandscape', 400, 260, true);
        add_image_size('professorPortrait', 480, 650, true);
        add_image_size('bannerImage', 1500, 350, true);

    }
    //add title tag to each page
    add_action('after_setup_theme', 'university_features');

    function university_post_types() {

        //Register Event
        register_post_type('event',array(
            'supports' => array('title', 'editor', 'excerpt'),
            'rewrite' => array('slug' => 'events'),
            'has_archive' => true,
            'public' => true,
            'labels' => array(
                'name' => 'Events',
                'singular_name' => 'Event',
                'add_new_item' => 'Add New Event',
                'edit_item' => 'Edit Event',
                'all_items' => 'All Events'
            ),
            'menu_icon' => 'dashicons-calendar-alt'
        ));
        //Register Program
        register_post_type('program',array(
            'supports' => array('title'),
            'rewrite' => array('slug' => 'programs'),
            'has_archive' => true,
            'public' => true,
            'labels' => array(
                'name' => 'Programs',
                'singular_name' => 'Program',
                'add_new_item' => 'Add New Program',
                'edit_item' => 'Edit Program',
                'all_items' => 'All Programs'
            ),
            'menu_icon' => 'dashicons-awards'
        ));

        //Register Proffesors
        register_post_type('professor',array(
            'supports' => array('title', 'editor', 'thumbnail'),
            'public' => true,
            'labels' => array(
                'name' => 'Professors',
                'singular_name' => 'Professor',
                'add_new_item' => 'Add New Professor',
                'edit_item' => 'Edit Professor',
                'all_items' => 'All Professors'
            ),
            'menu_icon' => 'dashicons-welcome-learn-more'
        ));

        //Register Note
        register_post_type('note',array(
            'supports' => array('title', 'editor', 'author'),
            'public' => false,
            'show_in_rest' => true,
            'show_ui' => true,
            'labels' => array(
                'name' => 'Notes',
                'singular_name' => 'Note',
                'add_new_item' => 'Add New Note',
                'edit_item' => 'Edit Note',
                'all_items' => 'All Notes'
            ),
            'menu_icon' => 'dashicons-welcome-write-blog'
        ));
    }

    //add custom post types
    add_action('init','university_post_types');

    function university_adjust_queries($query) {
        //Check if the queries are not performed by the dashboard, the post archive is of type 'event', and the type of query is main query
        if(!is_admin() && is_post_type_archive('event') && $query->is_main_query()) {
            $today = date('Ymd');
            $query->set('meta_key', 'event_date');
            $query->set('orderby', 'meta_value_num');
            $query->set('order','ASC');
            $query->set('meta_query', array(
                array(
                    'key' => 'event_date',
                    'compare' => '>=',
                    'value' => $today,
                    'type' => 'numeric'
                    )
                )
            );
        }
        if(!is_admin() && is_post_type_archive('program') && $query->is_main_query()) {
            $query->set('orderby', 'title');
            $query->set('order','ASC');
            $query->set('posts_per_page', -1);
        }
    }

    //Adjust default query
    add_action('pre_get_posts', 'university_adjust_queries');

    add_action('admin_init', 'redirectSubsToFront');

    function redirectSubsToFront() {
        $currentUser = wp_get_current_user();

        if(count($currentUser->roles) == 1 && $currentUser->roles[0] == 'subscriber') {
            wp_redirect(site_url('/'));
            exit;
        }
    }

    add_action('wp_loaded', 'noAdminBarUsers');

    function noAdminBarUsers() {
        $currentUser = wp_get_current_user();

        if(count($currentUser->roles) == 1 && $currentUser->roles[0] == 'subscriber') {
           show_admin_bar(false);
        }
    }
    remove_filter( 'the_content', 'wpautop' );



?>