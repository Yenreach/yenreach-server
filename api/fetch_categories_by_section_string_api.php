<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['section_string']) ? (string)$_GET['section_string'] : "";
    if(!empty($string)){
        if($string == "others"){
            $categories = Categories::find_by_others();
        } else {
            $categories = Categories::find_by_section_string($string);   
        }
        if(!empty($categories)){
            $data_array = array();
            foreach($categories as $category){
                $data_array[] = array(
                        'id' => $category->id,
                        'verify_string' => $category->verify_string,
                        'section_string' => $category->section_string,
                        'category' => $category->category,
                        'details' => $category->details,
                        'created' => $category->created,
                        'last_updated' => $category->last_updated
                    );
            }
            $return_array['status'] = 'success';
            $return_array['data'] = $data_array;
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Category was fetched for this Section';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>