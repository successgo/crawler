<?php
require_once 'config.php';
require_once 'common.php';
require_once 'functions.php';

$db = getConnection();
foreach ($ctype as $c) {
    foreach ($stype as $s) {
        $chapters = getChapter($c, $s, $apiurl);
        //saveChapter($db, $one_chapter);
        foreach ($chapters as $k => $v) {
            $examid_list = getExamId($c, $s, $v['cid'], $apiurl);
            saveExams($db, $c, $s, $v['ctype'], $v['stype'], $v['cid'], $examid_list, $apiurl);
        }
        echo "******************************************\n";
    }
}
