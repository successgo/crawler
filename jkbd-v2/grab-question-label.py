#!/usr/bin/env python
# -*- coding: utf-8 -*-

import os
import sys
import requests
import json
from bs4 import BeautifulSoup

'''grab question label(专项练习) and save it to resource-question-label.txt'''

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

def get_html(url):
    ''' make GET request and return '''

    headers = {'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36'}
    result = requests.get(url, headers = headers)
    if result.status_code == 200:
        return result.content
    else:
        return None

# 专项练习 *strengthen*
# http://m.jiakaobaodian.com/mnks/strengthen/bus-kemu3-hefei.html
def Iturls():
    '''return Iterator'''
    base_url = 'http://m.jiakaobaodian.com/mnks/strengthen/'
    settings = [
        {'car_type': 'bus', 'course': 'kemu1'},
        {'car_type': 'bus', 'course': 'kemu3'},
        {'car_type': 'truck', 'course': 'kemu1'},
        {'car_type': 'truck', 'course': 'kemu3'},
        {'car_type': 'car', 'course': 'kemu1'},
        {'car_type': 'car', 'course': 'kemu3'},
        {'car_type': 'moto', 'course': 'kemu1'},
        {'car_type': 'moto', 'course': 'kemu3'},
        {'car_type': 'keyun', 'course': 'zigezheng'},
        {'car_type': 'huoyun', 'course': 'zigezheng'},
        {'car_type': 'weixian', 'course': 'zigezheng'},
        {'car_type': 'jiaolian', 'course': 'zigezheng'},
        {'car_type': 'chuzu', 'course': 'zigezheng'}
    ]
    return ({'car_type': s['car_type'], 'course': s['course'], 'url': base_url + s['car_type'] + '-' + s['course'] + '-hefei.html'} for s in settings)

def parse(content):
    '''return list => [{'big': 'xxx', 'small': 'xxx', 'link': 'xxx'}]'''
    results = []
    soup = BeautifulSoup(content, 'lxml')
    div = soup.find('div', attrs = {'class': 'jkbd-app-strengthen-home'})
    lis = div.find_all('li')
    for li in lis:
        spans = li.h2.find_all('span')
        question_type = spans[0].contents[0]
        count = spans[1].contents[0][:-1]
        hrefs = li.find_all('a')
        for href in hrefs:
            title = href.contents[0]
            link = href.get('href')
            result = {
                'big': question_type,
                'small': title,
                'link': link
            }
            results.append(result)
    return results

# run here
if __name__ == '__main__':
    for item in Iturls():
        url = item['url']
        print url
        html = get_html(url)
        results = parse(html)
        for r in results:
            raw_string = '%s|%s|%s|%s|%s' % (item['car_type'], item['course'], r['big'], r['small'], r['link'])
            print 'saving: %s' % raw_string
            utf8_string = raw_string.encode('UTF-8')
            save_txt(utf8_string, 'resource-question-label.txt')
