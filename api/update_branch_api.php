<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $verify_string = !empty($post->verify_string) ? (string)$post->verify_string : "";
        if(!empty($verify_string)){
            $branch = Branches::find_by_verify_string($verify_string);
            if(!empty($branch)){
                $branch->head_designation = !empty($post->head_designation) ? (string)$post->head_designation : "";
                $branch->head_name = !empty($post->head_name) ? (string)$post->head_name : "";
                $branch->phone = !empty($post->phone) ? (string)$post->phone : "";
                $branch->email = !empty($post->email) ? (string)$post->email : "";
                $branch->address = !empty($post->address) ? (string)$post->address : "";
                $branch->town = !empty($post->town) ? (string)$post->town : "";
                $branch->lga = !empty($post->lga) ? (string)$post->lga : "";
                $branch->state_id = !empty($post->state_id) ? (int)$post->state_id : "";
                $state = States::find_by_id($branch->state_id);
                $branch->state = $state->name;
                if($branch->insert()){
                    $return_array['status'] = 'success';
                    $return_array['data'] = array(
                            'id' => $branch->id, 
                            'verify_string' => $branch->verify_string,
                            'business_string' => $branch->business_string,
                            'head_designation' => $branch->head_designation,
                            'head_name' => $branch->head_name,
                            'phone' => $branch->phone,
                            'email' => $branch->email,
                            'address' => $branch->address,
                            'town' => $branch->town,
                            'lga' => $branch->lga,
                            'state_id' => $branch->state_id,
                            'state' => $branch->state,
                            'created' => $branch->created,
                            'last_updates' => $branch->last_updates
                        );
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = join(' ', $branch->errors);
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Branch was fetched';
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