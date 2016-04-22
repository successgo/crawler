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

$url = 'http://121.41.53.108:88/api/Questions/GetAllSubjectByCarType?ctype=C1&stype=1';
$r = file_get_contents($url);

$j = json_decode($r, true);
$page = $_GET['page'];
$limit = 100;
$index = ($page - 1)*$limit;
echo "<pre>";
$i = 1;
foreach ( $j as $k => $v) {
    /*
    print_r($v);
    exit();
    */
    if($index <= $k && $k < $index + $limit) {
    $sql = " INSERT INTO `cs_exams` (`question`, `an1`, `an2`, `an3`, `an4`, `answertrue`, `imageurl`, `explain`, `type`, `chapterid`, `SpeId`, `ctype`, `stype`) VALUES ( ";
    $sql .= "'{$v['question']}', '{$v['an1']}', '{$v['an2']}', '{$v['an3']}', '{$v['an4']}', {$v['answertrue']}, '{$v['imageurl']}', '{$v['explain']}', '{$v['type']}', ";
    $sql .= " '{$v['chapterid']}', '{$v['SpeId']}', 'C1', '1' ";
    $sql .= " ) ";
    $r = mysql_query($sql, $c);
    if ( $r ) {
        echo "<p>成功获取第: $i 条记录</p>";
        $i++;
    }
    }
}

