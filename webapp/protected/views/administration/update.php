<?php (Yii::app()->language == "fr") ? $plural = "s" : $plural = ""; ?>
<div class="panel panel-primary">
    <div class="panel-heading"><h4 align="center"><?php echo Yii::t('common', 'majProfil') . $model->profil . Yii::t('common', 'forPatientForm') . $model->type . $plural; ?></h4></div>
    <div class="panel-body">
        <?php

            echo $this->renderPartial('_form', array('model' => $model));
        ?>
    </div>
</div>