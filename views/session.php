<?php
// print_r($sessions);
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
                کاربر محترم «<?=$_SESSION['_user']['firstName']." ".$_SESSION['_user']['lastName'] ?>» <br/>
                با سلام <br/>
                دسترسی ورود به کلاس، 10 دقیقه قبل از شروع جلسه فعال می شود. <br/>
                در صورت تاخیر یک ربع، برای شما غیبت ثبت می گردد. 
            </div>
            <?=$message ?>
        </div>
    </div>
</div>
