/**
 * Select2 <Language> translation.
 * 
 * Author: Your Name <your@email>
 */
(function ($) {
    "use strict";

    $.extend($.fn.select2.defaults, {
        formatNoMatches: function () { return "چیزی یافت نشد."; },
        formatInputTooShort: function (input, min) { var n = min - input.length; return "Please enter " + n + " more character" + (n == 1 ? "" : "s"); },
        formatInputTooLong: function (input, max) { var n = input.length - max; return "Please enter " + n + " less character" + (n == 1? "" : "s"); },
        formatSelectionTooBig: function (limit) { return "You can only select " + limit + " item" + (limit == 1 ? "" : "s"); },
        formatLoadMore: function (pageNumber) { return "در حال بارگذاری نتایج..."; },
        formatSearching: function () { return "در حال جست و جو..."; }
    });
})(jQuery);
