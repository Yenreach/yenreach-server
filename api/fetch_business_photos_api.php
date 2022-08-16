<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $photos = BusinessPhotos::find_by_business_string($string);
        if(!empty($photos)){
            $data_array = array();
            foreach($photos as $photo){
                $data_array[] = array(
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
            }
            $return_array['status'] = 'success';
            $return_array['data'] = $data_array;
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Photo was fetched for this Business';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>