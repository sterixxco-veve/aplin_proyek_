<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-2">
        @csrf
        @method('patch')

        {{-- INPUT: FOTO PROFIL (Memenuhi TC-GEN-04) --}}
        <div class="mb-4">
            <label for="avatar" class="form-label fw-bold text-secondary" style="font-size: 0.875rem;">Foto Profil</label>
            <div class="d-flex align-items-center gap-3 mb-2">
                {{-- Preview lingkaran foto --}}
                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4f46e5&color=fff' }}" 
                     class="rounded-circle border border-2 p-1" 
                     style="width: 70px; height: 70px; object-fit: cover;" 
                     id="avatar-preview"
                     alt="Profile Picture">
                
                <div class="flex-grow-1">
                    <input type="file" id="avatar" name="avatar" class="form-control" accept="image/*" onchange="previewImage(this)">
                    <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Format: JPG, JPEG, PNG. Maksimal 2MB.</small>
                </div>
            </div>
            @if($errors->get('avatar'))
                <div class="text-danger small mt-1 fw-semibold">{{ $errors->get('avatar')[0] }}</div>
            @endif
        </div>

        {{-- INPUT: NAMA --}}
        <div class="mb-4">
            <label for="name" class="form-label fw-bold text-secondary" style="font-size: 0.875rem;">Nama Lengkap *</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @if($errors->get('name'))
                <div class="text-danger small mt-1 fw-semibold">{{ $errors->get('name')[0] }}</div>
            @endif
        </div>

        {{-- INPUT: DETAIL KONTAK / EMAIL --}}
        <div class="mb-4">
            <label for="email" class="form-label fw-bold text-secondary" style="font-size: 0.875rem;">Alamat Email / Kontak *</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @if($errors->get('email'))
                <div class="text-danger small mt-1 fw-semibold">{{ $errors->get('email')[0] }}</div>
            @endif

            {{-- Handle Verifikasi Email jika aktif --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 p-2 bg-light rounded-3 border">
                    <p class="text-sm text-dark mb-1 small">
                        {{ __('Your email address is unverified.') }}
                    </p>
                    <button form="send-verification" class="btn btn-sm btn-link text-decoration-none p-0 text-primary small fw-bold">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1 font-medium small text-success">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- TOMBOL AKSI SIMPAN --}}
        <div class="d-flex align-items-center gap-3 pt-2">
            <button type="submit" class="btn btn-primary px-4 py-2">
                <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <span class="text-success small fw-semibold animate-fade-out">
                    <i class="bi bi-check2-all"></i> Berhasil disimpan.
                </span>
            @endif
        </div>
    </form>
</section>

{{-- FITUR PREVIEW FOTO INSTAN SEBELUM DI-SUBMIT --}}
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>