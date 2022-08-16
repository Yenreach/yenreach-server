<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $cookie = new UserCookies();
    if($cookie->create_cookie()){
        $return_array['status'] = 'success';
        $return_array['data'] = array(
                'id' => $cookie->id,
                'cookie' => $cookie->cookie,
                'created' => $cookie->created
            );
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'Cooke was not created';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>