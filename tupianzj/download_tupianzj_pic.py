#!/usr/bin/env python
# -*- coding: utf-8 -*-

#
# File: download_tupianzj_pic.py
#  By : gdc[admin@dalinux.com]
# Date: 2016-02-12
#

import os
import sys
import requests
from bs4 import BeautifulSoup

reload(sys)
sys.setdefaultencoding('utf8')

website = 'http://www.tupianzj.com'
cat = ['xiezhen', 'xinggan', 'guzhuang', 'yishu', 'siwa', 'chemo']
headers = {'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36'}

def meizi_cat_list(url, subcat, cat):
    '''get the complete url list'''
    # check if the url ends with '/'
    if not url[-1] == '/':
        url = url + '/'
    i = 0
    for c in cat:
        cat[i] = url + subcat + '/' + c + '/'
        i = i + 1
    return cat

def meizi_list(url):
    r = requests.get(url)
    if not r.status_code == 200:
        return r.status_code
    soup = BeautifulSoup(r.content, 'lxml')
    results = soup.findAll('span', attrs = {'class': 'soxflashtext'})
    for meizi in results:
         meizi_url = website + meizi.a.get('href')
         download_meizi(meizi_url)
    return True

def meizi_download_nextpage(url):
    r = requests.get(url)
    if not r.status_code == 200:
        return False
    soup = BeautifulSoup(r.content, 'lxml')
    results = soup.findAll('li')
    for i in results:
        if i.string == u'下一页' and i.a.get('href').split('.')[-1] == 'html':
            return os.path.dirname(url) + '/' + i.a.get('href')
    return False

def download_meizi(meizi_url):
    r = requests.get(meizi_url, headers = headers)
    if not r.status_code == 200:
        return r.status_code
    soup = BeautifulSoup(r.content, 'lxml')
    title = soup.title.string.split('_')
    meizi_name = title[0]
    meizi_cat = title[2]
    CWD = os.path.dirname(os.path.abspath(sys.argv[0]))
    path = os.path.join(CWD, '图片之家美女')
    if not os.path.exists(path):
        os.mkdir(path)
        print '成功创建: ' + path
    path = os.path.join(path, meizi_cat)
    if not os.path.exists(path):
        os.mkdir(path)
        print '成功创建: ' + path
    path = os.path.join(path, meizi_name)
    if not os.path.exists(path):
        os.mkdir(path)
        print '成功创建: ' + path
    download_big_pic(meizi_url, path)
    meizi_url = meizi_download_nextpage(meizi_url)
    while meizi_url:
        download_big_pic(meizi_url, path)
        meizi_url = meizi_download_nextpage(meizi_url)
    print '分类:' + meizi_cat + '/' + meizi_name + '|下载完成'

def download_big_pic(meizi_url, path):
    r = requests.get(meizi_url)
    if not r.status_code == 200:
        return r.status_code
    soup = BeautifulSoup(r.content, 'lxml')
    results = soup.findAll('img', id = 'bigpicimg')
    for meizi in results:
        meizi_uri = meizi.get('src')
        filename = os.path.basename(meizi_uri)
        path = os.path.join(path, filename)
        if not os.path.exists(path):
            r = requests.get(meizi_uri, headers = headers)
            if not r.status_code == 200:
                return r.status_code
            fp = open(path, 'wb')
            fp.write(r.content)
            fp.close()
            print '->' + filename

def meizi_nextpage(url):
    r = requests.get(url)
    if not r.status_code == 200:
        return False
    soup = BeautifulSoup(r.content, 'lxml')
    results = soup.findAll('li')
    for pi in results:
        if pi.string == u'下一页':
            return os.path.dirname(os.path.dirname(str(url))) + '/' + pi.a.get('href')
    return False

# here we go
for url in meizi_cat_list(website, 'meinv', cat):
    meizi_list(url)
    url = meizi_nextpage(url)
    while url:
        meizi_list(url)
        url = meizi_nextpage(url)

