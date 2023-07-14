<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $data = json_decode($post_json);
        
        $terms = new Terms();
        $time = time();
        $terms->content = !empty($data->content) ? (string)$data->content : "";
        $terms->admin_string = !empty($data->admin_string) ? (string)$data->admin_string : "";
        $terms->created_at = $time;
        $terms->updated_at = $time;

        if($terms->admin_string){
            $admin = Admins::find_by_verify_string($terms->admin_string);
            if(!empty($admin)){
                if($terms->insert("create")){
                    $return_array['status'] = 'success';
                    $return_array['data'] = array(
                            'id' => $terms->id,
                            'content' => $terms->content,
                            'created_at' => $terms->created_at,
                            'updated_at' => $terms->updated_at,
                        );
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = join(' ', $terms->errors);
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
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>