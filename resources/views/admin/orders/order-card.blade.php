@php
    $statusMap = [
        'primljena'     => ['border-warning', 'bg-warning text-dark', 'Primljena'],
        'u_pripremi'    => ['border-primary', 'bg-primary', 'U pripremi'],
        'dostavlja_se'  => ['border-success', 'bg-success', 'Dostavlja se'],
        'rejected'      => ['border-danger', 'bg-danger', 'Odbijeno'],
    ];

    [$border, $badge, $label] =
        $statusMap[$order->status] ?? ['border-secondary', 'bg-secondary', ucfirst($order->status)];

    $info = $order->delivery_info;
    if (is_string($info)) {
        $info = json_decode($info, true);
    }
    $info = $info ?? [];
@endphp

<div class="card mb-3 shadow-sm {{ $border }} order-card"
     data-order-id="{{ $order->id }}"
     data-status="{{ $order->status }}">

    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>#{{ $order->id }}</strong>
        <span class="badge {{ $badge }}">{{ $label }}</span>
    </div>

    <div class="card-body">

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

        @if($order->status === 'rejected')
            <div class="alert alert-danger py-2 mb-3">
                ❌ <strong>Porudžbina je odbijena</strong><br>
                <strong>Razlog:</strong> {{ $order->rejection_reason ?? '—' }}
            </div>
        @endif

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

        @if(!empty($info['napomena']))
            <div class="alert alert-warning py-2 mb-3">
                📝 <strong>Napomena uz porudžbinu:</strong><br>
                {{ $info['napomena'] }}
            </div>
        @endif

        <hr class="my-2">

        <ul class="list-unstyled mb-0">
        @foreach($order->orderProducts as $item)

            @php
                $d = $item->details;
                if (is_string($d)) {
                    $d = json_decode($d, true);
                }
                $d = $d ?? [];

                $product = $item->product;

                $original = $order->order_type === 'takeaway'
                    ? $product->price_takeaway
                    : $product->price_delivery;

                $discounted = $product->price;

                $isPice = ($product->category && $product->category->slug == 'pice');

                $addonsPrice = 0;
                $addonNames = [];

                if (!empty($d['addons'])) {
                    $addonsPrice = \App\Models\AddOn::whereIn('id', $d['addons'])->sum('price');
                    $addonNames = \App\Models\AddOn::whereIn('id', $d['addons'])
                        ->pluck('name')
                        ->toArray();
                }

                $sizeExtra = (!empty($d['size']) && $d['size'] === 'velika') ? 200 : 0;

                $finalPrice = $discounted + $addonsPrice + $sizeExtra;

                $lineTotal = $finalPrice * $item->quantity;
            @endphp

            <li class="mb-3">

                <div class="d-flex justify-content-between">
                    <div>
                        <strong>{{ $product->name ?? 'Proizvod' }}</strong>
                        × {{ $item->quantity }}
                    </div>

                    <div class="text-end small">
                        @if(!$isPice)
                            <span style="text-decoration:line-through;color:gray;">
                                {{ number_format($original, 0) }} RSD
                            </span>
                            <br>
                            <strong style="color:#d32f2f;">
                                {{ number_format($discounted, 0) }} RSD
                            </strong>
                        @else
                            <strong>
                                {{ number_format($original, 0) }} RSD
                            </strong>
                        @endif
                    </div>
                </div>

                @if($addonsPrice > 0 || $sizeExtra > 0)
                    <div class="text-end text-muted small">
                        @if($addonsPrice > 0)
                            + dodaci: {{ number_format($addonsPrice, 0) }} RSD<br>
                        @endif

                        @if($sizeExtra > 0)
                            + velika porcija: 200 RSD<br>
                        @endif

                        <strong>Ukupno stavka: {{ number_format($lineTotal, 0) }} RSD</strong>
                    </div>
                @endif

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

                    {{-- PRIKAZUJEMO SAMO AKO JE IZABRANO DA SE MEŠA PIRINAČ --}}
                    @if(!empty($d['mix_rice']) && $d['mix_rice'] === 'da')
                        <li>🍚 <strong>Pirinac:</strong> Meša se u jelo</li>
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
