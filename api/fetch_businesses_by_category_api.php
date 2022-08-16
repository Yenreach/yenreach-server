<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $string = $database->escape_value($string);
        $category = Categories::find_by_category($string);
        if(!empty($category)){
            $businesses = BusinessCategories::find_by_category_string($category->verify_string);
            if(!empty($businesses)){
                $data_array = array();
                
                foreach($businesses as $categbusiness){
                    $business = Businesses::find_by_verify_string($categbusiness->business_string);
                    if((!empty($business)) && ($business->reg_stage == 4)){
                        $photos = BusinessPhotos::find_by_business_string($business->verify_string);
                        $categories = BusinessCategories::find_by_business_string($business->verify_string);
                        $data_array[] = array(
                                'id' => $business->id,
                                'verify_string' => $business->verify_string,
                                'name' => $business->name,
                                'description' => $business->description,
                                'user_string' => $business->user_string,
                                'address' => $business->address,
                                'town' => $business->town,
                                'lga' => $business->lga,
                                'state' => $business->state,
                                'state_id' => $business->state_id,
                                'modifiedby' => $business->modifiedby,
                                'experience' => $business->experience,
                                'month_started' => $business->month_started,
                                'year_started' => $business->year_started,
                                'reg_stage' => $business->reg_stage,
                                'activation' => $business->activation,
                                'filename' => $business->filename,
                                'created' => $business->created,
                                'last_updated' => $business->last_updated,
                                'photos' => $photos,
                                'categories' => $categories
                            );
                    }
                }
                if(!empty($data_array)){
                    $return_array['status'] = 'success';
                    $return_array['data'] = $data_array;
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No Active Busines was fetched for this Category';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Business was fetched for this Category';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Category was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>