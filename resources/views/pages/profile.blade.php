@extends('layouts.main')

@section('content')
<div class="container" style="margin-top: 100px;">
    <h2 class="mb-4" style="margin-bottom: 50px;">Profil Saya</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tampilkan data profil (view mode) --}}
    <div id="profile-view">
        <div class="mb-3">
            <img 
                src="{{ $user->profile_photo ? asset('storage/profile_photos/' . $user->profile_photo) : asset('default-profile.png') }}" 
                alt="Foto Profil" 
                style="max-width: 150px; max-height: 150px; border-radius: 50%; object-fit: cover;"
            >
        </div>
        <p><strong>Nama Lengkap:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>No. HP:</strong> {{ $user->phone ?? '-' }}</p>
        <p><strong>Alamat:</strong> {{ $user->address ?? '-' }}</p>
        <p><strong>Produk / Brand:</strong> {{ $user->company ?? '-' }}</p>
        <p><strong>Jabatan:</strong> {{ $user->position ?? '-' }}</p>
        <p><strong>Bio / Deskripsi Singkat:</strong> {{ $user->bio ?? '-' }}</p>
        <button id="edit-button" class="btn btn-primary">Edit Profil</button>
    </div>

    {{-- Form edit profil, awalnya disembunyikan --}}
    <div id="profile-edit" style="display:none;">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('tenant.profile.update') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="profile_photo" class="form-label">Foto Profil</label><br>
                <img 
                    id="preview-photo" 
                    src="{{ $user->profile_photo ? asset('storage/profile_photos/' . $user->profile_photo) : asset('default-profile.png') }}" 
                    alt="Preview Foto Profil" 
                    style="max-width: 150px; max-height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 10px;"
                >
                <input type="file" 
                       class="form-control @error('profile_photo') is-invalid @enderror" 
                       id="profile_photo" 
                       name="profile_photo" 
                       accept="image/*">
                @error('profile_photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" 
                       class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" 
                       value="{{ old('name', $user->name) }}">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" 
                       value="{{ old('email', $user->email) }}">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">No. HP</label>
                <input type="text" 
                       class="form-control @error('phone') is-invalid @enderror" 
                       id="phone" name="phone" 
                       value="{{ old('phone', $user->phone) }}">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Alamat</label>
                <textarea 
                    class="form-control @error('address') is-invalid @enderror" 
                    id="address" name="address">{{ old('address', $user->address) }}</textarea>
                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="company" class="form-label">Produk / Brand</label>
                <input type="text" 
                       class="form-control @error('company') is-invalid @enderror" 
                       id="company" name="company" 
                       value="{{ old('company', $user->company) }}">
                @error('company') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="position" class="form-label">Jabatan</label>
                <input type="text" 
                       class="form-control @error('position') is-invalid @enderror" 
                       id="position" name="position" 
                       value="{{ old('position', $user->position) }}">
                @error('position') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="bio" class="form-label">Bio / Deskripsi Singkat</label>
                <textarea 
                    class="form-control @error('bio') is-invalid @enderror" 
                    id="bio" name="bio">{{ old('bio', $user->bio) }}</textarea>
                @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            <button type="button" id="cancel-button" class="btn btn-secondary">Batal</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('edit-button').addEventListener('click', function() {
        document.getElementById('profile-view').style.display = 'none';
        document.getElementById('profile-edit').style.display = 'block';
    });

    document.getElementById('cancel-button').addEventListener('click', function() {
        document.getElementById('profile-edit').style.display = 'none';
        document.getElementById('profile-view').style.display = 'block';
    });

    // Preview gambar saat upload file baru
    document.getElementById('profile_photo').addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            const preview = document.getElementById('preview-photo');
            preview.src = URL.createObjectURL(file);
        }
    });
</script>
@endsection
