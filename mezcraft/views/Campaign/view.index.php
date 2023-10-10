<?php $_Campaign = new Logisticamente_Campaign();?>

<?php
  wpmez_partials('header', [
    'title' => 'Campagne',
    'description' => 'Elenco di tutte le campagne'
  ]);
?>

<?php wpmez_partials('banner/nav'); ?>

<div>
      <a class="btn btn-success" role="button" aria-disabled="true" href="<?= admin_url('admin.php') ?>?page=log-index-campaign&type=add">Aggiungi Campagna</a>
    </div>
    <table class="text-center table">
      <thead>
        <tr>
          <th scope="col">Opzioni</th>
          <th scope="col">Nome</th>
          <th scope="col">Banner</th>
          </tr>
      </thead>
      <tbody>
        <?= $_Campaign->display_campaign_table(); ?>
      </tbody>
    </table>  
  </main>
</div>