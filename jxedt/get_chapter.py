#!/usr/bin/env python
# -*- coding: utf-8 -*-
# get_exam.py
# v1

# refer: mnks.jxedt.com/akm1/zjlx/3/
# api: http://mnks.jxedt.com/get_question?index=46

import os
import sys
import requests
from bs4 import BeautifulSoup

reload(sys)
sys.setdefaultencoding('utf8')

baseurl = 'http://mnks.jxedt.com/'
headers = {'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36'}

ctype = ['a', 'b', 'c', 'd']
stype = ['1', '4']

def urlFact(ctype, stype, cid):
    """return a complete url -> http://mnks.jxedt.com/akm1/zjlx/3/"""
    url = baseurl + ctype + 'km' + stype + '/zjlx/' + cid + '/'
    return url

def request_url(url, headers):
    """fetch html page via request package"""
    r = requests.get(url, headers = headers)
    if r.status_code == 200:
        return r.content
    return False

def find_chapter(html):
    """find the chapter id info"""
    soup = BeautifulSoup(html, 'lxml')
    results = soup.findAll('script')
    for i in results:
        innerHtml = i.getText()
        if 'chapexamid' in innerHtml:
            statement_list = innerHtml.split(';')
            for statement in statement_list:
                if 'chapexamid' in statement:
                    if 'chapexamids = []' in statement:
                        return False
                    chapter = statement.split('=')[1][2:-1]
                    chapter = chapter.replace('],[', ']:[')
                    chapter = chapter.replace(']', '')
                    chapter = chapter.replace('[', '')
                    chapter = chapter.split(':')
                    exam_id_list = []
                    for i in chapter:
                        left = int(i.split(',')[0])
                        right = int(i.split(',')[1])
                        if left <= right:
                            while left <= right:
                                exam_id_list.append(str(left))
                                left = left + 1
                    e = ','.join(exam_id_list)
                    return e
    return False

def now_time():
    """get current time -> 2016-06-14 10:25:30.186828"""
    from datetime import datetime
    t = str(datetime.now())
    return t

def log(fn, msg, time = True):
    """save crawler operation log locally"""
    if os.path.isfile(fn):
        fp = open(fn, 'a')
    else:
        fp = open(fn, 'w')
    if msg != '':
        if time == False:
            log = msg + "\n"
        else:
            log = now_time() + ' ' + msg + "\n"
        fp.write(log)
    fp.close()

def main():
    """get exams by chapter-id, subject-Type, car-Type"""
    for c in ctype:
        for s in stype:
            chapter_id = 1
            chapter_exists = True
            while chapter_exists:
                url = urlFact(c, s, str(chapter_id))
                req_result = request_url(url, headers)
                if req_result != False:
                    chapter = find_chapter(req_result)
                    print '****' + c + ':' + s + ':' + str(chapter_id) + '****'
                    if chapter != False:
                        print now_time() + ' get it'
                        record = c + '|' + s + '|' + str(chapter_id) + '|' + chapter
                        log('chapter.txt', record, time = False)
                        log('log.txt', chapter)
                else:
                    print now_time() + ' request error'
                    log('log.txt', 'request error')
                if chapter_id > 4:
                    chapter_exists = False
                chapter_id = chapter_id + 1


if __name__ == '__main__':
    main()
