<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    // $email = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    $post_json = @file_get_contents("php://input");

    if(!empty($post_json)){
        $post = json_decode($post_json);

        $new_password = !empty($post->new_password) ? (string)$post->new_password : "";
        $confirm_password = !empty($post->confirm_password) ? (string)$post->confirm_password : "";
        $email = !empty($post->email) ? (string)$post->email : "";

        if($new_password == $confirm_password){
            if(strlen($new_password) >= 8){
                $user = Users::find_by_email($email);
                if(!empty($user)){
                    $user->password = $user->encrypt_value($user->timer, $new_password);
                    if($user->insert()){
                        $return_array['status'] = 'success';
                        $return_array['data'] = array(
                                'id' => $user->id,
                                'verify_string' => $user->verify_string,
                                'name' => $user->name,
                                'email' => $user->email,
                                'timer' => $user->timer,
                                'password' => $user->password,
                                'image' => $user->image,
                                'listed' => $user->listed,
                                'activation' => $user->activation,
                                'created' => $user->created,
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
                $return_array['message'] = 'Password must be at least 8 characters';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'Passwords do not match';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>
