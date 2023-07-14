<?php
    require_once("../../../includes_yenreach/initialize.php");
    $return_array = array();

    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){

        $product = Products::find_by_product_string($string);
        if(!empty($product)){
            $data_array = array();
            $categories = ProductCategories::find_by_product_string($product->product_string);
            foreach($categories as $category){
                $data_array[] = array(
                    'id' => $category->id,
                    'category' => $category->category,
                    'product_string' => $category->product_string,
                    'category_string' => $category->category_string,
                    'created_at' => $category->created_at,
                    'updated_at' => $category->updated_at
                );
            }
            $return_array['status'] = 'success';
            $return_array['data'] = $data_array;
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