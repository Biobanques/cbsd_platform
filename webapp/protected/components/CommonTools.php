<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CommonTools
{

    public function wsGetPatient($patient)
    {
        try {
            $soapClient = new SoapClient(CommonProperties::$SIP_WSDL);
            $token = $soapClient->login(CommonProperties::$SIP_LOGIN, CommonProperties::$SIP_PASSWORD);
            try {
                return $soapClient->getFullPatientWs($token, $patient);
            } catch (Exception $ex) {

                return $ex->faultcode;
            }
        } catch (Exception $ex) {
            return $ex->faultcode;
        }
    }

    public function wsAddPatient($patient)
    {
        try {
            $soapClient = new SoapClient(CommonProperties::$SIP_WSDL);
            $token = $soapClient->login(CommonProperties::$SIP_LOGIN, CommonProperties::$SIP_PASSWORD);
            try {
                return $soapClient->addPatientWs($token, $patient);
            } catch (Exception $ex) {
                return $ex->faultcode;
//                return $ex;
            }
        } catch (Exception $ex) {
            return $ex->faultcode;
        }
    }

    public static function isInDevMode()
    {
        return CommonProperties::$DEV_MODE;
    }
    
    public function isDate($date) {
        if (preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $date, $matches) || preg_match("/([0-9]{2})-([0-9]{2})-([0-9]{4})/", $date, $matches)) {
            if (!checkdate($matches[2], $matches[1], $matches[3])) {
                return false;
            }
        } elseif (preg_match("/([0-9]{4})\/([0-9]{2})\/([0-9]{2})/", $date, $matches) || preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2})/", $date, $matches)) {
            if (!checkdate($matches[2], $matches[3], $matches[1])) {
                return false;
            }
        } else {
            return false;
        }
        return true;
    }
}