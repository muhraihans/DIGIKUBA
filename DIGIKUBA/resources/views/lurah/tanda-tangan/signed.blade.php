@extends('layouts.admin')

@section('title', 'Surat Ditandatangani')

@section('content')

<h3 class="fw-bold mb-4">
    Surat Ditandatangani
</h3>

<div class="card content-card">

    <div class="card-body">

        <table class="table table-hover">

            <thead>

                <tr>

                    <th>No</th>
                    <th>Nomor Surat</th>
                    <th>Perihal</th>
                    <th>Pemohon</th>
                    <th>Tanggal Tanda Tangan</th>

                </tr>

            </thead>

            <tbody>

                @forelse($surat ?? [] as $item)

                    <tr data-created-at="{{ $item->created_at?->timestamp ?? '' }}">

                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $item->nomor_surat }}</td>

                        <td>{{ $item->perihal }}</td>

                        <td>
                            {{ $item->pengajuan->masyarakat->nama_lengkap }}
                        </td>

                        <td>
                            {{ $item->tandaTangan?->signed_at?->format('d/m/Y H:i') ?? '-' }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="text-center">

                            Belum ada surat.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection