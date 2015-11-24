<?php

/**
 * classe pour envoyer des mails via yii avec template de mise en forme.
 * @author nmalservet
 *
 */
class CommonMailer
{

    /**
     * "send" an email. To do it, store an email into db and a crontask will pull emails to send them.
     * the crontask will be executed using the command line yiic sendmail.
     * @param unknown $to
     * @param unknown $subject
     * @param unknown $body
     */
    public static function sendMail($to, $subject, $body) {
        $mailq = new mailqueue ();
        try {

            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
            $headers .= 'From: ' . CommonProperties::$SMTP_SENDER_FROM_EMAIL . "\r\n" . 'Reply-To: ' . CommonProperties::$SMTP_SENDER_FROM_EMAIL . "\r\n" . 'X-Mailer: PHP/' . phpversion();
            $mailq->emailto = $to;
            $subject = "$subject";
            $mailq->subject = $subject;
            $mailq->body = $body;
            $mailq->headers = $headers;
            if (!$mailq->validate())
                Yii::log("pb sur validation mail", "error");

            return $mailq->save();
        } catch (Exception $e) {
            Yii::log("exception sur save mail" . print_r($mailq->errors), "error");
        }
    }

    /**
     * envoi de mail inscription avec infos de connexion.
     */
    public static function sendConfirmationAdminProfilUser($user) {
        $to = Yii::app()->params['adminEmail'];
        $subject = "Mise à jour d'un profil utilisateur sur cbsdplatform";
        $userDetails = '';
        foreach ($user->getAttributes() as $label => $value) {
            if ($label == "profil") {
                $userDetails.="<li>" . $label . " : " . implode(", ", $value) . "</li>";
            } else
                $userDetails.="<li>$label : $value</li>";
        }
        $body = "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd\">
                <?xml version=\"1.0\" encoding=\"utf-8\"?>
                <html><head>
                <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">    <title>Mise à jour d'un profil utilisateur sur cbsdplatform</title>
                </head><body>" .
                ucfirst($user->prenom) . " " . strtoupper($user->nom) . " a mis à jour son profil (clinicien).<br>
                        Détails :<br>
    <ul>$userDetails</ul><br>
             Cordialement,<br>
                    L'équipe cbsdplatform
    </body>
        ";
        return CommonMailer::sendMail($to, $subject, $body);
    }

    /**
     * envoi de mail inscription avec infos de connexion.
     */

    /**
     *
     * @param type $to
     * @param type $identifiant
     * @param type $prenom
     * @param type $nom
     * @param type $pass
     */
    function sendMailInscriptionUser($to, $identifiant, $prenom, $nom, $pass) {
        $subject = "Bienvenue sur cbsdplatform !";
        $body = "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd\"><html><head>
                <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">    <title>Message de cbsdplatform.fr</title>
                </head><body>
                <table style=\"font-family:Verdana,sans-serif; font-size:11px; color:#374953; width: 550px;\">
                <tr>
                <td align=\"left\">Bonjour <strong style=\"color:#DB3484;\">" . $prenom . " " . $nom . "</strong>,</td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr>
                        <td align=\"left\" style=\"background-color:#DB3484; color:#FFF; font-size: 12px; font-weight:bold; padding: 0.5em 1em;\">

                        Merci d'avoir cr&eacute;&eacute; un compte sur cbsdplatform. Voici un rappel de vos codes d'acc&egrave;s:</td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr>
                        <td align=\"left\">
                        Nom d'utilisateur : <strong><span style=\"color:#DB3484;\">" . $identifiant . "</span></strong>
                                <br >Mot de passe : <strong>" . $pass . "</strong>
                                        </td>
                                        </tr>
                                        <tr><td>&nbsp;</td></tr>
                                        <tr>
                                        <td align=\"left\">
                                        Vous pouvez d&egrave;s &agrave; pr&eacute;sent utiliser notre site internet <a href=\"" . Yii::app()->getBaseUrl(true) . "\">cbsdplatform</a>.
                                                         <br>Cordialement,<br>
                            L'équipe cbsdplatform
                                        </td>
                                        </tr>
                                        <tr><td>&nbsp;</td></tr>
                                        <tr>
                                        <td align=\"center\" style=\"font-size:10px; border-top: 1px solid #D9DADE;\">
                                        <a href=\"" . Yii::app()->getBaseUrl(true) . "\" style=\"color:#DB3484; font-weight:bold; text-decoration:none;\">
                                cbsdplatform </a> - Copyright Biobanques 2015

                                </td>
                                </tr>
                                </table>
                                </body>
                                </html>";

        CommonMailer::sendMail($to, $subject, $body);
    }

