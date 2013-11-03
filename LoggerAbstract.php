<?php

abstract class LoggerAbstract
{
    const LOG       = 0;
    const INFO      = 1;
    const WARN      = 2;
    const ERR       = 4;
    const EXCEPTION = 8;
    const CRIT      = 16;

    protected $_iMinLogLevel    = NULL;
    protected $_iLogLevel       = NULL;
    protected $_mMessage        = NULL;

//    protected $_sFormatString = '%timestamp% [%priorityName%] (%priority%): %message%';
    protected $_sFormatString = '%-26s : %s %s';

    abstract public function write($mMessage, $iLogLevel);

    public function __construct($iMinLogLevel = NULL)
    {
        if (NULL !== $iMinLogLevel) {
            $this->setMinLogLevel($iMinLogLevel);
        }
    }

    public function setMinLogLevel($iMinLogLevel)
    {
        $this->_iMinLogLevel = $iMinLogLevel;
        return $this;
    }

    public function getMinLogLevel()
    {
        return $this->_iMinLogLevel;
    }

    public function setLogLevel($iLogLevel)
    {
        $this->_iLogLevel = $iLogLevel;
        return $this;
    }

    public function getLogLevel()
    {
        return $this->_iLogLevel;
    }

    public function setMessage($mMessage)
    {
        $this->_mMessage = $mMessage;
        return $this;
    }

    public function getMessage()
    {
        return $this->_mMessage;
    }

    public function update(ObservableInterface $oLogger)
    {
        if (!$oLogger instanceof LoggerInterface) {
            return false;
        }

        if ($oLogger->getLogLevel() >= $this->getMinLogLevel()) {
            $this->write($oLogger->getMessage(), $oLogger->getLogLevel());
        }
        return $this;
    }

    public function convertLogLevelToName()
    {
        $r = new ReflectionClass(get_class($this));
        $aConstantNames = array_flip($r->getConstants());
        $sLogLevelName = $aConstantNames[$this->getLogLevel()];

        return $sLogLevelName;
    }

    public function printf_array($sFormatString, $aArray)
    {
        return call_user_func_array('printf', array_merge((array) $sFormatString, $aArray));
    }

    public function getArrayContent($aContent)
    {
        ob_start();
        var_dump($aContent);
        $sContent = ob_get_contents();
        ob_end_clean();
        return $sContent;
    }

    public function getObjectContent($oContent)
    {
        ob_start();
        var_dump($oContent);
        $sContent = ob_get_contents();
        ob_end_clean();
        return $sContent;
    }

    public function getFormatString()
    {
        return $this->_sFormatString;
    }

    public function setFormatString($sFormatString)
    {
        $this->_sFormatString = $sFormatString;
        return $this;
    }

    protected function _useFormatString()
    {
        if (0 == strlen($this->getFormatString())) {
            $this->setFormatString('%s - %s');
        }
        return $this->getFormatString();
    }
}
