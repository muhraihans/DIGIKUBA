<footer class="admin-footer py-4 px-3 border-top bg-white">

    <div class="container-fluid">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">

            <small class="text-muted">

                © {{ date('Y') }} DIGIKUBA.
                Kelurahan Kutabaru.

            </small>

            <small class="text-muted">

                Aplikasi Penerbitan Surat Berbasis Digital

            </small>

        </div>

    </div>

</footer>

<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

<script src="{{ asset('assets/js/main.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))

<script>

Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: @json(session('success')),
    timer: 2500,
    showConfirmButton: false
});

</script>

@endif

@if(session('error'))

<script>

Swal.fire({
    icon: 'error',
    title: 'Gagal',
    text: @json(session('error'))
});

</script>

@endif

@stack('scripts')