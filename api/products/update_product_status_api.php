<?php
    require_once("../../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        $product_string = !empty($post->product_string) ? (string)$post->product_string : "";
        if(!empty($product_string)){
            $product = Products::find_by_product_string($product_string); 
            if(!empty($product)){
                $admin_string = !empty($post->admin_string) ? (string)$post->admin_string : "";
                $business_string = !empty($post->business_string) ? (string)$post->business_string : "";
                $product->product_status = !empty($post->status) ? (bool)$post->status : false;

                $business = Businesses::find_by_verify_string($product->business_string);

                if($business){
                    if(!empty($admin_string)){
                        $admin = Admins::find_by_verify_string($admin_string);
                        if(!empty($admin)){                     
                            if($product->insert()){
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
                                $return_array['message'] = join(' ', $product->errors);
                            }
                        } else {
                            $return_array['status'] = 'failed';
                            $return_array['message'] = 'No Admin was found';
                        }
                    } else if(!empty($business_string)){
                        if($product->business_string == $business_string){ 
                            if($product->insert()){
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
                                $return_array['message'] = join(' ', $product->errors);
                            }
                        } else {
                            $return_array['status'] = 'failed';
                            $return_array['message'] = 'No User was found';
                        }
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = 'No Admin or User was found';
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No Business was found';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Product was fetched';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No means of Identification';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>