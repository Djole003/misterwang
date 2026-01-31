@extends('layouts.app')
@include('partials.header')

@section('content')
<div class="container contact-container">

    {{-- SEO H1 --}}
    <h1 class="contact-title">
        Kontakt – Mister Wang {{ $contact->restaurant->name ?? '' }}
    </h1>

    {{-- SEO opis --}}
    <p style="max-width:720px; margin:0 auto 30px; color:#555; font-size:0.95rem; text-align:center;">
        Kontaktirajte kineski restoran Mister Wang – {{ $contact->restaurant->name ?? '' }}.
        Proverite radno vreme, lokaciju i dostavu.
    </p>

    {{-- Kontakt kartice --}}
    <div class="contact-cards">

        <div class="contact-card">
            <i class="fas fa-phone-alt contact-icon"></i>
            <div>
                <h2 style="font-size:1.1rem;">Telefon</h2>
                <p>{{ $contact->phone }}</p>
            </div>
        </div>

        <div class="contact-card">
            <i class="fas fa-envelope contact-icon"></i>
            <div>
                <h2 style="font-size:1.1rem;">Email</h2>
                <p>{{ $contact->email ?? '—' }}</p>
            </div>
        </div>

        <div class="contact-card">
            <i class="fas fa-map-marker-alt contact-icon"></i>
            <div>
                <h2 style="font-size:1.1rem;">Adresa</h2>
                <p>{{ $contact->address }}</p>
            </div>
        </div>

    </div>

    {{-- Radno vreme --}}
    <div class="working-hours mb-4">
        <h2 style="font-size:1.2rem;">Radno vreme</h2>
        <p>{{ $contact->working_hours ?? 'Radno vreme nije definisano.' }}</p>
    </div>

    {{-- Mapa --}}
    <div class="map-container mb-4">
        <h2 style="font-size:1.2rem;">
            Lokacija restorana {{ $contact->restaurant->name }}
        </h2>
        <p style="font-size:0.9rem; color:#666;">
            Dostava hrane dostupna po zonama za izabrani lokal.
        </p>
        <div id="map"></div>
    </div>

    {{-- Recenzije --}}
    <div class="reviews-container">
        <h2 style="font-size:1.2rem;">Ostavite recenziju</h2>

        @auth
            <form action="{{ route('contact.review.submit') }}" method="POST" class="review-form">
                @csrf

                <div class="mb-2">
                    <label for="rating">Ocena (1–5):</label><br>
                    <select id="rating" name="rating" required>
                        <option value="">-- Odaberi ocenu --</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="mb-2">
                    <label for="message">Poruka:</label><br>
                    <textarea id="message" name="message" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Pošalji</button>
            </form>
        @else
            <p>
                Da biste ostavili recenziju, morate biti
                <a href="{{ route('login') }}">prijavljeni</a>.
            </p>
        @endauth
    </div>

    {{-- Prikaz recenzija --}}
    <div class="user-reviews mt-4">
        <h2 style="font-size:1.2rem;">Iskustva korisnika</h2>

        @forelse($reviews as $review)
            <div class="review-card">
                <strong>{{ $review->user->name ?? 'Nepoznat korisnik' }}</strong>
                <span class="review-date">
                    ({{ $review->created_at->format('d.m.Y') }})
                </span><br>
                <em>Ocena: {{ $review->rating }}/5</em>
                <p>{{ $review->comment }}</p>
            </div>
        @empty
            <p>Još uvek nema recenzija.</p>
        @endforelse
    </div>

</div>

{{-- Leaflet --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const center = [
        {{ $contact->restaurant->center_lat }},
        {{ $contact->restaurant->center_lng }}
    ];

    const map = L.map('map').setView(center, 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // 🟢
    L.circle(center, { color:'green', fillOpacity:0.25, radius:2000 })
        .addTo(map).bindPopup("🟢 Zelena zona – 100 RSD");

    // 🟡
    L.circle(center, { color:'yellow', fillOpacity:0.25, radius:4000 })
        .addTo(map).bindPopup("🟡 Žuta zona – 150 RSD");

    // 🟠
    L.circle(center, { color:'orange', fillOpacity:0.25, radius:6000 })
        .addTo(map).bindPopup("🟠 Narandžasta zona – 200 RSD");

    // 🔴
    L.circle(center, { color:'red', fillOpacity:0.25, radius:8500 })
        .addTo(map).bindPopup("🔴 Crvena zona – 250 RSD");

    L.marker(center)
        .addTo(map)
        .bindPopup("<b>{{ $contact->restaurant->name }}</b><br>{{ $contact->address }}")
        .openPopup();
</script>

@include('partials.footer')
@endsection
