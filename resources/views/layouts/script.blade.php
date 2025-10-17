<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <script type="text/javascript">
    $(function() {
      var start = moment().subtract(29, 'days');
      var end = moment();

      function cb(start, end) {
        $('#reportrange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
      }
      $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
          'Hoy': [moment(), moment()],
          'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Últimos 7 Días': [moment().subtract(6, 'days'), moment()],
          'Últimos 30 Días': [moment().subtract(29, 'days'), moment()],
          'Este Mes': [moment().startOf('month'), moment().endOf('month')],
          'Mes Pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
      }, cb);
      cb(start, end);
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script type="text/javascript" src="{{ asset('assets/js/script.js') }}"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('.logout-btn').click(function() {
        const btn = $(this);
        const originalText = btn.text();

        // Disable button and show loading state
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Logging out...');

        $.ajax({
          url: '{{ route('logout') }}',
          type: 'POST',
          data: {
            _token: '{{ csrf_token() }}'
          },
          success: function(response) {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: response.message,
              showConfirmButton: false,
              timer: 1500
            }).then(function() {
              window.location.href = response.redirect;
            });
          },
          error: function(xhr) {
            // Reset button state
            btn.prop('disabled', false).text(originalText);
            
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Failed to logout. Please try again.'
            });
          }
        });
      });
    });
  </script>

  @yield('script')