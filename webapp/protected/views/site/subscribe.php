<?php

/* @var $this SiteController */
/* @var $model User */
?>

<?php

if (isset($_SESSION['profil'])) {
    echo "<h1>" . Yii::t('common', 'subscribe') . " en tant que " . $_SESSION['profil'] . "</h1>";
} else {
    echo "<h1>" . Yii::t('common', 'subscribe') . "</h1>";
}
?>


<?php

if ($model->isNewRecord)
    echo $this->renderPartial('_subscribeForm', array('model' => $model, 'profil' => $_SESSION['profil']));
else
    echo $this->renderPartial('_updateSubscribeForm', array('model' => $model));
?>