<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $branches = Branches::find_by_business_string($string);
        if(!empty($branches)){
            $data_array = array();
            foreach($branches as $branch){
                $data_array[] = array(
                        'id' => $branch->id, 
                        'verify_string' => $branch->verify_string,
                        'business_string' => $branch->business_string,
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
            }
            $return_array['status'] = 'success';
            $return_array['data'] = $data_array;
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