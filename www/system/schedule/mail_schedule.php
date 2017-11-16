<?php

require_once(APP_ROOT_PATH.'system/libs/schedule.php');
class mail_schedule implements schedule {
	
	/**
	 * $data 格式
	 * array("dest"=>xxxxx,"content"=>xxxxxx,"is_html"=>0/1,"title"=>xxxxxx);
	 */
	public function exec($data){
		//邮件
		require_once APP_ROOT_PATH."system/utils/es_mail.php";
		$mail = new mail_sender();
		$mail->AddAddress($data['dest']);
		$mail->IsHTML($data['is_html']); 				  // 设置邮件格式为 HTML
		$mail->Subject = $data['title'];   // 标题
		$mail->Body = $data['content'];  // 内容
		
		$result['status'] = intval($mail->Send());
		$result['attemp'] = 0;
		$result['info'] = $mail->ErrorInfo;
		return $result;
	}	
}
?>