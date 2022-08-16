<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $states = States::find_all();
    if(!empty($states)){
        $data_array = array();
        foreach($states as $state){
            $data_array[] = array(
                    'id' => $state->id,
                    'name' => $state->name
                );
        }
        $return_array['status'] = 'success';
        $return_array['data'] = $data_array;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No State was fetched';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>