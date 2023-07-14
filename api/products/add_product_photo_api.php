<?php
    require_once("../../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $product_string = !empty($post->product_string) ? (string)$post->product_string : "";
        $product = Products::find_by_product_string($product_string);
        if($product){
            $product_photo = new ProductPhotos();
            $product_photo->product_string = $product->product_string;
            $product_photo->filename = !empty($post->filename) ? (string)$post->filename : "";
            
            if($product_photo->insert()){
                $return_array['status'] = 'success';
                $return_array['data'] = array(
                        'id' => $product_photo->id,
                        'photo_string' => $product_photo->photo_string,
                        'product_string' => $product_photo->product_string,
                        'filename' => $product_photo->filename,
                        'created_at' => $product_photo->created_at,
                        'updated_at' => $product_photo->updated_at
                    );
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = join(' ', $product_photo->errors);
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'Product not found';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }

    $result = json_encode($return_array);
    echo $result;
?>