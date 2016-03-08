/**
 *
 * Script de test
 */

$(document).ready(function () {
    $("input:radio").each(function () {
        var name = $(this).attr("name");
        var donnees = name.match(/\[(.*?)\]/ig);
        var d = donnees[0].replace(/[[\]]/g, '');
        var mutation = d.replace("analyse", "mutation");
        if ($('input[type=radio][name="Questionnaire[' + d + ']"]:nth(1):checked').val() == "Non") {
            $('#' + mutation + '').val("Pas de mutation");
            document.getElementById('' + mutation + '').disabled = true;
        } else if ((name.indexOf("gene") != -1) && $('input[type=radio][name="Questionnaire[' + d + ']"]:checked').length == 0) {
            $('input[type=radio][name="Questionnaire[' + d + ']"][value=Non]').attr('checked', true);
            $('#' + mutation + '').val("Pas de mutation");
            document.getElementById('' + mutation + '').disabled = true;
        } else {
            document.getElementById('' + mutation + '').disabled = false;
        }
        $('#questionnaire-form').change(function () {
            if ($('input[type=radio][name="Questionnaire[' + d + ']"]:nth(1):checked').val() == "Non") {
                $('#' + mutation + '').val("Pas de mutation");
                document.getElementById('' + mutation + '').disabled = true;
            } else {
                document.getElementById('' + mutation + '').disabled = false;
            }
        });
    });
});

$('input:radio').change(function () {
    var name = $(this).attr("name");
    var donnees = name.match(/\[(.*?)\]/ig);
    var d = donnees[0].replace(/[[\]]/g, '');
    var mutation = d.replace("analyse", "mutation");
    if (this.value == "Non") {
        $('#' + mutation + '').val("Pas de mutation");
        document.getElementById('' + mutation + '').disabled = true;
    } else {
        $('#' + mutation + '').val("");
        document.getElementById('' + mutation + '').disabled = false;
    }
});