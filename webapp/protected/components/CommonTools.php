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
//                return $soapClient->getIdWs($token, $patient);
            } catch (Exception $ex) {
                Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
                Yii::log($ex->getTraceAsString(), CLogger::LEVEL_ERROR);

                return -1;
            }
        } catch (Exception $ex) {
            return -1;
        }
    }

    public static function isInDevMode() {
        return CommonProperties::$DEV_MODE;
    }

}
?>