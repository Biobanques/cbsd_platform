<?php

/**
 * unit test class to test CommonTools
 * @author nmalservet
 *
 */
class CommonToolsTest extends PHPUnit_Framework_TestCase {

    /**
     * testing method to check if the dev mode detector is ok
     */
    public function testIsInDevMode() {
        $this->assertTrue(CommonTools::isInDevMode());
    }

    /**
     * testing method to check if patient is found and return an object
     */
    public function testWsGetPatient() {
        $patient = $this->patient();
        $this->assertInternalType('string', CommonTools::wsGetPatient($patient));
        // TODO : test exception
    }
    
    /**
     * testing method to check if function return an object
     */
    public function testWsAddPatient() {
        $patient = $this->patient();
        $this->assertInternalType('string', CommonTools::wsAddPatient($patient));
        // TODO : test exception
    }
    
    public function patient() {
        $patient = (object) null;
        $patient->id = null;
        $patient->source = null; //à identifier en fonction de l'app
        $patient->sourceId = null;
        $patient->birthName = "Dale2";
        $patient->useName = "Dale";
        $patient->firstName = "Kareem";
        $patient->birthDate = "01/01/1970";
        $patient->sex = "U";
        return $patient;
    }

}

?>
