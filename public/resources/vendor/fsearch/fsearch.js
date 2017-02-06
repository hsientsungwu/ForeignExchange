/*
 * FSearch 0.1
 * @author HsienTsung Wu
 */
$.fn.fsearch = function ( options ) {
    var _this = this;

    var defaults = {
        length          : 2,
        list            : '.search-list',
        target          : 'data-content',
        reset           : false,
        keyupCallback   : function() {},
        resetCallback   : function() {},
        searchCallback  : function(count) {}
    };

    var settings = $.extend(defaults, options);

    function search(key) {
        var count = 0,
            regexp = '',
            words = key.trim().split(/\s+/g);


        regexp = '(?=.*' + words.join(")(?=.*") + ')+';

        $(settings.list).each(function() { 
            if ($(this).attr(settings.target).length === undefined) {
                $(this).hide();
            } else if ($(this).attr(settings.target).search(new RegExp(regexp, 'i')) < 0) {
                $(this).hide();
            } else {
                $(this).show();
                count++;
            }
        });

        if (typeof settings.searchCallback == 'function') settings.searchCallback(count);
    }

    function displayAll() {
        $(settings.list).each(function(e) {
            $(this).show();
        });
    }

    function reset() {
        if (typeof settings.resetCallback == 'function') settings.resetCallback();

        displayAll();
        $(this).val('');
    }

    if (settings.reset) {
        $(this).on('fsearch:reset', reset);
    }

    $(this).keyup(function(e) {
        if (typeof settings.keyupCallback == 'function') settings.keyupCallback();

        var key = $(this).val();

        if (key.length >= settings.length) {
            search(key);
        } else {
            reset();
        }
    });

    return this;
}