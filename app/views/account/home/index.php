<?= View::get('layout.header') ?>

<!-- Main content -->
<section class="content">
    <div class="row">
       <div class="col-md-12">
          <div class="details">
             <h3><?= !empty($title) ? $title : "" ?></h3>
             <p>
                 <?= !empty($message) ? $message : "" ?><br />
             </p>
          </div>
       </div>
    </div>
</section>

<?= View::get('layout.footer') ?>