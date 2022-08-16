<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $verify_string = !empty($post->verify_string) ? (string)$post->verify_string : "";
        if(!empty($verify_string)){
            $sub = BusinessSubscriptions::find_by_verify_string($verify_string);
            if(!empty($sub)){
                $sub->package = !empty($post->package) ? (string)$post->package : "";
                $sub->description = !empty($post->description) ? (string)$post->description : "";
                $sub->position = !empty($post->position) ? (int)$post->position : 0;
                $sub->photos = !empty($post->photos) ? (int)$post->photos : 0;
                $sub->videos = !empty($post->videos) ? (int)$post->videos : 0;
                $sub->slider = !empty($post->slider) ? (int)$post->slider : 0;
                $sub->socialmedia = !empty($post->socialmedia) ? (int)$post->socialmedia : 0;
                $sub->branches = !empty($post->branches) ? (int)$post->branches : 0;
                
                if($sub->insert()){
                    $return_array['status'] = 'success';
                    $return_array['data'] = array(
                            'id' => $sub->id, 
                            'verify_string' => $sub->verify_string,
                            'package' => $sub->package,
                            'description' => $sub->description,
                            'photos' => $sub->photos,
                            'videos' => $sub->videos,
                            'slider' => $sub->slider,
                            'socialmedia' => $sub->socialmedia,
                            'branches' => $sub->branches,
                            'created' => $sub->created, 
                            'last_updated' => $sub->last_updated
                        );
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = join(' ', $sub->errors);
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Business Subscription was fetched';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No means of Identification';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>