<?php

class VideoChat{
    static private $openClass = false;
    static function savePresence()
    {
        if(!self::$openClass) return true;

        $user_id = $_SESSION['_user']['user_id'];
        $code    = $_GET['cidReq'];

        // echo $_SESSION['chat-login-'.$code];
        // echo 'chat-login-'.$code;
        // if($_SESSION['chat-login-'.$code] == "")
        {
            $date = new Date();
            $date = str_replace(" / ", "/", $date->getPersianDate());
            $date = Tools::correctDate($date);
            $time = date("H:i:s");

            $_SESSION['chat-login-'.$code] = $date."#".$time;
            $query = "INSERT INTO `chat_presence` (`user_id`,`course_id`,`date_login`,`hour_login`)
                      VALUES('$user_id', '$code', '$date' , '$time')";
        
            $result = api_sql_query($query, __FILE__, __LINE__);            
        }
    }

    static function checkLogin(){
        if($_SESSION['_user'] == ""){
            Display::display_header();
            echo "<link rel='stylesheet' href='./assets/style.css' />";
        
            echo "<div class='alert alert-danger w-50'>
                    شما دسترسی به این صفحه ندارید، لطفا مجدد وارد سامانه شوید!! <br/>
                    <a class='re-login' href='http://ikvu.ir/Sdaneshjoo'>ورود مجدد</a>
                  </div>";
            Display::display_footer();
            exit();
        }
    }

    static function checkClassStarted(){
        $course = $_GET['cidReq'];
        $user   = $_SESSION['_user']['user_id'];
        $userCourse = self::getRegisterInfo($code, $user);
        // print_r($userCourse);
        $code     = Tools::getParentCode($course);
        $sessions = Reports::getCourseSessions($code, $userCourse['year'], $userCourse['semester'], $userCourse['group']);

        $i = 0;
        foreach($sessions['sessions'] as $session){
            $sessions['sessions'][$i]['presense'] = Reports::getUserSessionPresence($course, $session, $user, $session['cs_group']);
            $i++;
        }

        $message = "";

        if(count($sessions['sessions']) == 0){
            $message = 'در این درس جلسه ای ثبت نشده است!!';
        }else{
            $i = 0;
            $todayNext = false;
            foreach($sessions['sessions'] as $session){
                // $sessions['sessions'][$i]['presense'] = Reports::getUserSessionPresence($course, $session, $user, $session['cs_group']);
                $i++;

                // echo $session['cs_end']."-".$sessions['time'];
                // echo $session['cs_end']."-".$sessions['time'];
                // echo $session['cs_date']."-".$sessions['persian_date']."<br/>";
                if(Tools::getEnNumbers($session['cs_date']) < $sessions['persian_date']){
                    continue;
                }
                else if(Tools::getEnNumbers($session['cs_date']) == $sessions['persian_date'] && $todayNext == false){
                    if(Tools::addHours($session['cs_start'], BEFORE_ALLOWED_TIME, 'minus') > $sessions['time'])
                    {
                        $message = Tools::getFaNumbers("ساعت برگزاری جلسه امروز: <b>$session[cs_start]</b>");
                        break;
                    }
                    else if(Tools::getEnNumbers($session['cs_end']) < $sessions['time']){
                        $todayNext = true;
                    }else{
                        self::$openClass = true;
                        break;
                    }
                }
                else{
                    // echo 222;
                    $message = Tools::getFaNumbers("تاریخ جلسه بعد: <b>$session[cs_date]</b> ساعت <b>$session[cs_start]</b>");
                    break;
                }
            }

            if($message != "")
            $message = '<div class="alert alert-success" style="text-align: center; padding: 20px; width: 700px; margin: 10px auto;"> 
                            '.$message.'  
                        </div>';
        }

        if(self::$openClass) return;

        Display::display_header();
        // DisplayTools::view("views/sessionList.php", compact('sessions'));
        DisplayTools::view("views/session.php", compact('sessions', 'message'));
        Display::display_footer();
    }

    static function handleSSL(){
        if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
            $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $location);
            exit;
        }
    }

    static function getRegisterInfo($code, $user){
        $current = Tools::getCurrentSemester();
        if($_SESSION['_user']['status'] == 5)
            $query = "SELECT * FROM `course_rel_user_main`
                    WHERE `course_code` = '$code'
                    AND `user_id` = '$user'
                    AND `year` = '$current[year]'
                    AND `semester` = '$current[semester]'";
        else{
            $query = "SELECT * FROM `course_rel_user_main`
                    WHERE `course_code` = '$code'
                    AND `user_id` = '$user' ";
        }
        $result = api_sql_query($query, __FILE__, __LINE__);
        $data = mysql_fetch_assoc($result);

        // For users with status 1
        if($data['year'] == 0) {
            $data['year'] = $current['year'];
            $data['semester'] = $current['semester'];
        }

        return $data;
    }

    static function displayVideoChat(){
        if(!self::$openClass) return true;

        $userData    = Tools::getUserInfo($_SESSION['_user']['user_id']);
        $code        = Tools::getParentCode($_GET['cidReq']);
        $course_info = Tools::getCourseInfo($code);

        // $room = $code." ".$course_info['name'];
        $room = $code;
        $name = $_SESSION['_user']['firstName']." ".$_SESSION['_user']['lastName'];

        switch($userData['status']){
            case "1":
            case "3":
                include('./views/teacher.php');        
                break;
            default:
                include('./views/student.php');
        }
    }

}