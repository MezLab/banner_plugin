<?php
  wpmez_partials('header', [
    'title' => 'Aggiungi la risorsa',
    'description' => 'Carica i contenuti di logistica e supply chain'
  ]);
?>
    <div class="row pt-5 g-5">
      </div>
      <div class="col-md-12 col-lg-12"> 
        <form class="needs-validation" novalidate="" method="POST" action="" enctype="multipart/form-data">
          <div class="row g-3">
            <div class="col-sm-12">
              <label for="firstName" class="form-label">Titolo Risorsa</label>
              <input type="text" class="form-control" id="firstName" name="risorsa_name" placeholder="" value="" required="">
            </div>
            <div class="mb-3">
                <label for="formFile" class="form-label">Inserisci la risorsa</label>
                <input class="form-control" type="file" name="file_input" accept="*/*" id="formFile">
            </div>

          <hr class="my-4">

          <button class="w-100 btn btn-secondary btn-lg" type="submit">Aggiungi risorsa</button>
        </form>
      </div>
    </div>
  </main>
</div>