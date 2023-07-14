<?php
    require_once("../../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $photo_string = !empty($_GET['photo_string']) ? (string)$_GET['photo_string'] : "";
    if(!empty($photo_string)){
        $photo = ProductPhotos::find_by_photo_string($photo_string); 
        if(!empty($photo)){
            $admin_string = !empty($_GET['admin_string']) ? (string)$_GET['admin_string'] : "";
            $product_string = !empty($_GET['product_string']) ? (string)$_GET['product_string'] : "";
            if(!empty($admin_string)){
                $admin = Admins::find_by_verify_string($admin_string);
                if(!empty($admin)){
                    if($photo->delete()){
                        $return_array['status'] = 'success';
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = join(' ', $photo->errors);
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No Admin was found';
                }
            } else if(!empty($product_string)){
                if(!empty($photo->product_string == $product_string)){
                    if($photo->delete()){
                        $return_array['status'] = 'success';
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = join(' ', $photo->errors);
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
            $return_array['message'] = 'No Photo was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>