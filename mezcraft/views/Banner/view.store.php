<?php $_Banner = new Logisticamente_Banner();?>
<?php $_Campaign = new Logisticamente_Campaign(); ?>
<?php
  wpmez_partials('header', [
    'title' => 'Aggiungi Banner',
    'description' => 'Carica il banner'
  ]);
?>

<?php wpmez_partials('banner/nav'); ?>

<div class="row pt-5 g-5">
      <div class="col-md-12 col-lg-12">
        <form class="needs-validation" novalidate="" method="POST" action="" enctype="multipart/form-data">
          <div class="row g-3">
            <div class="row py-3">
            <h5>Settaggio Banner</h5>
            <div class="col-lg-4 col-md-4 col-sm-12">
              <label class="form-label">Inserisci il titolo del banner</label>
              <input type="text" class="form-control" name="banner_name" placeholder="" value="" required="">
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
              <label class="form-label">Inserisci il link del banner</label>
              <input type="text" class="form-control" name="banner_link" placeholder="" value="" required="">
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
              <label class="form-label">Seleziona la tipologia di banner</label>
              <select class="form-select" name="banner_type" required="">
                <option selected>Seleziona la tipologia</option>
                <?php foreach ($_Banner->display_banner_type() as $key => $value) : ?>
                  <option value="<?= $value->ID ?>"><?= $value->NomeTipologia ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            </div>
            <div class="row py-3">
              
              <h5>Dove deve essere visibile il banner?</h5>

              <!-- Pagine, Aziende, Eventi, Risorse, Articoli, Esperto, Direttamente dalle aziende -->
              <?= do_shortcode('[setting_section post="page" title="Pagine, Sezioni e Sottosezioni"]'); ?>
              <?= do_shortcode('[setting_section post="ElencoAziende" title="I Partner"]'); ?>
              <?= do_shortcode('[setting_section post="eventi" title="Singoli Eventi"]'); ?>
              <?= do_shortcode('[setting_section post="risorse" title="Singole Risorse"]'); ?>
              <?= do_shortcode('[setting_section post="post" title="Post"]'); ?>
              <?= do_shortcode('[setting_section post="esperto" title="Post Esperto"]'); ?>
              <?= do_shortcode('[setting_section post="direttamenteaziende" title="Post Dalle Aziende"]'); ?>

            </div>
            <div class="row py-3">
              <h5>Settaggi Pay per Click</h5>
              <div class="col-lg-3 col-md-3 col-sm-12">
                <label for="" class="form-label">Scegli la campagna promozionale</label>
                <select class="form-select" name="campaign" aria-label="Default select example">
                  <?php foreach ($_Campaign->campaign_list() as $key => $value) : ?>
                    <option value="<?= $value->ID ?>"><?= $value->Nome ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-lg-3 col-md-3 col-sm-12">
                <label class="form-label">Data Inizio</label>
                <input type="date" class="form-control" name="date_on" placeholder="" value="" required="">
              </div>
              <div class="col-lg-3 col-md-3 col-sm-12">
                <label class="form-label">Data Fine</label>
                <input type="date" class="form-control" name="date_off" placeholder="" value="" required="">
              </div>
              <div class="col-lg-3 col-md-3 col-sm-12">
                <label class="form-label">Pubblicato</label>
                <select name="publish" class="form-select" aria-label="">
                  <option value="1">on</option>
                  <option value="2">off</option>
                </select>
              </div>
            </div>
            <?php foreach ($_Banner->display_banner_size() as $key => $value) : ?>
              <hr class="my-4">
              <div class="col-sm-4">
                <label for="<?= $value->Device ?>" class="form-label"><?= strtoupper($value->Device) ?></label>
              </div>
              <div class="col-sm-8">
                  <label class="form-label">Inserisci il banner</label>
                  <input class="form-control" type="file" name="<?= $value->Device ?>" id="<?= $value->Device ?>" accept="*/*" required="">
              </div>
            <?php endforeach; ?>
            <hr class="my-4">
          <button class="w-100 btn btn-secondary btn-lg" type="submit">Aggiungi il Banner</button>
        </form>
      </div>
    </div>
  </main>
</div>