<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $username = !empty($post->username) ? (string)$post->username : "";
        $password = !empty($post->password) ? (string)$post->password : "";
        
        $user = new Users();
        $logged = $user->authenticate($username, $password);
        if($logged){
            $return_array['status'] = 'success';
            $return_array['data'] = array(
                    'id' => $logged->id, 
                    'verify_string' => $logged->verify_string,
                    'name' => $logged->name,
                    'username' => $logged->email,
                    'user_type' => "user",
                    'autho_level' => $logged->autho_level
                );
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = join(' ', $user->errors);
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['messave'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>