<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $states = LocalGovernments::find_all();
    if(!empty($states)){
        $data_array = array();
        foreach($states as $state){
            $data_array[] = array(
                    'id' => $state->id,
                    'state_id' => $state->state_id,
                    'name' => $state->name
                );
        }
        $return_array['status'] = 'success';
        $return_array['data'] = $data_array;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No LGA was fetched';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>