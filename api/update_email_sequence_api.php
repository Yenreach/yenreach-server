<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $data = json_decode($post_json);

        $id = !empty($data->id) ? (string)$data->id : "";
        if($id){
            $email = EmailList::find_by_id($id);
            if($email){
                $email->title = !empty($data->title) ? (string)$data->title : "";
                $email->content = !empty($data->content) ? (string)$data->content : "";
                $email->admin_string = !empty($data->admin_string) ? (string)$data->admin_string : "";
                
                if($email->admin_string){
                    $admin = Admins::find_by_verify_string($email->admin_string);
                    if(!empty($admin)){
                        if($email->insert("create")){
                            $return_array['status'] = 'success';
                            $return_array['data'] = array(
                                    'id' => $email->id,
                                    'title' => $email->title,
                                    'content' => $email->content,
                                    'created_at' => $email->created,
                                );
                        } else {
                            $return_array['status'] = 'failed';
                            $return_array['message'] = join(' ', $email->errors);
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
                $return_array['message'] = 'No Email was found';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No ID was provided';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>