#!/bin/sh
psql --username=phpuser -f db_publikationen.dump energieatlas
psql --username=phpuser --dbname=energieatlas -c "delete from site_text where label like 'pb_%';copy site_text FROM STDIN" < site_text.csv
