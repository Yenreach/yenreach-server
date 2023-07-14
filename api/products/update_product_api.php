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
                $product->product_name = !empty($post->name) ? (string)$post->name : "";
                $product->product_description = !empty($post->description) ? (string)$post->description : "";
                $product->product_price = !empty($post->price) ? (string)$post->price : "";
                $product->product_quantity = !empty($post->quantity) ? (string)$post->quantity : "";
                $product->product_color = !empty($post->color) ? (string)$post->color : "";
                $product->product_safety_tip = !empty($post->safety_tip) ? (string)$post->safety_tip : "";
                $business = Businesses::find_by_verify_string($product->business_string);

                $categories = !empty($post->categories) ? (array)$post->categories : array();
                $photos = !empty($post->photos) ? (array)$post->photos : array();

                if($business){
                    if(!empty($admin_string)){
                        $admin = Admins::find_by_verify_string($admin_string);
                        if(!empty($admin)){                     
                            if($product->insert()){
                                $category_temp = ProductCategories::find_by_product_string($product->product_string);
                                if(!empty($category_temp)){
                                    foreach($category_temp as $cat){
                                        $cat->delete();
                                    }
                                }

                                foreach($categories as $category){         
                                    $categ = ProductCategoryList::find_by_category((string)$category->category);
                                    if(!empty($categ)){
                                        $category_string = $categ->category_string;
                                    } else {
                                        $categ_new = new ProductCategoryList();
                                        $categ_new->category = $category->category;
                                        $categ_new->details = "category added by user";
                                        $categ_new->insert();
                                        $category_string = $categ_new->category_string;
                                    }
                        
                                    $product_catg = new ProductCategories();
                                    $product_catg->product_string = $product->product_string;
                                    $product_catg->category_string = $category_string;
                                    $product_catg->category = $category->category;
                                    $product_catg->insert();
                                }

                                $photos_temp = ProductPhotos::find_by_product_string($product->product_string);
                                if(!empty($photos_temp)){
                                    foreach($photos_temp as $photo){
                                        $photo->delete();
                                    }
                                }
                
                                foreach($photos as $photo){
                                    $product_photo = new ProductPhotos();
                                    $product_photo->product_string = $product->product_string;
                                    $product_photo->filename = $photo->filename;
                                    $product_photo->insert();
                                }
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
                                $category_temp = ProductCategories::find_by_product_string($product->product_string);
                                if(!empty($category_temp)){
                                    foreach($category_temp as $cat){
                                        $cat->delete();
                                    }
                                }
                                
                                foreach($categories as $category){         
                                    $categ = ProductCategoryList::find_by_category((string)$category->category);
                                    if(!empty($categ)){
                                        $category_string = $categ->category_string;
                                    } else {
                                        $categ_new = new ProductCategoryList();
                                        $categ_new->category = $category->category;
                                        $categ_new->details = "category added by user";
                                        $categ_new->insert();
                                        $category_string = $categ_new->category_string;
                                    }
                        
                                    $product_catg = new ProductCategories();
                                    $product_catg->product_string = $product->product_string;
                                    $product_catg->category_string = $category_string;
                                    $product_catg->category = $category->category;
                                    $product_catg->insert();
                                }

                                $photos_temp = ProductPhotos::find_by_product_string($product->product_string);
                                if(!empty($photos_temp)){
                                    foreach($photos_temp as $photo){
                                        $photo->delete();
                                    }
                                }
                
                                foreach($photos as $photo){
                                    $product_photo = new ProductPhotos();
                                    $product_photo->product_string = $product->product_string;
                                    $product_photo->filename = $photo->filename;
                                    $product_photo->insert();
                                }
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
                $return_array['message'] = 'No Blog was fetched';
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