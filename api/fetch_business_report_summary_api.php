<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $business = Businesses::find_by_verify_string($string);
        if(!empty($business)){
            $visits = PageVisits::find_by_business_string($business->verify_string);
            if(!empty($visits)){
                $total_people = 0;
                $total_visits = 0;
                
                $people = array();
                foreach($visits as $visit){
                    $total_visits += $visit->frequency;
                    if(!in_array($visit->user_string, $people)){
                        $people[] = $visit->user_string;
                    }
                }
                $total_people = count($people);
                
                $return_array['status'] = 'success';
                $return_array['data'] = array(
                        'business_string' => $business->verify_string,
                        'people' => $total_people,
                        'visits' => $total_visits
                    );
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'There are no Page Visits yet for this Business';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Business was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>