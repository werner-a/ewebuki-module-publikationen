#!/bin/sh
pg_dump --username=ewebuki --table=db_publikationen_id_seq --table=db_publikationen -xO --column-inserts --inserts energieatlas > db_publikationen.dump
