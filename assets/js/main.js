document.addEventListener('DOMContentLoaded', function () {
    // Live total price on the booking form.
    var qtyInput = document.getElementById('quantity');
    var totalBox = document.getElementById('total-price');
    if (qtyInput && totalBox) {
        var unitPrice = parseFloat(qtyInput.dataset.price || '0');
        var update = function () {
            var qty = parseInt(qtyInput.value, 10) || 1;
            if (qty < 1) qty = 1;
            totalBox.textContent = (unitPrice * qty).toLocaleString('en-EG') + ' EGP';
        };
        qtyInput.addEventListener('input', update);
        update();
    }

    // Ask before deleting in the admin panel.
    document.querySelectorAll('form.confirm-delete').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!window.confirm('Delete this item? This cannot be undone.')) {
                event.preventDefault();
            }
        });
    });
});
