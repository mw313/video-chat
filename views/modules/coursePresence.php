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
                            تعداد دانشجویان: <div class="badge badge-warning"><?=$sessions['stuNum'] ?></div>
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
                        <th scope="col">گروه</th>
                        <th scope="col">تعداد دانشجویان حاضر</th>
                        <th scope="col">درصد حضور</th>
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
                                    $session['count']= "برگزار نشده";
                                    $session['count']= "-";
                                    $session['count_persent']= "برگزار نشده";
                                    $session['count_persent']= "-";
                                    // else $class = "bg-green";
                                } 
                                else if($session['cs_date'] < $sessions['persian_date']) $class = "bg-green";
                                ?>
                                <tr>
                                    <td class="grid-col"><?=$i ?></td>
                                    <td class="grid-col"><?=$session['cs_date'] ?></td>
                                    <td class="grid-col"><?=$session['cs_start'] ?></td>
                                    <td class="grid-col"><?=$session['cs_group'] ?></td>
                                    <td class="grid-col"><?=$session['count'] ?></td>
                                    <td class="grid-col"><?=$session['count_persent'] ?></td>
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