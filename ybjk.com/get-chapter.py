#!/usr/bin/env python
# -*- coding: utf-8 -*-
# get-chapter.py

# refer: 
# [1] http://www.ybjk.com/kms-zjlx-xc/
# [2] http://www.ybjk.com/lianxiti-xc.js?r=t287hxc5um&act=kms-sxlx&cx=xc&zid=1511
# [3] http://jsc.mnks.cn/ybjk/js/lianxi2013_v3.js?t=201512.js

import os
import sys
import requests
from bs4 import BeautifulSoup

reload(sys)
sys.setdefaultencoding('utf8')

# e.g. http://www.ybjk.com/kms-zjlx-xc/
baseurl = 'http://www.ybjk.com/'

# 科目一, 科目四
lesson = ['kmy', 'kms']

# 小车, 摩托车, 货车, 客车
car = ['xc', 'hc', 'kc', 'mtc']

# 伪造浏览器头信息
headers = {'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36'}

def urlFact(car, lesson, base = 'http://www.ybjk.com/'):
    url = base + lesson + '-zjlx-' + car + '/'
    return url

def urlRequest(url, headers = headers):
    chapter_list = []
    r = requests.get(url, headers = headers)
    if not r.status_code == 200:
        return False
    soup = BeautifulSoup(r.content, 'lxml')
    results = soup.findAll('script')
    for i in results:
        if 'html' in i.getText():
             chapter_list.append(i.getText().split('"')[1][4:])

    return chapter_list

def main():
    fp = open('chapter.txt', 'w')
    for c in car:
        for l in lesson:
            url = urlFact(car = c, lesson = l)
            chapter_list = urlRequest(url = url)
            for chap in chapter_list:
                record = c+':'+l+':'+chap+'\n'
                fp.write(record)
    fp.close()

# start
if __name__ == '__main__':
    main()
