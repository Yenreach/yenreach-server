<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $business_string = !empty($post->business_string) ? (string)$post->business_string : "";
        $user_string = !empty($post->user_string) ? (string)$post->user_string : "";
        
        $time = time();
        $day = strftime("%d", $time);
        $month = strftime("%m", $time);
        $year = strftime("%Y", $time);
        
        $visited = PageVisits::find_previous_visit($business_string, $user_string, $day, $month, $year);
        $categories = BusinessCategories::find_by_business_string($business_string);
        if(!empty($categories)){
            $category_array = array();
            foreach($categories as $category){
                $category_array[] = $category->category_string;
            }
            $categs = join(",", $category_array);
        } else {
            $categs = "";
        }
        if(empty($visited)){
            $visited = new PageVisits();
            $visited->user_string = $user_string;
            $visited->business_string = $business_string;
            $visited->categories = $categs;
            $visited->day = $day;
            $visited->month = $month;
            $visited->year = $year;
            $visited->frequency = 1;
        } else {
            $frequency = $visited->frequency;
            $visited->categories = $categs;
            $visited->frequency += 1;
        }
        if($visited->insert()){
            $return_array['status'] = 'success';
            $return_array['data'] = $visited;
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = join(' ', $visited->errors);
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>