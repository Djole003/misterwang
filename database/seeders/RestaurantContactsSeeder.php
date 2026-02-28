<footer class="bg-dark text-white pt-5 pb-4 mt-5">
    <div class="container">
        <div class="row">

            {{-- LOGO --}}
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

                @if($footerContact)
                    <p class="mb-1">
                        📞 <a href="tel:{{ str_replace(' ', '', $footerContact->phone) }}"
                              class="text-white text-decoration-none">
                            {{ $footerContact->phone }}
                        </a>
                    </p>

                    <p class="mb-1">
                        ✉️ <a href="mailto:{{ $footerContact->email }}"
                              class="text-white text-decoration-none">
                            {{ $footerContact->email }}
                        </a>
                    </p>

                    <p class="mb-1">
                        📍 {{ $footerContact->address }}
                    </p>

                    <h6 class="fw-bold mt-3 mb-2">Radno vreme</h6>
                    <p class="mb-1">
                        {{ $footerContact->working_hours }}
                    </p>
                @endif
            </div>

            {{-- DRUŠTVENE MREŽE (možeš dodati kasnije u bazu) --}}
            <div class="col-md-4 mb-4 text-center text-md-start">
                <h6 class="fw-bold mb-3">Pratite nas</h6>

                <a href="https://www.instagram.com/"
                   target="_blank"
                   class="text-white text-decoration-none d-inline-flex align-items-center gap-2 mb-2">
                    <i class="fab fa-instagram fa-lg"></i> Instagram
                </a>
            </div>

        </div>

        <hr class="border-secondary">

        <div class="text-center text-secondary small">
            © {{ date('Y') }} Mister Wang
        </div>
    </div>
</footer>