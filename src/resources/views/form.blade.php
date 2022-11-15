<x-installer::layout>
  <div class="row vh-100">
    <div class="col-8 col-md-6 mx-auto my-auto">
      <div class="card text-center">
        <div class="card-header">Halaman Instalasi</div>
        <div class="card-body">
          <p class="card-text">
            <small id="message">Tekan tombol install untuk memulai instalasi</small>
          </p>
        </div>
        <div class="card-footer d-flex justify-content-center">
          <div id="spinner" class="spinner-border text-info d-none" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <button id="submit" data-submit="{{ route('install.submit') }}" class="btn btn-success">Install</button>
          <a id="home" href="{{ route('home') }}" class="btn btn-success d-none">Selesai</a>
        </div>
      </div>
    </div>
  </div>
  @section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
      $(function() {
        $('#submit').click(function (e) {
          let submitTo = $(this).attr('data-submit')
          $(this).addClass('d-none')
          $('#spinner').removeClass('d-none')
          $('#message').html('Instalasi sedang berlangsung...')
          
          $.ajax({url: submitTo}).done(function (data) {
            $('#spinner').addClass('d-none')

            if (data.status) {
              $('#message').html('Instalasi berhasil!')
              $('#home').removeClass('d-none')
            } else {
              $('#message').html('Instalasi gagal!')
              $(this).removeClass('d-none')
            }
          });
        })
      });
    </script>
  @endsection
</x-installer::layout>