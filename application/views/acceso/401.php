<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php if (isset($mensaje)) { ?>
                <div class="alert alert-<?php echo ($mensaje[0]) ? 'danger' : 'success'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $mensaje[1]; ?>
                </div>
            <?php } ?>
        </div>
    </div>
</section>