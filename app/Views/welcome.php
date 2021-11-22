<?php $this->layout('partials/template', ['title' => $title]) ?>

<?php $this->start('mainSection') ?>
<section class="uk-section">
    <div class="uk-container uk-container-small">

        <?= getFlashData('message') ?>
        
        <h1><?= $title ?></h1>
        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quo asperiores beatae officia accusamus quibusdam nobis ipsum repudiandae. Corporis odit voluptatem eveniet modi unde, nam fuga blanditiis harum, ut delectus optio.</p>    
    
    </div>
</section>

<?php $this->stop() ?>