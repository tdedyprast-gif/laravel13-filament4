<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>STKIP PGRI Pacitan | Sistem Informasi SKPI</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Reset agar tidak ada spasi putih */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .bg-campus {
            background-image: url('https://siakad.stkippacitan.ac.id/images_siakad/menu/bg-siakad-main.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
        }
        
        .bg-pattern {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px), 
                              linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        /* Modern Glass Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 3rem;
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
        }

        .btn-emerald {
            background-color: #34d399;
            color: #064e3b;
            font-weight: 700;
            border: none;
            padding: 0.8rem 2rem;
            transition: all 0.3s ease;
        }
        .btn-emerald:hover {
            background-color: #6ee7b7;
            transform: scale(1.05);
        }

        .logo-box {
            width: 50px;
            height: 50px;
            background-color: #ffffff;
            color: #065f46;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.5rem;
            border-radius: 0.75rem;
        }
    </style>
</head>

<body class="text-white">
    <main class="bg-campus">
        <div class="bg-pattern"></div>

        <section class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="glass-card">
                        <header class="mb-5 d-flex align-items-center gap-3">
                            <div class="logo-box">S</div>
                            <div>
                                <h6 class="mb-0 fw-bold" style="color: #a7f3d0;">STKIP PGRI PACITAN</h6>
                                <p class="mb-0 opacity-75 small">Sistem Informasi SKPI</p>
                            </div>
                        </header>

                        <div style="max-width: 700px;">
                            <h1 class="display-3 fw-bold mb-4 lh-sm">
                                Sistem Informasi <br><span style="color: #34d399;">SKPI</span>
                            </h1>
                            <p class="fs-5 mb-5 opacity-75">
                                Platform pengelolaan data Surat Keterangan Pendamping Ijazah (SKPI) STKIP PGRI Pacitan. Mendukung proses administrasi dan validasi akademik yang lebih efisien.
                            </p>
                            <a href="https://skpi.dwijacode.my.id/staff" class="btn btn-emerald rounded-pill">
                                Akses Staff
                            </a>
                        </div>

                        <div class="row g-4 mt-5 pt-4 border-top border-white border-opacity-10">
                            <div class="col-md-4">
                                <h6 class="fw-bold mb-1">Administrasi</h6>
                                <p class="small opacity-50">Pengelolaan data pendukung SKPI.</p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="fw-bold mb-1">Validasi</h6>
                                <p class="small opacity-50">Pemeriksaan data secara terpusat.</p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="fw-bold mb-1">Layanan</h6>
                                <p class="small opacity-50">Akses cepat untuk petugas kampus.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>