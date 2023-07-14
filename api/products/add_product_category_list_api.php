<?php
    require_once("../../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $category = new ProductCategoryList();
        $category->category = !empty($post->category) ? (string)$post->category : "";
        $category->details = !empty($post->details) ? (string)$post->details : "";
        
        if($category->insert()){
            $return_array['status'] = 'success';
            $return_array['data'] = array(
                    'id' => $category->id,
                    'category_string' => $category->category_string,
                    'category' => $category->category,
                    'details' => $category->details,
                    'created_at' => $category->created_at,
                    'updated_at' => $category->updated_at
                );
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = $category->errors;
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>