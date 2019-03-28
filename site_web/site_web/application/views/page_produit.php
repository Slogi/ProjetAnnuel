<div class="card flex-row flex-wrap">
    <div class="card-header border-0">
        <img src="<?= $produit[0]['product_img']; ?>" alt="" class="img-thumbnail img-fluid">
    </div>
    <div class="card-block px-auto">
        <h4 class="card-title"><?= $produit[0]['product_name'].' '.$produit[0]['product_brand']; ?></h4>
        <table id="site" class="table table-hover table-condensed">
            <thead>
            <tr>
                <th>Site</th>
                <th>Prix</th>
                <th>Lien</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach( $info as $ean ){ ?>
                <tr>
                    <td><?=$ean['siteName']; ?></td>
                    <td><?=$ean['price_exponent'].','.$ean['price_fraction']; ?></td>
                    <td><a target="_blank" href="<?=$ean['url']; ?>"><i class="fas fa-external-link-alt"></i></a></td>
                </tr>
            <?php } ?>
            </tbody>
            </tbody>
        </table>
        <?php if ( $pseudo != null ){
            if ( $suivi == false ) { ?>
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#ajouterListe">
                    Ajouter à ma liste
                </button>
            <?php }
            else { ?>
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#suivi">
                    Suivi
                </button>
            <?php }
        } else {?>
        <p class="alert alert-danger">Inscrivez-vous ou connectez-vous pour ajouter ce produit dans votre liste</p>
        <?php } ?>
    </div>
    <div class="w-100"></div>
    <div class="card-footer w-100 text-muted">
        Référence EAN : <?= $produit[0]['product_ean'];?>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="ajouterListe" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Ajouter cet article</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('produit/ajouterListe'); ?>
            <div class="modal-body">
                <div class="form-check">
                    <input id="mailNews" type="checkbox" value="<?php echo set_value('mail'); ?>"
                           class="form-check-input" name="mail">
                    <label class="form-check-label" for="exampleCheck1">Recevoir un email ?</label>
                </div>
                <input type="hidden" name="ean" value="<?= $produit[0]['product_ean'];?>">
                <div id="divSeuil" class="form-group row">

                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info">Valider</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#mailNews').change(function() {
            if(this.checked) {
                $('#divSeuil').append("<label for=\"seuil\" class=\"col-6 col-form-label\">Seuil pour recevoir un mail</label>\n" +
                    "                    <div class=\"col-3\">\n" +
                    "                        <input required step=\"0.01\" min=\"0,01\" class=\"form-control\" type=\"number\" value=\"<?php echo set_value('seuil'); ?>\" name=\"seuil\">\n" +
                    "                    </div>")
            }
            else {
                $('#divSeuil').empty();
            }
        });
    });
</script>
