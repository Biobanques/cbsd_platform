$(function () {
    navigator.userLanguage = 'fr';

    if ($.fn.themeswitcher) {
        $('#switcher')
                .css('padding-bottom', '8px')
                .before('<h4>Use the themeroller to dynamically change look and feel</h4>')
                .themeswitcher({imgpath: "images/"});
    }


    var defaultOptions = {
        //availableListPosition: 'bottom',
        moveEffect: 'blind',
        moveEffectOptions: {direction: 'vertical'},
        moveEffectSpeed: 'fast'
    };

    var widgets = {
        'simple': $.extend($.extend({}, defaultOptions), {
            sortMethod: 'standard',
            sortable: true
        }),
        'disabled': $.extend({}, defaultOptions),
        'groups': $.extend($.extend({}, defaultOptions), {
            sortMethod: 'standard',
            showEmptyGroups: true,
            sortable: true
        }),
        'dynamic': $.extend({}, defaultOptions)
    };

    $.each(widgets, function (k, i) {
        $('#multiselect_' + k).multiselect(i).on('multiselectChange', function (evt, ui) {
            var values = $.map(ui.optionElements, function (opt) {
                 return $(opt).attr('value');
            }).join(', ');
            var attr = $(this).attr("id");
            var valuesFormatted = values.replace('=', '');
            var valuesFormatted = valuesFormatted.replace('?', 'question_mark');
            if (ui.selected) {
                $('#' + attr + '_selection').append('<i id="' + valuesFormatted.replace(/ /g,'') + '">[' + values + ']</i> ');
                $('#' + attr + '_selection2').append('<i id="' + valuesFormatted.replace(/ /g,'') + '">[' + values + ']</i> ');
            } else {
                $('#' + valuesFormatted.replace(/ /g,'')).remove();
            }
        }).on('multiselectSearch', function (evt, ui) {
            $('#debug_' + k).prepend($('<div></div>').text('Multiselect beforesearch event! searching for "' + ui.term + '"'));
        }).closest('form').submit(function (evt) {
            return false;
        });

        $('#btnToggleOriginal_' + k).click(function () {
            var _m = $('#multiselect_' + k);
            if (_m.is(':visible')) {
                _m.next().toggle().end().toggleClass('uix-multiselect-original').multiselect('refresh');
            } else {
                _m.toggleClass('uix-multiselect-original').next().toggle();
            }
            return false;
        });
        $('#btnSearch_' + k).click(function () {
            $('#multiselect_' + k).multiselect('search', $('#txtSearch_' + k).val());
        });

    });

    $('#btnGenerate_dynamic').click(function () {
        var start = new Date().getTime();
        var temp = $('<select></select>');
        var count = parseInt($('#txtGenerate_dynamic').val());
        for (var i = 0; i < count; i++) {
            temp.append($('<option></option>').val('item' + (i + 1)).text("Item " + (i + 1)));
        }
        $('#multiselect_dynamic').empty().html(temp.html()).multiselect('refresh', function () {
            var diff = new Date().getTime() - start;
            if (diff > 1000) {
                diff /= 1000;
                if (diff > 60) {
                    diff = (diff / 60) + " min";
                } else {
                    diff += " sec";
                }
            } else {
                diff += " ms";
            }
            $('#debug_dynamic').prepend($('<div></div>').text("Generated " + count + " options in " + diff));
        });
    });



    $('#selectLocale').change(function () {
        $('.multiselect').multiselect('locale', $(this).val());
    });

    // build locale options
    for (var locale in $.uix.multiselect.i18n) {
        $('#selectLocale').append($('<option></option>').attr('value', locale).text(locale.length == 0 ? '(default)' : locale));
    }
    $('#selectLocale').val($('#multiselect').multiselect('locale'));

});