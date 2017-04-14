/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    var clinicien = false;
    var neuropathologiste = false;
    var checkedVals = $(':checkbox:checked').map(function () {
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
        $('#User_address').val('');
        $('#address').hide();
    }
    if (neuropathologiste) {
        $('#centre').show();
    }
    else {
        $('#User_centre').val('');
        $('#centre').hide();
    }
    if (clinicien && neuropathologiste) {
        $('#address').show();
        $('#centre').show();
    }
    if (!clinicien && !neuropathologiste) {
        $('#User_address').val('');
        $('#User_centre').val('');
        $('#address').hide();
        $('#centre').hide();
    }
});

function getProfil() {
    var clinicien = false;
    var neuropathologiste = false;
    var checkedVals = $(':checkbox:checked').map(function () {
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
        $('#User_address').val('');
        $('#address').hide();
    }
    if (neuropathologiste) {
        $('#centre').show();
    }
    else {
        $('#User_centre').val('');
        $('#centre').hide();
    }
    if (clinicien && neuropathologiste) {
        $('#address').show();
        $('#centre').show();
    }
    if (!clinicien && !neuropathologiste) {
        $('#User_address').val('');
        $('#User_centre').val('');
        $('#address').hide();
        $('#centre').hide();
    }
}