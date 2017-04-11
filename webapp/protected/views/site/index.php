<?php
/* @var $this SiteController */
$this->pageTitle = Yii::app()->name;
echo $_SESSION["patientBirthDate"] . "<br>";
if (strpos($_SESSION["patientBirthDate"], '/')) {
                    $birthdateFormat = explode('/', $_SESSION["patientBirthDate"]);
                } else {
                    $birthdateFormat = explode('-', $_SESSION["patientBirthDate"]);
                }
                var_dump($birthdateFormat);
                $dateNow = explode('/', date(CommonTools::FRENCH_SHORT_DATE_FORMAT));
                if (($birthdateFormat[1] < $dateNow[1]) || (($birthdateFormat[1] == $dateNow[1]) && ($birthdateFormat[0] <= $dateNow[0]))) {
                    $valueInput = $dateNow[2] - $birthdateFormat[2];
                } else {
                    $valueInput = $dateNow[2] - $birthdateFormat[2] - 1;
                }
?>

<div class="jumbotron">
<div class="container">    
    
      <h1><?php echo Yii::t('common', 'welcomeTo') . CHtml::encode(Yii::app()->name); ?></h1>
      <p><?php echo Yii::t('common', 'cbsdDescription') ?></p>
    </div>
</div>
