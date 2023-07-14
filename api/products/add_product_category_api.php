<?php
    require_once("../../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $product_string = !empty($post->product_string) ? (string)$post->product_string : "";
        $product = Products::find_by_product_string($product_string);
        if($product){
            //check if category exeeds limit
            $old_categs = ProductCategories::find_by_product_string($product_string);
            $counted = count($old_categs);
            if($counted < 3){
                $category = !empty($post->category) ? (string)$post->category : "";
                $categ = ProductCategoryList::find_by_category($database->escape_value($category));
                if(!empty($categ)){
                    $category_string = $categ->category_string;
                } else {
                    $categ = new ProductCategoryList();
                    $categ->category = $category;
                    $categ->details = "category added by user";
                    $categ->insert();
                    $category_string = $categ->category_string;
                }

                $product_catg = new ProductCategories();
                $product_catg->product_string = $product_string;
                $product_catg->category_string = $category_string;
                $product_catg->category = $category;
                if($product_catg->insert()){
                    $return_array['status'] = 'success';
                    $return_array['data'] = array(
                            'id' => $product_catg->id,
                            'category_string' => $product_catg->category_string,
                            'product_string' => $product_catg->product_string,
                            'category' => $product_catg->category,
                            'created_at' => $product_catg->created_at,
                            'updated_at' => $product_catg->updated_at
                        );
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = join(' ', $product_catg->errors);
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'You cannot add more that 3 Categories for each Product';
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