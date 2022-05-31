<?php
function print_pdf_bkh(){
    ?>
    <style type="text/css">
        body{
            max-width: 1000px;
            margin: auto;
            padding: 10px;
        }
        th,table{
            border-bottom: 1px solid;
            border-top: 1px solid;
            border-collapse: collapse;
        }
        table{
            width: 100%;
        }
        td,th{
            font-size: 15px;
        }
        .cat_name{
            font-size: 20px;
            font-weight: bold;
        }
        td,th,p{
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
            .pagebreak{
                /*page-break-after: always;*/
            }
        }
    </style>
    <?php echo apply_filters('the_content',get_option('product_table_header'),'features',true); ?>
        <caption>
            <button onclick="window.print()"><big>Print / Save as PDF</big></button>
        </caption>
    <?php
    $args = array(
        'taxonomy'     => 'product_cat',
        'orderby'      => 'name',
        'show_count'   => 0,
        'pad_counts'   => 0,
        'hierarchical' => 1,
        'title_li'     => '',
        'hide_empty'   => 1
    );
    $categories = get_categories( $args );
    foreach ($categories as $category) {
        echo '<p class="cat_name">'.$category->name.'</p>';
        ?>
        <table>
        <thead>
            <tr>
                <th>Producer</th>
                <th>Name</th>
                <th>SKU</th>
                <th>Format</th>
                <th>Price</th>
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
            $cat = get_the_terms ( $product->id, 'product_cat' )[0]->name;
            $tags = get_the_terms ( $product->id, 'product_tag' );
            $notes = get_post_meta( $product->id, 'notes', true );
            echo '<tr>';
            if ($image==1) {
                echo '<td rowspan="'.$loop->post_count.'">';
                if ($brand_url[0]) {
                    echo '<img src="'.$brand_url[0].'" height="80"></td>';
                }
                echo '</td>';
                $image = 0;
            }
            echo '<td>'.get_the_title().'</td>
            <td>'.$product->get_sku().'</td>
            <td>';
            foreach ($tags as $tag) {
                echo $tag->name.', ';
            }
            echo '</td>';
            
            $price = $product->get_price();
            if ($price) {
                $price = $sym.$price;
            }
            echo '<td>'.$price.'</td>';
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
    <button onclick="window.print()"><big>Print / Save as PDF</big></button>
    <?php echo apply_filters('the_content',get_option('product_table_footer'),'features',true); ?>
    <?php
    exit;
}
add_action("init","print_pdf_bkh");