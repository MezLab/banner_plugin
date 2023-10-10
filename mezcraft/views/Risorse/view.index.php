<?php $_Risorse = new Logisticamente_Risorsa();?>
<?php
  wpmez_partials('header', [
    'title' => 'Risorse',
    'description' => 'Elenco di tutti i contenuti di logistica e supply chain caricati.'
  ]);
?>

    <div class="py-5">
      <a class="btn btn-success" role="button" aria-disabled="true" href="<?= admin_url('admin.php') ?>?page=log-index-risorse&type=add">Aggiungi risorsa</a>
    </div>
    <table class="text-center table">
      <thead>
        <tr>
          <th scope="col">Opzioni</th>
          <th scope="col">Titolo</th>
          <th scope="col">Url Risorsa</th>
          <th scope="col"></th>
          </tr>
      </thead>
      <tbody>
        <?= $_Risorse->display_risorse_table(); ?>
      </tbody>
    </table>
  </main>
</div>