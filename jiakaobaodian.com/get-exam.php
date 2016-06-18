<?php
require_once 'common.php';
$db = getConnection();
/*
 * http://api2.jiakaobaodian.com/api/open/question/question-list.htm?_r=11258564547825243087&questionIds=803600
 */
$f = file_get_contents('exam_ids.txt');
$exam_ids = explode("\n", $f);
$baseurl = 'http://api2.jiakaobaodian.com/api/open/question/question-list.htm?_r=11258564547825243087&questionIds=';
$fields_buf = array(
    'question',
    'an1',
    'an2',
    'an3',
    'an4',
    'answertrue',
    'imageurl',
    'explain',
    'type',
    'chapterid',
    'ctype',
    'stype',
    'SpeId',
);
$count = 0;
foreach ($exam_ids as $line) {
    $count++;
    echo "Grabling: " . $count . "\n";
    if ($line == '') {
        continue;
    }
    $exam = explode('|', $line);
    $ctype = $exam[0];
    $stype = $exam[1];
    $id = $exam[2];
    $url = $baseurl . $id;
    $result = file_get_contents($url);
    if ($result) {
        $info = json_decode($result, true)['data'][0];
    } else {
        continue;
    }
    if ($info['mediaType'] > 0 && $info['mediaContent'] != '') {
        $media = explode('/', $info['mediaContent']);
        $fn = $media[count($media)-1];
    }
    $values_buf = array(
        addslashes($info['question']),
        addslashes($info['optionA']),
        addslashes($info['optionB']),
        addslashes($info['optionC']),
        addslashes($info['optionD']),
        restore_answer($info['answer']),
        isset($fn) ? $fn : '',
        addslashes($info['explain']),
        $info['optionType'] + 1,
        addslashes($info['chapterId']),
        $ctype,
        $stype,
        '',
    );
    $save_ok = save_exam($db = $db, $fields = $fields_buf, $values = $values_buf);
    if ($save_ok) {
        echo $count . " saved ok! \n";
    } else {
        echo "save failed and exam info is $ctype-$stype-$id\n";
    }
}

function save_exam($db, $fields, $values, $table = 'cs_exams_tmp') {
    $sql = " INSERT INTO `{$table}` (`".implode("`,`", $fields)."`) VALUES ('".implode("','", $values)."') ";
    $stmt = $db->query($sql);
    if ($stmt) {
        return true;
    } else {
        return false;
    }
}

function restore_answer ($answer) {
    $e = array();
    for ($a = 4; 12 > $a; $a++) {
        $i = $answer & 1 << $a;
        if ($i) {
            $e[] = $i;
        }
    }
    $a = array();
    foreach ($e as $v) {
        if ($v == 16) {
            $t = 1;
        } else if ($v == 32) {
            $t = 2;
        } else if ($v == 64) {
            $t = 3;
        } else if ($v == 128) {
            $t = 4;
        }
        $a[] = $t;
    }
    return implode('', $a);
}
?>
