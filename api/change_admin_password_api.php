<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $verify_string = !empty($post->verify_string) ? (string)$post->verify_string : "";
        if(!empty($verify_string)){
            $user = Admins::find_by_verify_string($verify_string);
            if(!empty($user)){
                $old_password = !empty($post->password1) ? (string)$post->password1 : "";
                if(!empty($old_password)){
                    $pword = $user->encrypt_value($user->timer, $old_password);
                    if($pword === $user->password){
                        $password1 = !empty($post->password2) ? (string)$post->password2 : "";
                        $password2 = !empty($post->password3) ? (string)$post->password3 : "";
                        
                        if(!empty($password1)){
                            if($password1 == $password2){
                                $time = time();
                                $user->timer = $time;
                                $password = $user->encrypt_value($user->timer, $password1);
                                $user->password = $password;
                                if($user->insert("update")){
                                    $return_array['status'] = 'success';
                                    $return_array['message'] = 'Password changed successfully';
                                } else {
                                    $return_array['status'] = 'failed';
                                    $return_array['message'] = join(' ', $user->errors);
                                }
                            } else {
                                $return_array['status'] = 'failed';
                                $return_array['message'] = 'Password was not correctly confirmed';
                            }
                        } else {
                            $return_array['status'] = 'failed';
                            $return_array['message'] = 'New Password was not provided';
                        }
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = 'Wrong Old Password';
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'Old Password must be provided';
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