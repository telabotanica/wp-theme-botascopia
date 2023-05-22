require('jquery-accessible-accordion-aria/jquery-accessible-accordion-aria.js');
import $ from 'jquery';

$(function () {

    var defaultConfig = {
        headersSelector: '.js-inner-accordion__header',
        panelsSelector: '.js-inner-accordion__panel',
        buttonsSelector: 'button.js-inner-accordion__header',
        buttonsGeneratedContent: 'text',
        button: $('<button></button>', {
            class: 'js-inner-accordion__header',
            type: 'button'
        }),
        buttonSuffixId: '_tab',
        multiselectable: true,
        prefixClass: 'inner-accordion',
        headerSuffixClass: '__title',
        buttonSuffixClass: '__header',
        panelSuffixClass: '__panel',
        direction: 'ltr',
        accordionPrefixId: 'inner-accordion'
    };

    var PLUGIN = 'inner_accordion';

    $.fn[PLUGIN] = function(params) {
        var options = $.extend({}, $.fn[PLUGIN].defaults, params);


        return this.each(function() {
            var $el = $(this);

            var specificOptions = {
                multiselectable: $el.attr('data-inner-accordion-multiselectable') === 'none' ? false : options.multiselectable,
                prefixClass: typeof($el.attr('data-inner-accordion-prefix-classes')) !== 'undefined' ? $el.attr('data-inner-accordion-prefix-classes') : options.prefixClass,
                buttonsGeneratedContent: typeof($el.attr('data-inner-accordion-button-generated-content')) !== 'undefined' ? $el.attr('data-inner-accordion-button-generated-content') : options.buttonsGeneratedContent,
                direction: $el.closest('[dir="rtl"]').length > 0 ? 'rtl' : options.direction
            };
            specificOptions = $.extend({}, options, specificOptions);

            $el.data[PLUGIN] = new Accordion($el, specificOptions);
        });
    };

    $.fn[PLUGIN].defaults = defaultConfig;

    $('.js-inner-accordion').inner_accordion();

/*    function toutDeplier(){
        let status = $('#bouton-toutdeplier').attr('accordion-status');

        if (status == 0){
            $('.js-inner-accordion__header').attr('aria-expanded', 'true');
            $('.js-inner-accordion__panel').attr('aria-hidden', 'false');
            $('#bouton-toutdeplier').attr('accordion-status', '1');
            $('#bouton-toutdeplier span').text('Tout replier');
            $('.formulaire-field-status svg use').attr("xlink:href", "#icon-angle-up");
            // $('.formulaire-field-status svg').toggleClass('icon-angle-down icon-angle-up');

        } else {
            $('.js-inner-accordion__header').attr('aria-expanded', 'false');
            $('.js-inner-accordion__panel').attr('aria-hidden', 'true');
            $('#bouton-toutdeplier').attr('accordion-status', '0');
            $('#bouton-toutdeplier span').text('Tout déplier');
            $('.formulaire-field-status svg use').attr("xlink:href", "#icon-angle-down");
            // $('.formulaire-field-status svg').toggleClass('icon-angle-down icon-angle-up');
        }
        $('#bouton-toutdeplier').toggleClass('outline');
    }

    $('#bouton-toutdeplier').on('click', toutDeplier);

    // Changement de l'icone lorsque l'on deplie/replie un accordion
    $('.js-inner-accordion__header').on('click', function() {
        var accordionId = $(this).closest('.js-inner-accordion').attr('id');
        changerIcone(accordionId);
    });*/

});

/*
function changerIcone(accordionId){
    var use = $("#" + accordionId + " .formulaire-field-status svg use");
    var svg = $("#" + accordionId + " .formulaire-field-status svg");
    var header = $("#" + accordionId + " .js-inner-accordion__header");

    if (header.attr("aria-expanded") === "true"){
        use.attr("xlink:href", "#icon-angle-down");
        svg.toggleClass('icon-angle-down icon-angle-up');
    } else {
        use.attr("xlink:href", "#icon-angle-up");
        svg.toggleClass('icon-angle-down icon-angle-up');
    }
}
*/
