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
                $dates_array = array();
                
                foreach($visits as $visit){
                    $day = $visit->year.'-'.$visit->month.'-'.$visit->day;
                    
                    if(!in_array($day, $dates_array)){
                        $dates_array[] = $day;
                    }
                }
                if(!empty($dates_array)){
                    $data_array = array();
                    
                    foreach($dates_array as $dates){
                        $date_array = explode("-", $dates);
                        
                        $year = $date_array[0];
                        $month = $date_array[1];
                        $day = $date_array[2];
                        
                        $frequency = 0;
                        $people = 0;
                        $daily_visits = PageVisits::find_business_day_visit($business->verify_string, $day, $month, $year);
                        if(!empty($daily_visits)){
                            foreach($daily_visits as $daily_visit){
                                $frequency += $daily_visit->frequency;
                            }
                            $people = count($daily_visits);
                        } 
                        $data_array[] = [
                                'year' => $year,
                                'month' => $month,
                                'day' => $day,
                                'people' => $people,
                                'visits' => $frequency
                            ];
                    }
                    $return_array['status'] = 'success';
                    $return_array['data'] = $data_array;
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No Visiting date was provided';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'This Business does not have any Visit';
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