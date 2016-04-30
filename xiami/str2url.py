#!/usr/bin/env python
# str2url.py

def str2url(s):
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

xiami = '6hAFlm%%35%__%h38E%dc846Ent%mei22%553l3_Dfb5%dd5%-ut25..FF2%E3.FkecbE5c155%lpF.xc31F288mae9216E877E5l%%fio837F12puy9%e995-5%E32iam3839113t%95d79e135-'
r = str2url(xiami)
print r
