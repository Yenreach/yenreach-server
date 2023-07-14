<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $data = json_decode($post_json);
        
        $privacypolicy = new PrivacyPolicy();
        $time = time();
        $privacypolicy->content = !empty($data->content) ? (string)$data->content : "";
        $privacypolicy->admin_string = !empty($data->admin_string) ? (string)$data->admin_string : "";
        $privacypolicy->created_at = $time;
        $privacypolicy->updated_at = $time;

        if($privacypolicy->admin_string){
            $admin = Admins::find_by_verify_string($privacypolicy->admin_string);
            if(!empty($admin)){
                if($privacypolicy->insert("create")){
                    $return_array['status'] = 'success';
                    $return_array['data'] = array(
                            'id' => $privacypolicy->id,
                            'content' => $privacypolicy->content,
                            'created_at' => $privacypolicy->created_at,
                            'updated_at' => $privacypolicy->updated_at,
                        );
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = join(' ', $privacypolicy->errors);
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