<?php

class LoggerFile extends LoggerAbstract implements LoggerInterface, ObserverInterface
{

    public function write($mMsg, $iLogLevel)
    {
        if ($iLogLevel >= $this->getMinLogLevel()) {
            $this->setMessage($mMsg)
                ->setLogLevel($iLogLevel)
                ->_prepareOutput();
            echo $this->getOutputString();
        }
        return $this;
    }

    protected function _prepareOutput()
    {
        if (NULL === $this->getMessage()) {
            $this->setOutputString(NULL);
            return $this;
        }
        $sLogLevelName = $this->convertLogLevelToName();

        $sOutputString = sprintf($this->_useFormatString(), date('Y-m-d H:i:s') .
            ' [' . $sLogLevelName . ']', __METHOD__ . ' - ' . $this->getMessage(), PHP_EOL);

        $this->setOutputString($sOutputString);

        return $this;
    }

    public function setOutputString($sOutputString)
    {
        $this->_sOutputString = $sOutputString;
        return $this;
    }

    public function getOutputString()
    {
        return $this->_sOutputString;
    }
}