<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $application = BillboardApplications::find_by_verify_string($string);
        if(!empty($application)){
            if($application->delete()){
                $return_array['status'] = 'success';
                $return_array['data'] = $application;
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Billboard Application was fetched';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No means of Identification';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>