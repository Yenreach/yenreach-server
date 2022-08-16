<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $admins = Admins::find_all();
    if(!empty($admins)){
        $data_array = array();
        foreach($admins as $admin){
            $data_array[] = array(
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
        }
        $return_array['status'] = "success";
        $return_array['data'] = $data_array;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No Admin was fetched';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>