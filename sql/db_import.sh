#!/bin/sh
psql --username=ewebuki -f db_publikationen.dump energieatlas
psql --username=ewebuki --dbname=energieatlas -c "delete from site_text where label like 'pb_%';copy site_text FROM STDIN" < site_text.csv
