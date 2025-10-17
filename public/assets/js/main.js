// Main JavaScript file for Mobiplay Web

$(document).ready(function() {
    // Initialize all tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Initialize all popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    });

    // Auto hide alerts after 5 seconds
    $('.alert:not(.alert-permanent)').delay(5000).fadeOut(500);

    // Toggle sidebar on mobile
    $('#sidebar-toggle').on('click', function(e) {
        e.preventDefault();
        $('#wrapper').toggleClass('toggled');
    });

    // Add active class to current menu item
    const currentPath = window.location.pathname;
    $('.sidebar-nav li a').each(function() {
        if ($(this).attr('href') === currentPath) {
            $(this).closest('li').addClass('active');
        }
    });

    // Handle form submissions with loading state
    $('form:not(.no-loading)').on('submit', function() {
        const form = $(this);
        const submitBtn = form.find('[type="submit"]');
        const loadingText = submitBtn.data('loading-text') || '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
        const originalHtml = submitBtn.html();

        // Disable button and show loading state
        submitBtn.prop('disabled', true)
                .data('original-html', originalHtml)
                .html(loadingText);

        // Form will submit normally
        return true;
    });

    // Initialize any date pickers
    if($.fn.daterangepicker) {
        $('input.datepicker').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 1901,
            maxYear: parseInt(moment().format('YYYY'),10)
        });

        $('input.daterangepicker').daterangepicker({
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            alwaysShowCalendars: true,
            startDate: moment().subtract(29, 'days'),
            endDate: moment()
        });
    }

    // Handle logout button clicks
    $('.logout-btn').click(function(e) {
        e.preventDefault();
        const btn = $(this);
        const originalHtml = btn.html();

        // Show loading state
        btn.prop('disabled', true)
           .html('<i class="fas fa-spinner fa-spin me-2"></i>Logging out...');

        // Make logout request
        $.ajax({
            url: '/logout',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                window.location.href = response.redirect || '/';
            },
            error: function() {
                // Reset button state
                btn.prop('disabled', false).html(originalHtml);
                alert('Failed to logout. Please try again.');
            }
        });
    });

    // Handle currency inputs
    $('input[data-type="currency"]').on({
        keyup: function() {
            formatCurrency($(this));
        },
        blur: function() { 
            formatCurrency($(this), "blur");
        }
    });

    // Confirm dangerous actions
    $('[data-confirm]').on('click', function(e) {
        if (!confirm($(this).data('confirm') || 'Are you sure you want to do this?')) {
            e.preventDefault();
        }
    });
});

// Format currency values
function formatNumber(n) {
    return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}

function formatCurrency(input, blur) {
    var input_val = input.val();
    if (input_val === "") { return; }
    var original_len = input_val.length;
    var caret_pos = input.prop("selectionStart");
    if (input_val.indexOf(".") >= 0) {
        var decimal_pos = input_val.indexOf(".");
        var left_side = input_val.substring(0, decimal_pos);
        var right_side = input_val.substring(decimal_pos);
        left_side = formatNumber(left_side);
        right_side = formatNumber(right_side);
        if (blur === "blur") {
            right_side += "00";
        }
        right_side = right_side.substring(0, 2);
        input_val = "$" + left_side + "." + right_side;
    } else {
        input_val = formatNumber(input_val);
        input_val = "$" + input_val;
        if (blur === "blur") {
            input_val += ".00";
        }
    }
    input.val(input_val);
    var updated_len = input_val.length;
    caret_pos = updated_len - original_len + caret_pos;
    input[0].setSelectionRange(caret_pos, caret_pos);
}