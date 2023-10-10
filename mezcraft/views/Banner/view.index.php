<?php $_Banner = new Logisticamente_Banner();?>
<?php
  wpmez_partials('header', [
    'title' => 'Banner',
    'description' => 'Elenco di tutti i banner'
  ]);
?>

<?php wpmez_partials('banner/nav'); ?>

<div class="">
    <a class="btn btn-success" role="button" aria-disabled="true" href="<?= admin_url('admin.php') ?>?page=log-index-banner&type=add">Aggiungi Banner</a>
    </div>
    <table class="text-center table">
      <thead>
        <tr>
          <th scope="col">Opzioni</th>
          <th scope="col">Titolo</th>
          <th scope="col">Tipologia</th>
          <th scope="col">Periodo</th>
          <th scope="col">Pubblicato</th>
          <th scope="col">File</th>
          <th scope="col">Misura</th>

          </tr>
      </thead>
      <tbody>
        <?= $_Banner->display_banner_table(); ?>
      </tbody>
    </table>
  </main>
</div>