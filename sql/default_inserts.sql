INSERT INTO classes VALUES
    ('B','bullet'),
    ('P','plate'),
    ('C','column'),
    ('I','irregular'),
    ('PA','plate agg.'),
    ('CA','column agg.'),
    ('R','rosette'),
    ('RA','rosette agg.'),
    ('U','unclassified');

INSERT INTO PCA_method VALUES
    ('nw1nn', 'nearest neighbour', 'nwnn'),
    ('nw3nn', 'no weight 3 nearest neighbours', 'nwnn'),
    ('nw5nn', 'no weight 5 nearest neighbours', 'nwnn'),
    ('c3nn', '3 nearest neighbours', 'nn'),
    ('c5nn', '5 nearest neighbours', 'nn'),
    ('bayes', 'bayesian', 'bayes');

INSERT INTO ARM_site VALUES
    ('SGP', 'Southern Great Plains'),
    ('NSA', 'North Slope of Alaska'),
    ('TWP', 'Tropical Western Pacific'),
    ('AMF', 'ARM Mobile Facility'),
    ('AAF', 'ARM Aerial Facility'),
    ('oth', 'Other');

