<?php


    namespace IntersectionPluginHandler {
        
        use WP_Query;
       

        class Ai1EC implements interface_handler  {
   
            protected $EVENT_TABLE = "ai1ec_events";
            protected $POST_TABLE = "posts";
            protected $TERM_RELATIONSHIP_TABLE = "term_relationships";
            protected $TERM_TAXONOMY_TABLE = "term_taxonomy";
            protected $TERM_TABLE = "terms";

            public function prepare(array $requested_sections = [], $phpunit=false){
                $events = $this->get_events();
                return $events;
            }

            public function render(array $events, $exclude = []){
            
                $post_ids = [];
                
                foreach( $events as $event ){
                    $post_ids[] = $event['post_id'];
                }

                $args = array(
                    'post_type' => 'ai1ec_event',
                    'post__in'  => $post_ids,
                );

                $query = new WP_Query( $args );
                
                $order = [];
                foreach($query->posts as $post){
                    $position = array_search($post->ID, $post_ids);
                    $order[$position] = $post;
                }
                ksort($order);
                $query->posts = array_values($order);
                // return assoc array 
                return json_decode(json_encode($query,true), true);
            }

            public function get_events(){
                
                global $wpdb;
                $prefix = $wpdb->base_prefix;
                $params = array(
                    'e.post_id',
                    'e.start',
                    'e.end',
                    'e.allday',
                    'e.venue',
                    'e.address',
                    'e.show_map',
                    'p.post_title',
                    'p.post_parent',
                    't.slug AS label',
                );

                $param_string = implode(',', $params);

                $sql = "SELECT {$param_string} FROM {$prefix}{$this->EVENT_TABLE} e
                        LEFT JOIN {$prefix}{$this->POST_TABLE} p ON e.post_id = p.ID 
                        LEFT JOIN {$prefix}{$this->TERM_RELATIONSHIP_TABLE} rel ON rel.object_id = p.ID 
                        LEFT JOIN {$prefix}{$this->TERM_TAXONOMY_TABLE} tax ON tax.term_taxonomy_id = rel.term_taxonomy_id 
                        LEFT JOIN {$prefix}{$this->TERM_TABLE} t ON t.term_id = tax.term_id
                        ORDER BY FROM_UNIXTIME(e.start,'%Y %M %D %h:%i:%s %x') DESC";

                $events = $wpdb->get_results($sql, ARRAY_A);
                
                $upcoming_events = [];
                $featured_events = [];
                $previous_events = [];

                foreach($events as $event){
                    $now = time();
                    if((int)$event['end'] >= $now){
                        $is_featured = $event['label'] === 'featured';
                        if($is_featured){
                            $featured_events[] = $event;
                        }else{
                            $upcoming_events[] = $event;
                        }
                    }else{
                        $previous_events[] = $event;
                    }
                }
                $sorted_events = array_merge($featured_events, $upcoming_events);
                
                if( count($sorted_events) === 0 ){
                    $sorted_events = $previous_events;
                }

                return $sorted_events;
            }
      } // end class
} // end namespace