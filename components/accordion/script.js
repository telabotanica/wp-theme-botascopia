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
        } else {
            $('.js-accordion__header').attr('aria-expanded', 'false');
            $('.js-accordion__panel').attr('aria-hidden', 'true');
            $('#bouton-toutdeplier').attr('accordion-status', '0');
            $('#bouton-toutdeplier span').text('Tout déplier');
        }
        $('#bouton-toutdeplier').toggleClass('outline');
    }

    $('#bouton-toutdeplier').on('click', toutDeplier);
});
