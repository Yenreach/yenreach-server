<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $user = Users::find_by_verify_string($string);
        if(!empty($user)){
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
                    'cv' => $user->cv,
                    'created' => $user->created,
                    'last_updated' => $user->last_updated,
                    'confirmed_email' => $user->confirmed_email,
                    'phone' => $user->phone,
                    'gender' => $user->gender,
                    'dob' => $user->dob,
                );
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No User was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>