<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CommonTools
{
    /*
     * FORMAT DATE
     */
    const MYSQL_DATE_FORMAT = "Y-m-d H:i:s";
    const MYSQL_DATE_DAY_FORMAT = "Y-m-d 00:00:00";
    const FRENCH_DATE_FORMAT = "H:i:s d/m/Y";
    const FRENCH_SHORT_DATE_FORMAT = "d/m/Y";
    const ENGLISH_SHORT_DATE_FORMAT = "Y-m-d";
    const FRENCH_HD_DATE_FORMAT = "d/m/Y H:i";
    const ENGLISH_HD_DATE_FORMAT = "Y-m-d H:i";
    const HOUR_DATE_FORMAT = "H:i";

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
     * get number of days from register date
     * @return dateNow - registerDate
     */
    public function fromRegisterDateToNow() {
        $dateNow = date(CommonTools::FRENCH_SHORT_DATE_FORMAT);
        $registerDate = User::model()->getRegisterDate();
        list($jour1, $mois1, $annee1) = explode('/', $dateNow);
        list($jour2, $mois2, $annee2) = explode('/', $registerDate);
        $timestamp1 = mktime(0,0,0,$mois1,$jour1,$annee1);
        $timestamp2 = mktime(0,0,0,$mois2,$jour2,$annee2);
        return round(abs($timestamp2 - $timestamp1)/86400, 0, PHP_ROUND_HALF_DOWN);
    }

    /**
     * format date d/m/Y
     * @return type
     */
    public function formatDateFR($date) {
        return date(CommonTools::FRENCH_SHORT_DATE_FORMAT, strtotime($date));
    }
    
    public function formatDateAndTimeFR($date) {
        return date(CommonTools::FRENCH_HD_DATE_FORMAT, strtotime($date));
    }
    
    /**
     * convert DatePickerRange to an array
     * @return type
     */
    public function formatDatePicker($date) {
        $res = array();
        $answerDate = explode("-", str_replace(' ', '', $date));
        $res['date_from'] = date(CommonTools::ENGLISH_SHORT_DATE_FORMAT, strtotime(str_replace('/', '-', $answerDate[0])));
        $res['date_to'] = date(CommonTools::ENGLISH_SHORT_DATE_FORMAT, strtotime(str_replace('/', '-', $answerDate[1])));
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