<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $branch = Branches::find_by_verify_string($string);
        if(!empty($branch)){
            $business = Businesses::find_by_verify_string($branch->business_string);
            $return_array['status'] = 'success';
            $return_array['data'] =  array(
                    'id' => $branch->id, 
                    'verify_string' => $branch->verify_string,
                    'business_string' => $branch->business_string,
                    'business' => $business->name,
                    'head_designation' => $branch->head_designation,
                    'head_name' => $branch->head_name,
                    'phone' => $branch->phone,
                    'email' => $branch->email,
                    'address' => $branch->address,
                    'town' => $branch->town,
                    'lga' => $branch->lga,
                    'state_id' => $branch->state_id,
                    'state' => $branch->state,
                    'created' => $branch->created,
                    'last_updates' => $branch->last_updates
                );
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Branch for this Business';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>