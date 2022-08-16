<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $categories = Categories::find_all();
    if(!empty($categories)){
        $data_array = array();
        foreach($categories as $category){
            if(empty($category->section_string) || $category->section_string == "others"){
                $section = "Others";
                $section_string = "others";
            } else {
                $section_string = $category->section_string;
                $sect = Sections::find_by_verify_string($section_string);
                $section = $sect->section;
            }
            $data_array[] = array(
                    'id' => $category->id, 
                    'verify_string' => $category->verify_string,
                    'section_string' => $section_string,
                    'section' => $section,
                    'category' => $category->category,
                    'details' => $category->details,
                    'created' => $category->created,
                    'last_updated' => $category->last_updated
                );
        }
        $return_array['status'] = 'success';
        $return_array['data'] = $data_array;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No Category was fetched';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>