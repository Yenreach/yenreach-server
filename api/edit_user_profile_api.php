<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $verify_string = !empty($post->verify_string) ? (string)$post->verify_string : "";
        if(!empty($verify_string)){
            $user = Users::find_by_verify_string($verify_string);
            if(!empty($user)){
                $user->name = !empty($post->name) ? (string)$post->name : "";
                $user->email = !empty($post->email) ? (string)$post->email : "";
                $user->image = !empty($post->image) ? (string)$post->image : "";
                $user->dob = !empty($post->dob) ? (string)$post->dob : "";
                $user->phone = !empty($post->phone) ? (string)$post->phone : "";
                $user->gender = !empty($post->gender) ? (string)$post->gender : "";
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
                            'phone' => $user->phone,
                            'gender' => $user->gender,
                            'dob' => $user->dob,
                            'last_updated' => $user->last_updated,
                            'confirmed_email' => $user->confirmed_email
                        );
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = join(' ', $user->errors);
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No User was fetched';
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