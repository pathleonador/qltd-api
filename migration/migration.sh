#!/bin/bash

set -e

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" <<-EOSQL
CREATE SCHEMA exam AUTHORIZATION postgres;

CREATE TABLE exam.article (
	article_id serial NOT NULL,
	article_title varchar(250) NOT NULL,
	article_description text NOT NULL,
	article_created_at timestamptz NOT NULL,
	article_created_by varchar(20) NOT NULL,
	article_updated_at timestamptz NOT NULL,
	article_updated_by varchar(20) NOT NULL,
	article_deleted_at timestamptz NULL,
	article_deleted_by varchar(20) NULL,
	CONSTRAINT article_pk PRIMARY KEY (article_id),
	CONSTRAINT article_title_un UNIQUE (article_title),
	CONSTRAINT article_un UNIQUE (article_id)
);
CREATE INDEX article_article_description_idx ON exam.article USING btree (article_description);
CREATE INDEX article_article_title_idx ON exam.article USING btree (article_title);

ALTER TABLE exam.article OWNER TO postgres;
GRANT ALL ON TABLE exam.article TO postgres;

CREATE TABLE exam.vote (
	vote_id serial NOT NULL,
	vote_user_id varchar(20) NOT NULL,
	vote_article_id int4 NOT NULL,
	vote_casted_vote varchar(10) NOT NULL,
	vote_created_at timestamptz NOT NULL,
	vote_created_by varchar(20) NOT NULL,
	vote_updated_at timestamptz NOT NULL,
	vote_updated_by varchar(20) NOT NULL,
	vote_deleted_at timestamptz NULL,
	vote_deleted_by varchar(20) NULL,
	CONSTRAINT vote_code_un UNIQUE (vote_user_id, vote_article_id),
	CONSTRAINT vote_pk PRIMARY KEY (vote_id)
);


ALTER TABLE exam.vote OWNER TO postgres;
GRANT ALL ON TABLE exam.vote TO postgres;
EOSQL
