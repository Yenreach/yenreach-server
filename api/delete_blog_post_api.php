    <?php
        require_once("../../includes_yenreach/initialize.php");
        $return_array = array();
        
        $blog_string = !empty($_GET['blog_string']) ? (string)$_GET['blog_string'] : "";
        if(!empty($blog_string)){
            $blogpost = BlogPost::find_by_blog_string($blog_string); 
            if(!empty($blogpost)){
                $blogpost->admin_string = !empty($_GET['admin_string']) ? (string)$_GET['admin_string'] : "";
                if(!empty($blogpost->admin_string)){
                    $admin = Admins::find_by_verify_string($blogpost->admin_string);
                    if(!empty($admin)){
                        if($blogpost->delete()){
                            $return_array['status'] = 'success';
                            $return_array['data'] = array(
                                'blog_string' => $blogpost->blog_string,
                            );
                        } else {
                            $return_array['status'] = 'failed';
                            $return_array['message'] = join(' ', $blogpost->errors);
                        }
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = 'No Admin was found';
                    }
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Blog was fetched';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No means of Identification';
        }
        
        $result = json_encode($return_array);
        echo $result;
    ?>