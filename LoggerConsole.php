<?php

class LoggerConsole extends LoggerAbstract implements LoggerInterface, ObserverInterface
{
    protected $_aForegroundColors = array(
        'black'         => '0;30',
        'dark_gray'     => '1;30',
        'blue'          => '0;34',
        'light_blue'    => '1;34',
        'green'         => '0;32',
        'light_green'   => '1;32',
        'cyan'          => '0;36',
        'light_cyan'    => '1;36',
        'red'           => '0;31',
        'light_red'     => '1;31',
        'purple'        => '0;35',
        'light_purple'  => '1;35',
        'brown'         => '0;33',
        'yellow'        => '1;33',
        'light_gray'    => '0;37',
        'white'         => '1;37',
        'black_u'       => '4;30',   // underlined
        'red_u'         => '4;31',
        'green_u'       => '4;32',
        'yellow_u'      => '4;33',
        'blue_u'        => '4;34',
        'purple_u'      => '4;35',
        'cyan_u'        => '4;36',
        'white_u'       => '4;37'
    );
    protected $_aBackgroundColors = array(
        'black'         => '40',
        'red'           => '41',
        'green'         => '42',
        'yellow'        => '43',
        'blue'          => '44',
        'magenta'       => '45',
        'cyan'          => '46',
        'light_gray'    => '47'
    );

    protected $_sOutputString = NULL;

    protected $_aColors = array(
        self::ERR   => '[0;31m',
        self::WARN  => '[1;32m'
    );

    public function update(ObservableInterface $oLogger)
    {
        if (!$oLogger instanceof LoggerInterface) {
            return false;
        }

        if ($oLogger->getLogLevel() >= $this->_iMinLogLevel) {
            $this->write($oLogger->getMessage(), $oLogger->getLogLevel());
        }
        return $this;
    }

    protected function _set($foreground, $background = null)
    {
        if (isset(self::$_aForegroundColors[$foreground])) {
            echo "\033[" . self::$_aForegroundColors[$foreground] . "m";
        }
        if (isset(self::$_aBackgroundColors[$background])) {
            echo "\033[" . self::$_aBackgroundColors[$background] . "m";
        }
    }

    protected function _reset()
    {
        echo "\033[0m";
    }

    protected function _bold()
    {
        echo "\033[1m";
    }

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
        $sColor = $this->getColorForLogLevel();
        $sLogLevelName = $this->convertLogLevelToName();

        $sOutputString = sprintf(chr(27) . $sColor . $this->_useFormatString(), date('Y-m-d H:i:s') .
            ' [' . $sLogLevelName . ']', __METHOD__ . ' - ' . $this->getMessage() . chr(27) . '[0m', PHP_EOL);

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

    public function getColorForLogLevel()
    {
        $sColor = '';
        if (NULL !== $this->getLogLevel()
            && isset($this->_aColors[$this->getLogLevel()])) {
            $sColor = $this->_aColors[$this->getLogLevel()];
        }
        return $sColor;
    }
}
