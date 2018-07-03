<?php

class AreaDrop {
    private $chart_boundaries = [
        'high' => 70,
        'low' => 20,
    ];
    private $point_size = 1;
    private $chart;
    private $chart_size;
    private $colors;

    public function __construct($width, $height, $line_color, $fill_color) {
        $this->chart_size = [
            'width' => $width,
            'height' => $height,
        ];
        $this->chart = imagecreatetruecolor($width, $height);
        imagesavealpha($this->chart, true);

        $chart_bg = imagecolorallocatealpha($this->chart, 255, 255, 255, 127);
        imagefill($this->chart, 0, 0, $chart_bg);
        $this->colors = [
            'line' => imagecolorallocatealpha($this->chart, $line_color[0], $line_color[1], $line_color[2], $line_color[3]),
            'fill' => imagecolorallocatealpha($this->chart, $fill_color[0], $fill_color[1], $fill_color[2], $fill_color[3]),
        ];
    }

    public function plot($values) {
        $point_count = count($values);
        $min_value = min($values);
        $variance = (max($values) - $min_value);
        $min_height = ($this->chart_size['height'] * ($this->chart_boundaries['low'] / 100));
        $avail_height = (($this->chart_size['height'] * ($this->chart_boundaries['high'] / 100)) - $min_height);
        $point_margin = [
            'x' => ($this->chart_size['width'] / ($point_count - 1)),
            'y' => ($avail_height / $variance),
        ];
        $x_pointer = 0;
        $plots = [0, $this->chart_size['height']];
        foreach($values AS $value) {
            $y_pointer = (($this->chart_size['height'] - $min_height) - (($value - $min_value) * $point_margin['y']));
            $plots[] = $x_pointer;
            $plots[] = $y_pointer;
            
            if(count($plots) > 4) {
                $previous_x = $plots[ (count($plots) - 4) ];
                $previous_y = $plots[ (count($plots) - 3) ];
                imageline($this->chart, $previous_x, $previous_y, $x_pointer, $y_pointer, $this->colors['line']);
            }
            $x_pointer += $point_margin['x'];
        }
        $plots[] = $this->chart_size['width'];
        $plots[] = $this->chart_size['height'];
        imagefilledpolygon($this->chart, $plots, ($point_count + 2), $this->colors['fill']);
    }

    public function save($path = false) {
        if($path) imagepng($this->chart, $path, 9);
        else imagepng($this->chart, NULL, 9);
        imagedestroy($this->chart);
    }
}