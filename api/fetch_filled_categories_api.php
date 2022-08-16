<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $businesses = Businesses::find_approved_businesses();
    if(!empty($businesses)){
        $categories = array();
        
        foreach($businesses as $business){
            $bus_categs = BusinessCategories::find_by_business_string($business->verify_string);
            if(!empty($bus_categs)){
                foreach($bus_categs as $categ){
                    if(!in_array($categ->category_string, $categories)){
                        $categories[] = $categ->category_string;
                    }
                }
            }
        }
        
        if(!empty($categories)){
            $data_array = array();
            foreach($categories as $category){
                $cates = Categories::find_by_verify_string($category);
                
                $data_array[] = $cates;
            }
            $return_array['status'] = 'success';
            $return_array['data'] = $data_array;
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Category was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No Business was fetched';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>