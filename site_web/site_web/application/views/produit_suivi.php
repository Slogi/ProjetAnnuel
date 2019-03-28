<table id="cart" class="table table-condensed">
    <thead>
    <tr>
        <th>Produit</th>
        <th style="width:10%;">Seuil</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach( $list as $product ) {  ?>
        <tr>
            <td data-th="Product" style="width: 70%;">
                <div class="row">
                    <div class="col-sm-2 hidden-xs"><img src="<?= $product->product_img ?>" class="img-thumbnail img-fluid"/></div>
                    <div class="col-sm-10">
                        <h4 class="titre-produit"><?= $product->product_name.' '. $product->product_brand ?></h4>
                        <div class="row">
                            <table id="site" class="table table-condensed">
                                <thead>
                                <tr>
                                    <th>Site</th>
                                    <th>Prix</th>
                                    <th>Lien</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach( $infoEan[$product->product_ean] as $ean ){ ?>

                                    <tr>
                                        <td><?=$ean['siteName']; ?></td>
                                        <td><?=$ean['price_exponent'].','.$ean['price_fraction']; ?>€</td>
                                        <td><a target="_blank" href="<?=$ean['url']; ?>"><i class="fas fa-external-link-alt"></i></a></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div id="deleteSuivi<?=$product->product_ean;?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3><?= $product->product_name.' '. $product->product_brand ?></h3>
                                <button type="button" class="close font-weight-light" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                <p>Voulez-vous le supprimer des articles suivis ? </p>
                            </div>
                            <div class="modal-footer">
                                <a href="<?=base_url().'produitSuivi/delete/'.$product->product_ean; ?>"><button class="btn btn-primary">Oui</button></a>
                                <button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="modifySuivi<?=$product->product_ean;?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3><?= $product->product_name.' '. $product->product_brand ?></h3>
                                <button type="button" class="close font-weight-light" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <?= form_open('produitSuivi/modifier'); ?>

                            <div class="modal-body">
                                <div class="form-check">
                                    <input id="mailNews<?=$product->product_ean;?>" type="checkbox" <?php if($product->list_products_suivi =='1') echo "checked" ?> value="<?php echo set_value('mail'); ?>"
                                           class="form-check-input" name="mail">
                                    <label class="form-check-label" for="exampleCheck1">Recevoir un email ?</label>
                                </div>
                                <input type="hidden" name="ean" value="<?= $product->product_ean;?>">
                                <div id="divSeuil<?=$product->product_ean;?>" class="form-group row">
                                    <?php if($product->list_products_suivi =='1') {?>
                                        <label for="seuil<?=$product->product_ean;?>" class="col-6 col-form-label">Seuil pour recevoir un mail</label>
                                        <div class="col-3">
                                            <input required step="0.01" min="0,01" class="form-control" type="number" value="<?= $product->list_products_seuil; ?>" name="seuil<?=$product->product_ean;?>">
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Modifier</button>
                                <button class="btn" data-dismiss="modal" aria-hidden="true">Annuler</button>
                            </div>
                            <?= form_close(); ?>
                        </div>
                    </div>
                </div>
            </td>
            <script>
                $(document).ready(function() {
                    $('#mailNews<?=$product->product_ean;?>').change(function() {
                        if(this.checked) {
                            $('#divSeuil<?=$product->product_ean;?>').append("<label for=\"seuil<?=$product->product_ean;?>\" class=\"col-6 col-form-label\">Seuil pour recevoir un mail</label>\n" +
                                "                    <div class=\"col-3\">\n" +
                                "                        <input required step=\"0.01\" min=\"0,01\" class=\"form-control\" type=\"number\" value=\"<?= set_value('seuil'); ?>\" name=\"seuil<?=$product->product_ean;?>\">\n" +
                                "                    </div>")
                        }
                        else {
                            $('#divSeuil<?=$product->product_ean;?>').empty();
                        }
                    });
                });
            </script>
            <td data-th="Seuil">
                <?php if($product->list_products_seuil != null ) echo $product->list_products_seuil.' €'; else echo '∅';  ?>
            </td>
            <td class="Actions" data-th="actions">
                <a href="<?= base_url('produitSuivi/graphique').'/'.$product->product_ean;?>">
                    <button title="Afficher le graphique" class="btn btn-success btn-sm">
                        <i class="fas fa-chart-line"></i>
                    </button>
                </a>
                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modifySuivi<?=$product->product_ean;?>" title="Modifier le seuil" >
                    <i class="far fa-edit"></i>
                </button>
                <button data-toggle="modal" data-target="#deleteSuivi<?=$product->product_ean;?>" class="btn btn-danger btn-sm" title="Supprimer des articles suivis" >
                    <i class="far fa-trash-alt"></i>
                </button>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
