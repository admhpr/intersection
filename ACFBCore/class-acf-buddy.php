<?php

namespace ACFBCore{

    class ACF_buddy {
        
        private $clean_sections = [];
        private $clean_requested_sections=[]; 
        /**
         * @param Array containing the string name of the section you wish to request
         * if no param is passed it will return all fields
         */
        public function prepare_sections($requested_sections = [], $phpunit=false){


            if(!$phpunit){
                $field_groups = \acf_get_field_groups();
            }else{
                $layout = $this->create_dummy_layout();
                $layout_type = "mock_partial";
            }
            
            
            if(isset($field_groups)){

                foreach ( $field_groups as $group ) {
                    // DO NOT USE: $fields = acf_get_fields($group['key']);
                    // because it causes repeater field bugs and returns "trashed" fields
                    $sections = get_posts(array(
                        'posts_per_page'   => -1,
                        'post_type'        => 'acf-field',
                        'orderby'          => 'menu_order',
                        'order'            => 'ASC',
                        'suppress_filters' => true, // DO NOT allow WPML to modify the query
                        'post_parent'      => $group['ID'],
                        'post_status'      => 'any',
                        'update_post_meta_cache' => false
                    ));
    
                    foreach ( $sections as $section ) {
                        
                 
                        $layout = get_field($section->post_name);
                        $layout_type = $section->post_excerpt;
                      
    
                        $this->process_layout($layout, $layout_type);
                        
                        
                    }
                }
                
   
                
                return $this->clean_sections;
            }

            // mock data for unit testing
            // TODO:
            // if(!empty($requested_sections)){
            //     return $clean_requested_sections;
            // }

            $this->process_layout($layout, $layout_type);
            return $this->clean_sections;  
        }
    
    
        /**
         * @param Array of sections mapped => to their layout => values
         * @param Array string names of sections to exclude
         */
        public function render_fields($partials, $exclude = []){

            foreach($partials as $sections  ){
                foreach($sections as $section => $contents){
                    if($section !== 'page_components' && !in_array($section, $exclude)){
                        $folder = '/partials/sections/' . str_replace('_', '-', $section ) . "/";
                    foreach($contents as $fields){
                            $path = $folder . str_replace('_', '-', $fields['acf_fc_layout'] ) . '.php';
                            include(locate_template( $path ));	                   
                        }
                    }
                }
            }

        }

        public function process_layout($layout,$layout_type, $requested_sections=[]){
            /***
             * 
             *  FIXME: requested_sections is a place holder at the moment. 
             *  write unit test
             *  refactor
             */
            if($layout_type !== 'page_components'){
                foreach($layout as $key => $value){
                    $this->clean_requested_sections[$key][$value["acf_fc_layout"]] = [];
                    $this->clean_sections[$key][$value["acf_fc_layout"]] = [];
                    foreach($value["section_layout"] as $field_values){
                        if(is_null($field_values)){
                            $field_values = "";
                        }
                        if(in_array($value['acf_fc_layout'], $requested_sections)){
                            array_push($this->clean_requested_sections[$key][$value["acf_fc_layout"]],$field_values);
                        }else{
                            array_push($this->clean_sections[$key][$value["acf_fc_layout"]],$field_values);
                        }
                    }
                }
            }

            return $this->clean_sections;
                
             
        }

        public function create_dummy_layout(){
            return array(
                    
                array(
                    "acf_fc_layout" => "mock_partial",
                    "section_layout" => array(
                        array(
                            "acf_fc_layout" => "mock_section",
                            "mock_section" => array(
                                array(
                                    "acf_fc_layout" => "section_contents",
                                    "section_contents" => array(
                                        "data" => "data"
                                    ),
                                )
                            ),
                        ),
                    ),
                ),
                array(
                    "acf_fc_layout" => "mock_partial",
                    "section_layout" => array(
                        array(
                            "acf_fc_layout" => "mock_section",
                            "mock_section" => array(
                                array(
                                    "acf_fc_layout" => "section_contents",
                                    "section_contents" => array(
                                        "data" => "data"
                                    ),
                                )
                            ),
                        ),
                    ),
                ),
                array(
                    "acf_fc_layout" => "mock_partial",
                    "section_layout" => array(
                        array(
                            "acf_fc_layout" => "mock_section",
                            "mock_section" => array(
                                array(
                                    "acf_fc_layout" => "section_contents",
                                    "section_contents" => array(
                                        "data" => "data"
                                    ),
                                )
                            ),
                        ),
                    ),
                ),
            );
        }

    } // end class
} // end namespace
