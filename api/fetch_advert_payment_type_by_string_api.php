<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $advert = AdvertPaymentTypes::find_by_verify_string($string);
        if(!empty($advert)){
            $return_array['status'] = 'success';
            $return_array['data'] = $advert;
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Advert Payment Type was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>