function datePicker(clicked) {
    $('input[name="' + clicked + '"]').daterangepicker({
        "applyClass": "btn-primary",
        "showDropdowns": true,
        locale: {
            format: "DD/MM/YYYY",
            applyLabel: 'Valider',
            cancelLabel: 'Effacer'
        }
    });
    $('input[name="' + clicked + '"]').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });
    $('#restrictSearch').show();
    $('#restrictReset').show();
    $('input[name="' + clicked + '"]').on('apply.daterangepicker', function (ev, picker) {
        $('#restrictSearch').show();
        $('#restrictReset').show();
    });
    $('input[name="' + clicked + '"]').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
        if ($('.col-lg-12 :selected').text() == "") {
            $('#restrictSearch').hide();
            $('#restrictReset').hide();
        }
    });
}