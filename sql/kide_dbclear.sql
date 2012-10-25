DROP TABLE IF EXISTS manual_classification CASCADE;

DROP TABLE IF EXISTS kide CASCADE;

DROP TABLE IF EXISTS users CASCADE;

DROP TYPE IF EXISTS nn CASCADE;

DROP TYPE IF EXISTS nwnn CASCADE;

DROP TYPE IF EXISTS bayesian CASCADE;

DROP FUNCTION IF EXISTS round_minutes(timestamp without time zone, integer);

DROP FUNCTION IF EXISTS round_seconds(timestamp without time zone, integer);

DROP FUNCTION IF EXISTS area(real, real);
