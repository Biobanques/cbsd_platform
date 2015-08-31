<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CommonTools
{

    public function wsGetPatient($patient) {
        $soapClient = new SoapClient(CommonProperties::$SIP_WSDL);
        $token = $soapClient->login(CommonProperties::SIP_LOGIN, CommonProperties::SIP_LOGIN);
        return $soapClient->getIdWs($token, $patient);
    }

}
?>