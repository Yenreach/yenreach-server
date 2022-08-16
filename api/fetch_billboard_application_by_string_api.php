<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $application = BillboardApplications::find_by_verify_string($string);
        if(!empty($application)){
            $advert = AdvertPaymentTypes::find_by_verify_string($application->advert_type);
            $application->advert_type = $advert;
            $user = Users::find_by_verify_string($application->user_string);
            $application->user = [
                    'user' => $user->name,
                    'id' => $user->id,
                    'verify_string' => $user->verify_string
                ];
            if($application->stage == 2){
                $scheduled = BillboardApplications::find_period_total($application->proposed_start_date);
                $scheduled = count($scheduled);
                $application->scheduled_total = $scheduled;
            }
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
    
    $result = json_encode($return_array);
    echo $result;
?>