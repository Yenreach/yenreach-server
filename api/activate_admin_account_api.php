<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $verify_string = !empty($post->verify_string) ? (string)$post->verify_string : "";
        if(!empty($verify_string)){
            $admin = Admins::find_by_verify_string($verify_string);
            if(!empty($admin)){
                $pword = !empty($post->password) ? (string)$post->password : "";
                if(!empty($pword)){
                    $time = time();
                    $admin->timer = $time;
                    $password = $admin->encrypt_value($admin->timer, $pword);
                    $admin->password = $password;
                    $admin->activation = 2;
                    if($admin->insert("update")){
                        $return_array['status'] = 'success';
                        $return_array['message'] = "Account activated successfully";
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = join(' ', $admin->errors);
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No Password was provided';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Admin was fetched';
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