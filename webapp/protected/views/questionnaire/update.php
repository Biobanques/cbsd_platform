<?php

Yii::app()->clientScript->registerScript('create', "

$('#addButton').click(function () {
    nbAleatoire = Math.round(Math.random()*1000);
    var idGroup, wrapper, name1, name2, name3, name4;
    idGroup = getActifOnglet();
    wrapper = $('#'+idGroup+'');
    $(wrapper).append(' \
        <div style=\"\"> \
            <div class=\"question-label\">Nom du gène</div> \
            <div class=\"question-input\"> \
                <input type=\"text\" id=\"' +idGroup+ '_' + 'gene' +nbAleatoire+ '\" name=\"Questionnaire[' +idGroup+ '_' + 'gene' +nbAleatoire+ ']\" /> \
            </div> \
        </div> \
        <div style=\"float:right;\"> \
            <div class=\"question-label\">Analysé</div> \
            <div class=\"question-input\"> \
                <input type=\"radio\" value=\"Oui\" id=\"' +idGroup+ '_' + 'analyse' +nbAleatoire+ '\" name=\"Questionnaire[' +idGroup+ '_' + 'analyse' +nbAleatoire+ ']\" onchange=\"enableMutation(nbAleatoire);\">Oui</input> \
                <input type=\"radio\" value=\"Non\" id=\"' +idGroup+ '_' + 'analyse' +nbAleatoire+ '\" name=\"Questionnaire[' +idGroup+ '_' + 'analyse' +nbAleatoire+ ']\" onchange=\"disableMutation(nbAleatoire);\">Non</input> \
            </div> \
        </div> \
        <div style=\"\"> \
            <div class=\"question-label\">Mutation(s)</div> \
            <div class=\"question-input\"> \
                <input type=\"text\" id=\"' +idGroup+ '_' + 'mutation' +nbAleatoire+ '\" name=\"Questionnaire[' +idGroup+ '_' + 'mutation' +nbAleatoire+ ']\" /> \
            </div> \
        </div> \
        <div style=\"float:right;\"> \
            <div class=\"question-label\">Commentaire</div> \
            <div class=\"question-input\"><input type=\"text\" id=\"' +idGroup+ '_' + 'comment' +nbAleatoire+ '\" name=\"Questionnaire[' +idGroup+ '_' + 'comment' +nbAleatoire+ ']\" /> \
            </div> \
        </div> \
        <div class=\"end-question-group\" /> ');
 	return false;
 });
 
function getActifOnglet () {
    $('.tab-content > div.active').each(function () {
        idGroup = $(this).attr('id');
    });
    return idGroup;
}


");

//widget de popup d'ajout dynamique de champ
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'addFieldPopup',
    // additional javascript options for the dialog plugin
    'options' => array(
        'title' => 'Ajout d\'un gène',
        'autoOpen' => false,
        'width' => '350px'
    ),
));
?>
<div class='wide form'>
    <form id="addField">
        <label for="newFieldName1"> id champ "Nom du gène" </label>
        <input id="newFieldName1" type="text" name="fieldName" required />
        <label for="newFieldName2"> id champ "Analysé" </label>
        <input id="newFieldName2" type="text" name="fieldName" required />
        <label for="newFieldName3"> id champ "Mutation(s)" </label>
        <input id="newFieldName3" type="text" name="fieldName" required />
        <label for="newFieldName4"> id champ "Commentaire" </label>
        <input id="newFieldName4" type="text" name="fieldName" required />
        <br>
        <div style="text-align: center">
            <input type='reset' value='Réinitialiser'/>
            <input type="submit" value="Ajouter"/>
        </div>
    </form>
</div>

<?php
$this->endWidget();
?>

<h4>Patient</h4>
<div class="well">
    <table>
        <tr>
            <td><b>Nom : </b><?php echo $patient->useName; ?></td> 
            <td><b>Nom de naissance : </b><?php echo $patient->birthName; ?></td>
        </tr>
        <tr>
            <td><b>Prénom : </b><?php echo $patient->firstName; ?></td>
            <?php
            if (Yii::app()->user->profil == "administrateur")
                echo "<td><b>Patient ID : </b>" . $patient->id . "</td>";
            ?>
        </tr>
    </table>  
</div>

<hr />

<h3 align="center">Formulaire <?php echo $model->name; ?></h3>
<p><b>Description: </b><?php echo $model->description; ?></p>

<hr />

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-error')); ?>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'questionnaire-form',
        'enableAjaxValidation' => false,
    ));
    ?>
    <br>
    <div>
        <?php
        echo $model->renderTabbedGroup(Yii::app()->language);
        ?>
    </div>
    <div style="display:inline; margin: 35%; width: 100px; ">
        <?php
        echo CHtml::submitButton('Enregistrer', array('class' => 'btn btn-primary', 'style' => 'margin-top:8px;padding-bottom:23px;'));
        echo CHtml::link('Annuler', array('answer/affichepatient', 'id' => $model->_id), array('class' => 'btn btn-primary', 'style' => 'margin-left:20px;'));
        echo CHtml::Button('Ajouter un gène', array('id' => 'addButton', 'class' => 'btn btn-primary', 'style' => 'margin-left:20px;padding-bottom:25px;'));
        ?>
    </div>
    <?php
    $this->endWidget();
    ?>

</div>

<script>
function enableMutation(test) {
    chaine1 = "#gene_mutation";
    chaine2 = test;
    chaine3 = chaine1.concat(chaine2);
    $(chaine3).val("");
    $(chaine3).attr("disabled", false);
}

function disableMutation(test) {
    chaine1 = "#gene_mutation";
    chaine2 = test;
    chaine3 = chaine1.concat(chaine2);
    $(chaine3).val("Pas de mutation");
    $(chaine3).attr("disabled", "disabled");
}
</script>