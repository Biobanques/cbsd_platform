/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    var clinicien = false;
    var neuropathologiste = false;
    var checkedVals = $('input[type=radio]:checked').map(function () {
        $(this).bind("click", false);
        return this.value;
    }).get();
    for (var i = 0; i < checkedVals.length; i++) {
        if (checkedVals[i] == "Clinicien")
            clinicien = true;
        if (checkedVals[i] == "Neuropathologiste")
            neuropathologiste = true;
    }
    if (clinicien) {
        $('#address').show();
    }
    else {
        $('#address').hide();
    }
    if (neuropathologiste) {
        $('#centre').show();
    }
    else {
        $('#centre').hide();
    }
});
function getProfil() {
    var clinicien = false;
    var neuropathologiste = false;
    var checkedVals = $('input[type=radio]:checked').map(function () {
        return this.value;
    }).get();
    for (var i = 0; i < checkedVals.length; i++) {
        if (checkedVals[i] == "Clinicien")
            clinicien = true;
        if (checkedVals[i] == "Neuropathologiste")
            neuropathologiste = true;
    }
    if (clinicien) {
        $('#address').show();
    }
    else {
        $('#address').hide();
    }
    if (neuropathologiste) {
        $('#centre').show();
    }
    else {
        $('#centre').hide();
    }
}