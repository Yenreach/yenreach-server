<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $businesses = Businesses::find_approved_businesses();
    if(!empty($businesses)){
        $states_array = array();
        
        foreach($businesses as $business){
            $stating = trim($business->state);
            if(!in_array($stating, $states_array)){
                $states_array[] = $stating;
            }
        }
        
        if(!empty($states_array)){
            sort($states_array);
            $data_array = array();
            
            foreach($states_array as $stated){
                $states = States::find_by_name($stated);
                $data_array[] = $states;
            }
            $return_array['status'] = 'success';
            $return_array['data'] = $data_array;
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No State was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No Business was fetched';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>