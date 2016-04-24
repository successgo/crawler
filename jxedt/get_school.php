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
    $db->exec("SET CHARACTER SET utf8");

	$school_detail = combine_school_list( $cities );

	foreach ( $school_detail as $school_index => $school ) {
	    $fields = array(
		    's_school_name',
		    's_frdb',
            's_frdb_mobile',
            's_frdb_tel',
            's_yyzz',
            's_zzjgdm',
            'i_dwxz',
            'i_wdid',
            's_address',
            'dc_base_je',
            'dc_bili',
            's_yh_name',
            's_yh_zhanghao',
            's_yh_huming',
            's_shuoming',
            'province_id',
            'city_id',
            'area_id',
            'shifts_intro',
            's_thumb',
            's_location_x',
            's_location_y',
            's_imgurl',
            'addtime',
	    );
        $sql = " INSERT INTO `cs_school_dax` (`". implode('`,`', $fields) ."`) VALUES ( ";
        $sql .= "'". implode("','", $school) ."') ";
        $insert_ok = $db->query($sql);
	    if ( $insert_ok ) {
            $sid = $db->lastInsertId();
		    $ok_count++;
		    echo t() . " 成功写入第: $ok_count 所驾校.\n";
            $save_picture_ok = save_local_picture($sid, 'upload/thumb', $school['s_thumb']);
            if ( $save_picture_ok ) {
                echo t() . " 保存驾校缩略图到本地成功，路径为: $save_picture_ok.\n";
            };
	    } else {
		    $fail_count++;
		    echo t() . " 写入失败.\n";
	    }
	}
} catch (PDOException $e) {
    var_dump($e->getLine() . $e->getMessage());
    exit();
}

echo "-------------------------------------\n";
echo "成功写入: $ok_count, 发生错误: $fail_count.\n";
echo t() . " completed.\n";
echo "             program written by daxg.\n";
echo "-------------------------------------\n";

$db = null;
// Execution of the crawler End


/**
 * Function Definition
 * [1] combine_school_list
 * [2] get_school_by_city
 * [3] get_school_detail
 * [4] save_local_picture
 * [5] t
 */

function t( $ts = 0 ) {
    if ( $ts === 0) {
        $ts = time();
        list($usec, $sec) = explode(' ', microtime());
    }
    return date('Y-m-d H:i:s', $sec) .".". explode('.', $usec)[1];
}

function save_local_picture ($sid, $path, $picture_url) {
    return "$path/$sid.png";
}

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
    $info['lat'] = @$detail['baseinfoarea']['mapaddr']['action']['extparam']['lat'];
    $info['lng'] = @$detail['baseinfoarea']['mapaddr']['action']['extparam']['lon'];
    $info['tel'] = $detail['baseinfoarea']['tel'];
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
        foreach ( $school_info as $v ) {
            if ( empty($v['title']) ) {
                continue; // if the school does not have the TITLE, delete it
            }
            $more_detail = get_school_detail($v['url']);
            $v = array_merge($v, $more_detail);
            $school = array(
                's_school_name' => $v['title'] ? $v['title'] : '',
                's_frdb' => '法人未知',
                's_frdb_mobile' => isset($v['tel'][0]) ? $v['tel'][0] : '',
                's_frdb_tel' => isset($v['tel'][1]) ? $v['tel'][1] : '',
                's_yyzz' => '',
                's_zzjgdm' => 'unknown',
                'i_dwxz' => 1,
                'i_wdid' => 0,
                's_address' => $v['addr'],
                'dc_base_je' => $v['price'],
                'dc_bili' => 0,
                's_yh_name' => '',
                's_yh_zhanghao' => '',
                's_yh_huming' => '',
                's_shuoming' => $v['descarea'],
                'province_id' => $province_id,
                'city_id' => $city_id,
                'area_id' => 0,
                'shifts_intro' => '普通班，只需要' . $v['price'] . '元，快速拿驾照.',
                's_thumb' => $v['imgUrl'],
                's_location_x' => $v['lng'],
                's_location_y' => $v['lat'],
                's_imgurl' => '',
                'addtime' => time(),
            );
            echo "Get one school:" . $school['s_school_name'] . "\n";
            $school_list[] = $school;
            if ( count($school_list) > 2 ) {
                return $school_list;
            }
        }
    }
    return $school_list;
} /* get school by one city END */

// ^_^ you are neach the end of crawler
