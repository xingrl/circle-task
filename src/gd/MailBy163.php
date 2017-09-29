<?php
namespace xingrl\circle_task\gd;

use PHPMailer\PHPMailer\PHPMailer;

class MailBy163
{

    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer();
        $this->mail->IsSMTP(); // 启用SMTP
        $this->mail->Host = 'smtp.163.com'; //smtp服务器的名称（这里以QQ邮箱为例）
        $this->mail->SMTPAuth = true; //启用smtp认证
        $this->mail->Username = Config::get('mail')['username']; //发件人邮箱名
        $this->mail->Password = Config::get('mail')['password']; //163邮箱发件人授权密码
        $this->mail->From = Config::get('mail')['username']; //发件人地址（也就是你的邮箱地址）
        $this->mail->FromName = 'xingwg'; //发件人姓名
        $this->mail->WordWrap = 80; //设置每行字符长度
        $this->mail->IsHTML( true ); // 是否HTML格式邮件
        $this->mail->CharSet = 'utf-8'; //设置邮件编码
    }

    public function send($mailTo, $subject, $body)
    {
        $this->mail->addAddress($mailTo);
        $this->mail->Subject = $subject;
        if( is_array($body) ){
            $mailBody = var_export($body, true);

            $this->mail->Body = '<pre>'. $mailBody. '</pre>';
            $this->mail->AltBody = htmlentities($mailBody);
        }
        else {
            $this->mail->Body = $body;
            $this->mail->AltBody = htmlentities($body);
        }


        if( !$this->mail->Send() ){
            echo $this->mail->ErrorInfo, PHP_EOL;
        }
    }
}