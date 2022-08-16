<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $category = Categories::find_by_verify_string($string);
        if(!empty($category)){
            if($category->delete()){
                $buscategs = BusinessCategories::find_by_category_string($category->verify_string);
                if(!empty($buscategs)){
                    foreach($buscategs as $categ){
                        $categ->delete();
                    }
                }
                
                $return_array['status'] = 'success';
                $return_array['data'] = array(
                        'id' => $category->id,
                        'verify_string' => $category->verify_string,
                        'section_string' => $category->section_string,
                        'category' => $category->category,
                        'details' => $category->details,
                        'created' => $category->created,
                        'last_updated' => $category->last_updated
                    );
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'Category Deleting failed';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Category was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>