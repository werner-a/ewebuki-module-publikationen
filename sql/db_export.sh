#!/bin/sh
pg_dump --username=ewebuki --table=db_publikationen_id_seq --table=db_publikationen -xO --column-inserts --inserts energieatlas > db_publikationen.dump
psql --username=ewebuki --dbname=energieatlas -c "copy(select * from site_text where label like 'pb_%')TO STDOUT" > site_text.csv
