<x-app-layout :title="$title">
    <div class="row g-5">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <x-form-image
                            background="{{ getFileUrl(auth()->user()->photo ?? null, asset('app/assets/media/avatars/blank.png')) }}"
                            width="150px" height="150px" :onlyShow=true />

                        <div class="mt-5">
                            <h5 class="fw-bold mb-1">{{ auth()->user()->name }}</h5>
                            <small class="text-muted">Bergabung pada {{ tanggal(auth()->user()->created_at) }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row align-items-center align-items-md-center gap-5">
                    <h5 class="card-title">Akun Saya</h5>

                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-sm btn-outline btn-outline-danger"
                            data-title="Ganti Password" data-url="{{ route("$permission_name.change-password") }}"
                            onclick="actionModalData(this)">
                            <i class="ki-duotone ki-lock-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                            Ganti Password
                        </button>
                        <a href="{{ route('account.edit', encode(auth()->user()->id)) }}"
                            class="btn btn-sm btn-warning">
                            <i class="ki-duotone ki-pencil"><span class="path1"></span><span class="path2"></span></i>
                            Ubah Data
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if (empty(auth()->user()->email_verified_at))
                        <x-alert alertType="warning" title="Email Belum Terverifikasi">
                            Klik tombol <b>Kirim Verifikasi Email</b> untuk verifikasi sekarang.
                        </x-alert>
                    @endif

                    <table class="table align-middle table-row-dashed gy-5">
                        <tbody class="fs-6 fw-semibold text-gray-600">
                            <tr>
                                <td>Nama Lengkap</td>
                                <td class="fw-bold text-dark">{{ auth()->user()->name }}</td>
                            </tr>
                            <tr>
                                <td>Username</td>
                                <td class="fw-bold text-dark">{{ auth()->user()->username }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td class="fw-bold text-dark">
                                    {{ auth()->user()->email }}

                                    @if (empty(auth()->user()->email_verified_at))
                                        <button type="button" id="btn-verify-email"
                                            class="ms-sm-3 mt-2 mt-sm-0 btn btn-sm btn-secondary d-block d-sm-inline">
                                            <i class="fas fa-check"></i> Kirim Verifikasi Email
                                        </button>
                                    @else
                                        <i class="ki-solid ki-verify ms-1 fs-5 align-middle" style="color: #2c7be5"
                                            data-bs-toggle="tooltip" title="Terverifikasi">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Peran</td>
                                <td>
                                    @foreach (auth()->user()->roles as $item)
                                        <span
                                            class="badge {{ $item->name == 'Super Admin' ? 'bg-superadmin' : 'badge-light-success' }} me-2">{{ $item->name }}</span>
                                    @endforeach
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(function() {
                const $btn = $("#btn-verify-email");

                function startCountdown(expiredAt) {
                    const timer = setInterval(() => {
                        const now = Date.now();
                        const remaining = Math.floor((expiredAt - now) / 1000);

                        if (remaining > 0) {
                            $btn.prop("disabled", true).text("Tunggu " + remaining + " dtk");
                        } else {
                            clearInterval(timer);
                            localStorage.removeItem("verifyEmailExpiredAt");
                            $btn.prop("disabled", false)
                                .html('<i class="fas fa-check"></i> Kirim Verifikasi Email');
                        }
                    }, 1000);
                }

                // cek countdown saat reload
                const savedExpiredAt = localStorage.getItem("verifyEmailExpiredAt");
                if (savedExpiredAt && Date.now() < savedExpiredAt) {
                    startCountdown(savedExpiredAt);
                }

                $btn.on("click", function() {
                    $btn.prop("disabled", true).html(
                        '<i class="fas fa-spin fa-spinner align-middle"></i> Mengirim...');

                    ajaxMaster("{{ route('account.sendVerifyEmail') }}", "POST", {})
                        .then((res) => {
                            alertSweet(res.message, "success");
                            const expiredAt = Date.now() + 60000; // 60 detik
                            localStorage.setItem("verifyEmailExpiredAt", expiredAt);
                            startCountdown(expiredAt);
                        })
                        .catch(() => {
                            alertSweet("Gagal mengirim email verifikasi", "error");
                            $btn.prop("disabled", false)
                                .html('<i class="fas fa-check"></i> Kirim Verifikasi Email');
                        });
                });
            });
        </script>
    @endpush
</x-app-layout>
