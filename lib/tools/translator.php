<?php

final class translator
{
    private static $translations;

    public static function init(array $Array)
    {
        self::$translations = $Array;
    }

    public static function get()
    {
        if (self::check_arguments(func_num_args())) {
            return self::getTranslation(func_get_args());
        }

        return '';
    }

    public static function getFormattedString()
    {
        if (self::check_arguments(func_num_args())) {
            return call_user_func_array('sprintf', self::getArrayForSprintf(func_get_args()));
        }

        return '';
    }

    private static function check_arguments($functionArgumentsCount)
    {
        if ($functionArgumentsCount == 0) {
            return false;
        } elseif (is_null(self::$translations)) {
            throw new Exception('Translator was not initialized');
        }

        return true;
    }

    private static function getArrayForSprintf($functionArguments)
    {
        $args = array_pop($functionArguments);
        $sprintf_array = array();
        $sprintf_array[] = self::getTranslation($functionArguments);
        if (is_array($args)) {
            foreach ($args as $arg) {
                $sprintf_array[] = $arg;
            }
        }

        return $sprintf_array;
    }

    private static function getTranslation($functionArguments)
    {
        $currentArguments = $functionArguments;
        $translation = self::$translations;
        while (count($currentArguments)) {
            $key = array_shift($currentArguments);
            if (is_string($key) && array_key_exists($key, $translation)) {
                $translation = $translation[$key];
            } else {
                return implode(' ', $functionArguments);
            }
        }

        return $translation;
    }
}
function tr()
{
    return call_user_func_array(array('Translator', 'get'), func_get_args());
}

function ftr()
{
    return call_user_func_array(array('Translator', 'getFormattedString'), func_get_args());
}
