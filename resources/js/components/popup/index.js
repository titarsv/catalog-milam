'use strict';

let $ = require('jquery');
require('magnific-popup');
// require('../../../../../node_modules/magnific-popup/src/css/main.scss');
// require('slick-carousel');
// require('../../../../../node_modules/slick-carousel/slick/slick.scss');

module.exports = function() {
    $('.popup-btn').each(function(index, obj) {

        let $this = $(obj);

        let settings = {};

        settings.type = 'inline';
        /*      settings.callbacks = {
                  open: function() {
                      var filterVal = 'blur(4px)';
                      $('header').css('filter',filterVal);
                      $('main').css('filter',filterVal);
                      $('footer').css('filter',filterVal);
                  },
                  close: function() {
                      var filterVal = 'blur(0px)';
                      $('header').css('filter',filterVal);
                      $('main').css('filter',filterVal);
                      $('footer').css('filter',filterVal);
                  }
              };*/
        if ($this.data('type') !== '') {
            settings.type = $this.data('type');
        }

        if ($(window).width() > 768) {
            settings.callbacks = {
                close: function(){
                    $('.header').css({'right': '0', 'z-index': '1080'});
                    setTimeout(function() { $('.header').css({'transition': '.3s linear'}); }, 10);
                },
                open: function(){
                    $('.header').css({'right': '17px', 'transition': 'none'});
                    setTimeout(function() { $('.header').css({'z-index': '10'}); }, 10);
                }
            };
        }

        if (settings.type === 'inline') {

            // let slider = $($this.data('mfp-src')).find('.slick-slider');

            // if (slider.length) {
            //   settings.callbacks = {
            //     open: function() {
            //       slider.slick();
            //     }
            //   };
            // }else{
            let target = $($this.data('mfp-src'));
            settings.callbacks = {
                open: function() {
                    console.log(target.find('picture'));
                    target.find('picture').each(function(){
                        $(this)
                            .children('source')
                            .each(function (index, el) {
                                var $child = $(el),
                                    source = $child.data('src');

                                if (source) {
                                    $child.attr('srcset', source);
                                }
                            });
                    });
                }
            };
            // }
        }

        $this.magnificPopup(settings);
    });
};
