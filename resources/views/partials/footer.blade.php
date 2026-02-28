<footer class="bg-dark text-white pt-5 pb-4 mt-5">
    <div class="container">
        <div class="row">

            {{-- LOGO + OPIS --}}
            <div class="col-md-4 mb-4 text-center text-md-start">
                <img src="{{ asset('assets/logo.png') }}" 
                     alt="Mister Wang Logo" 
                     height="60" 
                     class="mb-3">

                <p class="text-secondary small">
                    Autentična kineska kuhinja <br>
                    Brza dostava i kvalitetni sastojci.
                </p>
            </div>

            {{-- KONTAKT --}}
            <div class="col-md-4 mb-4 text-center text-md-start">
                <h6 class="fw-bold mb-3">
                    Kontakt – {{ $currentRestaurant->name ?? '' }}
                </h6>

                @if($restaurantContact)

                    <p class="mb-1">
                        📞 
                        <a href="tel:{{ preg_replace('/\s+/', '', $restaurantContact->phone) }}"
                           class="text-white text-decoration-none">
                            {{ $restaurantContact->phone }}
                        </a>
                    </p>

                    @if($restaurantContact->email)
                        <p class="mb-1">
                            ✉️ 
                            <a href="mailto:{{ $restaurantContact->email }}"
                               class="text-white text-decoration-none">
                                {{ $restaurantContact->email }}
                            </a>
                        </p>
                    @endif

                    <p class="mb-1">
                        📍 {{ $restaurantContact->address }}
                    </p>

                    {{-- RADNO VREME --}}
                    <h6 class="fw-bold mt-3 mb-2">Radno vreme</h6>
                    <p class="mb-1">
                        {{ $restaurantContact->working_hours }}
                    </p>

                @else
                    <p>Podaci nisu dostupni.</p>
                @endif
            </div>

            {{-- DRUŠTVENE MREŽE --}}
            <div class="col-md-4 mb-4 text-center text-md-start">
                <h6 class="fw-bold mb-3">Pratite nas</h6>

                <a href="https://www.instagram.com/mister_wang_2_miljakovac/"
                   target="_blank"
                   class="text-white text-decoration-none d-inline-flex align-items-center gap-2 mb-2">
                    <i class="fab fa-instagram fa-lg"></i> Instagram
                </a><br>

                <a href="#"
                   class="text-white text-decoration-none d-inline-flex align-items-center gap-2 mb-2">
                    <i class="fab fa-facebook fa-lg"></i> Facebook
                </a><br>

                <a href="#"
                   class="text-white text-decoration-none d-inline-flex align-items-center gap-2">
                    <i class="fab fa-linkedin fa-lg"></i> LinkedIn
                </a>
            </div>

        </div>

        <hr class="border-secondary">

        {{-- COPYRIGHT --}}
        <div class="text-center text-secondary small">
            © {{ date('Y') }} Mister Wang • Izrada sajta: Djordje Kitić
        </div>
    </div>
</footer>

<style>

/* ===== FOOTER BASE ===== */
footer {
    background: linear-gradient(135deg, #1a1a1a, #111);
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    position: relative;
}

/* Logo hover */
footer img {
    transition: transform 0.3s ease;
}

footer img:hover {
    transform: scale(1.08);
}

/* Naslovi */
footer h6 {
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
    position: relative;
}

/* Mala crvena linija ispod naslova */
footer h6::after {
    content: "";
    width: 35px;
    height: 2px;
    background: #e53935;
    display: block;
    margin-top: 6px;
}

/* Tekst */
footer p {
    color: #ccc;
    font-size: 0.9rem;
    margin-bottom: 6px;
}

/* Linkovi */
footer a {
    color: #fff;
    transition: all 0.3s ease;
}

footer a:hover {
    color: #ff6b6b;
    transform: translateX(4px);
    text-decoration: none;
}

/* Social ikone */
footer i.fab {
    transition: transform 0.3s ease, color 0.3s ease;
}

footer a:hover i.fab {
    transform: scale(1.2);
    color: #ff6b6b;
}

/* Horizontalna linija */
footer hr {
    border-color: #333;
    margin-top: 25px;
    margin-bottom: 15px;
}

/* Copyright */
footer .text-center.text-secondary {
    color: #777 !important;
    font-size: 0.85rem;
}

/* ===== ANIMACIJA ULASKA ===== */
footer .row > div {
    transition: transform 0.3s ease;
}

footer .row > div:hover {
    transform: translateY(-3px);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {

    footer .row {
        text-align: center;
    }

    footer .col-md-4 {
        margin-bottom: 25px;
    }

    footer h6::after {
        margin-left: auto;
        margin-right: auto;
    }
}

/* ===== BLAGI GLOW EFEKAT NA HOVER ===== */
footer a:hover {
    text-shadow: 0 0 8px rgba(255, 107, 107, 0.5);
}

</style>