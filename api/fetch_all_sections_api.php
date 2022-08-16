<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $sections = Sections::find_all();
    if(!empty($sections)){
        $data_array = array();
        foreach($sections as $section){
            $data_array[] = array(
                    "id" => $section->id,
                    "verify_string" => $section->verify_string,
                    "section" => $section->section,
                    "details" => $section->details,
                    "created" => $section->created,
                    "last_updated" => $section->last_updated
                );
        }
        $return_array['status'] = 'success';
        $return_array['data'] = $data_array;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No Section was fetched';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>