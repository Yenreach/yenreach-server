<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $business = Businesses::find_by_verify_string($string);
        if(!empty($business)){
            $old_filename = $business->filename;
            $business->filename = "";
            if($business->insert()){
                $return_array['status'] = 'success';
                $return_array['data'] = array(
                        'id' => $business->id,
                        'verify_string' => $business->verify_string,
                        'old_filename' => $old_filename,
                        'filename' => $business->filename
                    );
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = join('<br />', $business->errors);
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Business was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>