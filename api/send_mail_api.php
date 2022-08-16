<?php
    require_once('../../includes_yenreach/initialize.php');
    
    $return_array = array();
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $mail = new Mailings();
        
        $mail->ticket_id = !empty($post->ticket_id) ? (string)$post->ticket_id : "";
        $mail->movement = !empty($post->movement) ? (string)$post->movement : "";
        $mail->from_name = !empty($post->from_name) ? (string)$post->from_name : "";
        $mail->from_mail = !empty($post->from_mail) ? (string)$post->from_mail : "";
        $mail->recipient_name = !empty($post->recipient_name) ? (string)$post->recipient_name : "";
        $mail->recipient_mail = !empty($post->recipient_mail) ? (string)$post->recipient_mail : "";
        $mail->recipient_cc_name = !empty($post->recipient_cc_name) ? (string)$post->recipient_cc_name : "";
        $mail->recipient_cc = !empty($post->recipient_cc) ? (string)$post->recipient_cc : "";
        $mail->recpient_bcc_name = !empty($post->recipient_bcc_name) ? (string)$post->recipient_bcc_name : "";
        $mail->recipient_bcc = !empty($post->recipient_bcc) ? (string)$post->recipient_bcc : "";
        $mail->subject = !empty($post->subject) ? (string)$post->subject : "";
        $mail->reply_name = !empty($post->reply_name) ? (string)$post->reply_name : "";
        $mail->reply_mail = !empty($post->reply_mail) ? (string)$post->reply_mail : "";
        
        $body = !empty($post->content) ? (string)$post->content : "";
        $mail->alt_content = $body;
        
        $mail->content =    '<div bgcolor="#E3E3E3" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">';
        $mail->content .=       '<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">';
        $mail->content .=           '<tbody>';
        $mail->content .=               '<tr>';
        $mail->content .=                   '<td width="100%" valign="top" bgcolor="#E3E3E3" align="center">';
        $mail->content .=                       '<table align="center">';
        $mail->content .=                           '<tbody>';
        $mail->content .=                               '<tr>';
        $mail->content .=                                   '<td bgcolor="#FFFFFF">';
        $mail->content .=                                       '<div class="mktEditable" id="DNT-Header_Logos" style="border-bottom: 3px solid #0a1449; border-top: 5px solid #41bf41">';
        $mail->content .=                                           '<table width="100%" style="min-width: 100%; width: 100%;" border="0" cellspacing="0" cellpadding="0">';
        $mail->content .=                                               '<tbody>';
        $mail->content .=                                                   '<tr>';
        $mail->content .=                                                       '<td>';
        $mail->content .=                                                           '<table width="100%" align="center" style="min-width: 100%;" bgcolor="#ffffff" cellspacing="0" cellpadding="0">';
        $mail->content .=                                                               '<tbody>';
        $mail->content .=                                                                   '<tr>';
        $mail->content .=                                                                       '<td align="left" class="mob_padding" style="padding: 24px 24px 24px 24px; text-align: center">';
        $mail->content .=                                                                           '<a href="https://yenreach.com" style="text-align: center">';
        $mail->content .=                                                                               '<img src="https://yenreach.com/assets/img/logo.png" width="auto" height="100" style="display: inline-block; margin: 0 auto;" alt="Yenreach" border="0;" >';
        $mail->content .=                                                                           '</a>';
        $mail->content .=                                                                       '</td>';
        $mail->content .=                                                                   '</tr>';
        $mail->content .=                                                               '</tbody>';
        $mail->content .=                                                           '</table>';
        $mail->content .=                                                       '</td>';
        $mail->content .=                                                   '</tr>';
        $mail->content .=                                               '</tbody>';
        $mail->content .=                                           '</table>';
        $mail->content .=                                       '</div>';
        $mail->content .=                                       '<div id="B-zone-Body">';
        $mail->content .=                                           '<div class="mktEditable" id="B_zone-Body">';
        $mail->content .=                                               '<table align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" class="mob_padding mob_full_width" style="background-color: #ffffff; max-width: 640">';
        $mail->content .=                                                   '<tbody>';
        $mail->content .=                                                       '<tr>';
        $mail->content .=                                                           '<td>';
        $mail->content .=                                                               '<table width="100%" align="left" style="width: 100%; min-width: 100%;" border="0" cellspacing="0" cellpadding="0">';
        $mail->content .=                                                                       '<tbody>';
        $mail->content .=                                                                           '<tr>';
        $mail->content .=                                                                               '<td align="left" class="mob_padding" style="color: #464646; font-size: 15px; font-family: \'Segoe UI\',\'Segoe UI Regular\',SUWR,Arial,Sans-Serif; font-weight: 400; line-height: 20px; padding: 24px; text-align: left;">';
        $mail->content .=                                                                                   '<p>'.$body.'</p>';
        $mail->content .=                                                                               '</td>';
        $mail->content .=                                                                           '</tr>';
        $mail->content .=                                                                       '</tbody>';
        $mail->content .=                                                               '</table>';
        $mail->content .=                                                           '</td>';
        $mail->content .=                                                       '</tr>';
        $mail->content .=                                                   '</tbody>';
        $mail->content .=                                               '</div>';
        $mail->content .=                                           '</div>';
        $mail->content .=                                           '<table bgcolor="#41bf41" width="640" style="width:640px; min-width: 100%" border="0" cellpadding="0" cellspacing="0" align="center" class="mob_padding0 mob_full_width">';
        $mail->content .=                                               '<tbody>';
        $mail->content .=                                                   '<tr>';
        $mail->content .=                                                       '<td class="mob_padding0" style="padding:24px 24px 24px 24px;" bgcolor="#41bf41">';
        $mail->content .=                                                           '<table width="100%" cellspacing="0" cellpadding="0" border="0" style="width:100%; min-width:100%; ">';
        $mail->content .=                                                               '<tbody>';
        $mail->content .=                                                                   '<tr>';
        $mail->content .=                                                                       '<td class="mob_padding" align="left" style="font-family: \'Segoe UI\', SUWR, Arial, Sans-Serif; font-weight: 400; font-size:10px; line-height:12px; color:#FEFEFE;">';
        $mail->content .=                                                                           '<div class="mktEditable rest_paragraph" id="DNT-Social">';
        $mail->content .=                                                                               '<div height="0" style="font-size: 0px; line-height: 0px;">&nbsp;</div>';
        $mail->content .=                                                                           '</div>';
        $mail->content .=                                                                       '</td>';
        $mail->content .=                                                                   '</tr>';
        $mail->content .=                                                                   '<tr>';
        $mail->content .=                                                                       '<td class="mob_padding" align="left" style="font-family: \'Segoe UI\', SUWR, Arial, Sans-Serif; font-weight: 400; font-size:10px; line-height:12px; color:#FEFEFE;">';
        $mail->content .=                                                                           '<div class="mktEditable rest_paragraph" id="DNT-Footer">';
        $mail->content .=                                                                               '<div align="left" class="rest_paragraph">';
        // $mail->content .=                                                                                   '<p style="color: #FEFEFE;">';
        // $mail->content .=                                                                                       '<a style="text-decoration: none;  border-bottom: 1px solid #FEFEFE; color: #FEFEFE;" href="https://yenreach.com/terms.php">';
        // $mail->content .=                                                                                           '<strong style="font-weight: normal; color: #FEFEFE;">Terms and Conditions</strong>';
        // $mail->content .=                                                                                       '</a> | ';
        // $mail->content .=                                                                                       '<a style="text-decoration: none;  border-bottom: 1px solid #FEFEFE;" href="https://yenreach.com/policy.php">';
        // $mail->content .=                                                                                           '<strong style="font-weight: normal; color: #FEFEFE;">Privacy Poiicy</strong>';
        // $mail->content .=                                                                                       '</a>';
        // $mail->content .=                                                                                   '</p>';
        $mail->content .=                                                                                   '<p style="color: #FEFEFE; margin-bottom:30px; text-align:center;">#58, Azikoro Road (Authentic Plaza)<br />Opposite NUJ, Ekeki<br />Yenagoa, Bayelsa State</p>';
        $mail->content .=                                                                                              '<p style="color: #FEFEFE; margin-bottom:30px; text-align:center;">';
        $mail->content .=                                                                                       '<a style="text-decoration:none;"  border-bottom: 1px solid #FEFEFE; color: #FEFEFE;" href="https://yenreach.com/terms.php">';
        $mail->content .=                                                                                           '<strong style="font-weight: normal; text-decoration:underline; color: #FEFEFE;">Terms and Conditions</strong>';
        $mail->content .=                                                                                       '</a> | ';
        $mail->content .=                                                                                       '<a style="text-decoration:none;"  border-bottom: 1px solid #FEFEFE;" href="https://yenreach.com/policy.php">';
        $mail->content .=                                                                                           '<strong style="font-weight: normal; text-decoration:underline;  color: #FEFEFE;">Privacy Poiicy</strong>';
        $mail->content .=                                                                                       '</a>';
        $mail->content .=                                                                                   '</p>';
        $mail->content .=                                                                                       '<p style="color: #FEFEFE; margin-bottom:30px; text-align:center;">';
        $mail->content .=                                                                                         '<a style="color:#fff; text-decoration: none; padding:15px; href="https://web.facebook.com/yenreachng"><img src="https://yenreach.com/assets/img/facebook.png" width="30px" height="auto"></a>'; 
        $mail->content .=                                                                                          '<a style="color:#fff; text-decoration: none; padding: 15px;" href="https://instagram.com/yenreach?utm_medium=copy_link"><img src="https://yenreach.com/assets/img/instagram.png" width="30px" height="auto"></a>'; 
        $mail->content .=                                                                                           '<a style="color:#fff; text-decoration: none; padding:15px;"  href="https://www.linkedin.com/company/yenreach"><img src="https://yenreach.com/assets/img/linkedin.png" width="30px" height="auto"></a>';  
        $mail->content .=                                                                                       '</p>';
        $mail->content .=                                                                               '</div>';
        $mail->content .=                                                                           '</div>';
        $mail->content .=                                                                       '</td>';
        $mail->content .=                                                                   '</tr>';
        $mail->content .=                                                               '</tbody>';
        $mail->content .=                                                           '</table>';
        $mail->content .=                                                       '</td>';
        $mail->content .=                                                   '</tr>';
        $mail->content .=                                               '</tbody>';
        $mail->content .=                                           '</table>';
        $mail->content .=                                       '</div>';
        $mail->content .=                                   '</td>';
        $mail->content .=                               '</tr>';
        $mail->content .=                           '</tbody>';
        $mail->content .=                       '</table>';
        $mail->content .=                   '</td>';
        $mail->content .=               '</tr>';
        $mail->content .=           '</tbody>';
        $mail->content .=       '</table>';
        $mail->content .=   '<div>';
		
		
        $mail->attachments = !empty($post->attachments) ? $post->attachments : array();
        if($mail->send_mail()){
            $return_array['status'] = 'success';
            $return_array['message'] = 'Mail Sent';
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'Sending Failed';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided!';
    }
     
    $result = json_encode($return_array);
    echo $result;
?>