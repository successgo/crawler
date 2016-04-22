<?php
include_once '../include/config.php';
include_once '../include/functions.php';
/**
 * get school data
 * @author  GDax
 * @date    2016-04-22
 */

/*
    schools in one city api : http://m.jxedt.com/jiaxiao/sh/pn1
    school detail api : http://api.jxedt.com/detail/2417/?type=jx
**/

// 中国四个直辖市分别为 : 北京 上海 天津 重庆
$cities = array('bj', 'sh', 'tj', 'cq');

$school_url = 'http://api.jxedt.com/detail/';

$db = new PDO('mysql:host=localhost;dbname=xihaxueche', USER, PASS);
!is_object($db) && exit();

get_school_by_city('bj');
exit();
foreach ( $cities as $city ) {
    $school_list = get_school_by_city($city);
    var_dump($school_list);
}

function get_school_by_city( $city ) {
    static $count = 1;
    $school_list = array();
    $city_url = 'http://m.jxedt.com/jiaxiao/';
    $url = "$city_url$city/pn1";
    for ($page = 1; $page > 0; $page++) {
        $url = "$city_url$city/pn$page";
        $school_info = file_get_contents($url);
        $school_info = json_decode($school_info, true);
        if ( empty($school_info) ) {
            break;
        }
        foreach ( $school_info as $school ) {
            echo "get one: " . $count . " " . $school['title'] . "\n";
            $count++;
            $school_list[] = $school_info;
        }
    }

    return $school_list;
}


$db = null;

// ^_^ you are reach the end of crawler
