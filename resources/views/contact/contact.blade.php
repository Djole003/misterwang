@extends('layouts.app')
@include('partials.header')

@section('content')

<style>
    #map {
        height: 500px;
        width: 100%;
        border-radius: 10px;
        border: 1px solid #ddd;
    }

    .zone-legend {
        background: white;
        padding: 10px 14px;
        line-height: 1.6;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        font-size: 0.85rem;
    }

    .zone-color-box {
        display:inline-block;
        width:14px;
        height:14px;
        margin-right:6px;
        border-radius:3px;
    }
</style>

<div class="container contact-container">

    <h1 class="contact-title">
        Kontakt – Mister Wang {{ $contact->restaurant->name ?? '' }}
    </h1>

    <p style="max-width:720px; margin:0 auto 30px; color:#555; font-size:0.95rem; text-align:center;">
        Kontaktirajte kineski restoran Mister Wang – {{ $contact->restaurant->name ?? '' }}.
        Proverite radno vreme, lokaciju i dostavu po zonama.
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

    {{-- MAPA --}}
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

{{-- LEAFLET --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const center = [
        {{ $contact->restaurant->center_lat }},
        {{ $contact->restaurant->center_lng }}
    ];

    const map = L.map('map').setView(center, 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    L.marker(center)
        .addTo(map)
        .bindPopup("<b>{{ $contact->restaurant->name }}</b><br>{{ $contact->address }}")
        .openPopup();

    const zones = @json($zones);

    function getZoneColor(name) {
        if (name.includes('Zelena')) return '#2ecc71';
        if (name.includes('Zuta')) return '#f1c40f';
        if (name.includes('Narandzasta')) return '#e67e22';
        if (name.includes('Crvena')) return '#e74c3c';
        return '#3498db';
    }

    const group = L.featureGroup().addTo(map);

    zones.forEach(zone => {

        if (!zone.polygon) return;

        const polygonData = zone.polygon;

        const coords = polygonData.map(point => [
            parseFloat(point.lat),
            parseFloat(point.lng)
        ]);

        const color = getZoneColor(zone.name);

        const polygon = L.polygon(coords, {
            color: color,
            fillColor: color,
            fillOpacity: 0.35,
            weight: 2
        }).addTo(group);

        polygon.bindPopup(`
            <strong>${zone.name}</strong><br>
            Minimalna porudžbina: ${zone.minimum_amount} RSD<br>
            Cena dostave: ${zone.price} RSD
        `);
    });

    if (group.getLayers().length > 0) {
        map.fitBounds(group.getBounds());
    }

    // LEGENDA
    const legend = L.control({ position: "bottomright" });

    legend.onAdd = function () {
        const div = L.DomUtil.create("div", "zone-legend");
        div.innerHTML += "<strong>Zone dostave</strong><br>";
        div.innerHTML += '<span class="zone-color-box" style="background:#2ecc71"></span>Zelena<br>';
        div.innerHTML += '<span class="zone-color-box" style="background:#f1c40f"></span>Žuta<br>';
        div.innerHTML += '<span class="zone-color-box" style="background:#e67e22"></span>Narandžasta<br>';
        div.innerHTML += '<span class="zone-color-box" style="background:#e74c3c"></span>Crvena';
        return div;
    };

    legend.addTo(map);
</script>

@include('partials.footer')
@endsection