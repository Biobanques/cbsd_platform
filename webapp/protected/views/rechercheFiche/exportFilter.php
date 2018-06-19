<style>
    /* CSS REQUIRED */
    .state-icon {
        left: -5px;
    }
    .list-group-item-primary {
        color: rgb(255, 255, 255);
        background-color: rgb(66, 139, 202);
    }

    /* DEMO ONLY - REMOVES UNWANTED MARGIN */
    .well .list-group {
        margin-bottom: 0px;
    }
</style>
<script>
    $(function () {
        $('.list-group.checked-list-box .list-group-item').each(function () {

            // Settings
            var $widget = $(this),
                    $checkbox = $('<input type="checkbox" class="hidden" />'),
                    color = ($widget.data('color') ? $widget.data('color') : "primary"),
                    style = ($widget.data('style') == "button" ? "btn-" : "list-group-item-"),
                    settings = {
                        on: {
                            icon: 'glyphicon glyphicon-check'
                        },
                        off: {
                            icon: 'glyphicon glyphicon-unchecked'
                        }
                    };

            $widget.css('cursor', 'pointer')
            $widget.append($checkbox);

            // Event Handlers
            $widget.on('click', function () {
                $checkbox.prop('checked', !$checkbox.is(':checked'));
                $checkbox.triggerHandler('change');
                updateDisplay();
            });
            $checkbox.on('change', function () {
                updateDisplay();
            });


            // Actions
            function updateDisplay() {
                var isChecked = $checkbox.is(':checked');

                // Set the button's state
                $widget.data('state', (isChecked) ? "on" : "off");

                // Set the button's icon
                $widget.find('.state-icon')
                        .removeClass()
                        .addClass('state-icon ' + settings[$widget.data('state')].icon);

                // Update the button's color
                if (isChecked) {
                    $widget.addClass(style + color + ' active');
                } else {
                    $widget.removeClass(style + color + ' active');
                }
            }

            // Initialization
            function init() {

                if ($widget.data('checked') == true) {
                    $checkbox.prop('checked', !$checkbox.is(':checked'));
                }

                updateDisplay();

                // Inject the icon if applicable
                if ($widget.find('.state-icon').length == 0) {
                    $widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span>');
                }
            }
            init();
        });
    });
</script>
<?php
Yii::app()->clientScript->registerScript('searchView', "
$('#select-all').click(function(event) {   
    $(':checkbox').each(function() {
            this.checked = true;                        
        });
});
$(document).on('click', 'span', function () {
    var str = this.id;
    var res = str.replace('_Show', '');
    var resBis = res.replace('_Hide', '');
    if (str.search('_Show') != -1) {
        $('#' + resBis).show();
    }
});
$('#unselect-all').click(function(event) {   
    $(':checkbox').each(function() {
            this.checked = false;                        
        });
})
$('#check-list-box li').click(function() {
    var str1 = $('#hiddenFields').val();
    if (str1.indexOf($(this).text()) >= 0) {
        str1 = str1.replace('&&' + $(this).text(),'');
        str1 = str1.replace($(this).text(),'');
        var doubleAnd = str1.substring(0,2);
        if (doubleAnd == '&&') {
            str1 = str1.replace('&&','');
        }
        $('#hiddenFields').val(str1);
    } else {
        if (str1 == '') {
            var separator = '';
            } else {
            var separator = '&&';
            }
        var res1 = str1.concat(separator);
        var str2 = $(this).text();
        var res2 = res1.concat(str2);
        $('#hiddenFields').val(res2);
    }
});
");
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
        ));
?>
<?php
if (Yii::app()->user->getState('activeProfil') == "Administrateur de projet") {
    echo CHtml::label(Yii::t('common', 'projectName'), 'project', array('required' => 'required'));
    echo " " . CHtml::textField('project');
}
$labels = Answer::model()->attributeExportedLabels();
$fiches = Answer::model()->getNomsFichesByFilter($_SESSION['models']);

?>
<br>
<div class="row">
    <div class="col-xs-12">
        <h3 class="text-center">Variables communes</h3>
        <div class="well" style="max-height: 300px;overflow: auto;">
            <ul id="check-list-box" class="list-group checked-list-box">
                <?php
                foreach ($labels as $l) {
                    echo "<li class=\"list-group-item\">" . $l . "</li>";
                }
                ?>
            </ul>
            <br />
        </div>
    </div>
    <?php
    foreach ($fiches as $key => $value) {
        $res = Answer::model()->getAllQuestionsByFilterName($_SESSION['models'], $value);
        ?>

    <div class="col-xs-12">
        <h3 class="text-center"><?php echo $value; ?></h3>
        <div class="well" style="max-height: 300px;overflow: auto;">
            <ul id="check-list-box" class="list-group checked-list-box">
                <?php
                foreach ($res as $r) {
                    echo "<li class=\"list-group-item\">" . $r . "</li>";
                }
                ?>
            </ul>
            <br />
        </div>
    </div>
    <?php } ?>

    <input type="hidden" id="hiddenFields" name="hiddenFields"/>
</div>

<div class="row">
    <div class="col-lg-6">
        <?php echo CHtml::submitButton('Exporter', array('name' => 'exporter', 'class' => 'btn btn-primary')); ?>
    </div>
    <div class="col-lg-6">
<?php echo CHtml::submitButton('Exporter les tranches', array('name' => 'exportTranche', 'class' => 'btn btn-primary')); ?>
    </div>
</div>
<?php $this->endWidget(); ?>