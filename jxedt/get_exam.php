<?php
require_once 'common.php';
$db = getConnection();
$baseurl = 'http://mnks.jxedt.com/get_question?index=';
$f = file_get_contents('chapter.txt');
$chapters = explode("\n", $f);
$count = 1;
foreach ($chapters as $chapter) {
    $chapter_ar = explode('|', $chapter);
    if ($chapter_ar[0] === '') {
        continue;
    }
    $chapter_ar[3] = explode(',', $chapter_ar[3]);
    $ctype = $chapter_ar[0];
    $stype = $chapter_ar[1];
    $cid = $chapter_ar[2];
    $exam_ids = $chapter_ar[3];
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
        'ctype',
        'stype',
        'chapterid',
        'SpeId',
    );
    foreach ($exam_ids as $exam_id) {
        $url = $baseurl . $exam_id;
        $exam = file_get_contents($url);
        $exam = json_decode($exam, true);
        $imgurl = '';
        if ( !empty($exam['sinaimg']) ) {
            $imgurl = 'images/' . $exam['sinaimg'];
        } else if (!empty($exam['imageurl'])) {
            if (true === strpos($exam['imageurl'], 'img.58cdn.com.cn')) {
                $imgurl = $exam['imageurl'];
            }
        }
        $values_buf = array(
            addslashes($exam['question']),
            addslashes($exam['a']),
            addslashes($exam['b']),
            addslashes($exam['c']),
            addslashes($exam['d']),
            addslashes($exam['ta']),
            addslashes($imgurl),
            addslashes($exam['bestanswer']),
            addslashes($exam['Type']),
            addslashes($ctype),
            addslashes($stype),
            addslashes($cid),
            '',
        );
        /*
        $exam:
            Array
            (
                [id] => 1
                [question] => 驾驶机动车在道路上违反道路交通安全法的行为，属于什么行为？
                [a] => 违章行为
                [b] => 违法行为
                [c] => 过失行为
                [d] => 违规行为
                [ta] => 2
                [imageurl] => 
                [bestanswer] => “违反道路交通安全法”，违反法律法规即为违法行为。官方已无违章/违规的说法。
                [bestanswerid] => 2600001
                [Type] => 2
                [sinaimg] => 
            )

        */
        $sql = ' INSERT INTO `cs_exams_tmp`(`'.implode('`,`', $fields_buf).'`) ';
        $sql .= ' VALUES ("'.implode('","', $values_buf).'") ';
        $stmt = $db->query($sql);
        if ($stmt !== false) {
            echo  '[' . "$ctype-$stype-$cid-$exam_id" . ']'. "您已成功抓取第< " . $count . " >条记录" . "\n";
            $count++;
        }
    }
}
