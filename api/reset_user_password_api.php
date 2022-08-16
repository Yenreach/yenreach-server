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
                $password = !empty($post->password) ? (string)$post->password : "";
                if(!empty($password)){
                    $time = time();
                    $user->timer = $time;
                    $user->password = $user->encrypt_value($time, $password);
                    if($user->insert()){
                        $return_array['status'] = 'success';
                        $return_array['message'] = 'Password successfully Reset';
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
                $return_array['message'] = 'No User was fetched';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No meas of Identification';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>