    /**
     * envoi de mail inscription avec le nouveau profil .
     */
    public static function sendMailConfirmationProfilEmail($user, $profil, $complement) {
        $to = Yii::app()->params['adminEmail'];
        $params = array('arg1' => $user->_id, 'arg2' => $profil);
        if ($complement != NULL) {
            $params['arg3'] = $complement;
            $urlConfirm = Yii::app()->createAbsoluteUrl('site/confirmUser', $params);
        } else {
            $urlConfirm = Yii::app()->createAbsoluteUrl('site/confirmUser', $params);
        }
        $urlRefuse = Yii::app()->createAbsoluteUrl('site/refuseUser', $params);
        $subject = "Inscription d'un nouveau profil pour un utilisateur sur cbsdplatform.";
        $userDetails = '';
        foreach ($user->getAttributes() as $label => $value) {
            if ($label == "profil") {
                $userDetails.="<li>" . $label . " : " . $profil . "</li>";
            } else
                $userDetails.="<li>$label : $value</li>";
        }
        $body = "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd\">
                <?xml version=\"1.0\" encoding=\"utf-8\"?>
                <html><head>
                <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">    <title>Confirmation profil de cbsdplatform</title>
                </head><body>
                <table style=\"font-family:Verdana,sans-serif; font-size:11px; color:#374953; width: 550px;\">
                <tr><td>&nbsp;</td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr>
                        <td align=\"left\" style=\"background-color:#DB3484; color:#FFF; font-size: 12px; font-weight:bold; padding: 0.5em 1em;\">"
                . ucfirst($user->prenom) . " " . strtoupper($user->nom) . " s'est inscrit sur le site " . Yii::app()->getBaseUrl(true) . ".<br>
Détails :<br>
    <ul>$userDetails</ul><br>
Cliquez sur le lien ci-dessous ou copier l'adresse dans votre navigateur afin de finaliser la proc&eacute;dure de confirmation:
                        </td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr>
                        <td align=\"left\">
                        <a href=\"" . $urlConfirm . "\">" . $urlConfirm . "</a>

                                </td>
                                </tr>
                                <tr><td>&nbsp;</td></tr>
                                <td align=\"left\">
                                Vous pouvez refuser cet utilisateur en cliquant sur ce lien : <a href=\"" . Yii::app()->getBaseUrl(true) . "/index.php?r=site/refuseUser&arg1=" . $user->_id . "&arg2=" . $profil . "\">Refuser l'utilisateur</a>
                                    <td>
                                <tr>
                                <td align=\"left\">
                                Rendez-vous sur notre site internet <a href=\"" . Yii::app()->getBaseUrl(true) . "\">cbsdplatform</a>.
                                </td>
                                </tr>
                                <tr><td>&nbsp;</td></tr>
                                <tr>
                                <td align=\"center\" style=\"font-size:10px; border-top: 1px solid #D9DADE;\">
                                <a href=\"" . Yii::app()->getBaseUrl(true) . "\" style=\"color:#DB3484; font-weight:bold; text-decoration:none;\">
                                cbsdplatform </a> - Copyright Biobanques 2015

                                </td>
                                </tr>
                                </table>
                                </body>
                                </html>";

        CommonMailer::sendMail($to, $subject, $body);
    }

    /**
     * send an email to indicate to the admin that there is a new user to confirm
     * @param type $user
     * @return type
     */
    public static function sendSubscribeAdminMail($user) {
        $to = Yii::app()->params['adminEmail'];
        $subject = "Inscription d'un nouvel utilisateur sur cbsdplatform";
        $userDetails = '';
        foreach ($user->getAttributes() as $label => $value) {
            if ($label == "profil") {
                $userDetails.="<li>" . $label . " : " . implode(", ", $value) . "</li>";
            } else
                $userDetails.="<li>$label : $value</li>";
        }
        $body = "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd\">
                <?xml version=\"1.0\" encoding=\"utf-8\"?>
                <html><head>
                <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">    <title>Inscription d'un nouvel utilisateur sur cbsdplatform</title>
                </head><body>" .
                ucfirst($user->prenom) . " " . strtoupper($user->nom) . " s'est inscrit sur le site " . Yii::app()->getBaseUrl(true) . ".<br>
                        Détails :<br>
    <ul>$userDetails</ul><br>
             Cordialement,<br>
                    L'équipe cbsdplatform
    </body>
        ";
        return CommonMailer::sendMail($to, $subject, $body);
    }

