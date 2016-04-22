<?php
/**
 * get school data
 * @author  GDax
 * @date    2016-04-22
 */

include_once '../include/config.php';
include_once '../include/functions.php';

/**
 * schools in one city api : http://m.jxedt.com/jiaxiao/sh/pn1
 * school detail api : http://api.jxedt.com/detail/2417/?type=jx
 */

// 中国四个直辖市分别为 : 北京 上海 天津 重庆
$cities = array('bj', 'sh', 'tj', 'cq');


$db = new PDO('mysql:host=localhost;dbname=xihaxueche', USER, PASS);
!is_object($db) && exit();

/**
 * main part of the crawler
 * [1] get a very long school list (array)
 */

//$school_list = combine_school_list($cities);
//var_dump(count($school_list));
$school_detail = combine_school_list( $cities );
var_dump(count($school_detail));

// Execution of the crawler End


/**
 * Function Definition
 * [1] combine_school_list
 * [2] get_school_by_city
 * [3] get_school_detail
 */

function get_school_detail ( $school_id ) {
    $info = array();
    //school detail api : http://api.jxedt.com/detail/2417/?type=jx
    $school_url = 'http://api.jxedt.com/detail/';
    if ( empty($school_id) ) {
        return false;
    }
    $url = "$school_url$school_id/?type=jx";
    $school_detail = json_decode(file_get_contents($url), true);
    if ( empty( $school_detail ) ) {
        return false;
    }
    $detail = $school_detail['result']['info'];
    $info['addr'] = $detail['baseinfoarea']['mapaddr']['text'];
    $info['lat'] = $detail['baseinfoarea']['mapaddr']['action']['extparam']['lat'];
    $info['lon'] = $detail['baseinfoarea']['mapaddr']['action']['extparam']['lon'];
    $info['tel'] = json_encode($detail['baseinfoarea']['tel']);
    $info['descarea'] = $detail['descarea']['text'];
    $info['attentionnum'] = $detail['titlearea']['attentionnum'];

    return $info;
}

function combine_school_list ( $cities ) {
    $school_list = array();
    if ( empty($cities) ) {
        return false;
    }
    foreach ( $cities as $city ) {
        $school_list_of_one_city = get_school_by_city($city);
        /*
        foreach( $school_list_of_one_city as $school) {
            $school_list[] = $school;
        }
        */
        $school_list = array_merge($school_list, $school_list_of_one_city);
    }
    return $school_list;
} /* combine school list END */

function get_school_by_city ( $city ) {
    $school_list = array();
    $city_url = 'http://m.jxedt.com/jiaxiao/';
    $url = "$city_url$city/pn1";
    for ($page = 1; $page > 0; $page++) {
        $url = "$city_url$city/pn$page";
        $school_info = file_get_contents($url);
        $school_info = json_decode($school_info, true);
        if ( empty($school_info) ) {
            break; // jump out of the FOR-loop until we can not get the school info
        }
        foreach ( $school_info as $school ) {
            if ( empty($school['title']) ) {
                continue; // if the school does not have the TITLE, delete it
            }
            $more_detail = get_school_detail($school['url']);
            $school = array_merge($school, $more_detail);
            print_r($school);
            $school_list[] = $school;
        }
    }
    return $school_list;
} /* get school by one city END */


$db = null;

// ^_^ you are neach the end of crawler
