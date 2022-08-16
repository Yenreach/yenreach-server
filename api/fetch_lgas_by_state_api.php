<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $state_id = !empty($_GET['state_id']) ? (string)$_GET['state_id'] : "";
    if(!empty($state_id)){
        $lgas = LocalGovernments::find_by_state_id($state_id);
        if(!empty($lgas)){
            $data_array = array();
            foreach($lgas as $lga){
                $data_array[] = array(
                        'id' => $lga->id,
                        'state_id' => $lga->state_id,
                        'name' => $lga->name
                    );
            }
            $return_array['status'] = 'success';
            $return_array['data'] = $data_array;
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No LGA was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>