    /**
     * send an email to confirm that the subscritption is valid and the account waiting for validatin by admin
     * @param type $user
     * @return type
     */
    public static function sendSubscribeUserMail($user) {
        $to = $user->email;
        $subject = "Bienvenue sur cbsdplatform " . ucfirst($user->prenom) . " " . strtoupper($user->nom);
        $body = "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd\">
                <?xml version=\"1.0\" encoding=\"utf-8\"?>
                <html><head>
                <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
                                <title>Bienvenue " . ucfirst($user->prenom) . " " . strtoupper($user->nom) . " sur cbsdplatform</title>
                </head><body>
                                Bienvenue " . ucfirst($user->prenom) . " " . strtoupper($user->nom) . " sur cbsdplatform.<br>
            Votre compte est en attente de confirmation par l'administrateur de cbsdplatform.<br>
                        Si vous rencontrez des problèmes sur cbsdplatform n'hésitez pas à envoyer un email à " . Yii::app()->params['adminEmail'] . "<br>
                            Cordialement,
                            L'équipe cbsdplatform
    </body>
        ";
        return CommonMailer::sendMail($to, $subject, $body);
    }

    /**
     * send an email to confirm that the account is valid.
     * @param type $user
     * @return type
     */
    public static function sendUserRegisterConfirmationMail($user) {
        $to = $user->email;
        $subject = "Confirmation de votre inscription sur cbsdplatform";
        $body = "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd\">
        <?xml version=\"1.0\" encoding=\"utf-8\"?>
        <html><head>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">    <title>Confirmation de votre inscription sur cbsdplatform</title>
        </head><body>
        Cher (Chère) " . ucfirst($user->prenom) . " " . strtoupper($user->nom) . "<br><br>
        Merci de vous être inscrit sur le site <a href=\"" . Yii::app()->getBaseUrl(true) . "\">cbsdplatform</a>.<br>
        Vous pouvez vous connecter dès à présent sur le site avec vos identifiants : <br>
        <ul><li>Nom d'utilisateur : $user->login </li>
        <li>Mot de passe : $user->password </li></ul>
                    Cordialement,<br>
                    L'équipe cbsdplatform
        </body>
        ";
        return CommonMailer::sendMail($to, $subject, $body);
    }

    public static function sendUserRegisterRefusedMail($user) {
        $to = $user->email;
        $subject = "Refus de votre inscription sur cbsdplatform";
        $body = "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd\">
        <?xml version=\"1.0\" encoding=\"utf-8\"?>
        <html><head>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">    <title>Refus de votre inscription sur cbsdplatform</title>
        </head><body>
        Cher (Chère) " . ucfirst($user->prenom) . " " . strtoupper($user->nom) . ",<br><br>
        Merci de vous être intéressé à la plate-forme <a href=\"" . Yii::app()->getBaseUrl(true) . "\">cbsdplatform</a>.<br>
        Malheureusement, nous ne pouvons donner suite à votre inscription.<br>
                Pour toute question, merci de contacter l'administrateur de la plate-forme.<br><br>
                Cordialement,<br>
                L'équipe cbsdplatform
        </body>
        ";
        return CommonMailer::sendMail($to, $subject, $body);
    }

    /**
     * send a email to recover the password
     * @param type $user
     * @return true if it s sent ( stored in db then pull by the cron task)
     */
    public static function sendMailRecoverPassword($user) {
        try {
            if ($user != null)
                $to = $user->email;
            $fname = ucfirst($user->prenom);
            $lname = strtoupper($user->nom);
            $login = $user->login;
            $password = $user->password;
            $subject = "Informations perdues sur cbsdplatform.fr";
            $body = "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd\">
        <?xml version=\"1.0\" encoding=\"utf-8\"?>
        <html><head>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">    <title>Vos exports sur cbsdplatform.fr</title>
        </head><body>
        Cher (Chère) $fname $lname,<br><br>
        Suite à votre demande effectuée sur le site cbsdplatform, nous vous rappelons vos codes d'accès :<br>
                Identifiant : $login<br>
                Password : $password <br>
                Vous pouvez dès à présent vous connecter avec ces identifiants.<br>
A bientôt sur cbsdplatform.
        </body>
        ";
            return CommonMailer::sendMail($to, $subject, $body);
        } catch (Exception $e) {
            Yii::log("exception sur save mail", "error");
            return false;
        }
    }

}
?>