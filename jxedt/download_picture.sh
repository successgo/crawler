#!/usr/bin/env bash
#

for i in `cat imageurl.txt`; do
    wget -P images -c $i
done
