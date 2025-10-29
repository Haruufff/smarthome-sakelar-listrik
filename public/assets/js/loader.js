function showLoader() {
    $('#loader').removeClass('hidden').addClass('flex');
}

function hideLoader() {
    $('#loader').removeClass('flex').addClass('hidden');
}

$(document).ready(function() {
    $(document).ajaxStart(function() {
        hideLoader();
    });

    $(document).ajaxStop(function() {
        hideLoader();
    });

    $(document).ajaxError(function() {
        hideLoader();
    });

    $('form').on('submit', function() {
        showLoader();
    });

    $(window).on('beforeunload', function() {
        showLoader();
    });

    $('a:not([target="_blank"])').on('click', function(e) {
        const href = $(this).attr('href');
        if (href && href !== '#' && !href.startsWith('javascript:')) {
            showLoader();
        }
    });

    if (window.axios) {
        axios.interceptors.request.use(function(config) {
            showLoader();
            return config;
        });

        axios.interceptors.response.use(
            function(response) {
                hideLoader();
                return response;
            },
            function(error) {
                hideLoader();
                return Promise.reject(error);
            }
        );
    }
});