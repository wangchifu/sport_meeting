<?php
//顯示某目錄下的檔案
if (! function_exists('get_files')) {
    function get_files($folder){
        $files = [];
        $i=0;
        if (is_dir($folder)) {
            if ($handle = opendir($folder)) {
                while (false !== ($name = readdir($handle))) {
                    if ($name != "." && $name != "..") {
//去除掉..跟.
                        $files[$i] = $name;
                        $i++;
                    }
                }
                closedir($handle);
            }
        }
        return $files;
    }
}

if(! function_exists('get_date_semester')) {
    function get_date_semester($date)
    {
        $d = explode('-', $date);
        //查目前學期
        $y = (int)$d[0] - 1911;
        $array1 = array(8, 9, 10, 11, 12, 1);
        $array2 = array(2, 3, 4, 5, 6, 7);
        if (in_array($d[1], $array1)) {
            if ($d[1] == 1) {
                $this_semester = ($y - 1) . "1";
            } else {
                $this_semester = $y . "1";
            }
        } else {
            $this_semester = ($y - 1) . "2";
        }

        return $this_semester;

    }
}
