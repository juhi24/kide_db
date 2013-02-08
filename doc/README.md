#User manual
* Test version of the software can be found on http://jussitii.users.cs.helsinki.fi/
* It will soon be moved to http://ice-db.astro.helsinki.fi/

##Account
To request an account, please email jussi.tiira@helsinki.fi

For testing you can use the following login credentials:
* Username: Anonymous
* Password: testi

##Navigation
The site currently consists of three parts:
* CSV-generator
* Manual classification
* User statistics

The navigation links are listed in the page header.

##Usage

###CSV-generator
Here you can download CSV summations of habit distributions classified by IC-PCA. 
You can filter the results by measurement place, time and particle properties.

Example: We want habit distributions of particles larger than 200um from 2010-12-20 on two minute intervals.

1. Navigate to CSV-generator page
1. Set time frame from 2010-12-20 to 2010-12-21
2. Set time resolution to 2 minutes
3. Set maximum diameter between 200um and 9999um
4. Click submit (or hit enter). Your download should start immediately.

###Manual classification
The page will show you CPI images of ice particles and ask you to classify them into one or two of the eight classes 
(see the link to a reference image). You can filter the particles to be classified by 
* measurement place and time (Select dataset)
* particle properties
* IC-PCA classification

Example: Manually classify particles that were automatically classified as column aggregates by IC-PCA.

1. Navigate to manual classification page
2. Click IC-PCA classification to expand the fieldset
3. Choose "column agg." from the drop-down list
4. Classify the particle initially shown on the page using the section labelled "Your classification".
5. Click classify (or hit enter).
6. The next particle shown will match your selection. Repeat steps 4 and 5 to classify more particles that IC-PCA thinks are column aggregates.

###User statistics
On this page you can see how well IC-PCA performed on the particles that you have manually classified. 
In the future the page will allow filtering the results ie. by particle properties.
