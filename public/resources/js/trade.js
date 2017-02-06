$(document).ready(function() {
    // initiate chose select 
    $(".chosen-select").chosen({});

    // when filter button is clicked, we want to use update the hidden input action value to filter
    $('.js-filter-btn').on('click', function(e) {
        e.preventDefault();

        $('.js-action').prop('value', 'filter');
        $('.js-trade-form').submit();
    });

    // when download button is clicked, we want to use update the hidden input action value to download
    $('.js-download-btn').on('click', function(e) {
        e.preventDefault();

        $('.js-action').prop('value', 'download');
        $('.js-trade-form').submit();
    });
});
