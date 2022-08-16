<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $section = Sections::find_by_verify_string($string);
        if(!empty($section)){
            if($section->delete()){
                $return_array['status'] = 'success';
                $return_array['data'] = array(
                        "id" => $section->id,
                        "verify_string" => $section->verify_string,
                        "section" => $section->section,
                        "details" => $section->details,
                        "created" => $section->created,
                        "last_updated" => $section->last_updated
                    );
                
                $categorys = Categories::find_by_section_string($section->verify_string);
                if(!empty($categorys)){
                    foreach($categorys as $category){
                        if($category->delete()){
                            $buscategs = BusinessCategories::find_by_category_string($category->verify_string);
                            if(!empty($buscategs)){
                                foreach($buscategs as $categ){
                                    $categ->delete();
                                }
                            }
                        }
                    }
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'Section was not deleted';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Section was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>