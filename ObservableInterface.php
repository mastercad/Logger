<?php

interface ObservableInterface
{
    public function attach(ObserverInterface $oObserver);
    public function detach(ObserverInterface $oObserver);
    public function notify();
}