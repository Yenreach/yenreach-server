<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $category = Categories::find_by_verify_string($string);
        if(!empty($category)){
            $buscategs = BusinessCategories::find_by_category_string($category->verify_string);
            if(!empty($buscategs)){
                $data_array = array();
                
                foreach($buscategs as $buscateg){
                    $business = Businesses::find_by_verify_string($buscateg->business_string);
                    if(!empty($business)){
                        $user = Users::find_by_verify_string($business->user_string);
                        $business->owner_name = $user->name;
                        $business->owner_email = $user->email;
                        $data_array[] = $business;
                    }
                }
                $return_array['status'] = 'success';
                $return_array['data'] = $data_array;
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'This Category does not have any Business registered under it';
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