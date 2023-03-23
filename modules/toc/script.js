var _ = _ || {};
_.defer = require('lodash.defer');
_.each = require('lodash.foreach');
_.map = require('lodash.map');
_.throttle = require('lodash.throttle');

var Tela = window.Tela || {};
Tela.modules = Tela.modules || {};

Tela.modules.toc = (function(sel){

  var defaultOptions = {
    accordionsSelector: '.component-accordion',
    anchorsSelector: '.component-title.level-2 .component-title-anchor',
    anchorOffset: 40 // should be the same as $title-anchor-offset in component title style
  };
  function module(selector, userOptions){
    // console.log(selector)
    var el = document.querySelector(selector),
        options = Object.assign({}, defaultOptions, userOptions),
        items,
        headerHeight,
        currentItemId,
        articleContainer,
        accordions,
        anchors;

    function init(){
      headerHeight = document.querySelector('.header-nav').offsetHeight;

      articleContainer = document.querySelector('.layout-content article');
      accordions = articleContainer.querySelectorAll(options.accordionsSelector);
      anchors = articleContainer.querySelectorAll(options.anchorsSelector);

      if (anchors.length) {
        _.defer(parseItems);
        window.addEventListener('scroll', _.throttle(onScroll, 250));
      }

      if (accordions.length) {
        // Monitor changes to article height (when an accordion is open/closed)
        onElementHeightChange(articleContainer, parseItems);
      }
    }

    function initOptions() {
      Object.keys(el.dataset).forEach(function(key) {
        options[key] = el.dataset[key];
      });
    }

    function parseItems() {
      items = Array.prototype.map.call(anchors, function(anchor, index) {
        var $anchor = $(anchor);
        return {
          id: $anchor.attr('name'),
          top: Math.round(anchor.getBoundingClientRect().top + options.anchorOffset + window.pageYOffset)
        };
      });

      items.forEach(function(item, index, list) {
        item.bottom = (list[index+1]) ?
            list[index+1].top :
            Math.round(articleContainer.offsetTop + articleContainer.offsetHeight);
      });

      onScroll();
    }

    function onScroll() {
      var scrollTop = window.pageYOffset;

      items.forEach(function(item, index, list) {
        if (scrollTop > item.top - headerHeight
            && scrollTop < item.bottom - headerHeight
            && currentItemId != item.id) {
          currentItemId = item.id;
          el.querySelectorAll('.toc-subitem').forEach(function(subitem) {
            subitem.classList.remove('is-active');
          });
          el.querySelector('a[href="#' + item.id + '"]').closest('.toc-subitem').classList.add('is-active');
        }
      });
    }

    function onElementHeightChange(elm, callback){
      var lastHeight = elm.clientHeight, newHeight;

      (function run(){
        newHeight = elm.clientHeight;
        if (lastHeight != newHeight) callback();
        lastHeight = newHeight;

        if (elm.onElementHeightChangeTimer) clearTimeout(elm.onElementHeightChangeTimer);

        elm.onElementHeightChangeTimer = setTimeout(run, 500);
      })();
    }

    initOptions();
    init();

    return el;
  }

  return function(selector, userOptions){
    document.querySelectorAll(selector).forEach(function(el) {
      module(selector, userOptions);
    });
  };

})();

document.addEventListener('DOMContentLoaded', function() {
  Tela.modules.toc('.toc');
});
