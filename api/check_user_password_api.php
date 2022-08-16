<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $user_string = !empty($_GET['user_string']) ? (string)$_GET['user_string'] : "";
    if(!empty($user_string)){
        $admin = Users::find_by_verify_string($user_string);
        if(!empty($admin)){
            $p_string = !empty($_GET['p_string']) ? (string)$_GET['p_string'] : "";
            if(!empty($p_string)){
                $decoded = base64_decode($p_string);
                $password_string = "FakePass".$decoded."PassFake";
                $password = $admin->encrypt_value($admin->timer, $password_string);
                if($password === $admin->password){
                    $return_array['status'] = 'success';
                    $return_array['message'] = 'Password checked correctly';
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'Wrong Password';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Password was provided';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No User was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No User Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>