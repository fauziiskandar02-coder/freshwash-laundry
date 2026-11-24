/**
 * Invoice Dynamic Line Items & Real-Time Calculation Engine
 * Designed for AIS Revenue Cycle - Web UMKM
 */

(function ($) {
    'use strict';

    function formatRupiah(number) {
        return 'Rp ' + Number(number).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    }

    function calculateTotals() {
        let subtotal = 0;
        let totalDiscount = 0;

        $('#invoice-lines-tbody tr.line-row').each(function () {
            const row = $(this);
            const qty = parseFloat(row.find('.item-qty').val()) || 0;
            const price = parseFloat(row.find('.item-price').val()) || 0;
            const rawTotal = qty * price;

            const discType = row.find('.item-disc-type').val();
            const discVal = parseFloat(row.find('.item-disc-val').val()) || 0;
            let discAmt = 0;

            if (discType === 'percentage') {
                discAmt = (rawTotal * discVal) / 100;
            } else if (discType === 'amount') {
                discAmt = Math.min(rawTotal, discVal);
            }

            const lineTotal = Math.max(0, rawTotal - discAmt);

            row.find('.item-line-total-text').text(formatRupiah(lineTotal));
            row.find('.item-line-total').val(lineTotal.toFixed(2));
            row.find('.item-disc-amt').val(discAmt.toFixed(2));

            subtotal += rawTotal;
            totalDiscount += discAmt;
        });

        const taxableAmount = Math.max(0, subtotal - totalDiscount);
        const isPkp = $('#is_pkp').is(':checked');
        const taxRate = isPkp ? (parseFloat($('#tax_rate').val()) || 0) : 0;
        const taxAmount = (taxableAmount * taxRate) / 100;
        const grandTotal = taxableAmount + taxAmount;

        // Update UI Summary
        $('#summary-subtotal').text(formatRupiah(subtotal));
        $('#summary-discount').text('- ' + formatRupiah(totalDiscount));
        $('#summary-taxable').text(formatRupiah(taxableAmount));
        $('#summary-tax-rate-label').text(taxRate.toFixed(1) + '%');
        $('#summary-tax-amount').text(formatRupiah(taxAmount));
        $('#summary-grand-total').text(formatRupiah(grandTotal));

        // Update Hidden Inputs
        $('#input-subtotal').val(subtotal.toFixed(2));
        $('#input-discount-total').val(totalDiscount.toFixed(2));
        $('#input-tax-amount').val(taxAmount.toFixed(2));
        $('#input-grand-total').val(grandTotal.toFixed(2));
    }

    // Toggle Payment Type -> Due Date display
    function togglePaymentType() {
        const pType = $('input[name="payment_type"]:checked').val();
        if (pType === 'kredit') {
            $('#due-date-container').slideDown(200);
            $('#due_date').prop('required', true);
        } else {
            $('#due-date-container').slideUp(200);
            $('#due_date').prop('required', false);
        }
    }

    // Toggle PKP -> Tax Rate field
    function togglePkp() {
        const isPkp = $('#is_pkp').is(':checked');
        if (isPkp) {
            $('#tax-rate-container').slideDown(200);
            $('#tax-summary-row').show();
        } else {
            $('#tax-rate-container').slideUp(200);
            $('#tax-summary-row').hide();
        }
        calculateTotals();
    }

    $(document).ready(function () {
        // Init state
        togglePaymentType();
        togglePkp();
        calculateTotals();

        $('input[name="payment_type"]').on('change', togglePaymentType);
        $('#is_pkp').on('change', togglePkp);
        $('#tax_rate').on('input change', calculateTotals);

        // Tambah Baris Item Baru
        $('#btn-add-line').on('click', function () {
            const rowCount = $('#invoice-lines-tbody tr.line-row').length;
            const newRow = `
                <tr class="line-row">
                    <td>
                        <select class="form-control item-select" name="item_id[]">
                            <option value="">-- Pilih Layanan Jasa --</option>
                            ${$('#item-options-template').html()}
                        </select>
                        <input type="text" class="form-control form-control-sm mt-1 item-name" name="item_name[]" placeholder="Deskripsi layanan...">
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0.01" class="form-control text-right item-qty" name="quantity[]" value="1.00">
                    </td>
                    <td>
                        <input type="number" step="1000" min="0" class="form-control text-right item-price" name="unit_price[]" value="0">
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <select class="form-control item-disc-type" name="discount_type[]" style="max-width: 80px;">
                                <option value="none">0</option>
                                <option value="percentage">%</option>
                                <option value="amount">Rp</option>
                            </select>
                            <input type="number" step="any" min="0" class="form-control text-right item-disc-val" name="discount_value[]" value="0">
                        </div>
                        <input type="hidden" class="item-disc-amt" name="discount_amount[]" value="0">
                    </td>
                    <td class="text-right">
                        <span class="font-weight-bold item-line-total-text">Rp 0</span>
                        <input type="hidden" class="item-line-total" name="line_total[]" value="0">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-outline-danger btn-sm btn-remove-line" title="Hapus Baris">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#invoice-lines-tbody').append(newRow);
            calculateTotals();
        });

        // Hapus Baris
        $(document).on('click', '.btn-remove-line', function () {
            const totalRows = $('#invoice-lines-tbody tr.line-row').length;
            if (totalRows <= 1) {
                alert('Faktur minimal harus memiliki satu baris item layanan.');
                return;
            }
            $(this).closest('tr.line-row').remove();
            calculateTotals();
        });

        // Ketika Item Dipilih dari Dropdown -> Ambil Harga via AJAX
        $(document).on('change', '.item-select', function () {
            const select = $(this);
            const itemId = select.val();
            const row = select.closest('tr.line-row');

            if (!itemId) {
                row.find('.item-name').val('');
                row.find('.item-price').val(0);
                calculateTotals();
                return;
            }

            const itemName = select.find('option:selected').data('name');
            const itemPrice = select.find('option:selected').data('price');

            row.find('.item-name').val(itemName);
            row.find('.item-price').val(itemPrice);
            calculateTotals();
        });

        // Live calculation triggers
        $(document).on('input change', '.item-qty, .item-price, .item-disc-type, .item-disc-val', function () {
            calculateTotals();
        });
    });

})(jQuery);
