jQuery(document).ready(function($) {

    toggle_method_fields();
    toggle_handling_fields();
    toggle_fixed_fields();

    $(document).on('change', '#auctioninc_calc_method', function(e) {
        toggle_method_fields();
    });

    $(document).on('change', '#auctioninc_supp_handling_mode', function(e) {
        toggle_handling_fields();
    });

    $(document).on('change', '#auctioninc_fixed_mode', function(e) {
        toggle_fixed_fields();
    });

    function toggle_method_fields() {
        var calc_method = $('#auctioninc_calc_method').val();

        if (calc_method === 'C' || calc_method === 'CI') {
            $('.auctioninc_fixed_mode_field').fadeOut();
            $('.auctioninc_fixed_code_field').fadeOut();
            $('.auctioninc_fixed_fee_1_field').fadeOut();
            $('.auctioninc_fixed_fee_2_field').fadeOut();
            $('.auctioninc_pack_method_field').fadeIn();
            $('.auctioninc_insurable_field').fadeIn();
            $('.auctioninc_origin_code_field').fadeIn();
            $('.auctioninc_supp_handling_mode_field').fadeIn();
            toggle_handling_fields();
            $('.auctioninc_ondemand_codes_field').fadeIn();
            $('.auctioninc_access_fees_field').fadeIn();
        }
        else if (calc_method === 'F') {
            $('.auctioninc_fixed_mode_field').fadeIn();
            toggle_fixed_fields();
            $('.auctioninc_pack_method_field').fadeOut();
            $('.auctioninc_insurable_field').fadeOut();
            $('.auctioninc_origin_code_field').fadeOut();
            $('.auctioninc_supp_handling_mode_field').fadeOut();
            //$('.auctioninc_supp_handling_mode_code').fadeOut();
            //$('.auctioninc_supp_handling_mode_fee').fadeOut();
            $('.auctioninc_supp_handling_code_field').fadeOut();
            $('.auctioninc_supp_handling_fee_field').fadeOut();
            $('.auctioninc_ondemand_codes_field').fadeOut();
            $('.auctioninc_access_fees_field').fadeOut();
        }
        else if (calc_method === 'N') {
            $('.auctioninc_fixed_mode_field').fadeOut();
            $('.auctioninc_fixed_code_field').fadeOut();
            $('.auctioninc_fixed_fee_1_field').fadeOut();
            $('.auctioninc_fixed_fee_2_field').fadeOut();
            $('.auctioninc_pack_method_field').fadeOut();
            $('.auctioninc_insurable_field').fadeOut();
            $('.auctioninc_origin_code_field').fadeOut();
            $('.auctioninc_supp_handling_mode_field').fadeOut();
            //$('.auctioninc_supp_handling_mode_code').fadeOut();
            //$('.auctioninc_supp_handling_mode_fee').fadeOut();
            $('.auctioninc_supp_handling_code_field').fadeOut();
            $('.auctioninc_supp_handling_fee_field').fadeOut();
            $('.auctioninc_ondemand_codes_field').fadeOut();
            $('.auctioninc_access_fees_field').fadeOut();
        }
        else {
            $('.auctioninc_fixed_mode_field').hide();
            $('.auctioninc_fixed_code_field').hide();
            $('.auctioninc_fixed_fee_1_field').hide();
            $('.auctioninc_fixed_fee_2_field').hide();
            $('.auctioninc_pack_method_field').hide();
            $('.auctioninc_insurable_field').hide();
            $('.auctioninc_origin_code_field').hide();
            $('.auctioninc_supp_handling_mode_field').hide();
            //$('.auctioninc_supp_handling_mode_code').hide();
            //$('.auctioninc_supp_handling_mode_fee').hide();
            $('.auctioninc_supp_handling_code_field').hide();
            $('.auctioninc_supp_handling_fee_field').hide();
            $('.auctioninc_ondemand_codes_field').hide();
            $('.auctioninc_access_fees_field').hide();
        }
    }

    function toggle_handling_fields() {
        var handling_mode = $('#auctioninc_supp_handling_mode').val();
        if (handling_mode === 'code') {
            //$('.auctioninc_supp_handling_mode_code').fadeIn();
            //$('.auctioninc_supp_handling_mode_fee').fadeOut();
            $('.auctioninc_supp_handling_code_field').fadeIn();
            $('.auctioninc_supp_handling_fee_field').fadeOut();
        }
        else if (handling_mode === 'fee') {
            //$('.auctioninc_supp_handling_mode_code').fadeOut();
           // $('.auctioninc_supp_handling_mode_fee').fadeIn();
            $('.auctioninc_supp_handling_code_field').fadeOut();
            $('.auctioninc_supp_handling_fee_field').fadeIn();
        }
        else {
            //$('.auctioninc_supp_handling_mode_code').hide();
            //$('.auctioninc_supp_handling_mode_fee').hide();
            $('.auctioninc_supp_handling_code_field').hide();
            $('.auctioninc_supp_handling_fee_field').hide();
        }
    }

    function toggle_fixed_fields() {
        var calc_method = $('#auctioninc_calc_method').val();
        var fixed_mode = $('#auctioninc_fixed_mode').val();

        if (calc_method === 'F') {
            if (fixed_mode === 'code') {
                $('.auctioninc_fixed_code_field').fadeIn();
                $('.auctioninc_fixed_fee_1_field').fadeOut();
                $('.auctioninc_fixed_fee_2_field').fadeOut();
            }
            else if (fixed_mode === 'fee') {
                $('.auctioninc_fixed_code_field').fadeOut();
                $('.auctioninc_fixed_fee_1_field').fadeIn();
                $('.auctioninc_fixed_fee_2_field').fadeIn();
            }
            else {
                $('.auctioninc_fixed_code_field').hide();
                $('.auctioninc_fixed_fee_1_field').hide();
                $('.auctioninc_fixed_fee_2_field').hide();
            }
        }

    }

});