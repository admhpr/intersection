<?php


    namespace IntersectionHandler {
        
        use WP_Query;
        class Ai1EC implements interface_handler  {
        
        protected $EVENT_TABLE = "wp_ai1ec_events";
        protected $POST_TABLE = "wp_posts";
        protected $TERM_RELATIONSHIP_TABLE = "wp_term_relationships";
        protected $TERM_TAXONOMY_TABLE = "wp_term_taxonomy";
        protected $TERM_TABLE = "wp_terms";

        public function __construct(){
   
        }

        public function prepare(array $requested_sections = [], $phpunit=false){
            $events = $this->get_events();
        }

        public function render(array $partials, $exclude = []){

        }

        public function get_events(){
            
            global $wpdb;

            $sql = "SELECT * FROM {$this->EVENT_TABLE} e
                    LEFT JOIN {$this->POST_TABLE} p ON e.post_id = p.ID 
                    LEFT JOIN {$this->TERM_RELATIONSHIP_TABLE} rel ON rel.object_id = p.ID 
                    LEFT JOIN {$this->TERM_TAXONOMY_TABLE} tax ON tax.term_taxonomy_id = rel.term_taxonomy_id 
                    LEFT JOIN {$this->TERM_TABLE} t ON t.term_id = tax.term_id
                    ORDER BY FROM_UNIXTIME(e.start,'%Y %D %M %h:%i:%s %x') DESC";

            $events = $wpdb->get_results($sql, ARRAY_A);
            
            $upcoming_events = [];
            $featured_events = [];
            
            foreach($events as $event){
                $now = time();
                if($event['end'] >= $now){
                    $correct_setting = (strtolower($event['taxonomy']) === 'events_categories' || strtolower($event['taxonomy']) === 'events_tags');
                    $featured = strtolower($event['name']) == "featured"; 
                    $is_featured = $correct_setting && $featured;
                    if($is_featured){
                        $featured_events[] = $event;
                    }else{
                        $upcoming_events[] = $event;
                    }
                }
            }
            $sorted_events = array_merge($featured_events, $upcoming_events);
        
            return $sorted_events;
        }

        public function get_featured_events(){

        }
      } // end class
} // end namespace