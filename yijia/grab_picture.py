#!/usr/bin/python
# -*- coding: utf-8 -*-

import requests
import os
import time


def mkreq (url):
    headers = {'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36'}
    r = requests.get(url, headers)
    if 200 == r.status_code:
        return r
    else:
        return False

def getImageUri (j):
    l = []
    for i in j:
        if i['imageurl'] != '':
            l.append(i['imageurl'])
    return l

def grabPicture (l):
    for i in l:
        time.sleep(0.01)
        s = baseimgurl+i
        print 'I am downloading: ' + i
        r = mkreq (s)
        n = os.path.basename(i)
        if r is not False:
            img = r.content
            saveImage(img, n)
    return True

def saveImage (img, n):
    p = './DrivingImg'
    if not os.path.exists(p):
        os.mkdir(p)
    if os.path.exists(os.path.join(p, n)):
        print '本地已经存在: ' + str(n)
        return False
    fp = open(os.path.join(p, n), 'wb')
    fp.write(img)
    print 'Successfully downloaded: ' + n
    fp.close()
    return True


baseurl = u'http://121.41.53.108:88/api/Questions/GetAllSubjectByCarType'
baseimgurl = u'http://m.ej400.com/content'
cstype = [
        {'ctype': 'C1', 'stype': '1'},
        {'ctype': 'C1', 'stype': '4'},
        {'ctype': 'A1', 'stype': '1'},
        {'ctype': 'A1', 'stype': '4'},
        {'ctype': 'A2', 'stype': '1'},
        {'ctype': 'A2', 'stype': '4'},
        {'ctype': 'D', 'stype': '1'},
        {'ctype': 'D', 'stype': '4'},
    ]

for i in cstype:
    url = baseurl+'?ctype='+i['ctype']+'&stype='+i['stype']
    print url
    qlist = mkreq(url).json()
    image_list = getImageUri(qlist)
    print len(image_list)
    grabPicture(image_list)
