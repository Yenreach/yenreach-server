<?php
    require_once("../../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $products = Products::find_all();
    if(!empty($products)){
        $data_array = array();
        foreach($products as $product){
            $categories = ProductCategories::find_by_product_string($product->product_string);
            $photos = ProductPhotos::find_by_product_string($product->product_string);
            $data_array[] = array(
                'id' => $product->id,
                'product_string' => $product->product_string,
                'business_string' => $product->business_string,
                'product_name' => $product->product_name,
                'product_description' => $product->product_description,
                'product_price' => $product->product_price,
                'product_quantity' => $product->product_quantity,
                'product_color' => $product->product_color,
                'product_safety_tip' => $product->product_safety_tip,
                'categories' => $categories,
                'product_status' => $product->product_status,
                'photos' => $photos,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at
            );
        }
        $return_array['status'] = 'success';
        $return_array['data'] = $data_array;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No Product was fetched';
    }

    $result = json_encode($return_array);
    echo $result;
?>