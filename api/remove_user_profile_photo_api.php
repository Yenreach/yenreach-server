<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $user = Users::find_by_verify_string($string);
        if(!empty($user)){
            $filepath = $user->image;
            
            $user->image = "";
            if($user->insert()){
                $return_array['status'] = 'success';
                $return_array['data'] = array(
                        'id' => $user->id,
                        'verify_string' => $user->verify_string,
                        'filepath' => $filepath
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
        $return_array['status'] = "failed";
        $return_array['message'] = "No data was provided";
    }
    
    $result = json_encode($return_array);
    echo $result;
?>