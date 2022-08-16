<?php
    require_once('../../includes_yenreach/initialize.php');
    
    $return_array = array();
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $mail = new Mailings();
        
        $ticket_id = !empty($post->ticket_id) ? (string)$post->ticket_id : "";
        $movement = !empty($post->movement) ? (string)$post->movement : "";
        $from_name = !empty($post->from_name) ? (string)$post->from_name : "";
        $from_mail = !empty($post->from_mail) ? (string)$post->from_mail : "";
        $recipient_name = !empty($post->recipient_name) ? (string)$post->recipient_name : "";
        $recipient_mail = !empty($post->recipient_mail) ? (string)$post->recipient_mail : "";
        $recipient_cc_name = !empty($post->recipient_cc_name) ? (string)$post->recipient_cc_name : "";
        $recipient_cc = !empty($post->recipient_cc) ? (string)$post->recipient_cc : "";
        $recpient_bcc_name = !empty($post->recipient_bcc_name) ? (string)$post->recipient_bcc_name : "";
        $recipient_bcc = !empty($post->recipient_bcc) ? (string)$post->recipient_bcc : "";
        $subject = !empty($post->subject) ? (string)$post->subject : "";
        $reply_name = !empty($post->reply_name) ? (string)$post->reply_name : "";
        $reply_mail = !empty($post->reply_mail) ? (string)$post->reply_mail : "";
        
        $body = !empty($post->content) ? (string)$post->content : "";
        $alt_content = $body;
        
        $content =    '<div bgcolor="#E3E3E3" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">';
        $content .=       '<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">';
        $content .=           '<tbody>';
        $content .=               '<tr>';
        $content .=                   '<td width="100%" valign="top" bgcolor="#E3E3E3" align="center">';
        $content .=                       '<table align="center">';
        $content .=                           '<tbody>';
        $content .=                               '<tr>';
        $content .=                                   '<td bgcolor="#FFFFFF">';
        $content .=                                       '<div class="mktEditable" id="DNT-Header_Logos" style="border-bottom: 3px solid #0a1449; border-top: 5px solid #41bf41">';
        $content .=                                           '<table width="100%" style="min-width: 100%; width: 100%;" border="0" cellspacing="0" cellpadding="0">';
        $content .=                                               '<tbody>';
        $content .=                                                   '<tr>';
        $content .=                                                       '<td>';
        $content .=                                                           '<table width="100%" align="center" style="min-width: 100%;" bgcolor="#ffffff" cellspacing="0" cellpadding="0">';
        $content .=                                                               '<tbody>';
        $content .=                                                                   '<tr>';
        $content .=                                                                       '<td align="left" class="mob_padding" style="padding: 24px 24px 24px 24px; text-align: left">';
        $content .=                                                                           '<a href="https://yenreach.com">';
        $content .=                                                                               '<img src="https://yenreach.com/assets/img/logo.png" width="auto" height="80" style="display: inline-block; margin-left:250px;" alt="Yenreach" border="0;" >';
        $content .=                                                                           '</a>';
        $content .=                                                                       '</td>';
        $content .=                                                                       '<td align="right" class="mob_padding" style="padding: 24px 24px 24px 24px; color: #0071BC; font-size: 25px; line-height: 30px; font-family: \'Segoe UI\',SUWR,Arial,Sans-Serif; font-weight: 400;">';
        $content .=                                                                           '<a style="text-decoration: none;" href="https://yenreach.com" target="_blank">';
        // $content .=                                                                               '<strong style="font-weight: 400; color: #0071BC;">YENREACH.COM</strong>';
        $content .=                                                                           '</a>';
        $content .=                                                                       '</td>';
        $content .=                                                                   '</tr>';
        $content .=                                                               '</tbody>';
        $content .=                                                           '</table>';
        $content .=                                                       '</td>';
        $content .=                                                   '</tr>';
        $content .=                                               '</tbody>';
        $content .=                                           '</table>';
        $content .=                                       '</div>';
        $content .=                                       '<div id="B-zone-Body">';
        $content .=                                           '<div class="mktEditable" id="B_zone-Body">';
        $content .=                                               '<table align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" class="mob_padding mob_full_width" style="background-color: #ffffff; max-width: 640">';
        $content .=                                                   '<tbody>';
        $content .=                                                       '<tr>';
        $content .=                                                           '<td>';
        $content .=                                                               '<table width="100%" align="left" style="width: 100%; min-width: 100%;" border="0" cellspacing="0" cellpadding="0">';
        $content .=                                                                       '<tbody>';
        $content .=                                                                           '<tr>';
        $content .=                                                                               '<td align="left" class="mob_padding" style="color: #464646; font-size: 15px; font-family: \'Segoe UI\',\'Segoe UI Regular\',SUWR,Arial,Sans-Serif; font-weight: 400; line-height: 20px; padding-left: 24px; padding-right: 24px; text-align: left;">';
        $content .=                                                                                   '<p style="margin-top:30; margin-bottom:30;">This is the Test Body of the Email Template</p>';
        $content .=                                                                               '</td>';
        $content .=                                                                           '</tr>';
        $content .=                                                                       '</tbody>';
        $content .=                                                               '</table>';
        $content .=                                                           '</td>';
        $content .=                                                       '</tr>';
        $content .=                                                   '</tbody>';
        $content .=                                               '</div>';
        $content .=                                           '</div>';
        $content .=                                           '<table bgcolor="#41bf41" width="640" style="width:640px; min-width: 100%" border="0" cellpadding="0" cellspacing="0" align="center" class="mob_padding0 mob_full_width">';
        $content .=                                               '<tbody>';
        $content .=                                                   '<tr>';
        $content .=                                                       '<td class="mob_padding0" style="padding:24px 24px 24px 24px;" bgcolor="#41bf41">';
        $content .=                                                           '<table width="100%" cellspacing="0" cellpadding="0" border="0" style="width:100%; min-width:100%; ">';
        $content .=                                                               '<tbody>';
        $content .=                                                                   '<tr>';
        $content .=                                                                       '<td class="mob_padding" align="left" style="font-family: \'Segoe UI\', SUWR, Arial, Sans-Serif; font-weight: 400; font-size:10px; line-height:12px; color:#FEFEFE;">';
        $content .=                                                                           '<div class="mktEditable rest_paragraph" id="DNT-Social">';
        $content .=                                                                               '<div height="0" style="font-size: 0px; line-height: 0px;">&nbsp;</div>';
        $content .=                                                                           '</div>';
        $content .=                                                                       '</td>';
        $content .=                                                                   '</tr>';
        $content .=                                                                   '<tr>';
        $content .=                                                                       '<td class="mob_padding" align="left" style="font-family: \'Segoe UI\', SUWR, Arial, Sans-Serif; font-weight: 400; font-size:10px; line-height:12px; color:#FEFEFE;">';
        $content .=                                                                           '<div class="mktEditable rest_paragraph" id="DNT-Footer">';
        $content .=                                                                               '<div align="left" class="rest_paragraph">';
        // $content .=                                                                                   '<p style="color: #FEFEFE;">';
        // $content .=                                                                                       '<a style="text-decoration: none;  border-bottom: 1px solid #FEFEFE; color: #FEFEFE;" href="https://yenreach.com/terms.php">';
        // $content .=                                                                                           '<strong style="font-weight: normal; color: #FEFEFE;">Terms and Conditions</strong>';
        // $content .=                                                                                       '</a> | ';
        // $content .=                                                                                       '<a style="text-decoration: none;  border-bottom: 1px solid #FEFEFE;" href="https://yenreach.com/policy.php">';
        // $content .=                                                                                           '<strong style="font-weight: normal; color: #FEFEFE;">Privacy Poiicy</strong>';
        // $content .=                                                                                       '</a>';
        // $content .=                                                                                   '</p>';
        $content .=                                                                                   '<p>&nbsp;</p>';
        $content .=                                                                                   '<p style="color: #FEFEFE; margin-bottom:30px; text-align:center;">#58, Azikoro Road (Authentic Plaza)<br />Opposite NUJ, Ekeki<br />Yenagoa, Bayelsa State</p>';
        $content .=                                                                                              '<p style="color: #FEFEFE;">';
        $content .=                                                                                       '<a style="text-decoration:none;"  border-bottom: 1px solid #FEFEFE; color: #FEFEFE;" href="https://yenreach.com/terms.php">';
        $content .=                                                                                           '<strong style="font-weight: normal; text-decoration:underline; margin-left:210px;  color: #FEFEFE;">Terms and Conditions</strong>';
        $content .=                                                                                       '</a> | ';
        $content .=                                                                                       '<a style="text-decoration:none;"  border-bottom: 1px solid #FEFEFE;" href="https://yenreach.com/policy.php">';
        $content .=                                                                                           '<strong style="font-weight: normal; text-decoration:underline;  color: #FEFEFE;">Privacy Poiicy</strong>';
        $content .=                                                                                       '</a>';
        $content .=                                                                                   '</p>'; 
        $content .=                                                                                        '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">'; 
        $content .=                                                                                       '<a>';
        $content .=                                                                                         '<a style="color:#fff; text-decoration: none; padding:15px; margin-left:250px; font-size:17px;"   class="fa fa-facebook">'; 
        $content .=                                                                                          '<a style="color:#fff; text-decoration: none; font-size:17px;" href="#" class="fa fa-instagram">'; 
        $content .=                                                                                           '<a style="color:#fff; text-decoration: none; font-size:17px; padding:15px;"  href="#"  class="fa fa-linkedIn">';  
        $content .=                                                                                       '<a/>';
        $content .=                                                                               '</div>';
        $content .=                                                                               '<br />';
        $content .=                                                                               '<div>';
        //$content .=                                                                                   '<p>';
        //$content .=                                                                                       '<a href="https://yenreach.com"><img src="https://yenreach.com/assets/img/logo.png" width="auto" height="50" align="left" alt="Yenreach" border="0"></a>';
        //$content .=                                                                                   '</p>';
        $content .=                                                                               '</div>';
        $content .=                                                                           '</div>';
        $content .=                                                                       '</td>';
        $content .=                                                                   '</tr>';
        $content .=                                                               '</tbody>';
        $content .=                                                           '</table>';
        $content .=                                                       '</td>';
        $content .=                                                   '</tr>';
        $content .=                                               '</tbody>';
        $content .=                                           '</table>';
        $content .=                                       '</div>';
        $content .=                                   '</td>';
        $content .=                               '</tr>';
        $content .=                           '</tbody>';
        $content .=                       '</table>';
        $content .=                   '</td>';
        $content .=               '</tr>';
        $content .=           '</tbody>';
        $content .=       '</table>';
        $content .=   '<div>';
		
		
        $attachments = !empty($post->attachments) ? $post->attachments : array();
        if($send_mail()){
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