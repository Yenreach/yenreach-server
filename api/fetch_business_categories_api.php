<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $categories = BusinessCategories::find_by_business_string($string);
        if(!empty($categories)){
            $data_array = array();
            $total_rem = 5;
            foreach($categories as $category){
                if($total_rem > 0){
                    $total_rem -= 1;
                }
                $categ = Categories::find_by_verify_string($category->category_string);
                $data_array[] = array(
                        'id' => $category->id,
                        'verify_string' => $category->verify_string,
                        'section_string' => $category->section_string,
                        'category_string' => $category->category_string,
                        'category' => $categ->category,
                        'business_string' => $category->business_string,
                        'created' => $category->created,
                        'last_updated' => $category->last_updated
                    );   
            }
            $return_array['status'] = 'success';
            $return_array['message'] = 'You can add '.$total_rem.' more Categories';
            $return_array['data'] = $data_array;
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Category for this Business';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>