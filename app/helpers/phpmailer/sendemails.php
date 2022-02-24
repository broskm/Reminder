<?php

require_once '/var/www/html/reminder/app/config/config.php';
require_once '/var/www/html/reminder/app/libraries/Database.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';



class sendEmail
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->sendEmail();
    }

    function allReminders()
    {
        $this->db->query("SELECT * FROM reminders");
        //Execute function
        return $this->db->resultSetAssoc();
    }
    public function findUserByID($user_id)
    {
        $this->db->query("SELECT * FROM users where user_id=:user_id");
        $this->db->bind(":user_id", $user_id);
        $result = $this->db->single();
        return $result;
    }
    
    function sendEmail()
    {   
        function isToremindToday($remindBefor,$date){
            
            $date = strtotime($date);
            if($remindBefor=="One day"){
                $date = strtotime("-1 day", $date);
            }
            if($remindBefor=="Two days"){
                $date = strtotime("-2 day", $date);
            }
            if($remindBefor=="Four days"){
                $date = strtotime("-4 day", $date);
            }
            if($remindBefor=="One week"){
                $date = strtotime("-7 day", $date);
            }
            if($remindBefor=="Two weeks"){
                $date = strtotime("-14 day", $date);
            }
            $date = date('Y-m-d', $date);
            if ($date == date('Y-m-d')){
                return true;
            }
            return false;
        }
        
        $thisYear= date("Y");
        $nextYear = date('Y', strtotime('+1 year'));
        $reminders = $this->allReminders();
        foreach($reminders as $reminder){
            if (isToremindToday($reminder["remind_before"],$thisYear."-".$reminder["date"])||
            isToremindToday($reminder["remind_before"],$nextYear."-".$reminder["date"])){
                
                $user = $this->findUserByID($reminder['user_id']);

                $mail = new PHPMailer();
                
                $output = '';
                $mail->IsSMTP();
                $mail->SMTPDebug  = 0;  
                $mail->SMTPAuth   = TRUE;
                $mail->SMTPSecure = "tls";
                $mail->Port       = 587;
                $mail->Host       = "smtp.gmail.com";
                $mail->Username   = "reminderwebapp.team@gmail.com";
                $mail->Password   = "";

                $mail->IsHTML(true);
                $mail->AddAddress($user["email"], $user["username"]);
                $mail->SetFrom("reminderwebapp.team@gmail.com", "Reminder app Team");
                $mail->AddReplyTo("reminderwebapp.team@gmail.com", "no reply");/* 
                $mail->AddCC("broskjako@gmail.com", "cc-recipient-name"); */
                $mail->Subject = "Reminder for ".$reminder["title"];
                $content = "<b>Dear Mr./Ms ".ucfirst($user["username"]).",<br><br> We would like to remind you: in ".$reminder["remind_before"]." on the ".$reminder["date"]." you have ".$reminder["title"].".<br><br> Best regards <br>REMAINDER App Team.</b>";

                $mail->MsgHTML($content); 
                if(!$mail->Send()) {
                echo "Error while sending Email.";
                var_dump($mail);
                } else {
                echo "Email sent successfully";
                }           
            }
        }
}

}

$init = new sendEmail();