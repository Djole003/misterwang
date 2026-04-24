@extends('layouts.app')
@include('partials.header')

@section('title', 'Mister Wang - Kineski restoran u Beogradu | Online porucivanje')
@section('meta_description', 'Porucite autenticnu kinesku hranu online. Mister Wang Beograd nudi brzu dostavu, sveze sastojke i specijalitete iz jelovnika.')

@section('content')

<style>
.home-polish {
    background: #fffaf2;
    color: #241815;
}

.home-polish .hero {
    border-bottom: 5px solid #f1c232 !important;
    box-shadow: 0 18px 38px rgba(36, 24, 21, 0.24) !important;
}

.home-polish .hero-image {
    min-height: 68vh;
}

.home-polish .hero-overlay {
    align-items: flex-start !important;
    padding: 72px max(24px, calc((100vw - 1120px) / 2)) !important;
    background: linear-gradient(90deg, rgba(19, 13, 11, 0.86), rgba(19, 13, 11, 0.55), rgba(19, 13, 11, 0.2)) !important;
    text-align: left !important;
}

.home-polish .hero-overlay h1 {
    max-width: 830px;
    margin-bottom: 18px;
    font-size: 3.4rem;
    line-height: 1.05;
    font-weight: 900 !important;
    text-shadow: 0 4px 20px rgba(0, 0, 0, 0.42);
}

.home-polish .hero-overlay > p {
    max-width: 680px;
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.65;
}

.home-polish .promo-box {
    max-width: 720px !important;
    margin-top: 8px !important;
    margin-bottom: 22px !important;
    padding: 16px 18px !important;
    border: 1px solid rgba(241, 194, 50, 0.8) !important;
    border-radius: 8px !important;
    background: rgba(140, 24, 20, 0.88) !important;
    animation: none !important;
    box-shadow: 0 14px 30px rgba(0, 0, 0, 0.2);
}

.home-polish .hero .btn,
.home-polish .cta .btn {
    min-height: 48px;
    padding: 12px 26px;
    border: 0;
    border-radius: 8px;
    background: #e53935;
    color: #fff;
    font-weight: 800;
    box-shadow: 0 12px 25px rgba(125, 21, 18, 0.35);
    transition: transform 0.18s ease, background-color 0.18s ease, box-shadow 0.18s ease;
}

.home-polish .hero .btn:hover,
.home-polish .cta .btn:hover {
    background: #c62828;
    color: #fff;
    transform: translateY(-2px);
}

