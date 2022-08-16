<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $verify_string = !empty($post->verify_string) ? (string)$post->verify_string : "";
        if(!empty($verify_string)){
            $link = BusinessVideoLinks::find_by_verify_string($verify_string);
            if(!empty($link)){
                $link->platform = !empty($post->platform) ? (string)$post->platform : "";
                $video_link = !empty($post->video_link) ? (string)$post->video_link : "";
                if($link->platform == "YouTube"){
                    $extract = substr($video_link, 17);
                    $link->video_link = "https://www.youtube.com/embed/".$extract;
                }
                $link->real_link = $video_link;
                if($link->insert()){
                    $return_array['status'] = "success";
                    $return_array['data'] = array(
                            'id' => $link->id,
                            'verify_string' => $link->verify_string,
                            'user_string' => $link->user_string,
                            'business_string' => $link->business_string,
                            'real_link' => $link->real_link,
                            'video_link' => $link->video_link,
                            'platform' => $link->platform,
                            'created' => $link->created,
                            'last_updated' => $link->last_updated
                        );
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = join(' ', $link->errors);
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Video Link was provided';
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