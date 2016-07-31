<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 7/31/2016
 * Time: 6:41 PM
 */

namespace Kanboard\Helper;


class TextPersonalisationHelper
{

    private $shortcodes;

    public function setShortcodes($shortcodes)
    {
        $this->shortcodes = $shortcodes;
    }

    public function exchangeShortcodes($text)
    {
        foreach(array_keys($this->shortcodes) as $shortcode)
        {
            $text = str_replace('['.$shortcode.']',$this->shortcodes[$shortcode],$text);
        }

        return $text;
    }

}