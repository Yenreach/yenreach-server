<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $username = !empty($post->username) ? (string)$post->username : "";
        $password = !empty($post->password) ? (string)$post->password : "";
        
        $admin = new Admins();
        $login = $admin->authenticate($username, $password);
        if($login){
            $return_array['status'] = 'success';
            $return_array['data'] = array(
                    'id' => $login->id,
                    'verify_string' => $login->verify_string,
                    'username' => $login->username,
                    'autho_level' => $login->autho_level,
                    'user_type' => "admin"
                );
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = join(' ', $admin->errors);
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>