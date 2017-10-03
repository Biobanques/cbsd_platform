<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CommonTools {
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
        $timestamp1 = mktime(0, 0, 0, $mois1, $jour1, $annee1);
        $timestamp2 = mktime(0, 0, 0, $mois2, $jour2, $annee2);
        return round(abs($timestamp2 - $timestamp1) / 86400, 0, PHP_ROUND_HALF_DOWN);
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
     * convert Byte > ko, Mo or Go
     * @return type
     */
    public static function formatSizeUnits($fileSize) {
        $result;
        switch (true) {
            case ($fileSize > 1024 * 1024):$result = round($fileSize / (1024 * 1024), 2) . ' Mo';
                break;
            case ($fileSize > 1024):$result = round($fileSize / (1024), 2) . ' ko';
                break;
            default: $result = $fileSize . ' o';
                break;
        }
        return $result;
    }

    /**
     * retourne l'utilisateur connecté
     * @return type
     */
    public function getUserRecorderName() {
        $result = "-";
        $user = User::model()->findByPk(new MongoID(Yii::app()->user->id));
        if ($user != null) {
            $result = ucfirst($user->prenom) . " " . strtoupper($user->nom);
        }
        return $result;
    }
    
    public function getUserLogin() {
        $result = "-";
        $user = User::model()->findByPk(new MongoID(Yii::app()->user->id));
        if ($user != null) {
            $result = $user->login;
        }
        return $result;
    }

    public function array_swap(&$array, $swap_a, $swap_b) {
        list($array[$swap_a], $array[$swap_b]) = array($array[$swap_b], $array[$swap_a]);
    }
    
    public function getAllReferenceCenter() {
        $aRCenter = array();
        $referenceCenter = ReferenceCenter::model()->findAll();
        if ($referenceCenter != null) {
            foreach ($referenceCenter as $center) {
                $aRCenter[$center->center] = $center->center;
            }
        }
        asort($aRCenter, SORT_NATURAL | SORT_FLAG_CASE);
        return $aRCenter;
    }
    
    public function chkIds($checkedIds) {
        if (!isset($_SESSION['checkedIds'])) {
            $_SESSION['checkedIds'] = array();
        }
        $chkArray = explode(",", $checkedIds);
        foreach ($chkArray as $arow) {
            if (!in_array($arow, $_SESSION['checkedIds'])) {
                array_push($_SESSION['checkedIds'], $arow);
            }
            Yii::app()->user->setState($arow, 1);
        }
    }

    public function unckIds($uncheckedIds) {
        if (!isset($_SESSION['uncheckedIds'])) {
            $_SESSION['uncheckedIds'] = array();
        }
        $unchkArray = explode(",", $uncheckedIds);
        foreach ($unchkArray as $arownon) {
            if ((isset($_SESSION['checkedIds']) && $key = array_search($arownon, $_SESSION['checkedIds'])) !== false) {
                unset($_SESSION['checkedIds'][$key]);
                $_SESSION['checkedIds'] = array_values($_SESSION['checkedIds']);
            }
            Yii::app()->user->setState($arownon, 0);
        }
    }
    
    public function setValue($value) {
        return ($value != "" || $value != null) ? $value : null;
    }

}
