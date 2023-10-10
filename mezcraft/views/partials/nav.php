<?php

$options = [
    'Panel' => [
        'Risorse' => [
            'Desc' => 'Gestione risorse di tutti i contenuti di logistica e supply chain',
            'Link' => [
                'url' => '?page=log-index-risorse',
                'text' => 'Vai a risorse' 
            ]
        ],
        'Banner' => [
            'Desc' => 'Gestione banner - pay per click',
            'Link' => [
                'url' => '?page=log-index-banner',
                'text' => 'Vai ai banner' 
            ]
        ],
    ]
];
?>

<div class="row">
    <?php foreach ($options['Panel'] as $key => $value) { ?>
        <div class="col-lg-4 col-md-4 colsm-12">
            <div style="padding:0px;" class="card mb-4 box-shadow">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal"><?= $key ?></h4>
                </div>
                <div class="card-body">
                    <h6 class="card-title"><?= $value['Desc'] ?></h6>
                    <a href="<?= $value['Link']['url'] ?>" class="btn btn-outline-secondary"><?= $value['Link']['text'] ?></a>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
