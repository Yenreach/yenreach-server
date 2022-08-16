<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $applications = BillboardApplications::find_all();
    if(!empty($applications)){
        foreach($applications as $application){
            $user = Users::find_by_verify_string($application->user_string);
            $application->user = $user;
            $advert = AdvertPaymentTypes::find_by_verify_string($application->advert_type);
            $application->advert_type = $advert;
            unset($application->user_string);
        }
        $return_array['status'] = 'success';
        $return_array['data'] = $applications;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No Application was fetched';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>