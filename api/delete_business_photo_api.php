<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $photo = BusinessPhotos::find_by_verify_string($string);
        if(!empty($photo)){
            if($photo->delete()){
                $return_array['status'] = 'success';
                $return_array['data'] = array(
                        'id' => $photo->id,
                        'verify_string' => $photo->verify_string,
                        'user_string' => $photo->user_string,
                        'business_string' => $photo->business_string,
                        'filename' => $photo->filename,
                        'filepath' => $photo->filepath,
                        'size' => $photo->size,
                        'created' => $photo->created,
                        'last_updated' => $photo->last_updated
                    );
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'Photo Delete Failed';
            }
        } else {
            $return_array['status'] = 'success';
            $return_array['message'] = 'No Photo was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>