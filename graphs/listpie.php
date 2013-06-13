<?php
session_start();
require_once 'phplot.php';

$plot = new PHPlot(600,400);
$plot->SetImageBorderType('plain');
$plot->SetDataType('text-data-single');
$plot->SetDataValues($_SESSION['listpie']);
$plot->SetPlotType('pie');

$plot->SetShading(0);
$plot->SetTitle('IC-PCA habit distribution');
foreach ($_SESSION['listpie'] as $row) {
    $plot->SetLegend(implode(': ', $row));
}
$plot->SetLegendPixels(2, 2);

$plot->DrawGraph();
?>
