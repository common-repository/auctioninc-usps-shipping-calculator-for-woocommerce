<?php

/**
 * Adds a box to the main column on the Order edit screens.
 */
function wc_auctioninc_add_order_meta_box() {
    add_meta_box(
            'wc_auctioninc_packaging', __('AuctionInc Packaging Details', 'wc_auctioninc'), 'wc_auctioninc_add_order_callback', 'shop_order', 'normal'
    );
}

add_action('add_meta_boxes', 'wc_auctioninc_add_order_meta_box');

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function wc_auctioninc_add_order_callback($post) {
    $shipping_meta = get_post_meta($post->ID, 'auctioninc_order_shipping_meta', true);
    if (!empty($shipping_meta['PackageDetail'])) {
        $i = 1;
        foreach ($shipping_meta['PackageDetail'] as $package) :
            $flat_rate_code = !empty($package['FlatRateCode']) ? $package['FlatRateCode'] : __('NONE', 'wc_auctioninc');
            ?>
            <strong><?php echo __('Package', 'wc_auctioninc') . "# $i"; ?></strong><br>
            <?php
            echo __('Flat Rate Code', 'wc_auctioninc') . ": $flat_rate_code<br>";
            echo __('Quantity', 'wc_auctioninc') . ": {$package['Quantity']}<br>";
            echo __('Pack Method', 'wc_auctioninc') . ": {$package['PackMethod']}<br>";
            echo __('Origin', 'wc_auctioninc') . ": {$package['Origin']}<br>";
            echo __('Declared Value', 'wc_auctioninc') . ": {$package['DeclaredValue']}<br>";
            echo __('Weight', 'wc_auctioninc') . ": {$package['Weight']}<br>";
            echo __('Length', 'wc_auctioninc') . ": {$package['Length']}<br>";
            echo __('Width', 'wc_auctioninc') . ": {$package['Width']}<br>";
            echo __('Height', 'wc_auctioninc') . ": {$package['Height']}<br>";
            echo __('Oversize Code', 'wc_auctioninc') . ": {$package['OversizeCode']}<br>";
            echo __('Carrier Rate', 'wc_auctioninc') . ": ".number_format($package['CarrierRate'],2)."<br>";
            echo __('Fixed Rate', 'wc_auctioninc') . ": ".number_format($package['FixedRate'],2)."<br>";
            echo __('Surcharge', 'wc_auctioninc') . ": ".number_format($package['Surcharge'],2)."<br>";
            echo __('Fuel Surcharge', 'wc_auctioninc') . ": ".number_format($package['FuelSurcharge'],2)."<br>";
            echo __('Insurance', 'wc_auctioninc') . ": ".number_format($package['Insurance'],2)."<br>";
            echo __('Handling', 'wc_auctioninc') . ": ".number_format($package['Handling'],2)."<br>";
            echo __('Total Rate', 'wc_auctioninc') . ": ".number_format($package['ShipRate'],2)."<br>";

            $j = 1;
            echo '<br>';
            foreach ($package['PkgItem'] as $pkg_item) :
                ?>
                <strong><?php echo __('Item', 'wc_auctioninc') . "# $j"; ?></strong><br>
                <?php
                echo __('Ref Code', 'wc_auctioninc') . ": {$pkg_item['RefCode']}<br>";
                echo __('Quantity', 'wc_auctioninc') . ": {$pkg_item['Qty']}<br>";
                echo __('Weight', 'wc_auctioninc') . ": {$pkg_item['Weight']}<br>";
                $j++;
            endforeach;
            echo '<br><br>';
            $i++;
        endforeach;
    }
}
