<?php

error_reporting(E_ALL|E_STRICT);

require_once 'Logger.php';
require_once 'LoggerConsole.php';
require_once 'LoggerFile.php';
require_once 'LoggerFirebug.php';
require_once 'LoggerMail.php';

$oLogger = new Logger();
$oLoggerConsole = new LoggerConsole();
$oLoggerConsole->setMinLogLevel($oLogger::WARN);
$oLoggerFile = new LoggerFile($oLogger::ERR);
$oLoggerFirebug = new LoggerFirebug($oLogger::INFO);
$oLoggerMail = new LoggerMail($oLogger::CRIT);

$oLogger->attach($oLoggerConsole);
$oLogger->attach($oLoggerFile);
$oLogger->attach($oLoggerFirebug);
$oLogger->attach($oLoggerMail);

$oLogger->write('TEST', $oLoggerConsole::WARN);
$oLogger->write('TEST', $oLoggerConsole::ERR);
