<?php
/**
 * get school data
 * @author  me at gdax dot org
 * @date    2016-04-22
 */

include_once '../include/config.php';
include_once '../include/functions.php';

/**
 * schools in one city api : http://m.jxedt.com/jiaxiao/sh/pn1
 * school detail api : http://api.jxedt.com/detail/2417/?type=jx
 */

// 中国四个直辖市分别为 : 北京 天津 上海 重庆
$cities = array('bj', 'sh', 'tj', 'cq');

/**
 * main part of the crawler
 * [1] get a very long school list (array)
 */

$ok_count = 0;
$fail_count = 0;
try {
	$db = new PDO('mysql:host=localhost;dbname=xihaxueche', USER, PASS);
	!is_object($db) && exit();


	//$school_list = combine_school_list($cities);
	//var_dump(count($school_list));
	//$school_detail = combine_school_list( $cities );

	$fields = array(
		'name', // school name
		'amount', // school money
		'imageurl', // school introduction picture
		's_imgurl', // school loop image
		'infoid', // school id
		'star', // star level
		'address', // school address
		'descarea', // school description
		'attentionnum', // the number of whom has pay attention to
		'province_id',
		'city_id',
		'area_id',
		'tel', // school telephone
		'moredesc', // more description about school
		'lng',
		'log',
		'is_finished',
	    );
	$sql = " INSERT INTO `cs_temp_school` (`". implode('`,`', $fields) ."`) VALUES ( ";
	$sql .= ":name, ";
	$sql .= ":amount, ";
	$sql .= ":imageurl, ";
	$sql .= ":infoid, ";
	$sql .= ":star, ";
	$sql .= ":address, ";
	$sql .= ":descarea, ";
	$sql .= ":attentionnum, ";
	$sql .= ":province_id, ";
	$sql .= ":city_id, ";
	$sql .= ":area_id, ";
	$sql .= ":tel, ";
	$sql .= ":moredesc, ";
	$sql .= ":lng, ";
	$sql .= ":log, ";
	$sql .= ":is_finished";
	$sql .= " ) ";

	$stmt = $db->prepare($sql);

	$school_detail = get_school_by_city('bj');

	foreach ( $school_detail as $school_index => $school ) {
	    $t = date('Y-m-d H:i:s', time());
	    $stmt->bindParam(':name', $school['title'], PDO::PARAM_STR);
	    $stmt->bindParam(':amount', $school['price'], PDO::PARAM_INT);
	    $stmt->bindParam(':imageurl', $school['imgUrl'], PDO::PARAM_STR);
	    $stmt->bindParam(':infoid', $school['url'], PDO::PARAM_INT);
	    $stmt->bindParam(':star', $school['xingJi'], PDO::PARAM_STR);
	    $stmt->bindParam(':address', $school['addr'], PDO::PARAM_STR);
	    $stmt->bindParam(':descarea', $school['descarea'], PDO::PARAM_STR);
	    $stmt->bindParam(':attentionnum', $school['attentionnum'], PDO::PARAM_STR);
	    $stmt->bindParam(':province_id', $school['province_id'], PDO::PARAM_STR);
	    $stmt->bindParam(':city_id', $school['city_id'], PDO::PARAM_STR);
	    $stmt->bindParam(':area_id', $school['area_id'], PDO::PARAM_STR);
	    $stmt->bindParam(':tel', $school['tel'], PDO::PARAM_STR);
	    $stmt->bindParam(':moredesc', $school['moredesc'], PDO::PARAM_STR);
	    $stmt->bindParam(':lng', $school['lng'], PDO::PARAM_STR);
	    $stmt->bindParam(':log', $school['log'], PDO::PARAM_STR);
	    $stmt->bindParam(':is_finished', $school['is_finished'], PDO::PARAM_STR);

	    $insert_ok = $stmt->execute();
	    if ( $insert_ok ) {
		$count++;
		echo "$t 成功写入第: $count 所驾校.\n";
	    } else {
		$fail_count++;
		echo "$t 写入失败.\n";
	    }
	}
} catch (Exception $e) {
    var_dump($e->getMessage());
}

echo "-------------------------------------\n";
echo "成功写入: $ok_count, 发生错误: $fail_count.\n";
echo "$t completed.\n";
echo "             program written by daxg.\n";
echo "-------------------------------------\n";

$db = null;
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
    $city_id = 0;
    $province_id = 0;
    switch ( $city ) {
        case 'bj': 
            $city_id = 110100;
            $province_id = 110000;
            break;
        case 'tj': 
            $city_id = 120100;
            $province_id = 120000;
            break;
        case 'sh': 
            $city_id = 310100;
            $province_id = 310000;
            break;
        case 'cq': 
            $city_id = 500100;
            $province_id = 500000;
            break;
        default: 
            $city_id = 0;
            $province_id = 0;
            break;
    }
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
            $school['province_id'] = $province_id;
            $school['city_id'] = $city_id;
            $school['area_id'] = 0;
            $school['is_finished'] = 1;
            $school['moredesc'] = '';
            print_r($school);
            $school_list[] = $school;
        }
    }
    return $school_list;
} /* get school by one city END */

// ^_^ you are neach the end of crawler
