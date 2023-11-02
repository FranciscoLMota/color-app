<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

class Color extends Controller
{
    private $red;
    private $green;
    private $blue;

    public function __construct(int $red = 0, int $green = 0, int $blue = 0)
    {
        $minValue = 0;
        $maxValue = 255;

        $this->red = min(max($red, $minValue), $maxValue);
        $this->green = min(max($green, $minValue), $maxValue);;
        $this->blue = min(max($blue, $minValue), $maxValue);;
    }

    /**
     * Randomizes the colors in the instance.
     * @return void
     * @author Francisco Mota <franciscolmota@outlook.com>
     */
    public function randomize()
    {
        // Define the maximum value for a color component (0-255)
        $maxColorComponent = 255;

        // Generate random red, green, and blue components
        $this->red = mt_rand(0, $maxColorComponent);
        $this->green = mt_rand(0, $maxColorComponent);
        $this->blue = mt_rand(0, $maxColorComponent);

        return;
    }

    /**
     * Gets all the information and convertions for the color in the instance.
     * @return void
     * @author Francisco Mota <franciscolmota@outlook.com>
     */
    public function getInfo()
    {
        $info = [
            "hex" => [
                "value" => $this->toHex(),
                "clean" => Str::remove('#', $this->toHex())
            ],
            "hsl" => [
                "value" => $this->toHSL(),
            ],
            "rgb" => [
                "value" => $this->toRGB(),
                "r" => $this->red,
                "g" => $this->green,
                "b" => $this->blue,
            ],
            "websafe" => [
                "value" => $this->toWebSafe(),
            ],
        ];

        return $info;
    }


    /**
     * Returns the hex code of the color currently in the instance.
     * @return string
     * @author Francisco Mota <franciscolmota@outlook.com>
     */
    public function toHex()
    {
        //Convert each component to a two-digit hexadecimal representation
        $redHex = str_pad(dechex($this->red), 2, '0', STR_PAD_LEFT);
        $greenHex = str_pad(dechex($this->green), 2, '0', STR_PAD_LEFT);
        $blueHex = str_pad(dechex($this->blue), 2, '0', STR_PAD_LEFT);

        // Concatenate the components to form the final color code
        $hexColor = '#' . $redHex . $greenHex . $blueHex;

        return Str::upper($hexColor);
    }

    /**
     * Returns the rgb code of the color currently in the instance.
     * @return string
     * @author Francisco Mota <franciscolmota@outlook.com>
     */
    public function toRGB()
    {
        $rbgColor = 'rgb(' . $this->red . ', ' . $this->green . ', ' . $this->blue . ')';

        return $rbgColor;
    }

    /**
     * Returns the HSL (Hue, Saturation%, Lightness%) code of the color currently in the instance.
     * @return string
     * @author Francisco Mota <franciscolmota@outlook.com>
     */
    public function toHSL()
    {
        $r = $this->red;
        $g = $this->green;
        $b = $this->blue;

        // Normalize RGB values to the range [0, 1]
        $r /= 255.0;
        $g /= 255.0;
        $b /= 255.0;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);

        // Calculate lightness (L)
        $l = ($max + $min) / 2.0;

        if ($max == $min) {
            // Achromatic case (no hue)
            $h = $s = 0;
        } else {
            // Calculate saturation (S)
            $d = $max - $min;
            $s = $l > 0.5 ? $d / (2.0 - $max - $min) : $d / ($max + $min);

            // Calculate hue (H)
            if ($max == $r) {
                $h = ($g - $b) / $d + ($g < $b ? 6 : 0);
            } elseif ($max == $g) {
                $h = ($b - $r) / $d + 2;
            } else {
                $h = ($r - $g) / $d + 4;
            }

            $h *= 60; // Convert hue to degrees
        }

        return "hsl(" . round($h) . ', ' . round($s, 4) . "%, " . round($l, 4) . "%)";
    }

    /**
     * Returns the HSL (Hue, Saturation%, Lightness%) code of the color currently in the instance.
     * @return string
     * @author Francisco Mota <franciscolmota@outlook.com>
     */
    public function toWebSafe()
    {
        $webSafeColors = [
            0 => '00',
            51 => '33',
            102 => '66',
            153 => '99',
            204 => 'CC',
            255 => 'FF'
        ];

        //Find the closest value
        $webSafeR = $this->closestValue($this->red, $webSafeColors);
        $webSafeG = $this->closestValue($this->green, $webSafeColors);
        $webSafeB = $this->closestValue($this->blue, $webSafeColors);

        // Return the web-safe hex color
        return '#' . $webSafeR . $webSafeG . $webSafeB;
    }

    private function closestValue($search, $arr)
    {
        $closest = 0;
        $closestValue = "00";
        foreach ($arr as $key => $value) {
            if (abs( (int) $search - (int) $closest) > abs((int) $key - (int) $search)) {
                $closest = $key;
                $closestValue = $value;
            }
        }
        return $closestValue;
    }
}
