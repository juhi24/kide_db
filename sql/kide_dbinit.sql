CREATE TABLE users (
    username varchar(30) PRIMARY KEY,
    password varchar(30) NOT NULL,
    email varchar(50) NOT NULL
);

CREATE TABLE kide (
    fname varchar(50) PRIMARY KEY,
    "time" timestamp without time zone NOT NULL,
    dmax real NOT NULL,
    ar real NOT NULL,
    CHECK (ar > 0 AND ar < 1.1),
    ar_noholes real NOT NULL,
    CHECK (ar_noholes > 0 AND ar_noholes < 1.1),
    asprat real NOT NULL,
    n_corners integer,
    site varchar(3) REFERENCES ARM_site
);

CREATE TABLE project (
    id SERIAL PRIMARY KEY,
    shortname varchar(10),
    description varchar(240)
);

CREATE TABLE kide_project (
    kide varchar(50) REFERENCES kide ON DELETE CASCADE,
    project INTEGER REFERENCES project ON DELETE CASCADE,
    PRIMARY KEY (kide,project)
);

CREATE TABLE PCA_classification (
    kide varchar(50) REFERENCES kide(fname) ON DELETE CASCADE,
    pca_class varchar(2) REFERENCES habits(id),
    pca_method varchar(5) REFERENCES PCA_method(id),
    PRIMARY KEY (kide,pca_method)
);

CREATE TABLE PCA_method (
    id varchar(5) PRIMARY KEY,
    fullname varchar(30) NOT NULL,
    mtype varchar(5) NOT NULL
);

CREATE TABLE flags (
    kide varchar(50) REFERENCES kide(fname) ON DELETE CASCADE,
    flag varchar(15),
    PRIMARY KEY (kide,flag)
);

CREATE TABLE man_classification (
    kide varchar(50) REFERENCES kide ON DELETE CASCADE,
    class1 varchar(2) NOT NULL REFERENCES habits(id),
    class2 varchar(2) REFERENCES habits(id),
    classified_by varchar(30) REFERENCES users(username) ON DELETE CASCADE,
    quality boolean,
    PRIMARY KEY (kide,classified_by)
);

CREATE TABLE habits (
    id varchar(2) PRIMARY KEY,
    fullname varchar(30) NOT NULL
);

CREATE TABLE ARM_site (
    id varchar(3) PRIMARY KEY,
    fullname varchar(50)
);

CREATE OR REPLACE FUNCTION round_minutes(timestamp without time zone, integer DEFAULT 1) RETURNS timestamp without time zone
    AS $$ SELECT date_trunc('hour', $1) + cast(($2::varchar||' min') AS interval) * round(date_part('minute',$1)::float / cast($2 AS float)) $$
    LANGUAGE SQL
    IMMUTABLE;

CREATE OR REPLACE FUNCTION round_seconds(timestamp without time zone, integer DEFAULT 1) RETURNS timestamp without time zone
    AS $$ SELECT date_trunc('minute', $1) + cast(($2::varchar||' sec') AS interval) * round(date_part('second',$1)::float / cast($2 AS float)) $$
    LANGUAGE SQL
    IMMUTABLE;

CREATE OR REPLACE FUNCTION area(real, real) RETURNS double precision
    AS $$ SELECT $1 * pi() * 0.25 * $2 ^ 2 $$
    LANGUAGE SQL
    IMMUTABLE
    RETURNS NULL ON NULL INPUT;
