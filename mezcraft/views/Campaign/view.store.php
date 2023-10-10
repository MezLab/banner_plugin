<?php $_Campaign = new Logisticamente_Campaign(); ?>
<?php
  wpmez_partials('header', [
    'title' => 'Aggiungi Campagna',
    'description' => 'Carica la tua campagna'
  ]);
?>

<?php wpmez_partials('banner/nav'); ?>

<div class="row pt-5 g-5">
      </div>
      <div class="col-md-12 col-lg-12"> 
        <form class="needs-validation" novalidate="" method="POST" action="" enctype="multipart/form-data">
          <div class="row g-3">
            <div class="col-lg-6 col-md-6 col-sm-12">
              <label class="form-label">Inserisci il nome della campagna</label>
              <input type="text" class="form-control" name="campaign_name" placeholder="" value="" required="">
            </div>
            <hr class="my-4">
          <button class="w-100 btn btn-secondary btn-lg" type="submit">Aggiungi la campagna</button>
        </form>
      </div>
    </div>
  </main>
</div>