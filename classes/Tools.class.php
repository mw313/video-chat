<?php

class Tools{
    public static $courses;
    public static $current;
    public static $messages;

    static function getParentCode($course)
    {
        $parentCode = "";
        $query = "SELECT `parent_code` FROM chat_course_merge
                    JOIN `current_semester` ON
                    chat_course_merge.`year` = `current_semester`.`year` AND chat_course_merge.`semester` = `current_semester`.`semester`
                WHERE child_code = '$course' and active = '1'";    
        
        $result = api_sql_query($query, __FILE__, __LINE__);
        $data   = mysql_fetch_assoc($result);

        if($data['parent_code'] == "")
        {
            $parentCode = $course;
        }
        else
        {
            $parentCode = $data['parent_code'];
        }

        if(strlen($parentCode) == 1) $parentCode = '00'.$parentCode;
        if(strlen($parentCode) == 2) $parentCode = '0'.$parentCode;
        // echo "parent".$parentCode;
        return $parentCode;
    }

    static function getCourseInfo($code){
        $query = "SELECT * FROM course  
                  WHERE code = '$code'";
        
        $result = api_sql_query($query, __FILE__, __LINE__);
        $data   = mysql_fetch_assoc($result);

        return $data;
    }

    static function getUserInfo($user_Id){
        $query = "SELECT * FROM `user`  
                  WHERE `user_id` = '$user_Id'";
        
        $result = api_sql_query($query, __FILE__, __LINE__);
        $data   = mysql_fetch_assoc($result);

        return $data;
    }

    static function login_user($user_id)
    {	
        //init ---------------------------------------------------------------------
        global $uidReset, $loginFailed, $_configuration;

        $main_user_table     = Database :: get_main_table(TABLE_MAIN_USER);
        $main_admin_table    = Database :: get_main_table(TABLE_MAIN_ADMIN);
        $track_e_login_table = Database :: get_statistic_table(TABLE_STATISTIC_TRACK_E_LOGIN);

        //logic --------------------------------------------------------------------
        if (!isset ($user_id))
        {
            $uidReset = true;
            return;
        }

        $sql_query = "SELECT * FROM $main_user_table WHERE user_id='$user_id'";
        $sql_result = api_sql_query($sql_query, __FILE__, __LINE__);
        $result = Database :: fetch_array($sql_result);

        $firstname = $result["firstname"];
        $lastname = $result["lastname"];
        $user_id = $result["user_id"];

        $message = sprintf(get_lang('AttemptingToLoginAs'),$firstname,$lastname,$user_id);

        $loginFailed = false;
        $uidReset = false;

        if ($user_id) // a uid is given (log in succeeded)
        {
            if ($_configuration['tracking_enabled'])
            {
                $sql_query = "SELECT user.*, a.user_id is_admin,
                    UNIX_TIMESTAMP(login.login_date) login_date
                    FROM $main_user_table
                    LEFT JOIN $main_admin_table a
                    ON user.user_id = a.user_id
                    LEFT JOIN $track_e_login_table login
                    ON user.user_id = login.login_user_id
                    WHERE user.user_id = '".$user_id."'
                    ORDER BY login.login_date DESC LIMIT 1";
            }
            else
            {
                $sql_query = "SELECT user.*, a.user_id is_admin
                    FROM $main_user_table
                    LEFT JOIN $main_admin_table a
                    ON user.user_id = a.user_id
                    WHERE user.user_id = '".$user_id."'";
            }

            $sql_result = api_sql_query($sql_query, __FILE__, __LINE__);

            if (Database::num_rows($sql_result) > 0)
            {
                $user_data = Database::fetch_array($sql_result);

                unset($_SESSION['_user']);
                unset($_SESSION['is_platformAdmin']);
                unset($_SESSION['is_allowedCreateCourse']);
                unset($_SESSION['_uid']);


                $_user['firstName'] 	= $user_data['firstname'];
                $_user['lastName'] 		= $user_data['lastname'];
                $_user['mail'] 			= $user_data['email'];
                $_user['lastLogin'] 	= $user_data['login_date'];
                $_user['official_code'] = $user_data['official_code'];
                $_user['picture_uri'] 	= $user_data['picture_uri'];
                $_user['user_id']		= $user_data['user_id'];

                $is_platformAdmin = (bool) (!is_null($user_data['is_admin']));
                $is_allowedCreateCourse = (bool) ($user_data['status'] == 1);

                // Filling session variables with new data
                $_SESSION['_uid'] = $user_id;
                $_SESSION['_user'] = $_user;
                $_SESSION['is_platformAdmin'] = $is_platformAdmin;
                $_SESSION['is_allowedCreateCourse'] = $is_allowedCreateCourse;
                $_SESSION['login_as'] = true; // will be usefull later to know if the user is actually an admin or not (example reporting)s
                
            }
            else
            {
                exit ("<br/>WARNING UNDEFINED UID !! ");
            }
        }
    }

