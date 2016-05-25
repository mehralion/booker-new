<?php
namespace common\helpers;
/**
 * Created by JetBrains PhpStorm.
 * User: Николай
 * Date: 28.08.13
 * Time: 16:23
 * To change this template use File | Settings | File Templates.
 */

class StringHelper
{
    public static function substr($string, $length = 300, $after='...')
    {
        if(mb_strlen($string)>$length)
        {
            return mb_substr($string, 0, $length).$after;
        }
        return $string;
    }

    public static function transliterate($string)
    {
        $table = ['А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO',
            'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'N' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'CSH', 'Ь' => '', 'Ы' => 'Y', 'Ъ' => '', 'Э' => 'E','Ю' => 'YU', 'Я' => 'YA',

            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
            'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
            'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'csh', 'ь' => '', 'ы' => 'y', 'ъ' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya'];

        $output = str_replace(array_keys($table), array_values($table), $string);

        return $output;
    }

    public static function doDir($string)
    {
        $string = preg_replace('/[^a-zа-я0-9]/ui', '-', $string);

        return strtolower(self::transliterate($string));
    }

    public static function getNumEndingBall($number)
    {
        $endingsArray = ['мяч', 'мяча', 'мячей']; //1 - мяч, 2 - мяча 5 - мячей
        $number = $number % 100;
        if ($number>=11 && $number<=19) {
            $ending = $endingsArray[2];
        } else {
            $i = $number % 10;
            switch ($i)
            {
                case (1): $ending = $endingsArray[0]; break;
                case (2):
                case (3):
                case (4): $ending = $endingsArray[1]; break;
                default: $ending=$endingsArray[2];
            }
        }
        return $ending;
    }

    public static function getNumEndingGoal($number)
    {
        $endingsArray = ['гол', 'гола', 'голов']; //1 - мяч, 2 - мяча 5 - мячей
        $number = $number % 100;
        if ($number>=11 && $number<=19) {
            $ending = $endingsArray[2];
        } else {
            $i = $number % 10;
            switch ($i)
            {
                case (1): $ending = $endingsArray[0]; break;
                case (2):
                case (3):
                case (4): $ending = $endingsArray[1]; break;
                default: $ending=$endingsArray[2];
            }
        }
        return $ending;
    }
}