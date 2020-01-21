<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <h1 class="page-header">Consulter les groupes</h1>
            </div>        
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-xs-12">
                <table class="table table-responsive table-striped">
                    <tr>
                        <th>Nom</th>
                        <th class="text-center">Détails, Membres et Responsables</th>
                        <th class="text-center">Influence</th>
                        <th class="text-center"></th>
                        <th class="text-center"></th>
                    </tr>
                    <?php foreach ($groupes as $groupe) : ?>
                        <tr>
                            <td><?php echo $groupe->Nom; ?></td>
                            <td class="text-center">
                                <a href="<?php echo site_url('groupes/singleGroupe/' .$groupe->Id); ?>">
                                    <button class="btn btn-primary"><span class="fa fa-edit"></span></button>
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo site_url('groupes/editInfluence/'.$groupe->Id); ?>">
                                    <button class="btn btn-primary"><span class="fa fa-info-circle"></span></button>
                                </a>
                            </td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
       
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->