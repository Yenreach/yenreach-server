<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $section = new Sections();
        $section->section = !empty($post->section) ? (string)$post->section : "";
        $section->details = !empty($post->details) ? (string)$post->details : "";
        if($section->insert()){
            $return_array['status'] = 'success';
            $return_array['data'] = array(
                    "id" => $section->id,
                    "verify_string" => $section->verify_string,
                    "section" => $section->section,
                    "details" => $section->details,
                    "created" => $section->created,
                    "last_updated" => $section->last_updated
                );
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = $section->errors;
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>