.home-special-offer {
    background: linear-gradient(135deg, #8f1714, #d93632) !important;
    border-top: 5px solid #f1c232 !important;
    border-bottom: 5px solid #f1c232 !important;
}

.home-special-offer > div {
    width: min(1120px, calc(100% - 32px)) !important;
}

.home-special-offer h2 {
    font-size: 2.7rem !important;
    line-height: 1.15 !important;
}

.home-special-offer a {
    border-radius: 8px !important;
    transition: transform 0.18s ease, background-color 0.18s ease;
}

.home-special-offer a:hover {
    background: #ffd24c !important;
    transform: translateY(-2px);
}

.home-special-offer img {
    border-radius: 8px !important;
}

.home-polish .about-us {
    background: #fffaf2 !important;
    padding: 72px 0 !important;
}

.home-polish .about-us h2,
.home-polish .cta h2 {
    color: #241815;
    font-weight: 900 !important;
}

.home-polish .about-us > .container > p {
    max-width: 760px;
    margin-right: auto;
    margin-left: auto;
    color: #655752;
    line-height: 1.7;
}

.home-polish .feature-item {
    min-height: 100%;
    padding: 18px;
    border: 1px solid #eadfd1;
    border-radius: 8px;
    background: #fff;
    box-shadow: 0 10px 24px rgba(67, 41, 21, 0.08);
}

.home-polish .feature-item img {
    width: 108px;
    height: 108px;
    border-radius: 8px;
    object-fit: cover;
}

.home-polish .feature-item h6 {
    color: #2a1d18;
    font-weight: 800;
}

.home-polish .cta {
    margin-top: 0;
    padding: 64px 16px !important;
    background: #201816 !important;
    color: #fff;
}

.home-polish .cta h2 {
    color: #fff;
}

.home-polish .cta p {
    color: rgba(255, 255, 255, 0.78);
}

@media (max-width: 768px) {
    .home-polish .hero-image {
        height: auto !important;
        min-height: 58vh;
    }

    .home-polish .hero-overlay {
        padding: 52px 18px !important;
    }

    .home-polish .hero-overlay h1 {
        font-size: 2.05rem;
    }

    .home-polish .hero-overlay > p {
        font-size: 1rem !important;
    }

    .home-special-offer {
        padding: 52px 18px !important;
    }

    .home-special-offer h2 {
        font-size: 2rem !important;
    }
}
</style>

<div class="home-polish">

<section class="hero position-relative"
    style="
        border-bottom: 6px solid #ffd600;
        box-shadow: 0 15px 30px rgba(0,0,0,0.35);
    "
>
    <div class="hero-image"
         style="background-image: url('{{ asset('assets/hero.jpg') }}'); height: 70vh; background-size: cover; background-position: center;">

        <div class="hero-overlay d-flex flex-column justify-content-center align-items-center text-white text-center h-100"
             style="background: rgba(0,0,0,0.5); padding: 15px;">

            {{-- SEO H1 - OSTAVLJAMO NETAKNUTO --}}
            <h1 class="display-4 fw-bold">
                Kineski restoran Mister Wang – Online poručivanje
            </h1>

            <p class="fs-5 mb-3">
                Autentična kineska hrana u Beogradu – brza dostava i vrhunski kvalitet
            </p>

            {{-- ===== PROMO SEKCIJA ZA POPUST ===== --}}
            <div class="promo-box mt-2 mb-3 p-3"
                 style="
                    background: rgba(211,47,47,0.9);
                    border-radius: 12px;
                    max-width: 800px;
                    border: 2px solid #ffd600;
                    animation: pulse 2s infinite;
                 ">

                <h2 style="font-weight:800; margin-bottom:5px; font-size:1.6rem;">
                    🔥 AKCIJA – 15% POPUSTA 🔥
                </h2>

                <p style="margin-bottom:0; font-size:1rem;">
                    Ostvarite <strong>15% popusta na ceo asortiman</strong> kineskih specijaliteta.  
                    Akcija važi za sva jela iz jelovnika <strong>(osim pića)</strong>.
                </p>

            </div>
            {{-- ===== KRAJ PROMO SEKCIJE ===== --}}

            <a href="{{ route('jelovnik') }}"
                class="btn btn-primary btn-lg open-order-type-modal">
                    Pogledaj jelovnik
            </a>
        </div>
    </div>
</section>

<style>
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}
</style>


