<?php
require_once 'common.php';
$baseurl = 'http://ww3.sinaimg.cn/mw600/';
$db = getConnection();
$sql = ' SELECT DISTINCT(imageurl) FROM `cs_exams_tmp` ';
$stmt = $db->query($sql);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
array_shift($results);
$count = 1;
foreach ($results as $key => $val) {
    $image = $val['imageurl'];
    if (strpos($image, '58cdn.com.cn')) {
        save('imageurl.txt', $image);
    } else {
        $imageurl = $baseurl . explode('/', $image)[1];
        save('imageurl.txt', $imageurl);
    }
    echo "成功获取一个图片地址: $count" . "\n";
    $count++;
}
$db = null;

function save($fn, $msg, $time = true) {
    if (file_exists($fn)) {
        $fp = fopen($fn, 'a');
    } else {
        $fp = fopen($fn, 'w');
    }
    if (!empty($msg)) {
        $msg = $msg . "\n";
        fwrite($fp, $msg);
    }
    fclose($fp);
}
