
{if isset($confirmation)}
    <div class="alert alert-success">
        La configuration a bien été mise à jour
    </div>
{/if}


{if isset($valide)}
    <div class="alert {if $valide==true}alert-success{else}alert-warning{/if}">
        {$result}
    </div>
{/if}
{foreach from=$blocks key=index item=block}
<form method="post" action="" class="defaultForm form-horizontal" enctype="multipart/form-data">
    <div class="panel">
        <div class="panel-heading">
            <i class="icon-cogs"></i> La configuration de block {$index+1}
        </div>
        <div class="panel-body">
            <div class="form-wrapper">
                <div class="form-group">
                    <label class="control-label col-lg-3">link :</label>
                    <div class="col-lg-9">
                        <input type="text" id="link" name="link" value="{$block.link}" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3">title :</label>
                    <div class="col-lg-9">
                        <input type="text" id="title" name="title" value="{$block.title}" />
                    </div>
                </div>
                {if !empty($block.img)}
                <div class="form-group">
                    <label class="control-label col-lg-6"</label>
                    <div class="col-lg-6">
                        <img src="{$link}{$block.img}" width="60px" >
                    </div>
                </div>
                {/if}
                <div class="form-group">
                    <label class="control-label col-lg-3">Images :</label>
                    <div class="col-lg-9">
                        <input type="file" id="img" name="img" class="form-control-file" value="{$block.img}"/>
                    </div>
                </div>

            </div>
            <input type="hidden"  name="block_id" value="{$index+1}"/>
        </div>

        <div class="panel-footer">
            <button class="btn btn-default pull-right" name="submit_block" value="1" type="submit">
                <i class="process-icon-save"></i> Enregistrer
            </button>
        </div>
    </div>
</form>
{/foreach}

