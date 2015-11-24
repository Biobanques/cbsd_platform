<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    ));
    ?>
    <div class="row">
        <div class="col-lg-6">
            <?php echo $form->label($model, 'type'); ?>
            <?php echo $form->textField($model, 'type'); ?>
        </div>

        <div class="col-lg-6">
            <?php echo $form->label($model, 'login'); ?>
            <?php echo $form->textField($model, 'login'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <?php echo $form->label($model, 'id_patient'); ?>
            <?php echo $form->textField($model, 'id_patient'); ?>
        </div>

        <div class="col-lg-6">
            <?php echo $form->label($model, 'name'); ?>
            <?php echo $form->textField($model, 'name'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <?php echo $form->label($model, 'last_modified'); ?>
            <?php echo $form->textField($model, 'last_modified'); ?>
        </div>

        <div class="col-lg-6">
            <?php echo $form->label($model, 'last_updated'); ?>
            <?php echo $form->textField($model, 'last_updated'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php echo CHtml::label('Ajouter une question', 'question'); ?>
            <?php echo CHtml::dropDownList('question', 'addQuestion', Answer::model()->getAllQuestions(), array('prompt' => '----')); ?>
            <?php
            echo CHtml::ajaxSubmitButton(
                'Ajouter', Yii::app()->createUrl('site/index'),
                array(
                    'type' => 'POST',
                    //'success' => 'js:function(data){alert(data);}',
                    'success' => 'js:function(){var e = document.getElementById("question");
var strUser = e.options[e.selectedIndex].value; alert(strUser);}',
                    'error'=>'js:function(data){alert("comment NOT Submitted");}', 
                )
            );
            ?>

        </div>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Rechercher', array('name' => 'rechercher')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->