<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $verify_string = !empty($post->verify_string) ? (string)$post->verify_string : "";
        if(!empty($verify_string)){
            $photo = BusinessPhotos::find_by_verify_string($verify_string);
            if(!empty($photo)){
                $old_filename = !empty($photo->filename) ? (string)$post->filename : "";
                $photo->size = !empty($post->size) ? (int)$post->size : 0;
                $photo->filename = "";
                if($photo->insert()){
                    $return_array['status'] = 'success';
                    $return_array['data'] = array(
                            'id' => $photo->id,
                            'verify_string' => $photo->verify_string,
                            'user_string' => $photo->user_string,
                            'business_string' => $photo->business_string,
                            'filename' => $photo->filename,
                            'old_filename' => $old_filename,
                            'filepath' => $photo->filepath,
                            'size' => $photo->size,
                            'created' => $photo->created,
                            'last_updated' => $photo->last_updated
                        );
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = join(' ', $photo->errors);
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Business Photo was fetched';
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