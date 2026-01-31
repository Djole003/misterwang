@extends('layouts.app')
@include('partials.header')

@section('content')


{{-- =======================
   HERO SECTION
======================= --}}

<section class="hero position-relative"
    style="
        border-bottom: 6px solid #ffd600;
        box-shadow: 0 15px 30px rgba(0,0,0,0.35);
    "
>
    <div class="hero-image"
         style="background-image: url('{{ asset('assets/hero.jpg') }}'); height: 70vh; background-size: cover; background-position: center;">
        <div class="hero-overlay d-flex flex-column justify-content-center align-items-center text-white text-center h-100"
             style="background: rgba(0,0,0,0.4);">

            {{-- SEO H1 --}}
            <h1 class="display-4 fw-bold">
                Kineski restoran Mister Wang – Online poručivanje
            </h1>

            <p class="fs-5 mb-3">
                Autentična kineska hrana u Beogradu – brza dostava i vrhunski kvalitet
            </p>

            <a href="{{ route('jelovnik') }}"
               class="btn btn-danger btn-lg open-order-type-modal">
                Pogledaj jelovnik
            </a>
        </div>
    </div>
</section>

{{-- =======================
   AKCIJA – PRIVREMENI REDIRECT
======================= --}}
<section
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
   HERO SLIDER
======================= --}}
<section class="hero-slider-section"
    style="
        border-top: 6px solid #ffd600;
        border-bottom: 6px solid #ffd600;
        box-shadow: 0 18px 35px rgba(0,0,0,0.30);
        padding-top: 30px;
        padding-bottom: 30px;
    "
>
    <div class="hero-slider-container"> 

        {{-- Slide 1 --}}
        <div class="dish-slide show">
            <div class="dish-content">
                <p class="dish-category">Predjelo</p>
                <h3 class="dish-title">Rolnice sa povrćem</h3>
                <p class="dish-description">
                    Hrskave rolnice punjene svežim povrćem, savršen početak obroka.
                </p>
                <a href="{{ url('/jela/5') }}"
                   class="btn btn-details open-order-type-modal">
                    Detalji proizvoda
                </a>
            </div>
            <div class="dish-image">
                <img src="{{ asset('assets/rolnice_povrce.JPG') }}"
                     alt="Rolnice sa povrćem – kineski restoran Mister Wang Beograd">
            </div>
        </div>

        {{-- Slide 2 --}}
        <div class="dish-slide">
            <div class="dish-content">
                <p class="dish-category">Glavno jelo</p>
                <h3 class="dish-title">Kraljevska piletina</h3>
                <p class="dish-description">
                    Sočna piletina u aromatičnom sosu sa povrćem i začinima.
                </p>
                <a href="{{ url('/jela/27') }}"
                   class="btn btn-details open-order-type-modal">
                    Detalji proizvoda
                </a>
            </div>
            <div class="dish-image">
                <img src="{{ asset('assets/kralj.JPG') }}"
                     alt="Kraljevska piletina – kineska hrana dostava Beograd">
            </div>
        </div>

        {{-- Slide 3 --}}
        <div class="dish-slide">
            <div class="dish-content">
                <p class="dish-category">Glavno jelo</p>
                <h3 class="dish-title">
                    Kung Pao piletina <span style="color:#FF4D4D;">(Specijalitet kuće)</span>
                </h3>
                <p class="dish-description">
                    Autentična kineska piletina sa kikirikijem, pikantna i sočna.
                </p>
                <a href="{{ url('/jela/28') }}"
                   class="btn btn-details open-order-type-modal">
                    Detalji proizvoda
                </a>
            </div>
            <div class="dish-image">
                <img src="{{ asset('assets/kung-pao.jpg') }}"
                     alt="Kung Pao piletina – kineski restoran Mister Wang Beograd">
            </div>
        </div>

        {{-- Slide 4 --}}
        <div class="dish-slide">
            <div class="dish-content">
                <p class="dish-category">Vegetarijansko</p>
                <h3 class="dish-title">Mešano povrće</h3>
                <p class="dish-description">
                    Sveže sezonsko povrće pripremljeno na tradicionalan kineski način.
                </p>
                <a href="{{ url('/jela/12') }}"
                   class="btn btn-details open-order-type-modal">
                    Detalji proizvoda
                </a>
            </div>
            <div class="dish-image">
                <img src="{{ asset('assets/mesano_povrce.jpg') }}"
                     alt="Mešano povrće – vegetarijanska kineska hrana Beograd">
            </div>
        </div>

        {{-- Slide 5 --}}
        <div class="dish-slide">
            <div class="dish-content">
                <p class="dish-category">Dezert</p>
                <h3 class="dish-title">Pohovana banana</h3>
                <p class="dish-description">
                    Sladak i hrskav kineski dezert, savršen završetak obroka.
                </p>
                <a href="{{ url('/jela/21') }}"
                   class="btn btn-details open-order-type-modal">
                    Detalji proizvoda
                </a>
            </div>
            <div class="dish-image">
                <img src="{{ asset('assets/poh_banana.JPG') }}"
                     alt="Pohovana banana – kineski dezert Mister Wang">
            </div>
        </div>

        {{-- Kontrole --}}
        <button class="slider-btn prev">&#10094;</button>
        <button class="slider-btn next">&#10095;</button>
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

<script>
document.addEventListener("DOMContentLoaded", function() {
    let currentSlide = 0;
    const slides = document.querySelectorAll('.dish-slide');
    const totalSlides = slides.length;

    const prevBtn = document.querySelector('.slider-btn.prev');
    const nextBtn = document.querySelector('.slider-btn.next');

    function showSlide(index){
        slides.forEach(slide => slide.classList.remove('show'));
        slides[index].classList.add('show');
    }

    showSlide(currentSlide);

    let slideInterval = setInterval(() => {
        currentSlide = (currentSlide + 1) % totalSlides;
        showSlide(currentSlide);
    }, 5000);

    nextBtn.addEventListener('click', () => {
        currentSlide = (currentSlide + 1) % totalSlides;
        showSlide(currentSlide);
    });

    prevBtn.addEventListener('click', () => {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        showSlide(currentSlide);
    });
});
</script>
