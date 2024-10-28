<?php



/**

 * WC_USPS_Shipping_AuctionInc class.

 *

 * @extends WC_Shipping_Method

 */

class WC_USPS_Shipping_AuctionInc extends WC_Shipping_Method {



    /**

     * __construct function.

     *

     * @access public

     * @return void

     */

    public function __construct() {

        $this->id = 'auctioninc';

        $this->method_title = __('USPS', 'wc_auctioninc');

        $this->method_description = __('<strong>AuctionInc\'s USPS</strong> extension obtains rates dynamically from the AuctionInc API during cart/checkout.', 'wc_auctioninc');

        $this->init();

    }



    /**

     * init function.

     *

     * @access public

     * @return void

     */

    private function init() {

        // Load the settings.

        $this->init_form_fields();

        $this->init_settings();



        // Define user set variables

        $this->enabled = isset($this->settings['enabled']) ? $this->settings['enabled'] : $this->enabled;

        $this->title = $this->method_title;

        $this->account_id = isset($this->settings['account_id']) ? $this->settings['account_id'] : $this->account_id;

        $this->delivery_type = isset($this->settings['delivery_type']) && $this->settings['delivery_type'] == 'residential' ? true : false;

        $this->debug = isset($this->settings['debug_mode']) && $this->settings['debug_mode'] == 'yes' ? true : false;

        $this->calc_method = isset($this->settings['calc_method']) ? $this->settings['calc_method'] : $this->calc_method;

        $this->package = isset($this->settings['package']) ? $this->settings['package'] : $this->package;

        $this->insurance = isset($this->settings['insurance']) ? $this->settings['insurance'] : $this->insurance;

        $this->fixed_mode = isset($this->settings['fixed_mode']) ? $this->settings['fixed_mode'] : $this->fixed_mode;

        $this->fixed_code = isset($this->settings['fixed_code']) ? $this->settings['fixed_code'] : $this->fixed_code;

        $this->fixed_fee_1 = isset($this->settings['fixed_fee_1']) ? $this->settings['fixed_fee_1'] : $this->fixed_fee_1;

        $this->fixed_fee_2 = isset($this->settings['fixed_fee_2']) ? $this->settings['fixed_fee_2'] : $this->fixed_fee_2;

        $this->fallback_type = isset($this->settings['fallback_type']) ? $this->settings['fallback_type'] : $this->fallback_type;

        $this->fallback_fee = isset($this->settings['fallback_fee']) ? $this->settings['fallback_fee'] : $this->fallback_fee;



        add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));

        add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'clear_transients'));

    }



    /**

     * clear_transients function.

     *

     * @access public

     * @return void

     */

    public function clear_transients() {

        global $wpdb;



        $wpdb->query("DELETE FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_ai_quote_%') OR `option_name` LIKE ('_transient_timeout_ai_quote_%')");

    }



    /**

     * init_form_fields function.

     *

     * @access public

     * @return void

     */

    public function init_form_fields() {

        global $woocommerce;



        $this->form_fields = array(

            'enabled' => array(

                'title' => __('Enable/Disable', 'wc_auctioninc'),

                'type' => 'checkbox',

                'label' => __('Enable this shipping method', 'wc_auctioninc'),

                'default' => 'no'

            ),

            'api' => array(

                'title' => __('API Settings', 'wc_auctioninc'),

                'type' => 'title',

                'description' => __('An AuctionInc account ID is required to enable shipping rates.', 'wc_auctioninc'),

            ),

            'account_id' => array(

                'title' => __('AuctionInc Account ID', 'wc_auctioninc'),

                'type' => 'text',

                'description' => __('Please enter your account ID that you received when you registered at the AuctionInc site.', 'wc_auctioninc'),

                'default' => ''

            ),

            'delivery_type' => array(

                'title' => __('Delivery Destination Type', 'wc_auctioninc'),

                'type' => 'select',

                'default' => 'residential',

                'options' => array(

                    'residential' => __('Residential', 'wc_auctioninc'),

                    'commercial' => __('Commercial', 'wc_auctioninc'),

                ),

                'description' => __('Set rates to apply to either residential or commercial destination addresses.', 'wc_auctioninc')

            ),

            'default_values' => array(

                'title' => __('Default Values', 'wc_auctioninc'),

                'type' => 'title',

                'description' => __('These settings will apply to those products for which you haven\'t configured AuctionInc values.', 'wc_auctioninc'),

            ),

            'calc_method' => array(

                'title' => __('Calculation Method', 'wc_auctioninc'),

                'type' => 'select',

                'default' => '',

                'options' => array(

                    'C' => __('Carrier Rates', 'wc_auctioninc'),

                    'F' => __('Fixed Fee', 'wc_auctioninc'),

                    'N' => __('Free', 'wc_auctioninc'),

                    'CI' => __('Free Domestic', 'wc_auctioninc')

                ),

                'description' => __('For carrier rates, your configured product weights & dimensions will be used.', 'wc_auctioninc')

            ),

            'package' => array(

                'title' => __('Package Items', 'wc_auctioninc'),

                'type' => 'select',

                'default' => '',

                'options' => array(

                    '' => __('-- Select -- ', 'wc_auctioninc'),

                    'T' => __('Together', 'wc_auctioninc'),

                    'S' => __('Separately', 'wc_auctioninc')

                ),

                'description' => __('Select to pack items from the same origin into the same box or each in its own box.', 'wc_auctioninc')

            ),

            'insurance' => array(

                'title' => __('Insurance', 'wc_auctioninc'),

                'type' => 'checkbox',

                'label' => __('Enable Insurance', 'wc_auctioninc'),

                'default' => 'no',

                'description' => __('If enabled your items will utilize your AuctionInc insurance settings.', 'wc_auctioninc')

            ),

            'fixed_mode' => array(

                'title' => __('Mode', 'wc_auctioninc'),

                'type' => 'select',

                'default' => '',

                'options' => array(

                    '' => __('-- Select --', 'wc_auctioninc'),

                    'code' => __('Code', 'wc_auctioninc'),

                    'fee' => __('Fee', 'wc_auctioninc')

                ),

                'description' => ''

            ),

            'fixed_code' => array(

                'title' => __('Code', 'wc_auctioninc'),

                'type' => 'text',

                'description' => __('Enter your AuctionInc-configured fixed fee code.', 'wc_auctioninc'),

                'default' => ''

            ),

            'fixed_fee_1' => array(

                'title' => __('Fixed Fee 1', 'wc_auctioninc'),

                'type' => 'number',

                'description' => __('Enter fee for first item.', 'wc_auctioninc'),

                'custom_attributes' => array(

                    'step' => '0.01',

                    'min' => '0.01'

                )

            ),

            'fixed_fee_2' => array(

                'title' => __('Fixed Fee 2', 'wc_auctioninc'),

                'type' => 'number',

                'description' => __('Enter fee for additional items and quantities.', 'wc_auctioninc'),

                'custom_attributes' => array(

                    'step' => '0.01',

                    'min' => '0'

                )

            ),

            'fallback' => array(

                'title' => __('Fallback Rate', 'wc_auctioninc'),

                'type' => 'title',

                'description' => __('Default rate if the API cannot be reached or if no rates are found.', 'wc_auctioninc'),

            ),

            'fallback_type' => array(

                'title' => __('Type', 'wc_auctioninc'),

                'type' => 'select',

                'default' => '',

                'options' => array(

                    '' => __('None', 'wc_auctioninc'),

                    'per_item' => __('Per Item', 'wc_auctioninc'),

                    'per_order' => __('Per Order', 'wc_auctioninc')

                ),

                'description' => ''

            ),

            'fallback_fee' => array(

                'title' => __('Amount', 'wc_auctioninc'),

                'type' => 'number',

                'description' => __('Enter an amount for the fallback rate.', 'wc_auctioninc'),

                'custom_attributes' => array(

                    'step' => '0.01',

                    'min' => '0'

                )

            ),

            'debug_mode' => array(

                'title' => __('Debug Mode', 'wc_auctioninc'),

                'label' => __('Enable debug mode', 'wc_auctioninc'),

                'type' => 'checkbox',

                'default' => 'no',

                'description' => __('Enable debug mode to show debugging data for ship rates in your cart. Only you, not your customers, can view this debug data.', 'wc_auctioninc')

            )

        );

    }



    /**

     * calculate_shipping function.

     *

     * @access public

     * @param mixed $package

     * @return void

     */

    public function calculate_shipping( $package=array() ) {

        global $woocommerce;

        global $current_user;



        $base_currency = get_woocommerce_currency();



        $is_admin = (!empty($current_user->roles) && in_array('administrator', $current_user->roles)) ? true : false;



        if ($this->account_id) {

            // Calculate if shipping fields are set

            if (!empty($package['destination']['country'])) {

                // Instantiate the Shipping Rate API object

                $shipAPI = new ShipRateAPI($this->account_id);



                // SSL currently not supported

                $shipAPI->setSecureComm(false);



                // Header reference code

                $shipAPI->setHeaderRefCode('woo-usps');



                // Set base currency

                $shipAPI->setCurrency($base_currency);



                // Set the Detail Level (1, 2 or 3) (Default = 1)

                // DL 1:  minimum required data returned 

                // DL 2:  shipping rate components included

                // DL 3:  package-level detail included

                $detailLevel = 3;

                $shipAPI->setDetailLevel($detailLevel);



                // Show table of any errors for inspection

                $showErrors = true;



                // Set Destination Address for this API call

                $destCountryCode = $package['destination']['country'];

                $destPostalCode = !empty($package['destination']['postcode']) ? $package['destination']['postcode'] : '';

                $destStateCode = ($package['destination']['country'] == 'US' && !empty($package['destination']['state'])) ? $package['destination']['state'] : '';


                // Check if customer has set required destination info
                if ( ( ($destCountryCode == 'US' || $destCountryCode == 'CA') && empty($destPostalCode) ) ) {
                    return;
                }


                // Specify residential delivery

                $delivery_type = $this->delivery_type == 'residential' ? true : false;



                $shipAPI->setDestinationAddress($destCountryCode, $destPostalCode, $destStateCode, $delivery_type);



                // Create an array of items to rate

                $items = array();



                // Loop through package items

                foreach ($package['contents'] as $item_id => $values) {



                    // Skip digital items

                    if (!$values['data']->needs_shipping()) {

                        continue;

                    }



                    // Get AuctionInc shipping fields

                    $product_id = $values['data']->post->ID;

                    $sku = $values['data']->get_sku();



                    // Calculation Method

                    $calc_method = get_post_meta($product_id, 'auctioninc_calc_method', true);

                    $calc_method = !empty($calc_method) ? $calc_method : $this->calc_method;

                    $calc_method = !empty($calc_method) ? $calc_method : 'C';



                    // Fixed Fee Mode

                    $fixed_mode = get_post_meta($product_id, 'auctioninc_fixed_mode', true);

                    $fixed_mode = !empty($fixed_mode) ? $fixed_mode : $this->fixed_mode;



                    // Fixed Fee Code

                    $fixed_code = get_post_meta($product_id, 'auctioninc_fixed_code', true);

                    $fixed_code = !empty($fixed_code) ? $fixed_code : $this->fixed_code;



                    // Fixed Fee 1

                    $fixed_fee_1 = get_post_meta($product_id, 'auctioninc_fixed_fee_1', true);

                    $fixed_fee_1 = is_numeric($fixed_fee_1) ? $fixed_fee_1 : $this->fixed_fee_1;



                    // Fixed Fee 2

                    $fixed_fee_2 = get_post_meta($product_id, 'auctioninc_fixed_fee_2', true);

                    $fixed_fee_2 = is_numeric($fixed_fee_2) ? $fixed_fee_2 : $this->fixed_fee_2;



                    // Packaging Method

                    $pack_method = get_post_meta($product_id, 'auctioninc_pack_method', true);

                    $pack_method = !empty($pack_method) ? $pack_method : $this->package;



                    // Insurable

                    $insurable = get_post_meta($product_id, 'auctioninc_insurable', true);

                    $insurable = !empty($insurable) ? $insurable : $this->insurance;



                    // Origin Code

                    $origin_code = get_post_meta($product_id, 'auctioninc_origin_code', true);



                    // Supplemental Item Handling Mode

                    $supp_handling_mode = get_post_meta($product_id, 'auctioninc_supp_handling_mode', true);



                    // Supplemental Item Handling Code

                    $supp_handling_code = get_post_meta($product_id, 'auctioninc_supp_handling_code', true);



                    // Supplemental Item Handling Fee

                    $supp_handling_fee = get_post_meta($product_id, 'auctioninc_supp_handling_fee', true);



                    // On-Demand Service Codes

                    $ondemand_codes = get_post_meta($product_id, 'auctioninc_ondemand_codes', true);



                    // Special Accessorial Fees

                    $access_fees = get_post_meta($product_id, 'auctioninc_access_fees', true);



                    $item = array();

                    $item["refCode"] = $values['data']->post->post_name . ' ' . $sku;

                    $item["CalcMethod"] = $calc_method;

                    $item["quantity"] = $values['quantity'];



                    if ($calc_method === 'C' || $calc_method === 'CI') {

                        $item["packMethod"] = $pack_method;

                    }



                    // Fixed Rate Shipping

                    if ($calc_method === 'F') {

                        if (!empty($fixed_mode)) {

                            if ($fixed_mode === 'code' && !empty($fixed_code)) {

                                $item["FeeType"] = "C";

                                $item["fixedFeeCode"] = $fixed_code;

                            } elseif ($fixed_mode === 'fee' && is_numeric($fixed_fee_1) && (is_numeric($fixed_fee_2) || empty($fixed_fee_2)) ) {

                                $item["FeeType"] = "F";

                                $item["fixedAmt_1"] = $fixed_fee_1;

                                if(empty($fixed_fee_2)) $fixed_fee_2 = 0;
                                $item["fixedAmt_2"] = $fixed_fee_2;

                            }

                        }

                    }



                    // Insurable

                    if ($insurable === 'yes') {

                        $item["value"] = $values['data']->get_price();

                    }

					else {

						$item["value"] = 0;

					}



                    // Origin Code

                    if (!empty($origin_code)) {

                        $item["originCode"] = $origin_code;

                    }



                    if ($calc_method === 'C' || $calc_method === 'CI') {

                        // Weight

                        if (!$values['data']->get_weight()) {

                            $weight = 0;

                        } else {

                            $weight = woocommerce_get_weight($values['data']->get_weight(), 'lbs');

                        }



                        $item["weight"] = $weight;

                        $item["weightUOM"] = "LBS";



                        // Dimensions

                        if ($values['data']->length && $values['data']->height && $values['data']->width) {

                            $item["length"] = woocommerce_get_dimension($values['data']->length, 'in');

                            $item["height"] = woocommerce_get_dimension($values['data']->height, 'in');

                            $item["width"] = woocommerce_get_dimension($values['data']->width, 'in');

                            $item["dimUOM"] = "IN";

                        }

                    }



                    // Supplemental Item Handling

                    if (!empty($supp_handling_mode)) {

                        if ($supp_handling_mode === 'code' && !empty($supp_handling_code)) {

                            // Supplemental Item Handling Code

                            $item["suppHandlingCode"] = $supp_handling_code;

                        } elseif ($supp_handling_mode === 'fee' && !empty($supp_handling_fee)) {

                            // Supplemental Item Handling Fee

                            $item["suppHandlingFee"] = $supp_handling_fee;

                        }

                    }



                    // On-Demand Service Codes

                    if (!empty($ondemand_codes)) {

                        $codes_str = implode(", ", $ondemand_codes);

                        $item["odServices"] = $codes_str;

                    }



                    // Special Accessorial Fees

                    if (!empty($access_fees)) {

                        $codes_str = implode(", ", $access_fees);

                        $item["specCarrierSvcs"] = $codes_str;

                    }



                    // Add this item to Item Array

                    $items[] = $item;

                }



                // Debug output

                if ($this->debug && $is_admin === true) {

                    echo 'DEBUG ITEM DATA<br>';

                    echo '<pre>' . print_r($items, true) . '</pre>';

                    echo 'END DEBUG ITEM DATA<br>';

                }



                // Add Item Data from Item Array to API Object

                foreach ($items AS $val) {



                    if ($val["CalcMethod"] == "C" || $val["CalcMethod"] == "CI") {

                        $shipAPI->addItemCalc($val["refCode"], $val["quantity"], $val["weight"], $val['weightUOM'], $val["length"], $val["width"], $val["height"], $val["dimUOM"], $val["value"], $val["packMethod"], 1, $val["CalcMethod"]);



                        if (isset($val["originCode"]))

                            $shipAPI->addItemOriginCode($val["originCode"]);

                        if (isset($val["odServices"]))

                            $shipAPI->addItemOnDemandServices($val["odServices"]);

                        if (isset($val["suppHandlingCode"]))

                            $shipAPI->addItemSuppHandlingCode($val["suppHandlingCode"]);

                        if (isset($val["suppHandlingFee"]))

                            $shipAPI->addItemHandlingFee($val["suppHandlingFee"]);

                        if (isset($val["specCarrierSvcs"]))

                            $shipAPI->addItemSpecialCarrierServices($val["specCarrierSvcs"]);

                    } elseif ($val["CalcMethod"] == "F") {

                        $shipAPI->addItemFixed($val["refCode"], $val["quantity"], $val["FeeType"], $val["fixedAmt_1"], $val["fixedAmt_2"], $val["fixedFeeCode"]);

                    } elseif ($val["CalcMethod"] == "N") {

                        $shipAPI->addItemFree($val["refCode"], $val["quantity"]);

                    }

                }



                // Unique identifier for cart items & destiniation

                $request_identifier = serialize($items) . $destCountryCode . $destPostalCode;



                // Check for cached response

                $transient = 'ai_quote_' . md5($request_identifier);

                $cached_response = get_transient($transient);



                $shipRates = array();



                if ($cached_response !== false) {

                    // Cached response

                    $shipRates = unserialize($cached_response);

                } else {

                    // New API call

                    $ok = $shipAPI->GetItemShipRateSS($shipRates);

                    if ($ok) {

                        set_transient($transient, serialize($shipRates), 30 * MINUTE_IN_SECONDS);

                    }

                }



                if (!empty($shipRates['ShipRate'])) {



                    // Store response in the current user's session

                    // Used to retrieve package level details later

                    $woocommerce->session->auctioninc_response = $shipRates;



                    // Debug output

                    if ($this->debug && $is_admin === true) {

                        echo 'DEBUG API RESPONSE: SHIP RATES<br>';

                        echo '<pre>' . print_r($shipRates, true) . '</pre>';

                        echo 'END DEBUG API RESPONSE: SHIP RATES<br>';

                    }



                    foreach ($shipRates['ShipRate'] as $shipRate) {



                        // Add Rate

                        $rate = array(

                            'id' => $this->id . ':' . $shipRate['ServiceCode'],

                            'label' => $shipRate['ServiceName'],

                            'cost' => $shipRate['Rate']

                        );



                        $this->add_rate($rate);

                    }

                } else {



                    if ($this->debug && $is_admin === true) {

                        echo 'DEBUG API RESPONSE: SHIP RATES<br>';

                        echo '<pre>' . print_r($shipRates, true) . '</pre>';

                        echo 'END DEBUG API RESPONSE: SHIP RATES<br>';

                    }



                    $use_fallback = false;



                    if (empty($shipRates['ErrorList'])) {

                        $use_fallback = true;

                    } else {

                        foreach ($shipRates['ErrorList'] as $error) {

                            // Check for proper error code

                            if ($error['Message'] == 'Packaging Engine unable to determine any services to be rated') {

                                $use_fallback = true;

                                break;

                            }

                        }

                    }



                    // Add fallback shipping rates, if applicable

                    if (!empty($this->fallback_type) && !empty($this->fallback_fee) && $use_fallback == true) {



                        $cost = $this->fallback_type === 'per_order' ? $this->fallback_fee : $woocommerce->cart->cart_contents_count * $this->fallback_fee;

                        $rate = array(

                            'id' => $this->id . '_fallback_rate',

                            'label' => __('Shipping', 'wc_auctioninc'),

                            'cost' => $cost

                        );



                        $this->add_rate($rate);

                    } else {

                        $str = __('There do not seem to be any available shipping rates. Please double check your address, or contact us if you need any help.', 'wc_auctioninc');

                        //$this->display_notice($str, 'error');

                    }

                }

            }

        } else {

            $str = __('Please enter your AuctionInc account ID in order to calculate transactions.', 'wc_auctioninc');

            //$this->display_notice($str, 'error');

        }

    }



    /**

     * display_notice function.

     *

     * @access public

     * @param string $message, string $type

     * @return void

     */

    public function display_notice($message, $type = 'notice') {

        if (version_compare(WOOCOMMERCE_VERSION, '2.1', '>=')) {

            wc_add_notice($message, $type);

        } else {

            global $woocommerce;



            $woocommerce->add_message($message);

        }

    }



}

