<?php
/**
 * unit test class to test CommonMailer
 * @author nmalservet
 *
 */
class CommonMailerTest extends PHPUnit_Framework_TestCase
{
    /**
     * testing method to check if sendMail is correct.
     */
    public function testSendMail() {
        $to = "test@gmail.com";
        $subject = "test send mail from unit test";
        $body = "Have a nice day!";
        $this->assertTrue(CommonMailer::sendMail($to, $subject, $body));
    }
    
    /**
     * testing method to check if sendMail confirmation is correct.
     */
    public function testSendConfirmationAdminProfilUser() {
        $criteria = new EMongoCriteria;
        $criteria->login = "Bernard";
        $user = User::model()->find($criteria);
        $this->assertTrue(CommonMailer::sendConfirmationAdminProfilUser($user));
    }
    
    /**
     * testing method to check if sendMail recover password is correct.
     */
    public function testSendMailRecoverPassword() {
        $criteria = new EMongoCriteria;
        $criteria->login = "Bernard";
        $user = User::model()->find($criteria);
        $this->assertTrue(CommonMailer::sendMailRecoverPassword($user));
    }
    
    public function testSendSubscribeUserMail() {
        $criteria = new EMongoCriteria;
        $criteria->login = "Bernard";
        $user = User::model()->find($criteria);
        $this->assertTrue(CommonMailer::sendSubscribeUserMail($user));
    }
    
    /*public function testSendMailConfirmationProfilEmail(){
        $criteria = new EMongoCriteria;
        $criteria->login = "Bernard";
        $user = User::model()->find($criteria);
        $complement = "neuropathologiste";
        $this->assertTrue(CommonMailer::sendMailConfirmationProfilEmail($user, $user->profil, $complement));        
    }*/
    
    /*public function testSendMailInscriptionUser(){
        $to = "test@gmail.com";
        $identifiant = "JeanDubois";
        $prenom = "Jean";
        $nom = "Dubois";
        $pass = "dubois2016";
        $this->assertTrue(CommonMailer::sendMailInscriptionUser($to, $identifiant, $prenom, $nom, $pass));
    }*/
}
?>