<?php

    //Register Custom REST API Getpoint Route

    add_action('rest_api_init', 'universityRegisterSearch');

    function universityRegisterSearch() {
        //register route
        register_rest_route('university/v1' , 'search' , array(
            'methods' => WP_REST_SERVER::READABLE,
            'callback' => 'universitySearchResults'
        ));
    }

    function universitySearchResults($data) {
        //main query to return all post types
        $mainQuery = new WP_Query(array(
            'post_type' => array('professor', 'post', 'page', 'program', 'event'),
            's' => sanitize_text_field($data['term']) //search term (coming from $data passed into the url)
        ));

        //results array to be returned
        $results = array(
            'generalInfo' => array(),
            'professors' => array(),
            'programs' => array(),
            'events' => array()
        );

        
        while($mainQuery->have_posts()) {
            $mainQuery->the_post();
            
            if(get_post_type() == 'post' || get_post_type() == 'page') {
               
                array_push($results['generalInfo'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'post_type' => get_post_type(),
                    'author_name' => get_post_type() == 'post' ? get_the_author() : '',
                    'author_link' => get_post_type() == 'post' ? get_author_posts_url(get_the_author_meta('ID')) : ''
                ));
            };

            if(get_post_type() == 'professor') {
                array_push($results['professors'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),  
                    'post_type' => get_post_type(),
                    'thumbnail' => get_the_post_thumbnail_url()
                ));
            }

            if(get_post_type() == 'program') {
                array_push($results['programs'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'post_type' => get_post_type(),
                    'id' => get_the_id()
                ));
            };
            if(get_post_type() == 'event') {

                $customFieldDate = get_field('event_date'); // get custom field
                $date = new DateTime($customFieldDate);
            
                array_push($results['events'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),  
                    'post_type' => get_post_type(),
                    'date_month' => $date->format('M'),
                    'date_day' => $date->format('d'),
                    'excerpt' => has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 18)
                ));
            };
        };

        if($results['programs']) {
            //build query to retrieve all professors and events associated with a specific program
            $relatedProgramsArr = array('relation' => 'OR');
            
            foreach($results['programs'] as $item) {
                array_push($relatedProgramsArr, array(
                    'key' => 'related_programs',
                    'compare' => 'LIKE', 
                    'value' => '"' . $item['id'] . '"'
                ));
            }
    
            $relatedProfessorsQuery = new WP_Query(array(
                'post_type' => array('professor', 'event'),
                'meta_query' => $relatedProgramsArr
            ));
    
            if($relatedProfessorsQuery->have_posts()) {
                while($relatedProfessorsQuery->have_posts()) {
                    $relatedProfessorsQuery->the_post();
                    
                    if(get_post_type() == 'professor') {
                        array_push($results['professors'], array(
                            'title' => get_the_title(),
                            'permalink' => get_the_permalink(),  
                            'post_type' => get_post_type(),
                            'thumbnail' => get_the_post_thumbnail_url()
                        ));
                    }
                    if(get_post_type() == 'event') {

                        $customFieldDate = get_field('event_date'); // get custom field
                        $date = new DateTime($customFieldDate);

                        array_push($results['events'], array(
                            'title' => get_the_title(),
                            'permalink' => get_the_permalink(),  
                            'post_type' => get_post_type(),
                            'date_month' => $date->format('M'),
                            'date_day' => $date->format('d'),
                            'excerpt' => has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 18)
                        ));
                    }
                    
                    
                }
            };
            
            //remove duplicates
            $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));
            $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));

        }
        //return results
        return $results;
    }

?>
