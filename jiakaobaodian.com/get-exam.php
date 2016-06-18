<?php
/*
 * http://api2.jiakaobaodian.com/api/open/question/question-list.htm?_r=11258564547825243087&questionIds=803600
 */
$f = file_get_contents('exam_ids.txt');
$exam_ids = explode("\n", $f);
$baseurl = 'http://api2.jiakaobaodian.com/api/open/question/question-list.htm?_r=11258564547825243087&questionIds=';
foreach ($exam_ids as $line) {
    if ($line == '') {
        continue;
    }
    $exam = explode('|', $line);
    $id = $exam[2];
    $url = $baseurl . $id;
    $result = file_get_contents($url);
    if ($result) {
        $info = json_decode($result, true)['data'][0];
    } else {
        continue;
    }
    print_r($info);
}
?>
