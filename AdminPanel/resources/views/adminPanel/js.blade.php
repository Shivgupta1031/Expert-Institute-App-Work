<script src="{{ asset('adminPanel/vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('adminPanel/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('adminPanel/vendor/js-cookie/js.cookie.js') }}"></script>
<script src="{{ asset('adminPanel/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
<script src="{{ asset('adminPanel/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
<!-- Optional JS -->
<script src="{{ asset('adminPanel/vendor/chart.js/dist/Chart.min.js') }}"></script>
<script src="{{ asset('adminPanel/vendor/chart.js/dist/Chart.extension.js') }}"></script>
<!-- Argon JS -->
<script src="{{ asset('adminPanel/js/argon.js') }}"></script>

<script>
    $('.alert[data-auto-dismiss]').each(function(index, element) {
        var $element = $(element),
            timeout = $element.data('auto-dismiss') || 5000;

        setTimeout(function() {
            $element.alert('close');
        }, timeout);
    });

    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>

@yield('js')
