<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $verify_string = !empty($post->verify_string) ? (string)$post->verify_string : "";
        if(!empty($verify_string)){
            $business = Businesses::find_by_verify_string($verify_string);
            if(empty($business)){
                $bus = new Businesses();
                $bus->verify_string = !empty($post->verify_string) ? (string)$post->verify_string : "";
                $bus->name = !empty($post->name) ? (string)$post->name : "";
                $bus->description = !empty($post->description) ? (string)$post->description : "";
                $bus->user_string = !empty($post->user_string) ? (string)$post->user_string : "";
                $bus->category = !empty($post->category) ? (string)$post->category : "";
                $bus->phonenumber = !empty($post->phonenumber) ? (string)$post->phonenumber : "";
                $bus->address = !empty($post->address) ? (string)$post->address : "";
                $bus->state = !empty($post->state) ? (string)$post->state : "";
                $bus->email = !empty($post->email) ? (string)$post->email : "";
                $bus->whatsapp = !empty($post->whatsapp) ? (string)$post->whatsapp : "";
                $bus->website = !empty($post->website) ? (string)$post->website : "";
                $bus->facebook_link = !empty($post->facebook_link) ? (string)$post->facebook_link : "";
                $bus->instagram_link = !empty($post->instagram_link) ? (string)$post->instagram_link : "";
                $bus->youtube_link = !empty($post->youtube_link) ? (string)$post->youtube_link : "";
                $bus->linkedin_link = !empty($post->linkedin_link) ? (string)$post->linkedin_link : "";
                $bus->working_hours = !empty($post->working_hours) ? (string)$post->working_hours : "";
                $bus->cv = !empty($post->cv) ? (string)$post->cv : "";
                if(!empty($post->modifiedby)){
                    $bus->modified = "admin";
                }
                $bus->experience = !empty($post->experience) ? (string)$post->experience : "";
                $bus->created = !empty($post->created) ? (int)$post->created : 0;
                
                if($bus->insert()){
                    $return_array['status'] = 'success';
                    $return_array['message'] = "Business moved successfully";
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = join(' ', $bus->errors);
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'Already moved';
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