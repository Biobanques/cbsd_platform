/**
*
* Script de test
*/

$(document).ready(function () {
    // Create
    $("input:radio").each(function () {
        var name = $(this).attr("name");
        var donnees = name.match(/\[(.*?)\]/ig);
        var d = donnees[0].replace(/[[\]]/g,'');
        var mutation = d.replace("analyse", "mutation");
        $('input[type=radio][name="Questionnaire['+d+']"]:nth(1)').val("Non").attr("checked","checked");
        $('#'+mutation+'').val("Pas de mutation");
        document.getElementById(''+mutation+'').disabled = true;
        $('#questionnaire-form').change(function(){
            if ($('input[type=radio][name="Questionnaire['+d+']"]:nth(1):checked', '#questionnaire-form').val() == "Non") {
            $('#'+mutation+'').val("Pas de mutation");
            document.getElementById(''+mutation+'').disabled = true;
        } else {
            document.getElementById(''+mutation+'').disabled = false;
        }
        });
    });
});