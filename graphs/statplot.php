<?php

session_start();

require_once 'phplot.php';

$plot = new PHPlot();
$plot->SetImageBorderType('plain');

$plot->SetPlotType('bars');
$plot->SetShading(0);
$plot->SetDataType('text-data');
$plot->SetDataValues($_SESSION['statplot']);

$plot->SetTitle('IC-PCA performance on manually classified data');

$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');

$plot->SetYLabel('%');

$plot->SetPlotAreaWorld(NULL, 0, NULL, 100);
$plot->SetYTickIncrement(10);

$plot->DrawGraph();
?>
