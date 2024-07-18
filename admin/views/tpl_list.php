<div class="wrap">
    <h1 class="wp-heading-inline">Short Codes</h1>
    <a href="<?=admin_url("admin.php?page=".$SC_NUTSHELL_CODE_PAGE_SLUG."&action=add")?>" class="page-title-action">Add New</a>
    <div style="height: 15px;">&nbsp;</div>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th style="width: 130px;">Exercise</th>
                <th>Title</th>
                <th>Short Code</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($short_codes as $s){ 
                $edit_url=admin_url("admin.php?page=".$SC_NUTSHELL_CODE_PAGE_SLUG."&action=edit&id=".$s["id"]);
            ?>
            <tr>
                <td>
                    <a href="<?=$edit_url;?>">
                        <?=$s["exercise_name"];?>
                    </a>
                </td>
                <td><?=$s["title"];?></td>
                <td><?=$s["short_code"];?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>