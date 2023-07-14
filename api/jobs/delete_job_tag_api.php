<?php
    require_once("../../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $category_string = !empty($_GET['category_string']) ? (string)$_GET['category_string'] : "";
    if(!empty($category_string)){
        $category = ProductCategories::find_by_category_string($category_string); 
        if(!empty($category)){
            $admin_string = !empty($_GET['admin_string']) ? (string)$_GET['admin_string'] : "";
            $product_string = !empty($_GET['product_string']) ? (string)$_GET['product_string'] : "";
            if(!empty($admin_string)){
                $admin = Admins::find_by_verify_string($admin_string);
                if(!empty($admin)){
                    if($category->delete()){
                        $return_array['status'] = 'success';
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = join(' ', $category->errors);
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No Admin was found';
                }
            } else if(!empty($product_string)){
                if(!empty($category->product_string == $product_string)){
                    if($category->delete()){
                        $return_array['status'] = 'success';
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = join(' ', $category->errors);
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No product was found';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Admin or business was found';
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