<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $facility = Facilities::find_by_verify_string($string);
        if(!empty($facility)){
            $facility->activation = 0;
            if($facility->insert()){
                $return_array['status'] = 'success';
                $return_array['data'] = array(
                        'id' => $facility->id,
                        'verify_string' => $facility->verify_string,
                        'facility' => $facility->facility,
                        'minimum_subscription' => $facility->minimum_subscription,
                        'activation' => $facility->activation,
                        'created' => $facility->created,
                        'last_updated' => $facility->last_updated
                    );
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = join(' ', $facility->errors);
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Facility was provided';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>