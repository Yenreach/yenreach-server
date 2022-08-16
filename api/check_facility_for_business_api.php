<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $business_string = !empty($_GET['business']) ? (string)$_GET['business'] : "";
    $facility_string = !empty($_GET['facility']) ? (string)$_GET['facility'] : "";
    
    $check = BusinessFacilities::find_by_business_facility($business_string, $facility_string);
    if(!empty($check)){
        $return_array['status'] = 'success';
        $return_array['message'] = 'Facility Available';
        $return_array['data'] = array(
                'id' => $check->id,
                'verify_string' => $check->verify_string,
                'business_string' => $check->business_string,
                'facility_string' => $check->facility_string,
                'created' => $check->created,
                'last_updated' => $check->last_updated
            );
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'Facility not Available';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>