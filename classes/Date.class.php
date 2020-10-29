<?php
class Date
{
   public static $year;
   public static $mounth;
   public static $mounth_name;
   public static $day;
   public static $week;
   public static $week_name;

   static function initialize()
   {
      self::$week=date("D");
	  self::$day="";
	  self::$mounth="";
	  self::$year="";
   }
//==============================================================================================

   static function getPersianDate($y="",$m="",$d="", $type=1)
   {
    if($y=="")
		 $y=date("Y");
		if($m=="")
		 $m=date("m");
		if($d=="")
		 $d=date("d");

		$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		$j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
		$gy = $y-1600;
		$gm = $m-1;
		$gd = $d-1;
		$g_day_no = 365*$gy+self::div($gy+3,4)-self::div($gy+99,100)+self::div($gy+399,400);
		for ($i=0; $i < $gm; ++$i)
		$g_day_no += $g_days_in_month[$i];
		if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0)))
		/* leap and after Feb */
		$g_day_no++;
		$g_day_no += $gd;
		$j_day_no = $g_day_no-79;
		$j_np = self::div($j_day_no, 12053); /* 12053 = 365*33 + 32/4 */
		$j_day_no = $j_day_no % 12053;
		self::$year= 979+33*$j_np+4*self::div($j_day_no,1461); /* 1461 = 365*4 + 4/4 */
		$j_day_no %= 1461;
		if ($j_day_no >= 366)
		{
			self::$year += self::div($j_day_no-1, 365);
			$j_day_no = ($j_day_no-1)%365;
		}
		for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i)
		$j_day_no -= $j_days_in_month[$i];
		self::$mounth = $i+1;
		self::$day = $j_day_no+1;
		self::getMounthName();
		self::getWeekName();

    if($type == 1)
    {
      return self::$year." / ".self::$mounth." / ".self::$day;
    }
    else {
      if(strlen(self::$mounth)<2) self::$mounth = "0".self::$mounth;
      if(strlen(self::$day)<2) self::$day = "0".self::$day;

      return self::$year."/".self::$mounth."/".self::$day;
    }
   }
//==============================================================================
   static public function Persian2Miladi($j_y, $j_m, $j_d)
   {
      $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
      $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
      $jy = $j_y-979;
      $jm = $j_m-1;
      $jd = $j_d-1;
      $j_day_no = 365*$jy + self::div($jy, 33)*8 + self::div($jy%33+3, 4);
      for ($i=0; $i < $jm; ++$i)
      $j_day_no += $j_days_in_month[$i];
      $j_day_no += $jd;
      $g_day_no = $j_day_no+79;
      $gy = 1600 + 400*self::div($g_day_no, 146097); /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */
      $g_day_no = $g_day_no % 146097;
      $leap = true;
      if ($g_day_no >= 36525) /* 36525 = 365*100 + 100/4 */
      {
      $g_day_no--;
      $gy += 100*self::div($g_day_no, 36524); /* 36524 = 365*100 + 100/4 - 100/100 */
      $g_day_no = $g_day_no % 36524;
      if ($g_day_no >= 365)
      $g_day_no++;
      else
      $leap = false;
      }
      $gy += 4*self::div($g_day_no, 1461); /* 1461 = 365*4 + 4/4 */
      $g_day_no %= 1461;
      if ($g_day_no >= 366) {
      $leap = false;
      $g_day_no--;
      $gy += self::div($g_day_no, 365);
      $g_day_no = $g_day_no % 365;
      }
      for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++)
      $g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);
      $gm = $i+1;
      $gd = $g_day_no+1;
      return array($gy, $gm, $gd);
   }

//================================================================================================
   static private function div($a,$b)
   {
	  return (int) ($a / $b);
   }
//===============================================================================================
   static function getMiladiDate($seperator = "-" , $with_time = false)
   {
      //self::$mounth=date("m");
	  //self::$year=date("Y");
	  //self::$day=date("d");
      $fdate = getdate();
      $y = $fdate['year'];
      $m = $fdate['mon'];
      $d = $fdate['mday'];

      if($with_time)
      {
        $h = $fdate['hours'];
        $mi = $fdate['minutes'];
        $s = $fdate['seconds'];
      }
      $date = $y.$seperator.$m.$seperator.$d." ".$h.":".$mi.":".$s;

      return $date;
   }
   //============================================================================================
   static public function Miladi2Persian($EnDate , $with_span = true)
    {
        # Divide to Date and Hour
        $d = explode(" " , $EnDate);
        //echo($d[0]."<br/>");
        list($y,$m,$da) = explode("-" , $d[0]);
        //list($h,$mi,$s) = explode(":" , $d[0]);
        //echo($y.$m.$d."<br/>");
        $pdate = self::getPersianDate($y,$m,$da);
        $pdate = str_replace(" / ","/" , $pdate);
        $pdate .= "&nbsp&nbsp&nbsp".$d[1];

        if($with_span)
        $pdate = "<span dir = 'ltr'>".$pdate."</span>";

        return $pdate;
    }

