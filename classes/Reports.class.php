<?php

error_reporting(E_ERROR);
ini_set('display_errors', 1);

class Reports{
    private static $courses;
    private static $current;
    private static $messages;

    static public function routing(){

        switch($_GET['param']){
            case "activation":
                self::getAllCourseName();
                self::submitActivation();
                self::displayActivation();
                return;
            case "calendar":
                self::getAllCourseName();
                self::saveSession();
                self::displayCalendar();
                return;
            case "talfigh":
                self::displayTalfigh();
                return;
            case "report":
                self::displayReport();
                return;
                // self::displayLast();
                // return;
            case "edit":
                Quiz::getAllQuestion();
                if(Quiz::$questions[0]['type'] == 5){
                    self::displayAllDescriptive();
                }
                else{
                    self::displayAllTesti();
                }
                return;
        }                        
    }

    static public function displayMenu(){
        self::view("views/modules/menu.php");
    }

    static public function displayActivation(){
        $courses = self::getOnlineCourses();
        $modal = self::displayModal();
        // print_r($courses);
        self::view("views/modules/activation.php", compact('courses', 'modal'));
    }

    static public function displayCalendar(){
        $sessions = self::getCourseSessions();
        $modal = self::displayModal();
        // print_r($courses);
        self::view("views/modules/courseSessions.php", compact('sessions', 'modal'));
    }

    static public function displayTalfigh(){
        $courses = self::getOnlineCourses();
        $modal = self::displayModal();
        // print_r($courses);
        self::view("views/modules/activation.php", compact('courses', 'modal'));
    }

    static public function displayReport(){
        self::getAllCourseName();
        $sessions = self::getCourseSessions();
        $sessions = self::getSessionsPresence($sessions);
        $modal = self::displayModal();
        // print_r($courses);
        self::view("views/modules/coursePresence.php", compact('sessions', 'modal'));
    }

    static public function displayModal(){
        $message = "";
        foreach(self::$messages as $m){

            $message .= "<div class='alert alert-$m[type]'>$m[message]</div>";
        }
        $title = "پیغام سیستم";
        // print_r($courses);
        if($message != "")
            $result = self::view("views/modules/modal.php", compact('message', 'title'), false);
        else
            $result = "";
        return $result;
    }

    static function getAllCourseName(){
        $query = "SELECT `title`, `code` 
                    FROM `course`";
        self::$courses = array();
        $result = api_sql_query($query, __FILE__, __LINE__);
        while($data = mysql_fetch_assoc($result)){
            self::$courses[$data['code']] = $data['title'];
        }
    }

    static public function getOnlineCourses(){
        $date = $_POST['date'];
        $time = $_POST['time'];
        $current = Tools::getCurrentSemester();

        // Get chat_sessions times
        $timeQuery = "";
        if($time != ""){
            $timeQuery = " AND cs_start = '$time'";
        }
        $query = "SELECT * FROM `chat_sessions`
                    WHERE 
                        `cs_date` = '$date' 
                        AND `cs_status` = 1 
                        $timeQuery
                    ORDER BY `cs_start`";
        $courses = array();
        $result = api_sql_query($query, __FILE__, __LINE__);
        while($data = mysql_fetch_assoc($result)){
            // Get Tools Visibility
            $query1 = "SELECT `visibility`
                    FROM `dokeos_".$data["cs_course_id"]."`.`tool`
                    WHERE 
                        `name` = 'chatVideo'";
            $result1 = api_sql_query($query1, __FILE__, __LINE__);
            $data1 = mysql_fetch_assoc($result1);
            $data['status'] = $data1['visibility'];
            $data['title'] = self::$courses[$data['cs_course_id']];
            $data['childs'] = array();
            $courses[$data['cs_course_id']] = $data;
        }

        $parents = array_keys($courses);
        $parents = implode(', ', $parents);
        if($parents=="") return;
        $query = "SELECT `parent_code`, `child_code`
                    FROM `chat_course_merge`
                    WHERE 
                        parent_code IN ($parents)
                        AND `year` = '$current[year]'
                        AND `semester` = '$current[semester]'
                    ORDER BY `child_code`";
        $result = api_sql_query($query, __FILE__, __LINE__);
        while($data = mysql_fetch_assoc($result)){
            $parent = Tools::getCorrectCode($data['parent_code']);
            $child = Tools::getCorrectCode($data['child_code']);
            $data['code'] = $child;
            $data['title'] = self::$courses[$child];
            $courses[$parent]['childs'][] = $data;
        }
        // print_r($courses);
        return $courses;
    }

