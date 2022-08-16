<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $business = Businesses::find_by_verify_string($string);
        if(!empty($business)){
            if($business->reg_stage == 4){
                $week = new BusinessWeek();
                $week->business_string = $business->verify_string;
                if($week->insert()){
                    $return_array['status'] = 'success';
                    $return_array['data'] = array(
                            'name' => $business->name
                        );
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = $week->errors;
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'Business is not yet approved';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Business was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>