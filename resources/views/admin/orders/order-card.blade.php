@php
    $statusMap = [
        'primljena'     => ['border-warning', 'bg-warning text-dark', 'Primljena'],
        'u_pripremi'    => ['border-primary', 'bg-primary', 'U pripremi'],
        'dostavlja_se'  => ['border-success', 'bg-success', 'Dostavlja se'],
        'rejected'      => ['border-danger', 'bg-danger', 'Odbijeno'],
    ];

    [$border, $badge, $label] =
        $statusMap[$order->status] ?? ['border-secondary', 'bg-secondary', ucfirst($order->status)];

    // delivery_info može biti string ili array
    $info = $order->delivery_info;
    if (is_string($info)) {
        $info = json_decode($info, true);
    }
    $info = $info ?? [];
@endphp

<div class="card mb-3 shadow-sm {{ $border }} order-card"
     data-order-id="{{ $order->id }}"
     data-status="{{ $order->status }}">

    {{-- HEADER --}}
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>#{{ $order->id }}</strong>
        <span class="badge {{ $badge }}">{{ $label }}</span>
    </div>

    {{-- BODY --}}
    <div class="card-body">

        {{-- TIP + CENA --}}
        <div class="d-flex justify-content-between mb-2">
            <div>
                <strong>📦</strong>
                {{ $order->order_type === 'delivery' ? 'Dostava' : 'Preuzimanje' }}
            </div>
            <div>
                <strong>💰</strong>
                {{ number_format($order->total_price, 0) }} RSD
            </div>
        </div>

        {{-- DOSTAVA --}}
        @if($order->order_type === 'delivery')
            <div class="mb-2 bg-light p-2 rounded">
                <div><strong>👤</strong> {{ $info['ime'] ?? '—' }}</div>
                <div><strong>📞</strong> {{ $info['telefon'] ?? '—' }}</div>
                <div><strong>📍</strong> {{ $info['adresa'] ?? '—' }}</div>

                @if($order->delivery_zone)
                    <div>
                        <strong>🚚</strong>
                        {{ $order->delivery_zone }}
                        (+{{ number_format($order->delivery_price, 0) }} RSD)
                    </div>
                @endif
            </div>
        @endif

        {{-- AKO JE ODBIJENO – PRIKAZ RAZLOGA --}}
        @if($order->status === 'rejected')
            <div class="alert alert-danger py-2 mb-3">
                ❌ <strong>Porudžbina je odbijena</strong><br>
                <strong>Razlog:</strong> {{ $order->rejection_reason ?? '—' }}
            </div>
        @endif

        {{-- ODBROJAVANJE --}}
        @if($order->status === 'u_pripremi' && $order->ready_at)
            <div class="alert alert-info text-center py-2 mb-2">
                ⏱️ Preostalo vreme:

                <div
                    class="fw-bold countdown"
                    data-ready-at="{{ \Carbon\Carbon::parse($order->ready_at)->toIso8601String() }}">
                    --
                </div>

                <span class="badge bg-danger d-none late-badge mt-2">
                    ⛔ KASNI
                </span>
            </div>
        @endif

        {{-- GLAVNA NAPOMENA PORUDŽBINE --}}
        @if(!empty($info['napomena']))
            <div class="alert alert-warning py-2 mb-3">
                📝 <strong>Napomena uz porudžbinu:</strong><br>
                {{ $info['napomena'] }}
            </div>
        @endif

        <hr class="my-2">

        {{-- JELA --}}
        <ul class="list-unstyled mb-0">
        @foreach($order->orderProducts as $item)

            @php
                $d = $item->details;
                if (is_string($d)) {
                    $d = json_decode($d, true);
                }
                $d = $d ?? [];

                $addonNames = [];
                if (!empty($d['addons'])) {
                    $addonNames = \App\Models\AddOn::whereIn('id', $d['addons'])
                        ->pluck('name')
                        ->toArray();
                }
            @endphp

            <li class="mb-3">
                <strong>{{ $item->product->name ?? 'Proizvod' }}</strong>
                × {{ $item->quantity }}

                <ul class="ps-3 small text-muted mt-1">

                    @if(!empty($d['size']))
                        <li>📏 <strong>Veličina:</strong> {{ ucfirst($d['size']) }}</li>
                    @endif

                    @if(!empty($d['sos']))
                        <li>🥫 <strong>Sos:</strong> {{ $d['sos'] }}</li>
                    @endif

                    @if(!empty($d['meat']))
                        <li>🥩 <strong>Meso:</strong> {{ $d['meat'] }}</li>
                    @endif

                    @if(!empty($addonNames))
                        <li>➕ <strong>Dodaci:</strong> {{ implode(', ', $addonNames) }}</li>
                    @endif

                    @if(isset($d['mix_rice']))
                        <li>
                            🍚 <strong>Pirinac:</strong>
                            {{ $d['mix_rice'] === 'da' ? 'Meša se u jelo' : 'Odvojeno' }}
                        </li>
                    @endif


                    @if(!empty($d['cutlery']))
                        <li>
                            🍴 <strong>Pribor:</strong>
                            @switch($d['cutlery'])
                                @case('stapici')
                                    🥢 Štapići
                                    @break
                                @case('plasticni')
                                    🍴 Plastični pribor
                                    @break
                                @case('bez')
                                    ❌ Bez pribora
                                    @break
                                @default
                                    —
                            @endswitch
                        </li>
                    @endif

                    @if(!empty($d['notes']))
                        <li>
                            <div class="alert alert-secondary py-1 px-2 mb-1">
                                🧑‍🍳 <strong>Poruka kuvaru:</strong><br>
                                {{ $d['notes'] }}
                            </div>
                        </li>
                    @endif

                </ul>
            </li>

        @endforeach
        </ul>

    </div>

    {{-- FOOTER --}}
    <div class="card-footer text-end">

        @if($order->status === 'primljena')

            <button
                class="btn btn-sm btn-primary open-accept-modal"
                data-id="{{ $order->id }}">
                Prihvati
            </button>

            <button
                class="btn btn-sm btn-danger open-reject-modal"
                data-id="{{ $order->id }}">
                Odbij
            </button>

        @elseif($order->status === 'u_pripremi')

            <button
                class="btn btn-sm btn-success mark-ready-btn"
                data-id="{{ $order->id }}">
                Spremno
            </button>

        @endif

    </div>

</div>
