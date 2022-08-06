<?php
function print_pdf_bkh(){
    ?>
    <style type="text/css">
        body{
            max-width: 1000px;
            margin: auto;
            padding: 10px;
        }
        @page {
            size: A4;
            /*margin: 11mm 17mm 17mm 17mm;*/
        }
        .today-date{
            float: right;
            font-size: 13px;
        }
        .inner-table th{
            text-align: left;
        }
        .inner-table,.inner-table th{
            border-bottom: 1px solid;
            border-top: 1px solid;
            border-collapse: collapse;
        }
        .inner-table{
            width: 100%;
        }
        .inner-table td,.inner-table th{
            font-size: 14px;
        }
        .cat_name{
            margin: 0;
            font-size: 16px;
            padding-top: 10px;
            font-weight: bold;
            color: #414827;
        }
        .inner-table td,.inner-table th,p{
            font-family: sans-serif;
        }
        img.brand{
            max-width: 120px;
        }
        p{
            margin: 0 !important;
        }
        button{
            cursor: pointer;
        }
        @media print{
            button{
                display: none;
            }
            thead span,tfoot span,thead p,tfoot p{
                font-size: 10px !important;
            }
            
        }
    </style>
    <title><?php echo get_bloginfo( 'name' ); ?> - Products</title>
    <table class="outer-table" style="width:100%;"><thead><tr><td style="background-color: #404725;color: white;padding: 10px;position: relative;height: 90px;text-align: center;border-radius: 3px;">
    <?php echo apply_filters('the_content',get_option('product_table_header'),'features',true); ?>
    </td></tr></thead>
    <tbody><tr><td>
    <div style="text-align: right;"><button onclick="window.print()">Print / Save as PDF</button></div>
    <?php
    $uncat = get_term_by( 'slug', 'uncategorized','product_cat' );
    $args = array(
        'taxonomy'     => 'product_cat',
        'orderby'      => 'name',
        'show_count'   => 0,
        'pad_counts'   => 0,
        'hierarchical' => 1,
        'title_li'     => '',
        'hide_empty'   => 1,
        'exclude'      => $uncat->term_id
    );
    $categories = get_categories( $args );
    foreach ($categories as $category) {
        echo '<p class="cat_name">'.$category->name.'</p>';
        ?>
        <table class="inner-table">
        <thead>
            <tr>
                <th style="width: 120px;">Producer</th>
                <th style="width: 400px;">Name</th>
                <th style="width: 60px;">SKU</th>
                <th style="width: 250px;">Format</th>
                <th style="width: 60px;">Price</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                'taxonomy' => 'product_cat',
                'field'     => 'id', 
                'terms'     =>  $category->term_id,
                'operator'  => 'IN'
                ),
                array(
                    'taxonomy'      => 'product_visibility',
                    'field'         => 'slug',
                    'terms'         => 'exclude-from-catalog', // Possibly 'exclude-from-search' too
                    'operator'      => 'NOT IN'
                )
            )
        );
        $loop = new WP_Query( $args );
        $sym = get_woocommerce_currency_symbol();
        $image = 1;
        while ( $loop->have_posts() ) : $loop->the_post();
            global $product;
            $brand = get_post_meta( $product->id, 'brand_logo', true );
            $brand_url = wp_get_attachment_image_src($brand, 'medium');
            $tags = get_the_terms ( $product->id, 'product_tag' );
            $notes = get_post_meta( $product->id, 'notes', true );
            echo '<tr>';
            if ($image==1) {
                echo '<td rowspan="'.$loop->post_count.'" style="text-align:center;width: 120px;">';
                $cat_link = get_term_link( $category->term_id, 'product_cat' );
                if ($brand_url[0]) {
                    echo '<a href="'.$cat_link.'" target="_blank"><img class="brand" src="'.$brand_url[0].'"></a></td>';
                }
                echo '</td>';
                $image = 0;
            }
            echo '<td style="width: 400px;">'.get_the_title().'</td>
            <td style="width: 60px;">'.$product->get_sku().'</td>
            <td style="width: 250px;">';
            foreach ($tags as $tag) {
                echo $tag->name.', ';
            }
            echo '</td>';
            
            $price = $product->get_price();
            if ($price) {
                $price = $sym.$price;
            }
            echo '<td style="padding-right:10px;width: 60px;">'.$price.'</td>';
            echo '</tr>';
        endwhile;

        wp_reset_query();
        ?>
            </tbody>
        </table>
        <div class="pagebreak"></div>
        <?php
    }
    ?>
    <div style="text-align: right;"><button onclick="window.print()">Print / Save as PDF</button></div>
    </td></tr></tbody>
    <tfoot>
        <tr>
            <td style="background-color:#404725;color: white;padding: 10px;border-radius: 3px;">    
                <span class="today-date">Updated: <?php echo date('M d, Y - h:i A'); ?></span>
                <?php 
                echo apply_filters('the_content',get_option('product_table_footer'),'features',true);
                ?>
            </td>
        </tr>
    </tfoot>
    </footer>
</table>
    <?php
    exit;
}
add_action("init","print_pdf_bkh");