    static function submitActivation(){
        $check = "";
        if(isset($_POST['active'])){
            $check = 1;
        }
        else if(isset($_POST['deactive'])){
            $check = 0;
        }

        if($check === "") return;
        // echo "check:".$check;
        $courseAll = $_POST['courses'];
        
        $childs = self::getMergedCourses($_POST['courses']);
        foreach($childs as $c){
            $courseAll[] = $c['code'];
        }

        // print_r($courseAll);
        self::$messages = array();
        foreach($courseAll as $course){
            $sql = "UPDATE `dokeos_$course`.`tool` 
                    SET visibility = '$check' 
                    WHERE `name` = 'chatVideo'";
            $result = api_sql_query($sql, __FILE__, __LINE__);
            $affected = mysql_affected_rows();
            if($affected > 0){
                $type = ($check==1)?'<span class="badge badge-success"> فعال </span>':'<span class="badge badge-danger"> غیرفعال </span>';
                self::$messages[] = array('message'=>"کلاس آنلاین تصویری درس «{$course}» {$type} گردید!!", 'type'=>'success');
            }
        }
    }


    static public function getCourseSessions($course = null, $year = null, $semester = null, $group = null){
        $course = $course?$course:$_POST['course'];
        $year = $year?$year:$_POST['year'];
        $semester = $semester?$semester:$_POST['semester'];
        $group = $group?$group:$_POST['group'];

        date_default_timezone_set('Asia/Tehran');
        $info = array(
            'sessions'=>array(), 
            'course'=>array(),
            'persian_date' => Tools::getCurrentDate(),
            'time' => date("H:i:s"),
            'semester'=> Tools::getCurrentSemester(),
            'parent'=>array());
        
        $info['course']['code'] = $course;
        $info['course']['title'] = self::$courses[$course];
        $code = Tools::getParent($course, $_POST['year'], $_POST['semester']);
        $code = Tools::getCorrectCode($code);
        $info['parent']['code'] = $code;
        $info['parent']['title'] = self::$courses[$code];

        if($group != "")
        $groupCondition = "AND `cs_group` = '$group'";

        $sql = "SELECT `chat_sessions`.* FROM `chat_sessions`
                    WHERE 
                        `cs_course_id` = '$code'
                        AND `cs_year` = '$year'
                        AND `cs_semester` = '$semester'
                        $groupCondition
                    ORDER BY `cs_date`, `cs_start`
                    ";
        $result = api_sql_query($sql, __FILE__, __LINE__);

        while($data = mysql_fetch_assoc($result)){
            $info['sessions'][] = $data;
        }

        return $info;
    }

    static public function getSessionsPresence($sessions){
        $course = $_POST['course'];
        $year = $_POST['year'];
        $semester = $_POST['semester'];
        $group = $_POST['group'];

        if($group != "")
            $groupCondition = "AND `group` = '$group'";
        
        $users = self::getCourseStudents($course, $year, $semester, $group);
        $sessions['stuNum'] = count($users);

        $userList = implode(', ', $users);
        $i = 0;
        foreach($sessions['sessions'] as $session){
            $hour_from = Tools::addHours($session['cs_start'], '00:15:00', "minus");
            $hour_to = Tools::addHours($session['cs_start'], '00:20:00');

            $sql = "SELECT count(DISTINCT(`user_id`)) as `number` FROM `chat_presence`
                        WHERE 
                            `course_id` = '$course'
                            AND `date_login` = '$session[cs_date]'
                            AND `hour_login` > '$hour_from' AND `hour_login` < '$hour_to'
                            AND `user_id` IN ($userList)
                            $groupCondition
                        ";
            // echo $sql."<br/>";
            $result = api_sql_query($sql, __FILE__, __LINE__);
            $data = mysql_fetch_assoc($result);
            $sessions['sessions'][$i]['count'] = $data['number'];
            $sessions['sessions'][$i]['count_persent'] = "% ".round($data['number']/count($users)*100, 1);
            $i++;
            // while($data = mysql_fetch_assoc($result)){
        }

        return $sessions;
    }

