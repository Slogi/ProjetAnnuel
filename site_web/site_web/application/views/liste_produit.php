<p><?php echo $links; ?></p>
<div class="card-columns">
    <?php foreach( $list as $row) { ?>
        <a class="card border-info" href="<?= base_url().'produit/'.$row['product_ean']; ?>">
            <img class="card-img-left" src="<?= $row['product_img']; ?>" >
            <div class="card-body">
                <h5 class="card-title"><?= $row['product_name'].' '.$row['product_brand']; ?></h5>
            </div>
        </a>
    <?php } ?>
    <p><?php echo $links; ?></p>
</div>