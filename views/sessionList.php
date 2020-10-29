<?php
// print_r($sessions['sessions']);
?>
<link rel="stylesheet" href="/main/exercice/Quiz/views/assets/topmenu.css">
<link rel="stylesheet" href="/main/css/MDB/css/bootstrap.min.css">
<link rel="stylesheet" href="/main/css/MDB/font/sahel/style.css">
<link rel="stylesheet" href="/main/exercice/Quiz/views/assets/style.css">
<link rel="stylesheet" href="./assets/style.css">

<link rel="stylesheet" href="/main/exercice/Quiz/views/assets/MD.BootstrapPersianDateTimePicker-1.6.4/Content/bootstrap.min.css" />
<link rel="stylesheet" href="/main/exercice/Quiz/views/assets/MD.BootstrapPersianDateTimePicker-1.6.4/Content/bootstrap-theme.min.css" />
<link rel="stylesheet" href="/main/exercice/Quiz/views/assets/MD.BootstrapPersianDateTimePicker-1.6.4/Content/MdBootstrapPersianDateTimePicker/jquery.Bootstrap-PersianDateTimePicker.css" />

<script src="/main/exercice/Quiz/views/assets/MD.BootstrapPersianDateTimePicker-1.6.4/Scripts/jquery-2.1.4.js" type="text/javascript"></script>
<script src="/main/exercice/Quiz/views/assets/MD.BootstrapPersianDateTimePicker-1.6.4/Scripts/bootstrap.min.js" type="text/javascript"></script>
<style>
.menu_img{
    width: 45px;
}
.tick{
    width: 24px;
}
</style>

<div class="col-md-12" style="position: relative" id="gridForm">
    <div class="card">
        <div class="card-header">
           <h5> جلسات آنلاین تصویری </h5>
        </div>
        <div class="card-body">
        <div class="alert alert-primary" style="width: 700px; margin: 10px auto;">
            <?=$_SESSION['_user']['firstName']." ".$_SESSION['_user']['lastName'] ?> عزیز <br/>
            با سلام <br/>
            آیکون ورود به کلاس 10 دقیقه قبل از شروع جلسه فعال می شود. <br/>
            در صورت تاخیر یک ربع، برای شما غیبت ثبت می گردد. 
        </div>
        
        <table class="table table-hover table-sm table-responsive text-nowrap table-striped" align="center" style="width:700px;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>تاریخ</th>
                    <th>ساعت شروع</th>
                    <th>گروه</th>
                    <th style="text-align:center !important">حضور و غیاب</th>
                    <th>آرشیو</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($sessions['sessions']) == 0){ ?>
                <tr>
                    <td class="grid-col" colspan="8" style="text-align: center"> در این درس جلسه ای ثبت نشده است!! </td>
                </tr>
                <?php }else{
                        // print_r($courses);
                        $i = 0;
                        foreach($sessions['sessions'] as $session){
                            $i++;
                            if($session['cs_date'] > $sessions['persian_date']){
                                $session['status']= "-";
                            } 
                            else if($session['presense'] == "present" || $i % 2 == 0){
                                $session['status']= "<img class='tick' src='./assets/icons/colors/check.png' />";
                            }
                            else if($session['presense'] == "absent"){
                                $session['status']= "<img class='tick' src='./assets/icons/colors/ban.png' />";
                            }
                            ?>
                            <tr>
                                <td class="grid-col"><?=$i ?></td>
                                <td class="grid-col"><?=$session['cs_date'] ?></td>
                                <td class="grid-col"><?=$session['cs_start'] ?></td>
                                <td class="grid-col"><?=$session['cs_group'] ?></td>
                                <td class="grid-col" style="text-align:center"><?=$session['status'] ?></td>
                                <td class="grid-col"><?=$session['presense'] ?></td>
                            </tr>
                <?php   }
                    } 
                ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
