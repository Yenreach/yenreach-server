<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $admin_string = !empty($_GET['admin_string']) ? (string)$_GET['admin_string'] : "";
    if($admin_string){
        $admin = Admins::find_by_verify_string($admin_string);
        
        if($admin && $admin->autho_level <= 2){
            $admin_to_del_string = !empty($_GET['admin_to_del_string']) ? (string)$_GET['admin_to_del_string'] : "";
                if(!empty($admin_to_del_string)){
                    $admin_to_delete = Admins::find_by_verify_string($admin_to_del_string);
                    if(!empty($admin_to_delete)){
                        if($admin_to_delete->delete()){
                            $return_array['status'] = 'success';
                            $return_array['message'] = "admin deleted successfully";
                        } else {
                            $return_array['status'] = 'failed';
                            $return_array['message'] = join(' ', $blogpost->errors);
                        }
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = 'No Admin was found';
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No means of Identification';
                }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'You are not authorized to perform this action';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Authentication';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>