//==============================================================================================
   static function getMounthName()
   {
      switch(self::$mounth)
	  {
		    case "1":
		      self::$mounth_name="فروردین";
		      break;
		    case "2":
		      self::$mounth_name="اردیبهشت";
		      break;
		    case "3":
		      self::$mounth_name="خرداد";
		      break;
			case "4":
		     self::$mounth_name="تیر";
		     break;
			case "5":
		     self::$mounth_name="مرداد";
		     break;
			case "6":
		     self::$mounth_name="شهریور";
		     break;
			case "7":
		     self::$mounth_name="مهر";
		     break;
			case "8":
		     self::$mounth_name="آّبان";
		     break;
			case "9":
		     self::$mounth_name="آذر";
		     break;
			case "10":
		     self::$mounth_name="دی";
		     break;
			case "11":
		     self::$mounth_name="بهمن";
		     break;
		    case "12":
		     self::$mounth_name="اسفند";
		     break;
	  }
   }
//===============================================================================================
   static function getWeekName()
   {
      switch(self::$week)
		{
		   case "Sat":
		     self::$week_name="شنبه";
		     break;
		   case "Sun":
		     self::$week_name="یک شنبه";
		     break;
		   case "Mon":
		     self::$week_name="دو شنبه";
		     break;
		  case "Tue":
		     self::$week_name="سه شنبه";
		     break;
		  case "Wed":
		     self::$week_name="چهار شنبه";
		     break;
		  case "Thu":
		     self::$week_name="پنج شنبه";
		     break;
		  case "Fri":
		     self::$week_name="جمعه";
		     break;
		}
   }
//===============================================================================================
    static function getDate($date)
	{
	   $items_array=explode("/",$date);
	   $year=$items_array[0];
	   $mounth=$items_array[1];
	   $day=$items_array[2];

	   //echo($year."  ".$mounth."  ".$day);
	   if($year<100)
	   {
	     self::getPersianDate();
		 $y=self::$year;
		 if($y>=1400)
		   $year+=1400;
		 else
		   $year+=1300;
		 $items_array[0]=$year;
	   }

	   $date=implode("/",$items_array);
	   return $date;
	}
//================================================================================================
	static function showDate($date)
	{
	   $items_array=explode("-",$date);
	   $date=implode("/",$items_array);
	   return $date;
	}
//================================================================================================
	static function setIntDate($date)
	{
	   $items_array = explode("/",$date);
	   $year = $items_array[0];
	   $mounth = $items_array[1];
	   $day = $items_array[2];

	   //echo($year."  ".$mounth."  ".$day);
	   if($year<100)
	   {
		 self::getPersianDate();
		 $y = self::$year;
		 if($y >= 1400)
		   $year += 1400;
		 else
		   $year += 1300;
		 $items_array[0] = $year;
	   }
	   if($mounth < 10&& strlen($mounth)< 2)
	   {
		  $mounth='0'.$mounth;
	   }
	   if($day<10 && strlen($day)<2)
	   {
		  $day='0'.$day;
	   }

	   $items_array=array($year,$mounth,$day);
	   $date=implode("",$items_array);
	   return $date;

	}
//================================================================================================
     static function showIntDate($date)
	 {
	 	if(strlen($date)>4)
		{
		   $year=substr($date,0,4);
		   $mounth=substr($date,4,2);
		   $day=substr($date,6,2);
		   $mounth=$mounth/1;
		   $day=$day/1;
		   $items_array=array($year,$mounth,$day);
		   $date=implode("/",$items_array);
		   return $date;
	    }
		else
		{
		   return $date;
		}
	 }

    static function fa_numbers($digit)
    {
        $fa_numbers = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $fa_digit = "";
        //mod
    	/*while(true)
    	{
    		$fa_digit = $fa_numbers[fmod($digit , 10)].$fa_digit;
    		$digit = floor($digit / 10);
    		if($digit == 0)break;
    	}*/

        /*for($i=0 ; $i < strlen($digit) ; $i++)
        {
            if($fa_numbers[substr($digit , $i , 1)] != "")
            {
                $digit = str_replace();
            }
        }*/

        $fa_digit = $digit;
        for($i=0 ; $i < 10 ; $i++)
        {
            $fa_digit = str_replace($i , $fa_numbers[$i] , $fa_digit);
        }

    	return $fa_digit;
    }
}

?>
