<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        if($string == "others"){
            $return_array['status'] = 'success';
            $return_array['data'] = array(
                    'id' => 'others',
                    'verify_string' => 'others',
                    'section' => 'Others',
                    'details' => '',
                    'created' => '',
                    'last_updated' => ''
                );
        } else {
            $section = Sections::find_by_verify_string($string);
            if(!empty($section)){
                $return_array['status'] = 'success';
                $return_array['data'] = array(
                        "id" => $section->id,
                        "verify_string" => $section->verify_string,
                        "section" => $section->section,
                        "details" => $section->details,
                        "created" => $section->created,
                        "last_updated" => $section->last_updated
                    );
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Section was fetched';
            }
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>