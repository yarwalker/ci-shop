<div class="items_block">
    {items}
        <div class="item">
            <div class="item_img">
                <div class="wrap_img">
                    <img src="{Image}" title="{Name}" />
                </div>
            </div>
            <div class="item_descr">
                <p><a href="/catalog/item/{No}">{Name}</a></p>
                <span>Код: {No} - {Vendor_part}</span><br/>
                <span>{Brand}</span><br/>
                <span>Гарантия: {Warranty}</span><br/>
            </div>
            <div class="item_price">
                {Price} <i class="rubl">a</i><br/>
                {Available} шт.
            </div>
        </div>
    {/items}

    <div class="pagination">
        {item_pagination}
    </div>
</div>
