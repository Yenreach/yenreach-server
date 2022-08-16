<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $category = new Categories();
        $category->section_string = !empty($post->section_string) ? (string)$post->section_string : "";
        $category->category = !empty($post->category) ? (string)$post->category : "";
        $category->details = !empty($post->details) ? (string)$post->details : "";
        
        if($category->insert()){
            $return_array['status'] = 'success';
            $return_array['data'] = array(
                    'id' => $category->id,
                    'verify_string' => $category->verify_string,
                    'section_string' => $category->section_string,
                    'category' => $category->category,
                    'details' => $category->details,
                    'created' => $category->created,
                    'last_updated' => $category->last_updated
                );
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = $category->errors;
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>