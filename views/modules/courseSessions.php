<style>
.col-md-2{
    padding-right: 5px;
    padding-left: 5px;
}
</style>

<form action="#menu_div" class="row" method="post">
    <div class="col-md-12" style="position: relative" id="gridForm">
        <div class="card">
            <div class="card-header">
                <div class="md-form col-md-2" style="float: right">
                    <input type="text" name="course" id="course" class="form-control" required="required" value="<?=$_POST['course'] ?>" placeholder="کد درس" />
                </div>
                <div class="md-form col-md-2" style="float: right">
                    <input type="text" name="year" id="year" class="form-control" value="<?=$_POST['year']?$_POST['year']:$sessions['semester']['year'] ?>" placeholder="سال" />
                </div>
                <div class="md-form col-md-1" style="float: right">
                    <input type="text" name="semester" id="semester" class="form-control" value="<?=$_POST['semester']?$_POST['semester']:$sessions['semester']['semester'] ?>" placeholder="ترم" />
                </div>
                <div class="md-form col-md-2" style="float: right; text-align: right">
                    <select name="group" id="group" class="form-control" style="height: auto">
                        <option value="">همه</option>
                        <option value="1">گروه 1</option>
                        <option value="2">گروه 2</option>
                    </select>
                    <script>
                        $("#group").val('<?=$_POST['group'] ?>');
                    </script>
                </div>
                <div class="md-form col-md-5" style="float: left; text-align: left">
                    <input type="submit" name="listView" value="نمایش لیست جلسات" class="btn btn-info">
                    <input type="button" name="active" value="جلسه جدید" class="btn btn-success" data-toggle="modal" data-target="#newModal" >
                    <!-- <input type="submit" name="deactive" value="خاموش کردن" class="btn btn-danger"> -->
                </div>
            </div>
            <div class="card-body">
            <div class="col-md-12" style="padding: 10px; display:<?=(count($sessions['sessions']) == 0)?"none":"block" ?>">
                <div class="col-md-12 alert alert-primary">
                    <h5> جلسات آنلاین درس 
                        <a href="http://www.ikvu.ac.ir/courses/<?=$sessions['course']['code'] ?>/index.php" target="_blank">
                            «<?=$sessions['course']['code'] ?>» «<?=$sessions['course']['title'] ?>»
                        </a> 
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            کد کلاس مادر: 
                            <a href="http://www.ikvu.ac.ir/courses/<?=$sessions['parent']['code'] ?>/index.php" target="_blank">
                                «<?=$sessions['parent']['code'] ?>» «<?=$sessions['parent']['title'] ?>»
                            </a>
                        </div>
                        <div class="col-md-3">
                            تاریخ فعلی: <?=$sessions['persian_date'] ?>
                        </div>
                        <div class="col-md-3">
                            ساعت: <?=date("H:i:s") ?>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-hover table-sm table-responsive text-nowrap table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">تاریخ</th>
                        <th scope="col">ساعت شروع</th>
                        <th scope="col">مدت زمان</th>
                        <th scope="col">گروه</th>
                        <th scope="col">وضعیت</th>
                        <th scope="col" onclick="checkAll()" style="cursor: pointer">انتخاب</th>
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
                                $class = "bg-danger";
                                if($session['cs_date'] > $sessions['persian_date']){
                                    // if($session['cs_start'] > $sessions['time'])
                                        $class = "bg-disabled";
                                    // else $class = "bg-green";
                                } 
                                else if($session['cs_date'] < $sessions['persian_date']) $class = "bg-green";
                                ?>
                                <tr>
                                    <td class="grid-col"><?=$i ?></td>
                                    <td class="grid-col"><?=$session['cs_date'] ?></td>
                                    <td class="grid-col"><?=$session['cs_start'] ?></td>
                                    <td class="grid-col"><?=$session['cs_length'] ?></td>
                                    <td class="grid-col"><?=$session['cs_group'] ?></td>
                                    <td class="grid-col">
                                        <img class="check-img <?=$class ?>" src="/main/exercice/Quiz/views/assets/svg/check-box.svg">
                                    </td>
                                    <td class="grid-col"><input type="checkbox" name="courses[]" value="<?=$course['cs_course_id'] ?>" /></td>
                                </tr>
                    <?php   }
                        } 
                    ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</form>

<form action="#menu_div" class="row" method="post">
<input type="hidden" name="course"   value="<?=$_POST['course'] ?>" />
<input type="hidden" name="year"     value="<?=$_POST['year'] ?>" />
<input type="hidden" name="semester" value="<?=$_POST['semester'] ?>"/>

<div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center">
                مشخصات جلسه جدید
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <label for="code">کد درس</label>
                    <input type="text" id="code" name="code" class="form-control form-control-sm" required="required" value="<?=$_POST['course'] ?>">
                    <br>

                    <label for="date">تاریخ</label>
                    <input type="text" id="date" name="date" class="form-control form-control-sm" required="required" data-mddatetimepicker="true" data-placement="left">
                    <br>
                    
                    <label for="start">ساعت شروع</label>
                    <select name="start" id="start" class="form-control" style="height: auto" required="required">
                        <option value=""></option>
                        <?php for($i=7; $i<22; $i++){ $h = $i; if($h<10) $h = '0'.$h; ?>
                            <option value="<?=$h ?>:00:00"><?=$h ?>:00</option>
                        <?php } ?>
                    </select>
                    <br>

                    <label for="time">مدت زمان</label>
                    <select name="length" id="length" class="form-control" style="height: auto" required="required">
                        <option value="01:00:00">01:00:00</option>
                        <option value="01:15:00">01:15:00</option>
                        <option value="01:30:00">01:30:00</option>
                    </select>
                    <br>

                    <label for="group">گروه</label>
                    <select name="group" id="group" class="form-control" style="height: auto" required="required">
                        <option value="1">گروه 1</option>
                        <option value="2">گروه 2</option>
                        <option value="3">گروه 3</option>
                    </select>
                    <br>

                    <label for="group">وضعیت</label>
                    <select name="status" id="status" class="form-control" style="height: auto" required="required">
                        <option value="0">غیرفعال</option>
                        <option value="1" selected>فعال</option>
                        <option value="2">کنسل شده</option>
                    </select>
                    <br>

                    <div class="text-center mt-4 mb-2">
                        <button class="btn btn-info" name="newSession" type="submit"> ثبت
                            <i class="fa fa-send ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--/.Content-->
    </div>
</div>
</form>

<?=$modal ?>

<script type="text/javascript">
    $('#input1').change(function() {
        var $this = $(this),
            value = $this.val();
        alert(value);
    });
    $('#textbox1').change(function () {
        var $this = $(this),
            value = $this.val();
        alert(value);
    });
    const checkAll = function(){
        let num = $("[name='courses[]']:checked").length;
        if(num == 0)
            $("[name='courses[]']").prop('checked', true);
        else
            $("[name='courses[]']").prop('checked', false);
    }
</script>
<script src="/main/exercice/Quiz/views/assets/MD.BootstrapPersianDateTimePicker-1.6.4/Scripts/MdBootstrapPersianDateTimePicker/calendar.js" type="text/javascript"></script>
<script src="/main/exercice/Quiz/views/assets/MD.BootstrapPersianDateTimePicker-1.6.4/Scripts/MdBootstrapPersianDateTimePicker/jquery.Bootstrap-PersianDateTimePicker.js" type="text/javascript"></script>