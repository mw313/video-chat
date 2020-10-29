<form action="#menu_div" class="row" method="post">
    <div class="col-md-12" style="position: relative" id="gridForm">
        <div class="card">
            <div class="card-header">
                <div class="md-form col-md-3" style="float: right">
                    <input type="text" name="date" id="date" class="form-control" value="<?=$_POST['date'] ?>" placeholder="تاریخ برگزاری کلاس آنلاین" data-mddatetimepicker="true" data-placement="left" />
                </div>
                <div class="md-form col-md-2" style="float: right; text-align: right">
                    <select name="time" id="time" class="form-control" style="height: auto">
                        <option value="">همه</option>
                        <?php for($i=7; $i<22; $i++){ $h = $i; if($h<10) $h = '0'.$h; ?>
                            <option value="<?=$h ?>:00:00"><?=$h ?>:00</option>
                        <?php } ?>
                    </select>
                    <script>
                        $("#time").val('<?=$_POST['time'] ?>');
                    </script>
                </div>
                <div class="md-form col-md-7" style="float: left; text-align: left">
                    <input type="submit" name="listView" value="نمایش لیست دروس" class="btn btn-info">
                    <input type="submit" name="active" value="فعالسازی" class="btn btn-success">
                    <input type="submit" name="deactive" value="خاموش کردن" class="btn btn-danger">
                </div>
            </div>
            <div class="card-body">                    
            <table class="table table-hover table-sm table-responsive text-nowrap table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">کد درس مادر</th>
                        <th scope="col">کد دروس تلفیق شده</th>
                        <!-- <th scope="col">تاریخ</th> -->
                        <th scope="col">ساعت شروع</th>
                        <!-- <th scope="col">مدت زمان</th> -->
                        <th scope="col">وضعیت</th>
                        <th scope="col" onclick="checkAll()" style="cursor: pointer">انتخاب</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($courses) == 0){ ?>
                    <tr>
                        <td class="grid-col" colspan="8" style="text-align: center"> در این تاریخ کلاس آنلاینی ثبت نشده است!! </td>
                    </tr>
                    <?php }else{
                            // print_r($courses);
                            $i = 0;
                            foreach($courses as $course){
                                $i++;
                                $class = "bg-danger";
                                if($course['status'] == 1) $class = "bg-green";
                                ?>
                                <tr>
                                    <td class="grid-col"><?=$i ?></td>
                                    <td class="grid-col">
                                        <a target="_blank" href='http://www.ikvu.ac.ir/courses/<?=$course['cs_course_id']?>/index.php'><?=$course['cs_course_id']." «".$course['title']."»" ?></a>
                                    </td>
                                    <td class="grid-col">
                                        <?php foreach($course['childs'] as $child){ ?>
                                            <a target="_blank" href='http://www.ikvu.ac.ir/courses/<?=$child['code']?>/index.php'><?=$child['code']." «".$child['title']."»" ?></a> <br/>
                                        <?php } ?>
                                    </td>
                                    <!-- <td class="grid-col"><?=$course['cs_date'] ?></td> -->
                                    <td class="grid-col"><?=$course['cs_start'] ?></td>
                                    <!-- <td class="grid-col"><?=$course['cs_length'] ?></td> -->
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