<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $pagevisits = PageVisits::find_by_business_string($string);
        if(!empty($pagevisits)){
            $return_array['status'] = 'success';
            $return_array['data'] = array(
                'pagevisits' => $pagevisits
            );
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Business was found';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>