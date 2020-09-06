<?php

namespace votingSystemTutorial;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * Performs Monolog functions, wrapped in a class and accessed by the application via the application container object
 *
 * Class MonologWrapper
 * @package votingSystemTutorial
 */
class MonologWrapper
{
    public function __construct(){}

    public function __destruct(){}

    /**
     * Allows the log type to be set externally, and passed through into the application
     *
     * @param $logType
     * @return int - returns selected log type in Logger format
     */

    public function setLogType($logType)
    {
        switch ($logType){
            case 'info':
                return Logger::INFO;
                break;
            case 'warning':
                return Logger::WARNING;
                break;
            case 'error':
                return Logger::ERROR;
                break;
            case 'critical':
                return Logger::CRITICAL;
                break;
            case 'alert':
                return Logger::ALERT;
                break;
            case 'emergency':
                return Logger::EMERGENCY;
                break;
        }
    }

    /**
     * Adds the log message to the log file
     *
     * @param $message
     * @param $logType
     */
    public function addLogMessage($message, $logType)
    {
        $logger = new Logger('VotingSystemsLogger');
        $logger->pushHandler(new StreamHandler(LOG_FILE_LOCATION . LOG_FILE_NAME, $this->setLogType($logType)));
        $logger->$logType($message);
    }
}