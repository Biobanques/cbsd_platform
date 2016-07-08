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
        $patient = (object) null;
        $patient->id = null;
        $patient->source = null; //à identifier en fonction de l'app
        $patient->sourceId = null;
        $patient->birthName = "Dale2";
        $patient->useName = "Dale";
        $patient->firstName = "Kareem";
        $patient->birthDate = "01/01/1970";
        $patient->sex = "U";
        $this->assertInternalType('object', $patient);

    }
    
    /**
     * testing method to check if function return an object
     */
    public function testWsAddPatient() {
        $patient = (object) null;
        $patient->id = null;
        $patient->source = null; //à identifier en fonction de l'app
        $patient->sourceId = null;
        $patient->birthName = "Dupont";
        $patient->useName = "Dupont";
        $patient->firstName = "Robert";
        $patient->birthDate = "06/04/1958";
        $patient->sex = "M";
        $this->assertInternalType('object', $patient);
    }

}

?>
