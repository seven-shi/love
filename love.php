<?php


$str = [".",":","-","=","+","*","#","%","@"];
//一个普通的爱心
for ($y = 1.5; $y > -1.5; $y -= 0.1) {
	for ($x = -1.5; $x < 1.5; $x += 0.05) {
		$a = $x * $x + $y * $y - 1;
		CLI::echo_colore($a * $a * $a - $x * $x * $y * $y * $y <= 0 ? '*' : ' ','red','yellow');
	}
	echo PHP_EOL;
}

//一个带点花纹的
for ($y = 1.5; $y > -1.5;$y -= 0.1) {
	for ($x = -1.5; $x < 1.5; $x += 0.05) {
		$z = $x * $x + $y * $y - 1;
		$f = $z * $z * $z - $x * $x * $y * $y * $y;
		
		CLI::echo_colore($f <= 0 ? $str[(int)($f * -8)] : ' ','yellow','red');
	}
	echo PHP_EOL;
}


//一个有3d效果的爱心
function f($x, $y, $z) {
    $a = $x * $x + 9 / 4 * $y * $y + $z * $z - 1;
    return $a * $a * $a - $x * $x * $z * $z * $z - 9 / 80 * $y * $y * $z * $z * $z;
}

function h($x, $z) {
    for ($y = 1; $y >= 0; $y -= 0.001)
        if (f($x, $y, $z) <= 0)
            return $y;
    return 0;
}

for ($z = 1.5; $z > -1.5; $z -= 0.05) {
    for ($x = -1.5; $x < 1.5; $x += 0.025) {
        $v = f($x, 0, $z);
        if ($v <= 0) {
            $y0 = h($x, $z);
            $ny = 0.01;
            $nx = h($x + $ny, $z) - $y0;
            $nz = h($x, $z + $ny) - $y0;
            $nd = 1 / sqrt($nx * $nx + $ny * $ny + $nz * $nz);
            $d = ($nx + $ny - $nz) * $nd * 0.5 + 0.5;
            CLI::echo_error($str[(int) ($d * 5)]);
        } else
            echo ' ';
    }
    echo PHP_EOL;
}

class CLI {

    /**
     * 输出内容
     * 因为cmd 编码为 gb2312 所以输出的中文必须要转码
     * @param string message 要输出的内容
     * @param string br 换行符
     * @return string
     */
    static public function echos($message, $br = false) {
        $br = $br?PHP_EOL:'';
        switch (true) {
            case stristr(PHP_OS, 'WIN'): $t = eval('return ' . mb_convert_encoding(var_export($message, true), 'gb2312', 'utf-8') . ';');
                break;
            case stristr(PHP_OS, 'DAR'): $t = eval('return ' . mb_convert_encoding(var_export($message, true), 'utf-8', 'auto') . ';');
                break;
            case stristr(PHP_OS, 'LINUX'): $t = eval('return ' . mb_convert_encoding(var_export($message, true), 'utf-8', 'auto') . ';');
                break;
            default : $t = eval('return ' . mb_convert_encoding(var_export($message, true), 'utf-8', 'auto') . ';');
        }
        print_r($t);
        echo $br;
    }

    /**
     * 输入信息
     * @param  string  message 输入的信息
     * @return string 返回输入的信息
     */
    static public function scanf($message = false) {
        $message and self::echos($message, '');
        return trim(fgets(STDIN));
    }

    /**
     * 输出警告
     * @return string 返回输入的信息
     */
    static public function echo_warning($message) {
        $str = self::getColoredString($message, "yellow", "black");
        self::echos($str);
    }

    /**
     * 输出系统消息
     */
    static public function echo_system($message) {
        $str = self::getColoredString($message, "light_green", "black");
        self::echos($str);
    }

    /**
     * 输出系统消息
     */
    static public function echo_error($message) {
        $str = self::getColoredString($message, "red", "black");
        self::echos($str);
    }

    /**
     * 输出带颜色的字体
     */
    static public function echo_colore($message, $foreground_colors, $background_colors = 'black') {
        $str = self::getColoredString($message, $foreground_colors, $background_colors);
        self::echos($str);
    }

    /**
     * 返回字体颜色
     */
    static public function get_foreground_Colors() {
        return array_keys(self::foreground_colors);
    }

    /**
     * 返回字体背景颜色
     */
    static public function get_background_Colors() {
        return array_keys(self::background_colors);
    }

    /**
     * 设置字体颜色
     */
    static public function getColoredString($string, $foreground_color = null, $background_color = null) {
        if (is_array($string)) {
            $string = var_export($string, true);
        }
        if (stristr(PHP_OS, 'WIN')) { //如果是windows系统 就直接返回
            return $string;
        }
        $colored_string = "";
        // 设置前景色
        $colored_string .= "\033[" . self::foreground_colors($foreground_color) . "m";
        // 设置背景色
        $colored_string .= "\033[" . self::background_colors($background_color) . "m";

        // 还原颜色
        $colored_string .= $string . "\033[0m";

        return $colored_string;
    }

    /**
     * 配置前景色
     * @
     */
    static protected function foreground_colors($colore = 'black') {
        $foreground_colors = array(
            'black' => '0;30', //黑色的
            'dark_gray' => '1;30', //深灰色
            'blue' => '0;34', //蓝色的
            'light_blue' => '1;34', //浅蓝色
            'green' => '0;32', //绿
            'light_green' => '1;32', //浅绿色
            'cyan' => '0;36', //青色的
            'light_cyan' => '1;36', //淡青色
            'red' => '0;31', //红色的
            'light_red' => '1;31', //淡红色的
            'purple' => '0;35', //紫色的
            'light_purple' => '1;35', //淡紫色的
            'brown' => '0;33', //棕色的
            'yellow' => '1;33', //黄色的
            'light_gray' => '0;37', //浅灰色
            'white' => '1;37'//白色的
        );

        if ('all' == $colore)
            return $foreground_colors;
        if (isset($foreground_colors[$colore])) {
            return $foreground_colors[$colore];
        }
        return $foreground_colors['black'];
    }

    /**
     * 配置背景色
     */
    static protected function background_colors($colore = 'black') {
        $background_colors = array(
            'black' => '40', //黑色的
            'red' => '41', //红色的
            'green' => '42', //绿
            'yellow' => '43', //黄色的
            'blue' => '44', //蓝色的
            'magenta' => '45', //红色
            'cyan' => '46', //青色
            'light_gray' => '47'//灰色
        );
        if ('all' == $colore)
            return $background_colors;
        if (isset($background_colors[$colore])) {
            return $background_colors[$colore];
        }
        return $background_colors['black'];
    }

}
