DROP TABLE IF EXISTS man_classification CASCADE;

DROP TABLE IF EXISTS kide CASCADE;

DROP TABLE IF EXISTS users CASCADE;

DROP TABLE IF EXISTS filter_flag CASCADE;

DROP TABLE IF EXISTS PCA_classification CASCADE;

DROP TABLE IF EXISTS PCA_method CASCADE;

DROP TABLE IF EXISTS classes CASCADE;

DROP TABLE IF EXISTS ARM_site CASCADE;

DROP FUNCTION IF EXISTS round_minutes(timestamp without time zone, integer);

DROP FUNCTION IF EXISTS round_seconds(timestamp without time zone, integer);

DROP FUNCTION IF EXISTS area(real, real);
