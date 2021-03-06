<?php

/**
 * classe pour envoyer les mails en queue.
 * La commande a executer et a mettre dans les cron task est :
 * ${PATH_TO_PROJECT}/protected/yiic sendmail
 * exemple POUR AUTOMATISER CET ENVOI :
 * >crontab -e
 * >* * * * * /Volumes/antares/NetBeansProjects/cbsd_platform/webapp/protected/yiic sendmail
 * //ancienne methode longue avec php car block le thread courant si serveur smtp ne repond pas : 
 * mail($model->emailto, $model->subject, $model->body,$model->headers);
 * @author nicolas
 *
 */
class SendMailCommand extends CConsoleCommand
{

    public function run($args)
    {
        Yii::import('application.extensions.phpmailer.JPhpMailer');

        if (Yii::app()->params['mailSystemActif'] == true) {
            $models = mailqueue::model()->findAll();
            foreach ($models as $model) {
                $mail = new JPhpMailer;
                $mail->IsSMTP();
                $mail->Host = CommonProperties::$SMTP_SENDER_HOST;
                $mail->SMTPAuth = true;
                $mail->Port = CommonProperties::$SMTP_SENDER_PORT;
                $mail->Username = CommonProperties::$SMTP_SENDER_USERNAME;
                $mail->Password = CommonProperties::$SMTP_SENDER_PASSWORD;
                $mail->SetFrom(CommonProperties::$SMTP_SENDER_FROM_EMAIL, 'cbsd_platform');
                $mail->Subject = $model->subject;
                $mail->AltBody = $model->body;
                $mail->MsgHTML($model->body);
                $mail->AddAddress($model->emailto, $model->emailto);
                $mail->CharSet = 'UTF-8';
                if ($mail->Send()) {
                    $model->delete();
                } else {
                    print_r($mail->ErrorInfo);
                }
            }
        } else {
            echo 'Le système d\'envoi de mail n\'est pas activé';
        }
    }
}