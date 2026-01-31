<footer class="bg-dark text-white pt-5 pb-4 mt-5">
    <div class="container">
        <div class="row">

            {{-- LOGO + OPIS --}}
            <div class="col-md-4 mb-4 text-center text-md-start">
                <img src="{{ asset('assets/logo.png') }}" alt="Mister Wang Logo" height="60" class="mb-3">
                <p class="text-secondary small">
                    Autentična kineska kuhinja <br>
                    Brza dostava i kvalitetni sastojci.
                </p>
            </div>

            {{-- KONTAKT --}}
            <div class="col-md-4 mb-4 text-center text-md-start">
                <h6 class="fw-bold mb-3">Kontakt</h6>
                <p class="mb-1">📞 <a href="tel:0654522157" class="text-white text-decoration-none">064 52 14 800</a></p>
                <p class="mb-1">📞 <a href="tel:0654522157" class="text-white text-decoration-none">064 52 14 802</a></p>
                <p class="mb-1">✉️ <a href="mailto:djordjekitic2003@gmail.com" class="text-white text-decoration-none">djordjekitic2003@gmail.com</a></p>

                {{-- RADNO VREME --}}
                <h6 class="fw-bold mt-3 mb-2">Radno vreme</h6>
                <p class="mb-1">Radnim danima: 9-22h</p>
                <p class="mb-1">Nedelja: 11-20h</p>
                <p class="mb-1">Subota: Ne radimo</p>
            </div>

            {{-- DRUŠTVENE MREŽE --}}
            <div class="col-md-4 mb-4 text-center text-md-start">
                <h6 class="fw-bold mb-3">Pratite nas</h6>
                <a href="https://www.instagram.com/mister_wang_2_miljakovac/" target="_blank"
                   class="text-white text-decoration-none d-inline-flex align-items-center gap-2 mb-2">
                    <i class="fab fa-instagram fa-lg"></i> Instagram
                </a><br>
                <a href="#" target="_blank"
                   class="text-white text-decoration-none d-inline-flex align-items-center gap-2 mb-2">
                    <i class="fab fa-facebook fa-lg"></i> Facebook
                </a><br>
                <a href="#" target="_blank"
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
    /* Footer pozadina i tekst */
    footer {
        background: #1a1a1a; /* tamnija nijansa crne */
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Logo */
    footer img {
        transition: transform 0.3s;
    }

    footer img:hover {
        transform: scale(1.1);
    }

    /* Naslovi u footeru */
    footer h6 {
        color: #fff;
        letter-spacing: 0.5px;
    }

    /* Linkovi */
    footer a {
        color: #fff;
        transition: color 0.3s, transform 0.3s;
    }

    footer a:hover {
        color: #ff6b6b; /* crveno-roza boja pri hoveru */
        transform: translateX(5px);
        text-decoration: none;
    }

    /* Ikonice društvenih mreža */
    footer i.fab {
        transition: transform 0.3s, color 0.3s;
    }

    footer a:hover i.fab {
        color: #ff6b6b;
        transform: scale(1.2);
    }

    /* Radno vreme i kontakt */
    footer p {
        color: #ccc;
    }

    /* Linija ispod */
    footer hr {
        border-color: #444;
    }

    /* Copyright */
    footer .text-center.text-secondary {
        color: #888;
        margin-top: 10px;
    }

    /* Responsivnost */
    @media (max-width: 768px) {
        footer .row {
            text-align: center;
        }
        footer .col-md-4 {
            margin-bottom: 20px;
        }
    }
</style>
