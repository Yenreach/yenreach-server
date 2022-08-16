<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $facilities = Facilities::find_all();
    if(!empty($facilities)){
        $data_array = array();
        foreach($facilities as $facility){
            if($facility->minimum_subscription != "free"){
                $package = BusinessSubscriptions::find_by_verify_string($facility->minimum_subscription);
                $subscription = $package->package;
            } else {
                $subscription = "Free Package";
            }
            $data_array[] = array(
                    'id' => $facility->id,
                    'verify_string' => $facility->verify_string,
                    'facility' => $facility->facility,
                    'minimum_subscription' => $facility->minimum_subscription,
                    'subscription' => $subscription,
                    'activation' => $facility->activation,
                    'created' => $facility->created,
                    'last_updated' => $facility->last_updated
                );
        }
        $return_array['status'] = 'success';
        $return_array['data'] = $data_array;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No Business Facility was fetched';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>