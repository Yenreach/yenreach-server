<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $reviews = BusinessReviews::find_by_business_string($string);
        if(!empty($reviews)){
            $total_reviews = count($reviews);
            $five = 0;
            $four = 0;
            $three = 0;
            $two = 0;
            $one = 0;
            $stars = array();
            
            foreach($reviews as $review){
                if($review->star == 5){
                    $five += 1;
                } elseif($review->star == 4){
                    $four += 1;
                } elseif($review->star == 3){
                    $three += 1;
                } elseif($review->star == 2){
                    $two += 1;
                } elseif($review->star == 1){
                    $one += 1;
                }
                $stars[] = $review->star;
            }
            
            $sum = array_sum($stars);
            $average = round($sum/$total_reviews);
            $theaverage = (int)$average;
            $return_array['status'] = 'success';
            $return_array['data'] = array(
                    'total' => $total_reviews,
                    'average' => $theaverage,
                    'five' => $five,
                    'four' => $four,
                    'three' => $three,
                    'two' => $two,
                    'one' => $one
                );
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Review for this Business';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>