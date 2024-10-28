jQuery(document).ready(function($) {

    toggle_method_fields();

    $(document).on('change', '#woocommerce_auctioninc_calc_method', function(e) {
        toggle_method_fields();
    });

    $(document).on('change', '#woocommerce_auctioninc_fixed_mode', function(e) {
        toggle_fixed_fields();
    });

    function toggle_method_fields() {
        var calc_method = $('#woocommerce_auctioninc_calc_method').val();
        if (calc_method === 'C' || calc_method === 'CI') {
            $('#woocommerce_auctioninc_package').closest('tr').fadeIn();
            $('#woocommerce_auctioninc_insurance').closest('tr').fadeIn();
            $('#woocommerce_auctioninc_fixed_mode').closest('tr').fadeOut();
            $('#woocommerce_auctioninc_fixed_code').closest('tr').fadeOut();
            $('#woocommerce_auctioninc_fixed_fee_1').closest('tr').fadeOut();
            $('#woocommerce_auctioninc_fixed_fee_2').closest('tr').fadeOut();
        }
        else if (calc_method === 'F') {
            $('#woocommerce_auctioninc_package').closest('tr').fadeOut();
            $('#woocommerce_auctioninc_insurance').closest('tr').fadeOut();
            $('#woocommerce_auctioninc_fixed_mode').closest('tr').fadeIn();
            toggle_fixed_fields();
        }
        else {
            $('#woocommerce_auctioninc_package').closest('tr').hide();
            $('#woocommerce_auctioninc_insurance').closest('tr').hide();
            $('#woocommerce_auctioninc_fixed_mode').closest('tr').hide();
            $('#woocommerce_auctioninc_fixed_code').closest('tr').hide();
            $('#woocommerce_auctioninc_fixed_fee_1').closest('tr').hide();
            $('#woocommerce_auctioninc_fixed_fee_2').closest('tr').hide();
        }
    }

    function toggle_fixed_fields() {
        var fixed_mode = $('#woocommerce_auctioninc_fixed_mode').val();
        if (fixed_mode === 'code') {
            $('#woocommerce_auctioninc_fixed_code').closest('tr').fadeIn();
            $('#woocommerce_auctioninc_fixed_fee_1').closest('tr').fadeOut();
            $('#woocommerce_auctioninc_fixed_fee_2').closest('tr').fadeOut();
        }
        else if (fixed_mode === 'fee') {
            $('#woocommerce_auctioninc_fixed_code').closest('tr').fadeOut();
            $('#woocommerce_auctioninc_fixed_fee_1').closest('tr').fadeIn();
            $('#woocommerce_auctioninc_fixed_fee_2').closest('tr').fadeIn();
        }
        else {
            $('#woocommerce_auctioninc_fixed_code').closest('tr').hide();
            $('#woocommerce_auctioninc_fixed_fee_1').closest('tr').hide();
            $('#woocommerce_auctioninc_fixed_fee_2').closest('tr').hide();
        }
    }

});