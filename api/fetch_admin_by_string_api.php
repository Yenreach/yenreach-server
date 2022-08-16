<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $admin = Admins::find_by_verify_string($string);
        if(!empty($admin)){
            $return_array['status'] = 'success';
            $return_array['data'] = array(
                    'id' => $admin->id,
                    'verify_string' => $admin->verify_string,
                    'name' => $admin->name,
                    'username' => $admin->username,
                    'personal_email' => $admin->personal_email,
                    'official_email' => $admin->official_email,
                    'phone' => $admin->phone,
                    'activation' => $admin->activation,
                    'autho_level' => $admin->autho_level,
                    'created' => $admin->created,
                    'last_updated' => $admin->last_updated
                );
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Admin was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>