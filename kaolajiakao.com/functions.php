<?php
require_once 'config.php';

//获取一个车型科目下的章节列表
function getChapter($c, $s, $apiurl) {
    $chapters = array();
    $passType = $c . "_" . $s;
    $url = $apiurl . "?passType=" . $passType . "&isChapterList=1";
    $one_chapter = json_decode(file_get_contents($url), true);
    array_pop($one_chapter);
    foreach ($one_chapter as $k => $v) {
        $chapters[$k] = array(
            'ctype' => $c,
            'stype' => ($s === 'km1') ? '1' : '4',
            'cid' => $v['Id'],
            'title' => $v['ChapterName']
        );
    }
    return $chapters;
}

//保存章节信息
function saveChapter($db, $chapters) {
    $fields_buf = array(
        'cid',
        'ctype',
        'stype',
        'title',
        'addtime',
    );
    $sql = 'INSERT INTO `cs_exam_chapters_tmp` (`'.implode('`,`', $fields_buf).'`) VALUES ';
    foreach ($chapters as $k => $v) {
        $values_buf = array(
            $v['cid'],
            $v['ctype'],
            $v['stype'],
            $v['title'],
            time(),
        );
        $sql .= " ('".implode("','", $values_buf)."')";
        if ($k < count($chapter)-1) {
            $sql .= ',';
        }
    }
    $stmt = $db->query($sql);
}

//获取一个车型科目下的题目id
function getExamId($ctype, $stype, $cid, $apiurl) {
    $passType = $ctype . "_" . $stype;
    $url = $apiurl . "?passType=" . $passType . "&chapter=" . $cid;
    $examid_list = json_decode(file_get_contents($url), true);
    return $examid_list;
}

//保存一个车型科目章节下的所有题
function saveExams($db, $c, $s, $ctype, $stype, $cid, $examid_list, $apiurl) {
    static $count = 0;
    $passType = $ctype . "_" . $stype;
    foreach ($examid_list as $kExamId) {
        $url = $apiurl . "?passType=" . $passType . "&id=" . $kExamId;
        $exam = json_decode(file_get_contents($url), true)[0];
        if (count($exam['Choices']) == 2) {
            $type = 1; //判断题
        } else if (strlen($exam['CorrectAnswer']) == 1) {
            $type = 2; //单选题
        } else {
            $type = 3; //多选题
        }
        $exam['CorrectAnswer'] = str_replace('A', '1', $exam['CorrectAnswer']);
        $exam['CorrectAnswer'] = str_replace('B', '2', $exam['CorrectAnswer']);
        $exam['CorrectAnswer'] = str_replace('C', '3', $exam['CorrectAnswer']);
        $exam['CorrectAnswer'] = str_replace('D', '4', $exam['CorrectAnswer']);
        $exam['type'] = $type;
        $fields_buf = array(
            'question',
            'type',
            'an1',
            'an2',
            'an3',
            'an4',
            'imageurl',
            'answertrue',
            'explain',
            'ctype',
            'stype',
            'chapterid',
            'SpeId',
        );
        if ($type === 1) {
            $values_buf = array(
                $exam['Question'],
                $type,
                $exam['Choices'][0]['Description'],
                $exam['Choices'][1]['Description'],
                '',
                '',
                $exam['PicName'],
                $exam['CorrectAnswer'],
                $exam['AnswerKeys'],
                $ctype,
                $stype,
                $cid,
                '',
            );
        } else {
            $values_buf = array(
                $exam['Question'],
                $type,
                $exam['Choices'][0]['Description'],
                $exam['Choices'][1]['Description'],
                $exam['Choices'][2]['Description'],
                $exam['Choices'][3]['Description'],
                $exam['PicName'],
                $exam['CorrectAnswer'],
                $exam['AnswerKeys'],
                $ctype,
                $stype,
                $cid,
                '',
            );
        }
        $sql = ' INSERT INTO `cs_exams_tmp` (`'.implode('`,`', $fields_buf).'`) VALUES ("'.implode('","', $values_buf).'") ';
        $stmt = $db->query($sql);
        if ($stmt !== false) {
            $count++;
            echo "您成功下载第".$count."条记录。\n";
        }
    }
}
