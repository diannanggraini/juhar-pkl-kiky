@extends('guru.layouts.app')

@section('title', ' Kegiatan Siswa')

@section('content')

@if ($kegiatan)
<div class="row bg-light rounded align-items-center mx-0">
    <div class="col-md-6">
        <table>
            <tr>
                <td width="100">nama_siswa</td>
                <td width="10">:</td>
                <td>{{ $kegiatan->kegiatanSiswa->nama_siswa }}</td>
            </tr>
        </table>
    </div>
</div>
<br>
@endif
<div class="row g-4">
    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            @if(session('success'))
            <div class="alert alert-succes">
                {{ session('success') }}
            </div>
            @endif
            <h6 class="mb-4">Data Kegiatan Siswa</h6>
            <div class="table-responsive">
                <form action="{{ route('guru.pembimbing.siswa.kegiatan.cari', ['id' => $id_pembimbing, 'id_siswa' => $id_siswa])}}" method="GET" class="row-g"></form>
                <div class="col-auto">
                    <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                    <input type="date" class="form-control" id="tanggal_awal" value="{{ $tanggalAwal ?? ''}}">
                    <div class="text-danger">
                        @error('tanggal_awal')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="col-aouto">
                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                    <input type="text" class="form-control" id="tanggal_akhir" value="{{ $tanggal_akhir ?? '' }}">
                    <div class="text-danger">
                        @error('tanggal_akhir')
                            {{ $message }}
                        @enderror
                    </div>
                <div class="col-auto align-self-end">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                    <a href="{{  route('guru.pembimbing.siswa.kegiatan', ['id' => $id_pembimbing, 'id_siswa' => $id_siswa])}}" class="btn btn-primary"><i class="fas fa-sync-alt"></i></a>
                </div>
                <table class="table" id="kegiatan" >
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">tanggal</th>
                            <th scope="col">Nama Kegiatan</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                         @foreach ($kegiatans as $kegiatan)
                         <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $kegiatan->tanggal_kegiatan }}</td>
                            <td>{{ $kegiatan->nama_kegiatan }}</td>
                            <td>
                                <a href="{{ route('guru.pembimbing.siswa.detail', ['id' => $id, 'id_siswa' => $kegiatan->id_siswa, 'id_kegiatan' => $kegiatan->id_kegiatan ]) }}" class="btn btn-primary btn-sm">Detail</a>
                            </td>
                        </tr>
                         @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        new DataTable('#siswa')
    });

</script>

@endsection
