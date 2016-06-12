<?php
require_once 'config.php';

foreach ($ctype as $c) {
    foreach ($stype as $s) {
        $passType = $c . "_" . $s . "\n";
        $url = $apiurl . "?passType=" . $passType . "&isChapterList=1";
        $one_chapter = json_decode(file_get_contents($url), true);
        foreach ($one_chapter as $k => $v) {
            if ($k === count($one_chapter)-1) {
                break;
            }
            var_dump($v);
        }
        echo "******************************************\n";
    }
}
