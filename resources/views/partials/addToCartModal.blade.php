<div class="modal fade" id="addToCartModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Dodaj u korpu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="addToCartForm" method="POST" action="{{ route('cart.add') }}">
          @csrf
          <input type="hidden" id="modalProductId" name="product_id">
          <input type="hidden" id="productBasePrice" name="base_price">

          <!-- Veličina -->
          <div class="mb-3" id="sizeSection">
            <label class="form-label fw-bold">Veličina</label>
            <select class="form-select" name="size" id="productSize">
              <option value="">Izaberi veličinu</option>
              <option value="mala" data-price="0">Mala</option>
              <option value="velika" data-price="200">Velika (+200 RSD)</option>
            </select>
          </div>

          <!-- Sos -->
          <div class="mb-3" id="sosSection">
            <label class="form-label fw-bold">Sos</label>
            <select class="form-select" name="sos">
              <option value="">Izaberi sos</option>
              <option value="Tomato">Tomato</option>
              <option value="Soja">Soja</option>
              <option value="Sečuan">Sečuan</option>
            </select>
          </div>

          <!-- Dodaci -->
          <div class="mb-3" id="addonsSection">
            <label class="form-label fw-bold">Dodaci</label>
            @foreach($addons as $addon)
              <div class="form-check">
                <input class="form-check-input addon-checkbox" type="checkbox" name="addons[]" value="{{ $addon->id }}" data-price="{{ $addon->price }}" id="addon{{ $addon->id }}">
                <label class="form-check-label" for="addon{{ $addon->id }}">
                  {{ $addon->name }} +{{ number_format($addon->price, 0, ',', '.') }} RSD
                </label>
              </div>
            @endforeach
          </div>

          <!-- Meso -->
          <div class="mb-3" id="meatSection">
            <label class="form-label fw-bold">Meso</label>
            <select class="form-select" name="meat">
              <option value="">Izaberi meso</option>
              <option value="Piletina">Piletina</option>
              <option value="Svinjetina">Svinjetina</option>
            </select>
          </div>

          <!-- Mešanje pirinča -->
          <div class="mb-3" id="riceMixSection" style="display:none;">
              <label class="form-label fw-bold">Da li se meša pirinač u jelo?</label>
              <div class="form-check">
                  <input class="form-check-input" type="radio" name="mix_rice" value="da" id="riceYes">
                  <label class="form-check-label" for="riceYes">Da</label>
              </div>
              <div class="form-check">
                  <input class="form-check-input" type="radio" name="mix_rice" value="ne" id="riceNo">
                  <label class="form-check-label" for="riceNo">Ne</label>
              </div>
          </div>

          <!-- Pribor -->
          <div class="mb-3">
              <label class="form-label fw-bold">Pribor</label>

              <div class="form-check">
                  <input class="form-check-input" type="radio" name="cutlery" value="stapici" id="cutleryChopsticks">
                  <label class="form-check-label" for="cutleryChopsticks">
                      🥢 Štapići
                  </label>
              </div>

              <div class="form-check">
                  <input class="form-check-input" type="radio" name="cutlery" value="plasticni" id="cutleryPlastic">
                  <label class="form-check-label" for="cutleryPlastic">
                      🍴 Plastični pribor
                  </label>
              </div>

              <div class="form-check">
                  <input class="form-check-input" type="radio" name="cutlery" value="bez" id="cutleryNone" checked>
                  <label class="form-check-label" for="cutleryNone">
                      ❌ Bez pribora
                  </label>
              </div>
          </div>



          <!-- Poruka kuvaru -->
          <div class="mb-3">
            <label class="form-label fw-bold">Poruka kuvaru</label>
            <textarea class="form-control" name="notes" rows="2"></textarea>
          </div>

          <!-- Količina -->
          <div class="mb-3">
            <label class="form-label fw-bold">Količina</label>
            <input type="number" class="form-control" name="quantity" id="productQuantity" min="1" value="1" required>
          </div>

          <!-- Cena -->
          <div class="mb-3 text-center">
            <h5><strong>Ukupna cena: <span id="totalPrice">0</span> RSD</strong></h5>
          </div>

          <!-- Buttons -->
          <div class="d-flex justify-content-center gap-3 mt-3">
            <button type="submit" class="btn btn-success px-4">Dodaj u korpu</button>
            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Otkaži</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
  