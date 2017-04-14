#!/bin/bash
echo "querying $@ ..."

##part-r-00000 is the inverted index, put it on hdfs
##bin/hdfs dfs -put /user/hadoop04/ input_ii
##generate sub-inverted index
export JAVA_HOME=/usr/java/default/
export HADOOP_HOME=/home/hadoop04/hadoop-dist
bin/hadoop jar ~/wiki_query-0.0.1-SNAPSHOT.jar code.querying.Query1 -Dmapreduce.job.queuename=hadoop04 /user/hadoop04/lt/invert2 output_ii "$@"
##move the result to local directory for use in getdocs
bin/hdfs dfs -get output_ii ~/

#display the results
bin/yarn jar ~/wiki_query-0.0.1-SNAPSHOT.jar code.querying.GetDocs -Dmapreduce.job.queuename=hadoop04 ~/output_ii/part-r-00000 "$@"

bin/hdfs dfs -rmr output_ii
echo "...BEGINNING_OUTPUT..."
cat temp.txt
rm -r ~/output_ii

