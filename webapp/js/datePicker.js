function datePicker(clicked) {
    $('input[name="' + clicked + '"]').daterangepicker({
        singleDatePicker: true,
        autoUpdateInput: false,
        applyClass: "btn-primary",
        showDropdowns: true,
        minDate: "10/10/1900",
        maxDate: "31/12/2999",
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
        $(this).val(picker.startDate.format('DD/MM/YYYY'));
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