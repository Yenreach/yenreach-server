<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $facility = new Facilities();
        $facility->facility = !empty($post->facility) ? (string)$post->facility : "";
        $facility->minimum_subscription = !empty($post->minimum_subscription) ? (string)$post->minimum_subscription : "";
        $facility->activation = 1;
        
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
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>