<h3 align="center"><?php echo Yii::t('common', 'htmlViewForm') . $model->name; ?></h3>
<?php echo CHtml::link(Yii::t('common', 'standardView'), array('rechercheFiche/view', 'id' => $model->_id)); ?>
<div style="margin-top: -20px; text-align:right;">
    <?php
    $img = CHtml::image(Yii::app()->request->baseUrl . '/images/page_white_acrobat.png', Yii::t('common', 'exportPdf'));
    echo CHtml::link(Yii::t('common', 'exportPdf') . $img, array('answer/exportPDF', 'id' => $model->_id), array());
    ?>
</div>
<?php echo $model->renderHTML(Yii::app()->language); ?>
