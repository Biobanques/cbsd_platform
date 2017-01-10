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
    
    /**
     * convert DatePickerRange to an array
     * @return type
     */
    public function formatDatePicker($date) {
        $res = array();
        $answerDate = explode("-", str_replace(' ', '', $date));
        $res['date_from'] = date('Y-m-d', strtotime(str_replace('/', '-', $answerDate[0])));
        $res['date_to'] = date('Y-m-d', strtotime(str_replace('/', '-', $answerDate[1])));
        return $res;
    }
    
    /**
     * regex for criteria search
     * @return type
     */
    public function regexString($values) {
        $regex = '/';
        foreach ($values as $word) {
            $regex.= $word;
            if ($word != end($values)) {
                $regex.= '|';
            }
        }
        $regex .= '/i';
        return $regex;
    }
    
    /**
     * regex for criteria search
     * @return type
     */
    public function regexNumeric($values) {
        $regex = '/^';
        foreach ($values as $word) {
            $regex.= $word;
            if ($word != end($values)) {
                $regex.= '$|^';
            }
        }
        $regex .= '$/i';
        return $regex;
    }
 
    /**
     * convert Byte > kB, MB or GB
     * @return type
     */    
    public function formatSizeUnits($bytes) {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' kB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }
        return $bytes;
    }
    
}