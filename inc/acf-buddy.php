<?php
class AFC_Buddy {

    /**
     * @param Array containing the string name of the section you wish to request
     * if no param is passed it will return all fields
     */
    static function prepare_sections($requested_sections = []){
        $clean_sections = [];
        $clean_requested_sections = [];
        $field_groups = acf_get_field_groups();

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
                
                if($layout_type !== 'page_components'){
                    foreach($layout as $key => $value){
                        $clean_requested_sections[$value["acf_fc_layout"]] = [];
                        $clean_sections[$value["acf_fc_layout"]] = [];
                        foreach($value["section_layout"] as $field_values){
                            if(is_null($field_values)){
                                $field_values = "";
                            }
                            if(in_array($value['acf_fc_layout'], $requested_sections)){
                                array_push($clean_requested_sections[$value["acf_fc_layout"]],$field_values);
                            }else{
                                array_push($clean_sections[$value["acf_fc_layout"]],$field_values);
                            }
                        }
                    }
                }
            }
        }

        if(!empty($requested_sections)){
            return $clean_requested_sections;
        }

        return $clean_sections;
    }


    /**
     * @param Array of sections mapped => to their layout => values
     * @example $all_sections [ 
     *              banner_section => [
     *                  acf_fc_layout : "banner_image_slider"
     *                  banner_image_slider: [
     *                      0 => [ image values ..]
     *                      1 => [ image values ..]
     *                      2 => [ image values ..]
     *              ]
     *          ] 
     *      ]
     * @param Array string names of sections to exclude
     */
    static function render_fields($sections, $exclude = []){
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