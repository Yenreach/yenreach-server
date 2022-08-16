<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $verify_string = !empty($post->verify_string) ? (string)$post->verify_string : "";
        if(!empty($verify_string)){
            $facility = Facilities::find_by_verify_string($verify_string);
            if(!empty($facility)){
                $facility->facility = !empty($post->facility) ? (string)$post->facility : "";
                $facility->minimum_subscription = !empty($post->minimum_subscription) ? (string)$post->minimum_subscription : "";
                if($facility->insert()){
                    $return_array['status'] = 'success';
                    $return_array['data'] = array(
                            'id' => $facility->id,
                            'verify_string' => $facility->verify_string,
                            'facility' => $facility->facility,
                            'minimum_subscription' => $minimum_subscription,
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
                $return_array['message'] = 'No Business Facility was fetched';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No means of Identification';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>