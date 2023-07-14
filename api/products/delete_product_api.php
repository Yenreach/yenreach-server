<?php
    require_once("../../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $product_string = !empty($_GET['product_string']) ? (string)$_GET['product_string'] : "";
    if(!empty($product_string)){
        $product = Products::find_by_product_string($product_string); 
        if(!empty($product)){
            $admin_string = !empty($_GET['admin_string']) ? (string)$_GET['admin_string'] : "";
            $business_string = !empty($_GET['business_string']) ? (string)$_GET['business_string'] : "";
            if(!empty($admin_string)){
                $admin = Admins::find_by_verify_string($admin_string);
                if(!empty($admin)){
                    if($product->delete()){
                        $return_array['status'] = 'success';
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = join(' ', $product->errors);
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No Admin was found';
                }
            } else if(!empty($business_string)){
                if($product->business_string == $business_string){
                    if($product->delete()){
                        $return_array['status'] = 'success';
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = join(' ', $product->errors);
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No Business was found';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Admin or User was found';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Product was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>