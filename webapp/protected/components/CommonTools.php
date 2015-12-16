<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CommonTools
{

    public function wsGetPatient($patient) {
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

    public function wsAddPatient($patient) {
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

    public static function isInDevMode() {
        return CommonProperties::$DEV_MODE;
    }

}
?>