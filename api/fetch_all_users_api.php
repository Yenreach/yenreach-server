<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $users = Users::find_all();
    if(!empty($users)){
        foreach($users as $user){
            unset($user->password);
            unset($user->timer);
            $bus_array = [];
            $businesses = Businesses::find_by_user_string($user->verify_string);
            if(!empty($businesses)){
                foreach($businesses as $business){
                    $bus_array[] = array(
                            'id' => $business->id,
                            'verify_string' => $business->verify_string,
                            'name' => $business->name,
                            'description' => $business->description
                        );
                    
                }
            }
            $user->businesses = $bus_array;
        }
        $return_array['status'] = 'success';
        $return_array['data'] = $users;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No User was fetched';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>