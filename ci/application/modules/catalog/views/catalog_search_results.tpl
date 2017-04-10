<div id="search_results">
    {results}
        <div class="search_item">
            <a href="{item_url}">{item_name}</a><br/>
            <strong>Производитель: </strong>{brand}<br/>
            <strong>Категория: </strong><a href="{cat_url}">{cat_path}</a>
        </div>
    {/results}
</div>
<div class="pagination">
    {search_pagination}
</div>