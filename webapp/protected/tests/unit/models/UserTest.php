<?php

/**
 * unit test class to test User model
 * @author bernard te
 *
 */

class CommonToolsTest extends PHPUnit_Framework_TestCase {
    
    /**
     * testing method to check if the dev mode detector is ok
     */
    public function testGetArrayCentre() {
        $model = new User;
        $centre = $model->getArrayCentre();
        $this->assertInternalType('array', $centre);
    }
    
}
