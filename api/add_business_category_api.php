<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $business_string = !empty($post->business_string) ? (string)$post->business_string : "";
        $old_categs = BusinessCategories::find_by_business_string($business_string);
        $counted = count($old_categs);
        if($counted < 5){
            $category = !empty($post->category) ? (string)$post->category : "";
            $categ = Categories::find_by_category($database->escape_value($category));
            if(!empty($categ)){
                $category_string = $categ->verify_string;
                $section_string = $categ->section_string;
            } else {
                $categ = new Categories();
                $categ->category = $category;
                $categ->insert();
                $category_string = $categ->verify_string;
                $section_string = "";
            }
            
            $bus_cat = new BusinessCategories();
            $bus_cat->business_string = $business_string;
            $bus_cat->category_string = $category_string;
            $bus_cat->category = $category;
            $bus_cat->section_string = $section_string;
            if($bus_cat->insert()){
                $return_array['status'] = 'success';
                $return_array['data'] = array(
                        'id' => $bus_cat->id,
                        'verify_string' => $bus_cat->verify_string,
                        'section_string' => $bus_cat->section_string,
                        'category_string' => $bus_cat->category_string,
                        'business_string' => $bus_cat->business_string,
                        'category' => $bus_cat->category,
                        'created' => $bus_cat->created,
                        'last_updated' => $bus_cat->last_updated
                    );
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = join(' ', $bus_cat->errors);
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'You cannot add more that 5 Categories for each Business';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>