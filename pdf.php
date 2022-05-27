<?php
function print_pdf_bkh(){
    ?>
    <style type="text/css">
        body{
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }
        table,td{
            border-collapse: collapse;
        }
        td,th{
            padding: 10px;
            font-family: sans-serif;
        }
        button{
            margin: 10px;
            padding: 10px;
        }
        @media print{
            button{
                display: none;
            }
        }
    </style>
        <?php echo apply_filters('the_content',get_option('product_table_header'),'features',true); ?>
    <center>
        <br><button onclick="window.print()"><big>Print / Save as PDF</big></button>
    </center>
    <table border="1">
        <thead>
            <tr>
                <th>Producer</th>
                <!-- <th>Regions/Producers</th> -->
                <th>Name</th>
                <th>SKU</th>
                <th>Format</th>
                <!-- <th>Summary</th> -->
                <th>Price</th>
                <th>Sale Price</th>
                <!-- <th>Notes</th> -->
            </tr>
        </thead>
        <tbody>
            
    <?php
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
    );

    $loop = new WP_Query( $args );
    $sym = get_woocommerce_currency_symbol();
    while ( $loop->have_posts() ) : $loop->the_post();
        global $product;
        $brand = get_post_meta( $product->id, 'brand_logo', true );
        $brand_url = wp_get_attachment_image_src($brand, 'medium');
        $cat = get_the_terms ( $product->id, 'product_cat' )[0]->name;
        $tags = get_the_terms ( $product->id, 'product_tag' );
        $notes = get_post_meta( $product->id, 'notes', true );
        echo '<tr>
        <td><img src="'.$brand_url[0].'" height="80"></td>';
        // echo '<td>'.$cat.'</td>';
        echo '<td>'.get_the_title().'</td>
        <td>'.$product->get_sku().'</td>
        <td>';
        foreach ($tags as $tag) {
            echo $tag->name.', ';
        }
        echo '</td>';
        
        $price = $product->get_regular_price();
        $sprice = $product->get_sale_price();
        if ($price) {
            $price = $sym.$price;
        }
        if ($sprice) {
            $sprice = $sym.$sprice;
        }
        // echo '<td>'.get_the_excerpt().'</td>';
        echo '<td>'.$price.'</td>
        <td>'.$sprice.'</td>';
        // echo '<td>'.$notes.'</td>';
        
        echo '</tr>';
    endwhile;

    wp_reset_query();
    ?>
        </tbody>
    </table>
    <center><button onclick="window.print()"><big>Print / Save as PDF</big></button></center>
    <?php
    exit;
}
add_action("init","print_pdf_bkh");