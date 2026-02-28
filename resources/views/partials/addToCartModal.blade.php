<div class="modal fade" id="addToCartModal" tabindex="-1">
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
          <input type="hidden" id="productBasePrice">
          <input type="hidden" id="isDrink">

          {{-- VELIČINA --}}
          <div class="mb-3" id="sizeSection">
            <label class="form-label fw-bold">Veličina</label>
            <select class="form-select" name="size" id="productSize">
              <option value="">Izaberi veličinu</option>
              <option value="mala">Mala</option>
              <option value="velika">Velika (+200 RSD)</option>
            </select>
          </div>

          {{-- SOS --}}
          <div class="mb-3" id="sosSection">
            <label class="form-label fw-bold">Sos</label>
            <select class="form-select" name="sos" id="sosSelect">
              <option value="">Izaberi sos</option>
              <option value="Tomato">Tomato</option>
              <option value="Soja">Soja</option>
              <option value="Sečuan">Sečuan</option>
            </select>
          </div>

          {{-- MESO --}}
          <div class="mb-3" id="meatSection">
            <label class="form-label fw-bold">Meso</label>
            <select class="form-select" name="meat" id="meatSelect">
              <option value="">Izaberi meso</option>
              <option value="Piletina">Piletina</option>
              <option value="Svinjetina">Svinjetina</option>
            </select>
          </div>

          {{-- DODACI --}}
          <div class="mb-3" id="addonsSection" style="display:none;">
            <label class="form-label fw-bold">Dodaci</label>
            <div id="addonsContainer"></div>
          </div>

          {{-- PIRINAČ --}}
          <div class="mb-3" id="riceSection">
            <label class="form-label fw-bold">Da li se meša pirinač?</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="mix_rice" value="da">
              <label class="form-check-label">🍚 Da</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="mix_rice" value="ne">
              <label class="form-check-label">❌ Ne</label>
            </div>
          </div>

          {{-- PRIBOR --}}
          <div class="mb-3">
            <label class="form-label fw-bold">Pribor</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="utensils" value="stapici" >
              <label class="form-check-label">🥢 Štapići</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="utensils" value="plasticni" >
              <label class="form-check-label">🍴 Plastični pribor</label>
            </div>
          </div>

          {{-- KOLIČINA --}}
          <div class="mb-3">
            <label class="form-label fw-bold">Količina</label>
            <input type="number" class="form-control" name="quantity" id="productQuantity" min="1" value="1" required>
          </div>

          {{-- UKUPNA CENA --}}
          <div class="mb-3 text-center">
            <h5><strong>Ukupna cena: <span id="totalPrice">0</span> RSD</strong></h5>
          </div>

          <div class="d-flex justify-content-center gap-3 mt-3">
            <button type="submit" class="btn btn-success px-4">Dodaj u korpu</button>
            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Otkaži</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('addToCartForm');
    const productIdInput = document.getElementById('modalProductId');
    const basePriceInput = document.getElementById('productBasePrice');
    const isDrinkInput = document.getElementById('isDrink');

    const totalPriceEl = document.getElementById('totalPrice');
    const quantityInput = document.getElementById('productQuantity');
    const sizeSelect = document.getElementById('productSize');

    const addonsContainer = document.getElementById('addonsContainer');
    const addonsSection = document.getElementById('addonsSection');

    document.querySelectorAll('.order-btn').forEach(button => {

        button.addEventListener('click', function () {

            const price = parseFloat(this.dataset.price);
            const isDrink = this.dataset.isDrink == "1";
            const categoryAddons = JSON.parse(this.dataset.addons || '[]');

            productIdInput.value = this.dataset.id;
            basePriceInput.value = price;
            isDrinkInput.value = isDrink ? 1 : 0;

            addonsContainer.innerHTML = '';

            if (categoryAddons.length > 0) {
                addonsSection.style.display = 'block';

                categoryAddons.forEach(addon => {

                    const id = "addon_" + addon.id + "_" + Math.random().toString(36).substr(2, 5);

                    const wrapper = document.createElement('div');
                    wrapper.className = "form-check";

                    wrapper.innerHTML = `
                        <input class="form-check-input addon-checkbox"
                               type="checkbox"
                               id="${id}"
                               name="addons[]"
                               value="${addon.id}"
                               data-price="${addon.price}">
                        <label class="form-check-label"
                               for="${id}"
                               style="cursor:pointer;">
                            ${addon.name} +${addon.price} RSD
                        </label>
                    `;

                    addonsContainer.appendChild(wrapper);
                });

            } else {
                addonsSection.style.display = 'none';
            }

            recalcPrice();
        });

    });

    function recalcPrice() {

        let base = parseFloat(basePriceInput.value) || 0;

        if (sizeSelect.value === 'velika') {
            base += 200;
        }

        document.querySelectorAll('.addon-checkbox:checked').forEach(cb => {
            base += parseFloat(cb.dataset.price);
        });

        const qty = parseInt(quantityInput.value) || 1;

        // 15% POPUST NA SVE OSIM PIĆA
        if (isDrinkInput.value != "1") {
            base = base * 0.85;
        }

        totalPriceEl.innerText = (base * qty).toFixed(0);
    }

    document.addEventListener('change', function(e){
        if(e.target.classList.contains('addon-checkbox') || e.target.id === 'productSize') {
            recalcPrice();
        }
    });

    quantityInput.addEventListener('input', recalcPrice);

});
</script>