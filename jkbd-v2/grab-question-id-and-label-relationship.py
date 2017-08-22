#!/usr/bin/env python
# -*- coding: utf-8 -*-

import os
import sys
import requests
import json
from bs4 import BeautifulSoup

'''grab question id and label(关联关系), and save it to resource-question-id-and-label-relationship.txt'''


def save_txt(content, file_name = 'log.txt', new_line = True, over_write = False):
    ''' save text to file '''
    if over_write:
        fp = open(file_name, 'w')
    else:
        fp = open(file_name, 'a')
    if new_line:
        content = str(content) + '\n'
    fp.write(content)
    fp.close()

def get_json(url):
    ''' make GET request and return '''

    headers = {'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36'}
    result = requests.get(url, headers = headers)
    if result.status_code == 200:
        return json.loads(str(result.content))
    else:
        return None

def Itlabels(file_name = 'resource-question-label.txt'):
    fp = open(file_name, 'r')
    contents = fp.read()
    chapters = filter(lambda x: x != '', contents.split('\n'))
    fp.close()
    return (chapter.split('|') for chapter in chapters)

# http://api2.jiakaobaodian.com/api/open/question/list-by-tag.htm?_r=111589549559260798110&cityCode=340100&page=1&limit=25&_=0.3376577657655737&carType=bus&course=kemu3&tagId=1
def build_url(s):
    ''' return Iterator '''

    tag_id = s[-1].split('.')[0].split('-')[-1]
    base_url = 'http://api2.jiakaobaodian.com/api/open/question/list-by-tag.htm?_r=111589549559260798110&cityCode=340100&page=1&limit=25&_=0.3376577657655737'
    return base_url + '&carType=' + s[0] + '&course=' + s[1] + '&tagId=' + tag_id

# run here
if __name__ == '__main__':
    for label in Itlabels():
        url = build_url(label)
        results = get_json(url)[u'data']
        if len(results) != 0:
            print 'we make it: %s' % url
            tag_id = label[-1].split('.')[0].split('-')[-1]
            # Format: car_type,course,big,small,tag_id,question_id
            raw_string = '\n'.join(label[0] + ',' +  label[1] + ',' + label[2] + ',' + label[3] + ',' + tag_id + ',' + str(qid) for qid in results)
            save_txt(raw_string, 'resource-question-id-and-label-relationship.txt')
        else:
            print 'empty: %s' % url
