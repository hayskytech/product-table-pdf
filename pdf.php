<?php
function print_pdf_bkh(){
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $logo = wp_get_attachment_image_src( $custom_logo_id , 'medium' )[0];
    ?>
    <style type="text/css">
        table{
            margin: auto;
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
    <center>
        <img src="<?php echo $logo; ?>" height="200">
        <br><button onclick="window.print()"><big>Print</big></button>
    </center>
    <table border="1">
        <thead>
            <tr>
                <th>Producer</th>
                <th>Regions/Producers</th>
                <th>Name</th>
                <th>SKU</th>
                <th>Format</th>
                <th>Summary</th>
                <th>Price</th>
                <th>Sale Price</th>
                <th>Notes</th>
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
        <td><img src="'.$brand_url[0].'" height="80"></td>
        <td>'.$cat.'</td>
        <td>'.get_the_title().'</td>
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
        echo '<td>'.get_the_excerpt().'</td>
        <td>'.$price.'</td>
        <td>'.$sprice.'</td>
        <td>'.$notes.'</td>
        </tr>';
    endwhile;

    wp_reset_query();
    ?>
        </tbody>
    </table>
    <center><button onclick="window.print()"><big>Print</big></button></center>
    <?php
    exit;
}
add_action("init","print_pdf_bkh");