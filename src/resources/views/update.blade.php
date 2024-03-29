<x-installer::layout>
  <div class="row vh-100">
    <div class="col-8 col-md-6 mx-auto my-auto">
      <div class="card text-center">
        <div class="card-header">Halaman Update</div>
        <div class="card-body">
          <p class="card-text">
            <small id="message">Tekan tombol update untuk memulai Update</small>
          </p>
        </div>
        <div class="card-footer d-flex justify-content-center">
          <div id="spinner" class="spinner-border text-info d-none" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <button id="submit" data-submit="{{ route('installer.update-submit') }}" class="btn btn-success">Update</button>
          <a id="home" href="{{ route('home') }}" class="btn btn-success d-none">Selesai</a>
        </div>
      </div>
    </div>
  </div>
  @section('scripts')
    <script src="{{ asset('/plugins/jquery/jquery.min.js') }}"></script>
    <script>
      $(function() {
        $('#submit').click(function (e) {
          let submitTo = $(this).attr('data-submit')
          $(this).addClass('d-none')
          $('#spinner').removeClass('d-none')
          $('#message').html('Update sedang berlangsung...')
          
          $.ajax({url: submitTo}).done(function (data) {
            $('#spinner').addClass('d-none')

            if (data.status) {
              $('#message').html('Update berhasil!')
              $('#home').removeClass('d-none')
            } else {
              $('#message').html('Update gagal!')
              $(this).removeClass('d-none')
            }
          });
        })
      });
    </script>
  @endsection
</x-installer::layout>