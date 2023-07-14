<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
 
        $user_string = !empty($post->user_string) ? (string)$post->user_string : "";
        if(!empty($user_string)){
            $user = Users::find_by_verify_string($user_string); 
            if(!empty($user)){
                $user->cv = !empty($post->cv) ? (string)$post->cv : '';

                if($user->insert()){
                    $return_array['status'] = 'success';
                    $return_array['data'] = array(
                        'id' => $user->id,
                        'verify_string' => $user->verify_string,
                        'name' => $user->name,
                        'email' => $user->email,
                        'image' => $user->image, 
                        'listed' => $user->listed,
                        'refer_method' => $user->refer_method,
                        'activation' => $user->activation,
                        'autho_level' => $user->autho_level,
                        'created' => $user->created,
                        'cv' => $user->cv,
                        'last_updated' => $user->last_updated,
                        'confirmed_email' => $user->confirmed_email
                    );
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = join(' ', $job->errors);
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No user was fetched';
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
