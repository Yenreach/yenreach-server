<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $adverts = AdvertPaymentTypes::find_all();
    if(!empty($adverts)){
        $return_array['status'] = 'success';
        $return_array['data'] = $adverts;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No Advert Payment Type was fetched';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>