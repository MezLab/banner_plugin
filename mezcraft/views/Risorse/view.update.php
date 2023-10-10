<?php $_Risorse = new Logisticamente_Risorsa();?>
<?php $_single_risorsa = $_Risorse->display_risorsa($_GET['id']);?>

<?php
  wpmez_partials('header', [
    'title' => 'Modifica la risorsa',
    'description' => 'Modifica i contenuti della risorsa di logistica e supply chain'
  ]);
?>
    <div class="row pt-5 g-5">
      </div>
      <div class="col-md-12 col-lg-12"> 
      <form class="needs-validation" novalidate="" method="POST" action="" enctype="multipart/form-data">
          <div class="row g-3">
            <div class="col-sm-12">
              <label for="firstName" class="form-label">Titolo Risorsa</label>
              <input type="text" class="form-control" id="firstName" placeholder="" name="risorsa_name" value="<?= $_single_risorsa->title; ?>" required="">
            </div>
            <div class="mb-3">
                <label for="formFile" class="form-label">File Inserito</label>
                <input type="text" class="form-control" id="" placeholder="" value="<?= $_single_risorsa->path_url; ?>" required="">
            </div>
            <div class="mb-3">
                <label for="formFile" class="form-label">Inserisci nuovo File</label>
                <input class="form-control" type="file" name="file_input" accept="*/*" id="formFile">
            </div>

          <hr class="my-4">

          <button class="w-100 btn btn-secondary btn-lg" type="submit">Modifica risorsa</button>
        </form>
      </div>
    </div>
  </main>
</div>