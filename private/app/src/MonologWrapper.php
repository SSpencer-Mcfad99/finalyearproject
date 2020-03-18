<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 04/03/2020
 * Time: 11:47
 */

namespace VotingSystemsTutorial;

    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;

    /**
     * Performs Monolog functions, wrapped in a class and accessed by the application via the application container object
     *
     * Class MonologWrapper
     * @package SecureWebAppCoursework
     */
class MonologWrapper
{
    public function __construct(){}

    public function __destruct(){}

    /**
     * Allows the log type to be set externally, and passed through into the application
     *
     * @param $log_type
     * @return int - returns selected log type in Logger format
     */

    public function setLogType($logType)
    {
        switch ($logType){
            case 'debug':
                return Logger::DEBUG;
                break;
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