    static function getUserSessionPresence($course, $session, $user, $group=1){
        $hour_from = Tools::addHours($session['cs_start'], '00:15:00', "minus");
        $hour_to = Tools::addHours($session['cs_start'], '00:15:00');

        if($group != "")
            $groupCondition = "AND `group` = '$group'";

        $sql = "SELECT * FROM `chat_presence`
                    WHERE 
                        `course_id` = '$course'
                        AND `date_login` = '$session[cs_date]'
                        AND `hour_login` > '$hour_from' AND `hour_login` < '$hour_to'
                        AND `user_id` = '$user'
                        $groupCondition
                    ";
        // echo $sql."<br/>";
        $result = api_sql_query($sql, __FILE__, __LINE__);
        $data = mysql_fetch_assoc($result);

        if($data['user_id'] == ""){
            $present = "absent";
        }else{
            $present = "present";
        }

        // echo "present:".$present."<br/>";

        return $present;
    }

    static public function getCourseStudents($course, $year, $semester, $group, $count = false){
        if($group != "")
            $groupCondition = "AND `group` = '$group'";

        $field = "`user_id`";
        if($count) 
            $field = "count(`user_id`) as `count`";

        $sql = "SELECT $field FROM `course_rel_user_main`
                    WHERE 
                        `status` = 5
                        AND `course_code` = '$course'
                        AND `year` = '$year'
                        AND `semester` = '$semester'
                        $groupCondition
                    ";
        $result = api_sql_query($sql, __FILE__, __LINE__);

        if(!$count){
            $users = array();
            while($data = mysql_fetch_assoc($result)){
                $users[] = $data['user_id'];
            }
        }
        else{
            $data = mysql_fetch_assoc($result);
            $users = $data['count'];
        }

        return $users;
    }

    static private function saveSession(){
        if(!isset($_POST['newSession'])) return;

        $code = Tools::getParent($_POST['code'], $_POST['year'], $_POST['semester']);
        $code = Tools::getCorrectCode($code);
        $lessCode = Tools::getLessCode($code);
        $end = Tools::addHours($_POST['start'], $_POST['length']);
        $date = Tools::getEnNumbers($_POST['date']);
        // echo $end;
        // exit();

        $sql = "INSERT INTO `chat_sessions` 
                    (`cs_course_id`, `cs_year`, `cs_semester`, `cs_group`, `cs_date`, `cs_start`, `cs_length`, `cs_status`, `cs_end`, `lesCode`)
                    VALUES ('$code', '$_POST[year]', '$_POST[semester]', '$_POST[group]', '$date', '$_POST[start]', '$_POST[length]', '$_POST[status]', '$end', '$lessCode')
                    ";
        $result = api_sql_query($sql, __FILE__, __LINE__);

        return $code;
    }

    static private function deleteSession(){
        if(!isset($_POST['deleteSession'])) return;
        $sessions = $_POST['courses'];
        $sessions = implode(', ', $sessions);

        $sql = "DELETE `chat_sessions`
                    WHERE 
                        `cs_id` IN ($sessions)";

        $result = api_sql_query($sql, __FILE__, __LINE__);

        return $code;
    }

    

    static private function getMergedCourses($course){
        if(is_array($course)){
            $course = implode(", ", $course);
        }
        $current = Tools::getCurrentSemester();
        $query = "SELECT `parent_code`, `child_code`
                    FROM `chat_course_merge`
                    WHERE 
                        parent_code IN ($course)
                        AND `year` = '$current[year]'
                        AND `semester` = '$current[semester]'
                    ORDER BY `child_code`";
        $result = api_sql_query($query, __FILE__, __LINE__);
        $merged = array();
        while($data = mysql_fetch_assoc($result)){
            $parent = Tools::getCorrectCode($data['parent_code']);
            $child = Tools::getCorrectCode($data['child_code']);
            $data['code'] = $child;
            $data['title'] = self::$courses[$child];
            $merged[] = $data;
        }

        return $merged;
    }

}