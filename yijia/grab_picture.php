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
$baseurl = 'http://121.41.53.108:88/api/Questions/GetChapter';

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
        $now = time();
        $sql = " INSERT INTO `cs_exam_chapters` (`cid`, `ctype`, `stype`, `title`, `addtime`) VALUES ( ";
        $sql .= "'{$v['Id']}', '{$value['ctype']}', '{$value['stype']}', '{$v['ChapterName']}', '{$now}'";
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
