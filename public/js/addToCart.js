document.addEventListener('DOMContentLoaded', function() {

    console.log("addToCart.js učitan ✔");

    const modalEl = document.getElementById('addToCartModal');
    const addToCartForm = document.getElementById('addToCartForm');

    if (!modalEl || !addToCartForm) return;

    let basePrice = 0;
    let currentModal = new bootstrap.Modal(modalEl);

    const quantityInput = document.getElementById('productQuantity');
    const sizeSelect = document.getElementById('productSize');
    const totalPriceEl = document.getElementById('totalPrice');
    const addonCheckboxes = document.querySelectorAll('.addon-checkbox');

    const sizeSection = document.getElementById('sizeSection');
    const sosSection = document.getElementById('sosSection');
    const addonsSection = document.getElementById('addonsSection');
    const meatSection = document.getElementById('meatSection');
    const riceMixSection = document.getElementById('riceMixSection'); // nova sekcija

    document.querySelectorAll('.order-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.id;
            const productPrice = parseInt(this.dataset.price);
            const productType = this.dataset.type; // meat / vegetarian / seafood

            resetForm();

            document.getElementById('modalProductId').value = productId;
            document.getElementById('productBasePrice').value = productPrice;
            basePrice = productPrice;
            totalPriceEl.textContent = basePrice;

            // Prikaz/sakrivanje sekcija po proizvodu
            sizeSection.style.display   = this.dataset.hideSize === "1"  ? "none" : "block";
            sosSection.style.display    = this.dataset.hideSos === "1"   ? "none" : "block";
            addonsSection.style.display = this.dataset.hideAddons === "1"? "none" : "block";
            meatSection.style.display   = this.dataset.hideMeat === "1"  ? "none" : "block";

            // Required polja
            if (sizeSelect) sizeSelect.required = this.dataset.hideSize !== "1";
            const sosSelect = sosSection.querySelector('select');
            if (sosSelect) sosSelect.required = this.dataset.hideSos !== "1";
            const meatSelect = meatSection.querySelector('select');
            if (meatSelect) meatSelect.required = this.dataset.hideMeat !== "1";

            // Prikaz sekcije za mešanje pirinča samo za tri kategorije
            const productCategory = this.dataset.category;
            if (['jela-sa-mesom', 'jela-bez-mesa', 'morski-plodovi'].includes(productCategory)) {
                riceMixSection.style.display = 'block';
            } else {
                riceMixSection.style.display = 'none';
                riceMixSection.querySelectorAll('input').forEach(i => i.checked = false);
            }


            currentModal.show();
        });
    });

    // Kalkulacija ukupne cene
    function calculateTotal() {
        let total = basePrice;

        if (sizeSelect && sizeSelect.value) {
            total += parseInt(sizeSelect.selectedOptions[0].dataset.price || 0);
        }

        addonCheckboxes.forEach(cb => {
            if (cb.checked) total += parseInt(cb.dataset.price || 0);
        });

        total *= parseInt(quantityInput.value || 1);
        totalPriceEl.textContent = total;
    }

    if (sizeSelect) sizeSelect.addEventListener('change', calculateTotal);
    if (quantityInput) quantityInput.addEventListener('input', calculateTotal);
    addonCheckboxes.forEach(cb => cb.addEventListener('change', calculateTotal));

    // Submit forme AJAX
    addToCartForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                let badge = document.querySelector('.cart-icon-wrapper .badge');

                if (badge) {
                    badge.textContent = data.cart_count;
                } else {
                    const span = document.createElement('span');
                    span.classList.add('badge');
                    span.textContent = data.cart_count;
                    document.querySelector('.cart-icon-wrapper').appendChild(span);
                }

                currentModal.hide();
            } else {
                alert('Došlo je do greške!');
            }
        })
        .catch(err => console.error(err));
    });

    // Reset forme i overlay kada se modal zatvori
    modalEl.addEventListener('hidden.bs.modal', function () {
        resetForm();
        riceMixSection.style.display = 'none';
        riceMixSection.querySelectorAll('input').forEach(i => i.checked = false);
        document.body.classList.remove('modal-open');
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    });

    // Funkcija za reset forme
    function resetForm() {
        if (!addToCartForm) return;

        addToCartForm.reset();
        if (sizeSelect) sizeSelect.value = "";
        if (quantityInput) quantityInput.value = 1;
        if (totalPriceEl) totalPriceEl.textContent = basePrice;
        addonCheckboxes.forEach(cb => cb.checked = false);
    }

});
