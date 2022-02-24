<?php
class Reminders extends Controller
{

    public function __construct()
    {
        $this->userModel = $this->model('Reminder');
    }

    public function index()
    {
        if (isset($_SESSION["user_id"])) {
            $data = $this->userModel->findUserReminders($_SESSION["user_id"]);

            $this->view('Reminders/index', $data);
        } else {
            //Redirect to the login page
            header('location: ' . URLROOT . '/Users/login');
        }
    }
    public function list()
    {
        if (isset($_GET["user_id"])) {
            $data = $this->userModel->findUserReminders($_GET["user_id"]);
            echo(json_encode($data));/* 
            $this->view('reminders/list', $data); */
        } else {
            //Redirect to the login page
            echo(json_encode("Somthing went wrong"));
        }
    }

    public function create()
    {

        $data = [
            'user_id' => '',
            'title' => '',
            'date' => '',
            'remind_before' => '',
            'creation_date' => '',
            'Error' => [],
            'successful'=>false
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // Process form
            // Sanitize GET data
            $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);

            $data = [
                'user_id' => trim($_GET['user_id']),
                'title' => trim($_GET['title']),
                'date' => trim($_GET['date']),
                'remind_before' => trim($_GET['remind_before']),
                'creation_date' => date("Y-m-d"),
                'Error' => [],
                'successful'=>false

            ];


            if (empty($data['user_id'])) {
                $data['Error'][] = 'User not found.';
            }else{
                $isuser = $this->userModel->findUserByID($data['user_id']);
                if(empty($isuser)){
                    $data['Error'][] = "User Doesn't exsist!";
                }
            }

            if (empty($data['title'])) {
                $data['Error'][] = 'Please enter a title.';
            }


            if (empty($data['date'])) {
                $data['Error'][] = 'Please enter the date.';
            }elseif (!strtotime("1989-".$data['date'])) {
                $data['Error'][] = 'Please enter a valid Date.';
            }

            if (empty($data['remind_before'])) {
                $data['Error'][] = 'Please enter a remind before.';
            }

            // Make sure that errors are empty
            if (empty($data['Error'])) {
                if (!$this->userModel->create($data)) {
                    $data['Error'][] = "Somthing went wrong!";
                }else{
                    $data['successful'] = true; 
                }
            }
        }
        echo(json_encode($data));
        /* $this->view('reminders/create', $data); */
    }
    

    public function update()
    {
        $data = [
            'user_id' =>'',
            'reminder_id' => '',
            'title' => '',
            'date' => '',
            'remind_before' => '',
            'Error' => [],
            'successful'=>false
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // Process form
            // Sanitize GET data
            $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);

            $data = [
                
                'user_id' => trim($_GET['user_id']),
                'reminder_id' => trim($_GET['reminder_id']),
                'title' => trim($_GET['title']),
                'date' => trim($_GET['date']),
                'remind_before' => trim($_GET['remind_before']),
                'Error' => []
            ];

            if(empty($data['user_id'])){
                $data['Error'][] = "Somthing went wrong!";
            }else{
                $isuser = $this->userModel->findUserByID($data['user_id']);
                if(empty($isuser)){
                    $data['Error'][] = "User Doesn't exsist!";
                }
            }
            if (empty($data['reminder_id'])) {
                $data['Error'][] = "Somthing went wrong!";
            }

            if (empty($data['title'])) {
                $data['Error'][] = 'Please enter a title.';
            }

            if (empty($data['date'])) {
                $data['Error'][] = 'Please enter a date.';
            }


            if (empty($data['remind_before'])) {
                $data['Error'][] = 'Please enter a remind before.';
            }

            if (empty($data['Error'])) {
                if (!$this->userModel->update($data)) {
                    $data['Error'][] = "Somthing went wrong!";
                }else{
                    $data['successful']= true;
                }
            }
        }
        echo(json_encode($data));
    }



    public function delete()
    {
        $data = [
            'user_id' => '',
            'reminder_id' => '',
            'Error' => [],
            'successful'=>false
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // Sanitize GET data
            $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
            if(!empty($_GET['user_id']) && !empty($_GET['reminder_id'])){
            $data = [
                'user_id' => trim($_GET['user_id']),
                'reminder_id' => trim($_GET['reminder_id']),
                'Error' => [],
                'successful'=>false
            ];
            $isuser = $this->userModel->findUserByID($data['user_id']);}
            if (!empty($data['user_id']) && !empty($isuser) && $this->userModel->delete($data)){
                $data['successful']=true;
            }
        }
        echo(json_encode($data));
    }

    public function sendEmail()
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
        function email(){

        }
        $thisYear= date("Y");
        $nextYear = date('Y', strtotime('+1 year'));
        $reminders = $this->userModel->allReminders();
        foreach($reminders as $reminder){
            echo"yello";
            if (isToremindToday($reminder["remind_before"],$thisYear."-".$reminder["date"])||
            isToremindToday($reminder["remind_before"],$nextYear."-".$reminder["date"])){
                $user = $this->userModel->findUserByID($reminder['user_id']);
                var_dump($user);
                var_dump($reminder);
                $to      = $user["email"];
                $subject = 'Reminder for'.$reminder["title"];
                $message = 'Dear Mr./Ms'.ucfirst($user["username"]).',\r\n We would like to remind you that in '.$reminder["remind_before"].'
                 you have a '.$reminder["title"].'at'.$reminder["date"].'\r\n Best regards \r\n REMAINDER App Team.';
                $headers = 'From: webmaster@example.com' . "\r\n" .
                    'Reply-To: NO Reply' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

                mail($to, $subject, $message, $headers);

            }
        }



        
    }
}
