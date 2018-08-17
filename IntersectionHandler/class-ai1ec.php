<?php


    namespace IntersectionHandler {
        
        use WP_Query;
        // use HelperClass?
        class Ai1EC implements interface_handler  {

    
        public function __construct(){
   
        }

        public function prepare(array $requested_sections = [], $phpunit=false){
            echo "here";
            $this->get_latest_events();
        }

        public function render(array $partials, $exclude = []){

        }

        public function get_latest_events(){
   
            $args = array(
                'posts_per_page'  => 4,
                'paged' => get_query_var('paged'),
                'post_type'=> 'ai1ec_event',
                'orderby' => 'post__in'
            );


            $events_added = new WP_Query( $args );


            // the Loop
            while ( $events_added->have_posts() ) : $events_added->the_post();
                // $event = Ai1ec_Events_Helper::get_event($post->ID);
                // $event = $events_added->the_post();
                
            endwhile;
        }

        public function get_featured_events(){

        }
      } // end class
} // end namespace