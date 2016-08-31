<?php

/*
 * CommonProperties to store variables used for connexion etc.
 * Save this file as CommonProperties.php to use it in the application.
 * @author nicolas malservet
 * @since version 1.0
 */

class CommonProperties
{
    /*
     * DEV MODE : true or false.
     * if true activate some refrences to the server to localhost and send mails to the from mail ( admin mail)
     */
    public static $DEV_MODE = true;
    /*
     * connection string used in ./protected/config/main_dev.php
     */
    public static $CONNECTION_STRING = 'mongodb://qfuseradmin:bbanques2015@localhost/qualityformsdb';
    
    /**
     * string that contains the name of the mongo database
     * @var type
     */
    public static $DBNAME = 'cbsdplatformdb';
    /**
     * Mail system active: true if you want to send email.
     */
    public static $MAIL_SYSTEM_ACTIVE = false;
    /**
     * true if you want to send an email to signal biobank to apply their export.
     *
     */
    public static $MAIL_RELANCE_EXPORT = false;
    /*
     * Admin email to send mails in case of errors or news.
     */
    public static $ADMIN_EMAIL = 'contact@ebiobanques.fr';
    /*
     * SMTP Sender. Allow the script sendmailcommand to send mails via smtp with autentication
     */
    public static $SMTP_SENDER_HOST = '';
    public static $SMTP_SENDER_PORT = '';
    public static $SMTP_SENDER_USERNAME = '';
    public static $SMTP_SENDER_PASSWORD = '';
    public static $SMTP_SENDER_FROM_EMAIL = '';
    
    /*
     * PATH data FileMaker XML
     */
    public static $IMPORT_FOLDER_NOMINATIF = 'files/nominatif/';
    public static $IMPORT_FOLDER_ANONYME = 'files/anonyme/';


    /*
     * SIP web service info
     */
    public static $SIP_WSDL = '';
    public static $SIP_LOGIN = '';
    public static $SIP_PASSWORD = '';
    
}