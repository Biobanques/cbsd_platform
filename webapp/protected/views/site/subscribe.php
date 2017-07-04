<?php

/* @var $this SiteController */
/* @var $model User */

if (isset($_SESSION['profil'])) {
    echo "<h1>" . Yii::t('common', 'subscribeAs') . Yii::t('profile', $_SESSION['profil']) . "</h1>";
} else {
    echo "<h1>" . Yii::t('button', 'subscribe') . "</h1>";
}

if ($model->isNewRecord) {
    echo $this->renderPartial('_subscribeForm', array('model' => $model, 'profil' => $_SESSION['profil']));
} else {
    echo $this->renderPartial('_updateSubscribeForm', array('model' => $model));
}