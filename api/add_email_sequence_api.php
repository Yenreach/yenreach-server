<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $data = json_decode($post_json);
        
        $emaillist = new EmailList();
        $time = time();
        $emaillist->title = !empty($data->title) ? (string)$data->title : "";
        $emaillist->content = !empty($data->content) ? (string)$data->content : "";
        $emaillist->admin_string = !empty($data->admin_string) ? (string)$data->admin_string : "";
        $emaillist->created_at = $time;
        $emaillist->updated_at = $time;

        if($emaillist->admin_string){
            $admin = Admins::find_by_verify_string($emaillist->admin_string);
            if(!empty($admin)){
                if($emaillist->insert("create")){
                    $return_array['status'] = 'success';
                    $return_array['data'] = array(
                            'id' => $emaillist->id,
                            'title' => $emaillist->title,
                            'content' => $emaillist->content,
                            'created_at' => $emaillist->created,
                        );
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = join(' ', $emaillist->errors);
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