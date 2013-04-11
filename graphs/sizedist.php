<?php
session_start();
require_once 'phplot.php';

$plot = new PHPlot();
$plot->SetImageBorderType('plain');

$plot->SetPlotType('stackedbars');
$plot->SetShading(0);
$plot->SetDataType('text-data');
$plot->SetDataValues($_SESSION['sizedistplot']);

$plot->SetTitle('Particle size distribution');

$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');

$plot->SetXLabel('size (um)');
$plot->SetYLabel('particle count');
$plot->SetLegend($_SESSION['classes']);

$plot->SetPlotAreaWorld(NULL, 0, NULL, NULL);
$plot->SetYTickIncrement(10);

$plot->DrawGraph();
?>
