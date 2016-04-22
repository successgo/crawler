#!/usr/bin/python
# -*- coding: utf-8 -*-

import requests
import MySQLdb as mdb


def mkreq (url, ctype, stype):
    
    headers = {'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36'}
    url = baseurl + '?' + 'ctype=' + str(ctype) + '&stype=' + str(stype)
    n = []
    r = requests.get(url, headers)
    if 200 == r.status_code:
        j = r.json()
        for i in range(0, len(j)):
            j[i].pop('Id')
            j[i]['ctype'] = ctype
            j[i]['stype'] = stype
            for k in j[i]:
                if (j[i][k] == None):
                    j[i][k] = 'None'
            n.insert(0, tuple(j[i].values()))
        return n
    else:
        return []

def connect_mysql():
    h = 'localhost'
    u = 'root'
    p = 'root'
    db = 'xihaxueche'
    con = mdb.connect(h, u, p, db)
    return con

def insert_questions(questions):
    query = """ INSERT INTO cs_exams_2 (
                imageurl,question,ctype,an4,SpeId,explain,an1,an2,an3,answertrue,chapterid,type,stype ) 
                VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %d, %d, %s)
            """
    con = mdb.connect(host='localhost', 
                      user='root', 
                      passwd='root', 
                      db='dax',
                      charset='utf8',
                      use_unicode=True)
    cursor = con.cursor()
    try:
        cursor.executemany(query, questions)
        con.commit()
    #except:
        #con.rollback()
    finally:
        cursor.close()
        con.close()

baseurl = 'http://121.41.53.108:88/api/Questions/GetAllSubjectByCarType'
res = mkreq(baseurl, 'C1', '1')
print(res)
insert_questions(res)
