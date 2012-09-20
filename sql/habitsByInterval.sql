SELECT
	round_seconds(time,30) AS interval,
	COUNT(*) AS tot,
	COUNT(NULLIF(c5nn='P',FALSE)) AS P, 
	COUNT(NULLIF(c5nn='B',FALSE)) AS B,
	COUNT(NULLIF(c5nn='C',FALSE)) AS C,
	COUNT(NULLIF(c5nn='I',FALSE)) AS I,
        COUNT(NULLIF(c5nn='R',FALSE)) AS R, 
        COUNT(NULLIF(c5nn='RA',FALSE)) AS RA,
        COUNT(NULLIF(c5nn='PA',FALSE)) AS PA,
        COUNT(NULLIF(c5nn='CA',FALSE)) AS CA,
	AVG(dmax)::real AS dmax_mean,
	(sum(ar*area(ar,dmax))/sum(area(ar,dmax)))::real AS ar_weighted_mean
FROM kide
WHERE dmax>100
AND time BETWEEN '01-01-2000 00:00:00' AND '01-01-2012 00:00:00'
GROUP BY interval
ORDER BY interval
;
