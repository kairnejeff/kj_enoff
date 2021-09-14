<div class="en-off home-page-block">
    <h2 class="lwc">Karine & Jeff en off</h2>
    <div class="content">
        {foreach $blocks as $block}
            <div class="en-off-block">
                <a href="{$block.link}">
                {if $modules.kj_detectdevice.machine !=='Mac'&&$modules.kj_detectdevice.machine !=='iPad'}
                    <picture>
                        <source srcset="{$link}{$block.img}.webp" type="image/webp">
                        <img src="{$link}{$block.img}">
                    </picture>
                {else}
                    <img src="{$link}{$block.img}">
                {/if}
                <h3 class="font2">{$block.title nofilter}</h3>
                </a>
            </div>
        {/foreach}

    </div>
</div>