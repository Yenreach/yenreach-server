<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $category = Categories::find_by_category($string);
        if(!empty($category)){
            if(!empty($category->section_string)){
                $categories = Categories::find_others_by_section_string($category->section_string, $category->verify_string);
                if(!empty($categories)){
                    $data_array = array();
                    foreach($categories as $categ){
                        $bus_category = BusinessCategories::find_by_category_string($categ->verify_string);
                        if(!empty($bus_category)){
                            foreach($bus_category as $bus_string){
                                $business = Businesses::find_by_verify_string($bus_string->business_string);
                                if((!empty($business)) && ($business->reg_stage == 4)){
                                    if(!in_array($categ->verify_string, $data_array)){
                                        $data_array[] = $categ->verify_string;
                                    }
                                }
                            }
                        }
                    }
                    if(!empty($data_array)){
                        $categ_array = array();
                        foreach($data_array as $string){
                            $cat = Categories::find_by_verify_string($string);
                            $categ_array[] = array(
                                    'id' => $cat->id,
                                    'verify_string' => $cat->verify_string,
                                    'category' => $cat->category
                                );
                        }
                        $return_array['status'] = 'success';
                        $return_array['data'] = $categ_array;
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = 'No filled Category was fetched';
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No Related Category';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'Category does not belong to a Section';
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