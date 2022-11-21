<x-installer::layout>
  <div class="row vh-100">
    <div class="col-8 col-md-6 mx-auto my-auto">
      <div class="card text-center">
        <div class="card-header">Halaman Instalasi</div>
        <form action="{{ route('installer.validation') }}" method="post">
          <div class="card-body text-start">
            <div class="mb-3">
              <label for="license" class="form-label">Lisensi</label>
              <input name="license" type="text" class="form-control" id="license" required>
            </div>
            <p class="card-text"><small>ID Lisensi: {{ $identifier }}</small></p>
          </div>
          <div class="card-footer d-flex justify-content-end">
            <button type="submit" class="btn btn-success">Validasi</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</x-installer::layout>