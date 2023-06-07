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

    var Accordion = function($el, options) {
        this.options = $.extend({}, defaultConfig, options);

        this.$wrapper = $el;
        this.$panels = $(this.options.panelsSelector, this.$wrapper);

        this.initAttributes();
        this.initEvents();
    };

    Accordion.prototype.initAttributes = function() {
        this.$wrapper.attr({
            'role': 'tablist',
            'aria-multiselectable': this.options.multiselectable.toString()
        }).addClass(this.options.prefixClass);

        // id generated if not present
        this.$wrapper.each($.proxy(function(index, el) {
            var $wrapper = $(el);
            var index_lisible = Math.random().toString(32).slice(2, 12);

            if (!$wrapper.attr('id')) {
                $wrapper.attr('id', this.options.accordionPrefixId + '-' + index_lisible);
            }
        }, this));

        this.$panels.each($.proxy(function(index, el) {
            var $panel = $(el);
            var $header = $(this.options.headersSelector, $panel);
            var $button = this.options.buttonsGeneratedContent === 'html' ? this.options.button.clone().html($header.html()) : this.options.button.clone().text($header.text());

            $header.attr('tabindex', '0').addClass(this.options.prefixClass + this.options.headerSuffixClass);
            $panel.before($button);

            var panelId = $panel.attr('id') || this.$wrapper.attr('id') + '-' + index;
            var buttonId = panelId + this.options.buttonSuffixId;

            $button.attr({
                'aria-controls': panelId,
                'aria-expanded': 'false',
                'role': 'tab',
                'id': buttonId,
                'tabindex': '-1',
                'aria-selected': 'false'
            }).addClass(this.options.prefixClass + this.options.buttonSuffixClass);

            $panel.attr({
                'aria-labelledby': buttonId,
                'role': 'tabpanel',
                'id': panelId,
                'aria-hidden': 'true'
            }).addClass(this.options.prefixClass + this.options.panelSuffixClass);

            // if opened by default
            if ($panel.attr('data-accordion-opened') === 'true') {
                $button.attr({
                    'aria-expanded': 'true',
                    'data-accordion-opened': null
                });

                $panel.attr({
                    'aria-hidden': 'false'
                });
            }

            // init first one focusable
            if (index === 0) {
                $button.removeAttr('tabindex');
            }
        }, this));

        this.$buttons = $(this.options.buttonsSelector, this.$wrapper);
    };

    Accordion.prototype.initEvents = function() {
        this.$wrapper.on('focus', this.options.buttonsSelector, $.proxy(this.focusButtonEventHandler, this));

        this.$wrapper.on('click', this.options.buttonsSelector, $.proxy(this.clickButtonEventHandler, this));

        this.$wrapper.on('keydown', this.options.buttonsSelector, $.proxy(this.keydownButtonEventHandler, this));

        this.$wrapper.on('keydown', this.options.panelsSelector, $.proxy(this.keydownPanelEventHandler, this));
    };

    Accordion.prototype.focusButtonEventHandler = function(e) {
        var $target = $(e.target);
        var $button = $target.is('button') ? $target : $target.closest('button');

        $(this.options.buttonsSelector, this.$wrapper).attr({
            'tabindex': '-1',
            'aria-selected': 'false'
        });

        $button.attr({
            'aria-selected': 'true',
            'tabindex': null
        });
    };

    Accordion.prototype.clickButtonEventHandler = function(e) {
        var $target = $(e.target);
        var $button = $target.is('button') ? $target : $target.closest('button');
        var $panel = $('#' + $button.attr('aria-controls'));

        this.$buttons.attr('aria-selected', 'false');
        $button.attr('aria-selected', 'true');

        // opened or closed?
        if ($button.attr('aria-expanded') === 'false') { // closed
            $button.attr('aria-expanded', 'true');
            $panel.attr('aria-hidden', 'false');
        } else { // opened
            $button.attr('aria-expanded', 'false');
            $panel.attr('aria-hidden', 'true');
        }

        if (this.options.multiselectable === false) {
            this.$panels.not($panel).attr('aria-hidden', 'true');
            this.$buttons.not($button).attr('aria-expanded', 'false');
        }

        setTimeout(function() {
            $button.focus();
        }, 0);

        e.stopPropagation();
        e.preventDefault();
    };

    Accordion.prototype.keydownButtonEventHandler = function(e) {
        var $target = $(e.target);
        var $button = $target.is('button') ? $target : $target.closest('button');
        var $firstButton = this.$buttons.first();
        var $lastButton = this.$buttons.last();
        var $prevButton = $button.prevAll(this.options.buttonsSelector).first();
        var $nextButton = $button.nextAll(this.options.buttonsSelector).first();

        $target = null;

        var k = this.options.direction === 'ltr' ? {
            prev: [38, 37], // up & left
            next: [40, 39], // down & right
            first: 36, // home
            last: 35 // end
        } : {
            prev: [38, 39], // up & left
            next: [40, 37], // down & right
            first: 36, // home
            last: 35 // end
        };

        var allKeyCode = [].concat(k.prev, k.next, k.first, k.last);

        if ($.inArray(e.keyCode, allKeyCode) >= 0 && !e.ctrlKey) {
            this.$buttons.attr({
                'tabindex': '-1',
                'aria-selected': 'false'
            });


            if (e.keyCode === 36) {
                $target = $firstButton;
            }
            // strike end in the tab => last tab
            else if (e.keyCode === 35) {
                $target = $lastButton;
            }
            // strike up or left in the tab => previous tab
            else if ($.inArray(e.keyCode, k.prev) >= 0) {
                // if we are on first one, activate last
                $target = $button.is($firstButton) ? $lastButton : $prevButton;
            }
            // strike down or right in the tab => next tab
            else if ($.inArray(e.keyCode, k.next) >= 0) {
                // if we are on last one, activate first
                $target = $button.is($lastButton) ? $firstButton : $nextButton;
            }

            if ($target !== null) {
                this.goToHeader($target);
            }

            e.preventDefault();
        }
    };

    Accordion.prototype.keydownPanelEventHandler = function(e) {
        var $panel = $(e.target).closest(this.options.panelsSelector);
        var $button = $('#' + $panel.attr('aria-labelledby'));
        var $firstButton = this.$wrapper.find(this.options.buttonsSelector).first();
        var $lastButton = this.$wrapper.find(this.options.buttonsSelector).last();
        var $prevButton = $button.prevAll(this.options.buttonsSelector).first();
        var $nextButton = $button.nextAll(this.options.buttonsSelector).first();
        var $target = null;

        // strike up + ctrl => go to header
        if (e.keyCode === 38 && e.ctrlKey) {
            $target = $button;
        }
        // strike pageup + ctrl => go to prev header
        else if (e.keyCode === 33 && e.ctrlKey) {
            $target = $button.is($firstButton) ? $lastButton : $prevButton;
        }
        // strike pagedown + ctrl => go to next header
        else if (e.keyCode === 34 && e.ctrlKey) {
            $target = $button.is($lastButton) ? $firstButton : $nextButton;
        }

        if ($target !== null) {
            this.goToHeader($target);
            e.preventDefault();
        }
    };

    Accordion.prototype.goToHeader = function($target) {
        if ($target.length !== 1) {
            return;
        }

        $target.attr({
            'aria-selected': 'true',
            'tabindex': null
        });

        setTimeout(function() {
            $target.focus();
        }, 0);
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
