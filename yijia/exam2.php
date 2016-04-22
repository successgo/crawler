<?php
$c = mysql_connect('localhost', 'root', 'root');
if ( !$c ) {
    echo 'error:' . mysql_error();
    exit();
}

$sql = 'set names utf8';
mysql_query($sql, $c);

$sql = 'use xihaxueche';
mysql_query($sql, $c);

$type = array(
        //array('ctype', 'stype') 车型，科目
        array('ctype' => 'C1', 'stype' => '1'),
        array('ctype' => 'C1', 'stype' => '4'),
        array('ctype' => 'A1', 'stype' => '1'),
        array('ctype' => 'A1', 'stype' => '4'),
        array('ctype' => 'A2', 'stype' => '1'),
        array('ctype' => 'A2', 'stype' => '4'),
        array('ctype' => 'D', 'stype' => '1'),
        array('ctype' => 'D', 'stype' => '4'),
    );
$baseurl = 'http://121.41.53.108:88/api/Questions/GetAllSubjectByCarType';

$i = 1;
foreach ($type as $key => $value) {
    echo "正在获取: {$value['ctype']} & 科目{$value['stype']} ......\n";
    echo "正在获取: {$value['ctype']} & 科目{$value['stype']} ......\n";
    echo "正在获取: {$value['ctype']} & 科目{$value['stype']} ......\n";
    echo "正在获取: {$value['ctype']} & 科目{$value['stype']} ......\n";
    $url = $baseurl . '?ctype=' . $value['ctype'] . '&stype=' . $value['stype'];
	//echo "$url\n";
    $r = file_get_contents($url);

    $j = json_decode($r, true);
    foreach ( $j as $k => $v) {
        $sql = " INSERT INTO `cs_exams_2` (`question`, `an1`, `an2`, `an3`, `an4`, `answertrue`, `imageurl`, `explain`, `type`, `chapterid`, `SpeId`, `ctype`, `stype`) VALUES ( ";
        $sql .= "'{$v['question']}', '{$v['an1']}', '{$v['an2']}', '{$v['an3']}', '{$v['an4']}', {$v['answertrue']}, '{$v['imageurl']}', '{$v['explain']}', '{$v['type']}', ";
        $sql .= " '{$v['chapterid']}', '{$v['SpeId']}', '{$value['ctype']}', '{$value['stype']}' ";
        $sql .= " ) ";
        $r = mysql_query($sql, $c);
        if ( $r ) {
            echo "成功获取第: $i 条记录\n";
            $i++;
        } else {
            echo 'error:' . mysql_error() . "\n";
            exit();
        }
    }
}
