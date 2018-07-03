<?php

include('AreaDrop.class.php');

$chart_width = 300; // define chart width
$chart_height = 200; // define chart height
$line_color = [255, 255, 255, 80]; // set RGBA values for line color
$fill_color = [0, 0, 0, 70]; // set RGBA values for fill color
$example_values = [1,3,9,7,5,6,4]; // example vales to be plotted

// create chart image and output to browser
$chart = new AreaDrop($chart_width, $chart_height, $line_color, $fill_color);
$chart->plot($example_values);
header('Content-Type: image/png');
$chart->save();

// store chart image as file
$chart = new AreaDrop($chart_width, $chart_height, $line_color, $fill_color);
$chart->plot($example_values);
$chart->save('/path/to/image.png');