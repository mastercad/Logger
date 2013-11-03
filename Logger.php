<?php

if (!class_exists('LoggerAbstract')) {
    require_once 'LoggerAbstract.php';
}

if (!class_exists('LoggerInterface')) {
    require_once 'LoggerInterface.php';
}

if (!class_exists('ObserverInterface')) {
    require_once 'ObserverInterface.php';
}

if (!class_exists('ObservableInterface')) {
    require_once 'ObservableInterface.php';
}

class Logger extends LoggerAbstract implements LoggerInterface, ObservableInterface
{
    protected $_aLoggerInstances = array();

    public function __construct()
    {

    }

    public function write($mMessage, $iLogLevel)
    {
        $this->setMessage($mMessage)
            ->setLogLevel($iLogLevel)
            ->notify();

        return $this;
    }

    public function attach(ObserverInterface $oLogger)
    {
        $this->_aLoggerInstances[] = $oLogger;
        return $this;
    }

    public function detach(ObserverInterface $oLogger)
    {
        $this->_aLoggerInstances = array_diff($this->_aLoggerInstances, array($oLogger));
        return $this;
    }

    public function notify()
    {
        foreach ($this->_aLoggerInstances as $oLoggerInstance) {
            $oLoggerInstance->update($this);
        }
        return $this;
    }
}