    static function correctDate($date)
    {
        if(strlen($date) == 0 || strlen($date) == 10)
        return $date;

        $temp = explode("/",$date);
        if(strlen($temp[1]) < 2) $temp[1] = "0".$temp[1];
        if(strlen($temp[2]) < 2) $temp[2] = "0".$temp[2];
        $date = implode("/",$temp);
        return $date;
    }

    static function getCorrectCode($code){
        if(strlen($code) == 1) $code = "00".$code;
        if(strlen($code) == 2) $code = "0".$code;

        return $code;
    }

    static function getLessCode($code){
        $code = Tools::getCorrectCode($code);
        if(strlen($code) == 3) $code = "88".$code;

        return $code;
    }

    static function getCurrentDate(){
        $date = new Date();
        $pdate = $date->getPersianDate();
        $pdateInf = explode(" / ", $pdate);
        if(strlen($pdateInf[1]) == 1) $pdateInf[1] = "0".$pdateInf[1];
        if(strlen($pdateInf[2]) == 1) $pdateInf[2] = "0".$pdateInf[2];
        $pdate = implode("/", $pdateInf);
        // print_r($pdateInf);

        return $pdate;
    }

    static function addHours($hour_one, $hour_two, $type = "add"){
        $parts = explode(':', $hour_one);
        $seconds1 = ($parts[0] * 60 * 60) + ($parts[1] * 60) + $parts[2];

        $parts = explode(':', $hour_two);
        $seconds2 = ($parts[0] * 60 * 60) + ($parts[1] * 60) + $parts[2];

        if($type == "add")
            $sum = $seconds1 + $seconds2;
        else
            $sum = $seconds1 - $seconds2;

        $new_time = gmdate('H:i:s', $sum);
        return $new_time;
    }

    static function getCurrentSemester(){
        if(self::$current != "")
            return self::$current;
        $sql = "SELECT * FROM `dokeos_main`.`current_semester`";
        $result = api_sql_query($sql, __FILE__, __LINE__);
        $semester = Database::fetch_array($result);
        self::$current = $semester;

        return $semester;
    }

    static function getParent($course, $year = null, $semester = null){
        
        // $current = Tools::getCurrentSemester();
        $query = "SELECT `parent_code`
                    FROM `chat_course_merge`
                    WHERE 
                        `child_code` = '$course'
                        AND `year` = '$year'
                        AND `semester` = '$semester'
                    ORDER BY `child_code`";
        $result = api_sql_query($query, __FILE__, __LINE__);
        $data = mysql_fetch_assoc($result);

        $parent = $data['parent_code'];
        if($parent == "") $parent = $course;

        return $parent;
    }

    static function getEnNumbers($string){
        $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $arabic = array('٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١','٠');

        $num = range(0, 9);
        $convertedPersianNums = str_replace($persian, $num, $string);
        $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

        return $englishNumbersOnly;
    }

    static function getFaNumbers($string){
        $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $arabic = array('٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١','٠');

        $num = range(0, 9);
        $convertToPersianNums = str_replace($num, $persian, $string);

        return $convertToPersianNums;
    }
}