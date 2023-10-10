<?php $_Campaign = new Logisticamente_Campaign();?>
<?php $output = $_Campaign->ppc_campaign($_GET['id']); ?>

<?php
  wpmez_partials('header', [
    'title' => 'Campagna',
    'description' => 'Visualizza la campagna'
  ]);
?>

<?php wpmez_partials('banner/nav'); ?>

<main class="col-lg-12 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Titolo: <span class="text-success"><?= $_Campaign->campaign_name($_GET['id'])->Nome; ?></span></h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group me-2">
            <button onclick="downloadExcel()" type="button" class="btn btn-sm btn-outline-secondary">Esporta Excel</button>
          </div>
        </div>
      </div>

      <canvas class="my-5 w-100" id="myChart"></canvas>

      <h2>Pay per Click</h2>
      <div class="table-responsive small">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th scope="col">Nome Banner</th>
              <th scope="col">Click</th>
            </tr>
          </thead>
          <tbody>
            
            <?php foreach ($output as $key => $value) { ?>
              <?php foreach ($value as $data) { ?>
                <tr>
                  <td><?= $data->Titolo ?></td>
                  <td><?= $data->Pay_Click ?></td>
                </tr>
              <?php } ?>
            <?php } ?>
            
          </tbody>
          <tfoot>
            <tr>
            <th scope="row">Totale Click</th>
            <?php foreach ($_Campaign->totalClick() as $key => $value) { ?>
              <td><?= $value ?></td>
            <?php } ?>
            </tr>
          </tfoot>
        </table>
      </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?= WPMEZ_PLUGIN_URL . 'admin/js/graphic.js'?>"></script>
    <script>
      Graphic_Campaign('<?= str_replace(' ', '_', $_Campaign->campaign_name($_GET['id'])->Nome); ?>', "<?= site_url() ?>");
    </script>

    <script>
      function downloadExcel(){
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
          console.log('Scaricato');
          }
        xhttp.open("GET", '<?= site_url() ?>/Excel/esporta-excel?url=<?= str_replace(' ', '_', $_Campaign->campaign_name($_GET['id'])->Nome) ?>', true);
        xhttp.send();
      }
    </script>