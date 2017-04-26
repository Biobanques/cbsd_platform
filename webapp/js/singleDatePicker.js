function singleDatePicker(clicked) {
    $('input[name="' + clicked + '"]').daterangepicker({
        singleDatePicker: true,
        autoUpdateInput: false,
        showDropdowns: true,
        locale: {
            format: "DD/MM/YYYY",
            applyLabel: 'Valider',
            cancelLabel: 'Effacer'
        }
    });
    $('input[name="' + clicked + '"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY'));
    });
}