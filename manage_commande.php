<?php
include "db_connect.php";

if(isset($_GET['id'])){
	$id = $_GET['id']; // Correction : Ajout de la déclaration de la variable $id
	$qry = $conn->query("SELECT * FROM commandes where id =" . $id);
	$data = $qry->fetch_assoc(); // Correction : Utilisation de fetch_assoc() au lieu de fetch_array()
	$nom_client = $data['nom_client']; // Correction : Récupération des données du client
	$status = $data['status']; // Correction : Récupération du statut
}
?>

<div class="container-fluid">
    <form action="" id="manage-laundry">
        <div class="col-lg-12">
            <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="" class="control-label">Nom du client</label>
                        <input type="text" class="form-control" name="nom_client" value="<?php echo isset($nom_client) ? $nom_client : ''; ?>">
                    </div>
                </div>
                <?php if (isset($_GET['id'])): ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="" class="control-label">Status</label>
                        <select name="status" id="" class="custom-select browser-default">
                            <option value="0" <?php echo $status == 0 ? "selected" : ''; ?>>En attente</option>
                            <option value="1" <?php echo $status == 1 ? "selected" : ''; ?>>Traitement</option>
                            <option value="2" <?php echo $status == 2 ? "selected" : ''; ?>>Pret</option>
                            <option value="3" <?php echo $status == 3 ? "selected" : ''; ?>>Livre</option>
                        </select>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="" class="control-label">Nettoyage Categories</label>
                        <select class="custom-select browser-default" id="categories_id">
                            <?php
                                $cat = $conn->query("SELECT * FROM categories order by nom asc");
                                while ($row = $cat->fetch_assoc()):
                                    $cname_arr[$row['id']] = $row['nom'];
                            ?>
                            <option value="<?php echo $row['id'] ?>" data-price="<?php echo $row['prix'] ?>"><?php echo $row['nom'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="" class="control-label">Nombre</label>
                        <input type="number" step="any" min="1" value="1" class="form-control text-right" id="weight">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="" class="control-label">&nbsp;</label>
                        <button class="btn btn-info btn-sm btn-block" type="button" id="add_to_list"><i class="fa fa-plus"></i> Ajouter a la liste</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <table class="table table-bordered" id="list">
                    <colgroup>
                        <col width="30%">
                        <col width="15%">
                        <col width="25%">
                        <col width="25%">
                        <col width="5%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="text-center">Categories</th>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Prix Unitaire</th>
                            <th class="text-center">Montant</th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($_GET['id'])): ?>
                        <?php
                            $list = $conn->query("SELECT * from articles where article_id = ".$id);
                            while ($row = $list->fetch_assoc()):
                        ?>
                        <tr data-id="<?php echo $row['id'] ?>">
                            <td class="">
                                <input type="hidden" name="item_id[]" id="" value="<?php echo $row['id'] ?>">
                                <input type="hidden" name="categories_id[]" id="" value="<?php echo $row['categories_id'] ?>"><?php echo isset($cname_arr[$row['categories_id']]) ? ucwords($cname_arr[$row['categories_id']]) : ''; ?></td>
                            <td><input type="number" class="text-center" name="weight[]" id="" value="<?php echo $row['weight'] ?>"></td>
                            <td class="text-right"><input type="hidden" name="prix_unitaire[]" id="" value="<?php echo $row['prix_unitaire'] ?>"><?php echo number_format($row['prix_unitaire'], 2) ?></td>
                            <td class="text-right"><input type="hidden" name="montant[]" id="" value="<?php echo $row['montant'] ?>"><p><?php echo number_format($row['montant'], 2) ?></p></td>
                            <td><button class="btn btn-sm btn-danger" type="button" onclick="rem_list($(this))"><i class="fa fa-times"></i></button></td>
                        </tr>
                        <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-right" colspan="3"></th>
                            <th class="text-right" id="montant_total"></th>
                            <th class="text-right"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <hr>
            <div class="row">
                <div class="form-group">
                    <div class="custom-control custom-switch" id="pay-switch">
                        <input type="checkbox" class="custom-control-input" value="1" name="pay" id="paid" <?php echo isset($pay_status) && $pay_status == 1 ? 'checked' : ''; ?>>
                        <label class="custom-control-label" for="paid">Payement</label>
                    </div>
                </div>
            </div>
            <div class="row" id="payment">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="" class="control-label">Montant Paye</label>
                        <input type="number" step="any" min="0" value="<?php echo isset($montant_paye) ? $montant_paye : 0 ?>" class="form-control text-right" name="montant_paye">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="" class="control-label">Montant Total</label>
                        <input type="number" step="any" min="1" value="<?php echo isset($montant_total) ? $montant_total : 0 ?>" class="form-control text-right" name="montant_total" readonly="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="" class="control-label">Montant Restant</label>
                        <input type="number" step="any" min="1" value="<?php echo isset($montant_restant) ? $montant_restant : 0 ?>" class="form-control text-right" name="montant_restant" readonly="">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        if ('<?php echo isset($_GET['id']) ?>' === '1') {
            calc();
        }

        $('[name="pay"]').change(function() {
            if ($(this).prop('checked')) {
                $('[name="montant_paye"]').prop('required', true);
                $('#payment').slideDown();
            } else {
                $('#payment').slideUp();
                $('[name="montant_paye"]').prop('required', false);
            }
        });

        $('#add_to_list').click(function() {
            var cat = $('#categories_id').val(),
                _weight = $('#weight').val();
            
            if (cat === '' || _weight === '') {
                alert_toast('Remplir tous les champs.', 'warning');
                return false;
            }

            if ($('#list tr[data-id="' + cat + '"]').length > 0) {
                alert_toast('La catégorie existe déjà.', 'warning');
                return false;
            }

            var price = $('#categories_id option[value="' + cat + '"]').data('price');
            var cname = $('#categories_id option[value="' + cat + '"]').html();
            var montant = parseFloat(price) * parseFloat(_weight);
            var tr = $('<tr></tr>');
            tr.attr('data-id', cat);
            tr.append('<input type="hidden" name="item_id[]" id="" value=""><td class=""><input type="hidden" name="categories_id[]" id="" value="' + cat + '">' + cname + '</td>');
            tr.append('<td><input type="number" class="text-center" name="weight[]" id="" value="' + _weight + '"></td>');
            tr.append('<td class="text-right"><input type="hidden" name="prix_unitaire[]" id="" value="' + price + '">' + (parseFloat(price).toLocaleString('en-US', {style: 'decimal', maximumFractionDigits: 2, minimumFractionDigits: 2})) + '</td>');
            tr.append('<td class="text-right"><input type="hidden" name="montant[]" id="" value="' + montant + '"><p>' + (parseFloat(montant).toLocaleString('en-US', {style: 'decimal', maximumFractionDigits: 2, minimumFractionDigits: 2})) + '</p></td>');
            tr.append('<td><button class="btn btn-sm btn-danger" type="button" onclick="rem_list($(this))"><i class="fa fa-times"></i></button></td>');
            $('#list tbody').append(tr);
            calc();
            $('[name="weight[]"]').on('keyup keydown keypress montant_restant', function() {
                calc();
            });
            $('[name="montant_paye"]').trigger('keypress');
            
            $('#categories_id').val('');
            $('#weight').val('');
        });

        function rem_list(_this) {
            _this.closest('tr').remove();
            calc();
            $('[name="montant_paye"]').trigger('keypress');
        }

        function calc() {
            var total = 0;
            $('#list tbody tr').each(function() {
                var _this = $(this);
                var weight = _this.find('[name="weight[]"]').val();
                var prix_unitaire = _this.find('[name="prix_unitaire[]"]').val();
                var montant = parseFloat(weight) * parseFloat(prix_unitaire);
                _this.find('[name="montant[]"]').val(montant);
                _this.find('[name="montant[]"]').siblings('p').html(parseFloat(montant).toLocaleString('en-US', {style: 'decimal', maximumFractionDigits: 2, minimumFractionDigits: 2}));
                total += montant;
            });
            
            $('[name="montant_total"]').val(total);
            $('#montant_total').html(parseFloat(total).toLocaleString('en-US', {style: 'decimal', maximumFractionDigits: 2, minimumFractionDigits: 2}));
        }

        $('#manage-laundry').submit(function(e) {
            e.preventDefault();
            start_load();
            $.ajax({
                url: 'ajax.php?action=save_laundry',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                success: function(resp) {
                    if (resp == 1) {
                        alert_toast("Commande ajoutée avec succès", 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else if (resp == 2) {
                        alert_toast("Commande mise à jour avec succès", 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    }
                }
            });
        });
    });
</script>