{{-- =======================
   AKCIJA – PRIVREMENI REDIRECT
======================= --}}
<section class="home-special-offer"
    style="
        background: linear-gradient(135deg, #b31217, #e52d27);
        padding: 80px 20px;
        color: white;
        margin-top: -5px;
        border-top: 6px solid #ffd600;
        border-bottom: 6px solid #ffd600;
        box-shadow: 0 20px 40px rgba(0,0,0,0.35);
    "
>
    <div
        style="
            max-width: 1200px;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
            flex-wrap: wrap;
        "
    >
        {{-- TEKST --}}
        <div style="max-width: 520px;">
            <div
                style="
                    display: inline-block;
                    background: #ffd600;
                    color: #000;
                    padding: 6px 18px;
                    border-radius: 30px;
                    font-weight: 700;
                    margin-bottom: 18px;
                "
            >
                🔥 SPECIJALNA AKCIJA
            </div>

            <h2
                style="
                    font-size: 42px;
                    font-weight: 900;
                    margin-bottom: 15px;
                    line-height: 1.2;
                "
            >
                Susam piletina + Coca-Cola
            </h2>

            <p
                style="
                    font-size: 18px;
                    margin-bottom: 28px;
                    line-height: 1.6;
                "
            >
                Sočna susam piletina uz osvežavajuću Coca-Colu
                po specijalnoj ceni. Ograničena ponuda!
            </p>

            {{-- ✅ DUGME KOJE VODI NA KATEGORIJU --}}
            <a
                href="{{ url('/jelovnik/kategorija/akcije') }}"
                style="
                    display:inline-block;
                    background:#ffd600;
                    color:#000;
                    padding:14px 40px;
                    border-radius:40px;
                    font-weight:800;
                    text-decoration:none;
                    box-shadow:0 10px 25px rgba(0,0,0,.35);
                "
            >
                Poruči odmah
            </a>
        </div>

        {{-- SLIKA --}}
        <div style="text-align:center;">
            <img
                src="{{ asset('assets/susam-akcija.png') }}"
                alt="Akcija Susam piletina i Coca Cola"
                style="
                    max-width: 420px;
                    width: 100%;
                    border-radius: 22px;
                    box-shadow: 0 30px 50px rgba(0,0,0,0.45);
                "
            >
        </div>
    </div>
</section>




{{-- =======================
   ABOUT US
======================= --}}
<section class="about-us py-5 bg-light">
    <div class="container text-center">
        <h2 class="fw-bold mb-4">Zašto izabrati kineski restoran Mister Wang?</h2>

        <p class="mb-5">
            Mister Wang je kineski restoran u Beogradu koji nudi autentičnu kinesku hranu,
            pripremljenu od svežih namirnica, uz mogućnost online poručivanja i brze dostave.
        </p>

        <div class="row justify-content-center g-4 features-row">
            <div class="col-6 col-sm-4 col-md-2">
                <div class="feature-item">
                    <img src="{{ asset('assets/sveze-namirnice.jpg') }}"
                         alt="Sveže namirnice – kineski restoran Beograd">
                    <h6>Sveže namirnice</h6>
                    <p>Biramo samo najkvalitetnije sastojke.</p>
                </div>
            </div>

            <div class="col-6 col-sm-4 col-md-2">
                <div class="feature-item">
                    <img src="{{ asset('assets/brza-dostava.jpg') }}"
                         alt="Brza dostava kineske hrane Beograd">
                    <h6>Brza dostava</h6>
                    <p>Vaša porudžbina stiže brzo i tačno.</p>
                </div>
            </div>

            <div class="col-6 col-sm-4 col-md-2">
                <div class="feature-item">
                    <img src="{{ asset('assets/autenticni-recepti.jpg') }}"
                         alt="Autentični kineski recepti">
                    <h6>Autentični recepti</h6>
                    <p>Originalni kineski ukusi.</p>
                </div>
            </div>

            <div class="col-6 col-sm-4 col-md-2">
                <div class="feature-item">
                    <img src="{{ asset('assets/sigurna-priprema.jpg') }}"
                         alt="Sigurna priprema hrane">
                    <h6>Sigurna priprema</h6>
                    <p>Higijenski standardi.</p>
                </div>
            </div>

            <div class="col-6 col-sm-4 col-md-2">
                <div class="feature-item">
                    <img src="{{ asset('assets/odlicna-podrska.png') }}"
                         alt="Podrška korisnicima Mister Wang">
                    <h6>Odlična podrška</h6>
                    <p>Uvek smo tu za vas.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- =======================
   CTA
======================= --}}
<section class="cta py-5">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">
            Poruči kinesku hranu online – Mister Wang Beograd
        </h2>
        <p class="mb-4">
            Izaberi omiljena jela i naruči brzo i jednostavno.
        </p>
        <a href="{{ route('jelovnik') }}"
           class="btn btn-danger btn-lg open-order-type-modal">
            Poruči sada
        </a>
    </div>
</section>



</div>

@include('partials.footer')

@endsection


{{-- =======================
   STYLES & SCRIPTS
======================= --}}
<style>
.hero-image { width: 100%; }
@media (max-width:768px) {
    .hero-image { height: 50vh !important; }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const items = document.querySelectorAll('.feature-item');

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if(entry.isIntersecting){
                entry.target.classList.add('show');
            }
        });
    }, { threshold: 0.2 });

    items.forEach(item => observer.observe(item));
});
</script>
