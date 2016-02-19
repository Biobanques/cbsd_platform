/**
*
* Script de test
*/

$(document).ready(function () {
    $( ".target" ).hide();
    if ($('input[name="Questionnaire[gene_analyse1]"]:checked').val() === "Non") {
        $('#gene_mutation1').val("Pas de mutation");
        $('#gene_mutation1').attr("disabled", "disabled");
    }
    if ($('input[name="Questionnaire[gene_analyse2]"]:checked').val() === "Non") {
        $('#gene_mutation2').val("Pas de mutation");
        $('#gene_mutation2').attr("disabled", "disabled");
    }
    if ($('input[name="Questionnaire[gene_analyse3]"]:checked').val() === "Non") {
        $('#gene_mutation3').val("Pas de mutation");
        $('#gene_mutation3').attr("disabled", "disabled");
    }
    if ($('input[name="Questionnaire[gene_analyse4]"]:checked').val() === "Non") {
        $('#gene_mutation4').val("Pas de mutation");
        $('#gene_mutation4').attr("disabled", "disabled");
    }
});


$('input[type=radio][name="Questionnaire[gene_analyse1]"]').change(function () {
    if (this.value === 'Oui') {
        $('#gene_mutation1').val("");
        document.getElementById("gene_mutation1").disabled = false;
    }
    else if (this.value === 'Non') {
        $('#gene_mutation1').val("Pas de mutation");
        document.getElementById("gene_mutation1").disabled = true;
    }
});

$('input[type=radio][name="Questionnaire[gene_analyse2]"]').change(function () {
    if (this.value === 'Oui') {
        $('#gene_mutation2').val("");
        document.getElementById("gene_mutation2").disabled = false;
    }
    else if (this.value === 'Non') {
        $('#gene_mutation2').val("Pas de mutation");
        document.getElementById("gene_mutation2").disabled = true;
    }
});

$('input[type=radio][name="Questionnaire[gene_analyse3]"]').change(function () {
    if (this.value === 'Oui') {
        $('#gene_mutation3').val("");
        document.getElementById("gene_mutation3").disabled = false;
    }
    else if (this.value === 'Non') {
        $('#gene_mutation3').val("Pas de mutation");
        document.getElementById("gene_mutation3").disabled = true;
    }
});

$('input[type=radio][name="Questionnaire[gene_analyse4]"]').change(function () {
    if (this.value === 'Oui') {
        $('#gene_mutation4').val("");
        document.getElementById("gene_mutation4").disabled = false;
    }
    else if (this.value === 'Non') {
        $('#gene_mutation4').val("Pas de mutation");
        document.getElementById("gene_mutation4").disabled = true;
    }
});