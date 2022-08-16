<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $business = Businesses::find_by_verify_string($string);
        if(!empty($business)){
            $date_from = !empty($_GET['from']) ? (string)$_GET['from'] : "";
            $date_to = !empty($_GET['date_to']) ? (string)$_GET['date_to'] : "";
            
            $from_string = strtotime($date_from." 00:00:00");
            $to_string = strtotime($date_to." 23:59:59");
            
            $visits = PageVisits::find_business_limit($business->verify_string, $from_string, $to_string);
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
                $return_array['message'] = 'There was no Visit to this Business Page for this period';
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