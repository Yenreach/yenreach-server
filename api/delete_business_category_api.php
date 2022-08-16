<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $category = BusinessCategories::find_by_verify_string($string);
        if(!empty($category)){
            if($category->delete()){
                $return_array['status'] = 'success';
                $return_array['message'] = array(
                        'id' => $category->id,
                        'verify_string' => $category->verify_string,
                        'section_string' => $category->section_string,
                        'category_string' => $category->category_string,
                        'business_string' => $category->business_string,
                        'created' => $category->created,
                        'last_updated' => $category->last_updated
                    );
            } else {
                $return_array['status'] = 'failed';
                $return_message['message'] = 'Business Category Delete Failed';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'Business Category was not fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>