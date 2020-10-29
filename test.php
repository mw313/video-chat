<?php
header("Content-Type: application/json");

include_once('../inc/global.inc.php');

$sql = "SELECT * FROM `test002`.`moshkhasat`";
$result = api_sql_query($sql, __FILE__, __LINE__);
$numbers = array('id', 'form_code', 'rabet_code', 'takafol_number', 'gender_id', 'marital_status', 'house_status',
            'residence_status', 'poushesh_status', 'tavanaei_status', 'need_materials_id', 'need_moshaver_id', 'need_farhangi_id'
        , 'need_job_id', 'need_doktor_id', 'need_amozesh_id', 'need_manavi_id', 'status_id', 'education_status');

$info = array();
while($data = mysql_fetch_assoc($result)){
    $info[] = $data;
}

echo json_encode($info);