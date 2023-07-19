<?php

namespace FailAid\Service;

use Exception;

/**
 * Output class.
 */
class Output
{
    private static $output = [];

    public static function setOptions(array $options)
    {
        self::$output = $options;
    }

    public static function getOptions()
    {
        return self::$output;
    }

    public static function getExceptionDetails(
        $currentUrl,
        $statusCode,
        $featureFile,
        $contextFile,
        $screenshotPath,
        $debugBarDetails,
        $jsErrors,
        $jsLogs,
        $jsWarns,
        $driver,
        $scenario
    ) {
        $message = PHP_EOL . PHP_EOL;
        if (self::getOption('url')) {
            $message .= '[URL] ' . $currentUrl . PHP_EOL;
        }

        if (self::getOption('status')) {
            $message .= '[STATUS] ' . $statusCode . PHP_EOL;
        }

        if (self::getOption('feature')) {
            $message .= '[FEATURE] ' . $featureFile . PHP_EOL;
        }

        if (self::getOption('tags')) {
            $message .= '[TAGS] ' . implode(', ', $scenario->getScenario()->getTags()) . PHP_EOL;
        }

        if (self::getOption('context')) {
            $message .= '[CONTEXT] ' . $contextFile . PHP_EOL;
        }

        if (self::getOption('screenshot')) {
            $message .= '[SCREENSHOT] ' . $screenshotPath . PHP_EOL;
        }

        if (self::getOption('driver')) {
            $message .= '[DRIVER] ' . $driver . PHP_EOL;
        }

        if (self::getOption('rerun')) {
            $message .= '[RERUN] '
                . './vendor/bin/behat '
                . $featureFile
                . ':'
                . $scenario->getScenario()->getLine()
                . PHP_EOL;
        }

        $glue = PHP_EOL . '------' . PHP_EOL;
        if ($jsErrors) {
            $message .= PHP_EOL . '[JSERRORS] ' . implode($glue, $jsErrors) . PHP_EOL;
        }

        if ($jsWarns) {
            $message .= PHP_EOL . '[JSWARNS] ' . implode($glue, $jsWarns) . PHP_EOL;
        }

        if ($jsLogs) {
            $message .= PHP_EOL . '[JSLOGS] ' . implode($glue, $jsLogs) . PHP_EOL;
        }

        if ($debugBarDetails) {
            $message .= PHP_EOL . '[DEBUG BAR INFO]' . PHP_EOL;
            $message .= $debugBarDetails;
        }

        return $message;
    }

    public static function getOption($key)
    {
        if (!isset(self::$output[$key])) {
            throw new Exception("Undefined output option '$key' provided.");
        }

        return self::$output[$key];
    }

    public static function setOption($key, $value)
    {
        self::$output[$key] = $value;
    }

    /**
     * @param string $expected
     * @param string $actual
     * @param string $message
     *
     * @return string
     */
    public static function provideDiff($expected, $actual, $message = null)
    {
        return 'Mismatch: (- expected, + actual)' . PHP_EOL . PHP_EOL .
            '- ' . $expected . PHP_EOL .
            '+ ' . $actual . PHP_EOL . PHP_EOL .
            'Info: ' . $message;
    }
}
