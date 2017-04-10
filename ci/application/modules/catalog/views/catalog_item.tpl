<div class="breadcrumbs">{breadcrumbs}</div>
<h1>{name}</h1>
<div class="item_block">
    <div class="row">
        <div class="item_image">
            <div class="fotorama" data-nav="thumbs" data-loop="true" data-width="300" data-ratio="800/600"
                 data-minwidth="200"
                data-maxwidth="300"
                data-minheight="300"
                data-allowfullscreen="true"
             >
                {fotorama}
            </div>
        </div>
        <div class="item_descr">
            <strong>Производитель:</strong> {brand}<br/>
            <strong>ID:</strong> {vendor_part}<br/>
            <strong>Гарантия:</strong> {warranty}

            <div class="price_block">
                <span class="item_price">{price} <i class="rubl">a</i></span>
                <span class="item_available">Доступно: {available}</span>
                <span class="item_quantity"><input type="text" name="quantity" id="quantity" value="1" /></span>
                <input type="hidden" id="item_id" value="{No}" />
                <input type="hidden" id="item_name" value="{name}" />
                <input type="hidden" id="item_price" value="{price}" />
                <button class="add2cart">В корзину</button>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="item_properties">
            <h2>Характеристики</h2>
            {properties}
        </div>
    </div>
</div>


<link  href="/assets/css/fotorama.css" rel="stylesheet">
<script src="/assets/js/fotorama.js"></script>
