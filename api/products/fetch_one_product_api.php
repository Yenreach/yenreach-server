<?php
    require_once("../../../includes_yenreach/initialize.php");
    $return_array = array();

    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        // $data = json_decode($post_json);

        $product = Products::find_by_product_string($string);
        if(!empty($product)){
            $categories = ProductCategories::find_by_product_string($product->product_string);
            $photos = ProductPhotos::find_by_product_string($product->product_string);
            $return_array['status'] = 'success';
            $return_array['data'] = array(
                'id' => $product->id,
                'product_string' => $product->product_string,
                'business_string' => $product->business_string,
                'product_name' => $product->product_name,
                'product_description' => $product->product_description,
                'product_price' => $product->product_price,
                'product_quantity' => $product->product_quantity,
                'product_color' => $product->product_color,
                'product_safety_tip' => $product->product_safety_tip,
                'product_status' => $product->product_status,
                'categories' => $categories,
                'photos' => $photos,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at
            );
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Blog Post was found';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>