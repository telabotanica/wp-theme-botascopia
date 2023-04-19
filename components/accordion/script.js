require('jquery-accessible-accordion-aria/jquery-accessible-accordion-aria.js');
import $ from 'jquery';
$(function () {
    $('.js-accordion').accordion();

    function toutDeplier(){
        let status = $('#bouton-toutdeplier').attr('accordion-status');

        if (status == 0){
            $('.js-accordion__header').attr('aria-expanded', 'true');
            $('.js-accordion__panel').attr('aria-hidden', 'false');
            $('#bouton-toutdeplier').attr('accordion-status', '1');
            $('#bouton-toutdeplier span').text('Tout replier');
            $('.formulaire-field-status svg use').attr("xlink:href", "#icon-angle-up");
            $('.formulaire-field-status svg').toggleClass('icon-angle-down icon-angle-up');

        } else {
            $('.js-accordion__header').attr('aria-expanded', 'false');
            $('.js-accordion__panel').attr('aria-hidden', 'true');
            $('#bouton-toutdeplier').attr('accordion-status', '0');
            $('#bouton-toutdeplier span').text('Tout déplier');
            $('.formulaire-field-status svg use').attr("xlink:href", "#icon-angle-down");
            $('.formulaire-field-status svg').toggleClass('icon-angle-down icon-angle-up');
        }
        $('#bouton-toutdeplier').toggleClass('outline');
    }

    $('#bouton-toutdeplier').on('click', toutDeplier);

    // Changement de l'icone lorsque l'on deplie/replie un accordion
    $('.js-accordion__header').on('click', function() {
        var accordionId = $(this).closest('.js-accordion').attr('id');
        changerIcone(accordionId);
    });

});

function changerIcone(accordionId){
    var use = $("#" + accordionId + " .formulaire-field-status svg use");
    var svg = $("#" + accordionId + " .formulaire-field-status svg");
    var header = $("#" + accordionId + " .js-accordion__header");

    if (header.attr("aria-expanded") === "true"){
        use.attr("xlink:href", "#icon-angle-down");
        svg.toggleClass('icon-angle-down icon-angle-up');
    } else {
        use.attr("xlink:href", "#icon-angle-up");
        svg.toggleClass('icon-angle-down icon-angle-up');
    }
}
