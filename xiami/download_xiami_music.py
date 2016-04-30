#!/usr/bin/env python
# -*- coding: utf-8 -*-
# download_xiami_music.py

import os
import sys
import requests
import time
from bs4 import BeautifulSoup

reload(sys)
sys.setdefaultencoding('utf8')

def str2url(s):
    #7h%5xo2F589.ay55596-%%-t2.imF1744mu%EEcf4155ntFfa%13654pt3%b9f54EEup%im235%563hD5ad7458-l%2liF5%239%_dEb4235%%l3Fe.1%2F__3k163627555Am.c%2F32lFe%%5c8c8EE
    metamatrix = []
    seg = int(s[0])
    s = s[1:]
    mod = len(s) % seg
    basechar = len(s) / seg
    j = 1
    for i in range(0, seg):
        if j <= mod:
            extra = 1
        else:
            extra = 0
        j = j + 1
        l = s[0:basechar + extra]
        metamatrix.append(l)
        s = s[basechar + extra :]
    r = ''
    for i in range(0, basechar):
        for j in range(0, seg):
            e = metamatrix[j][i]
            r = r + e
    for k in range(0, mod):
        e = metamatrix[k][basechar]
        r = r + e
    
    r = r.replace('%5E', '^')
    r = r.replace('^', '0')
    r = r.replace('%3A', ':')
    r = r.replace('%2F', '/')
    r = r.replace('%3F', '?')
    r = r.replace('%3D', '=')

    return r

def get_song_info(song_id):
    print '-----------'
    print '正在努力查找歌曲信息: ' + song_id
    baseurl = 'http://www.xiami.com/song/playlist/id/'
    xmlurl = baseurl + song_id
    r = requests.get(xmlurl, headers = headers)
    if r.status_code == 200 and r.content:
        t = r.content
        t = t.replace('<![CDATA[', '')
        t = t.replace(']]>', '')
        soup = BeautifulSoup(t, 'lxml')
        song_info = {}
        song_info['title'] = soup.title.string
        print 'title:' + song_info['title']
        song_info['album_name'] = soup.album_name.string
        print 'album_name:' + song_info['album_name']
        song_info['artist'] = soup.artist.string
        print 'artist:' + song_info['artist']
        song_info['location'] = str2url(soup.location.string)
        print 'location:' + song_info['location']
        return song_info
    else:
        print 'song_id:' + song_id + '已经下架'
        return False

def get_song_audio(song):
    #{'album_name': u'\u597d\u8fd0\u4eca\u5e74\u8f6e\u5230\u6211', 'artist': u'\u8521\u632f\u5357', 'location': u'http://m5.file.xiami.com/51/51/188/2312_2935687_l.mp3?auth_key=320be5cc23fe5c4418da6de426cfdc6f-1455753600-0-null', 'title': u'\u8fc7\u5ba2'}
    #artist-album_name-title.mp3

    # 创建目录
    if not os.path.exists('./xiami'):
        print '需要创建目录: ./xiami'
        os.mkdir('./xiami')
        print '创建目录成功'
    else:
        print '目录已经存在: ./xiami'

    #下载歌曲
    song_save = song['artist'] + '-' + song['album_name'] + '-' + song['title'] + '.mp3'
    song_save = song_save.replace('/', '_')
    song_save = song_save.replace(' ', '')
    if os.path.exists('./xiami/' + song_save):
        print '本地已经存在此歌曲:' + song_save
        return False
    print '开启下载进程: ' + song_save
    r = requests.get(song['location'], headers = headers)
    if r.status_code == 200:
        print '歌曲下载成功:' + song_save
        fp = open('./xiami/' + song_save, 'wb')
        fp.write(r.content)
        fp.close()
        print '歌曲本地保存成功:' + './xiami/' + song_save
    else:
        print '下载出现异常, 返回状态码值:' + r.status_code
    return True


# test
headers = {'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36'}
i = 42333
while True:
    song_info = get_song_info(str(i))
    if song_info:
        get_song_audio(song_info)
    i = i + 1
    time.sleep(0.5)
