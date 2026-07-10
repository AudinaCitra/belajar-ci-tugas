<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<?php
if (session()->getFlashData('success')) {
?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}
?>
<!-- Table with stripped rows -->
<div class="row">
    <?php foreach ($products as $key => $item) : ?>         
            <?php
                $diskon = session()->get('diskon') ?? 0;
                $hargaSetelahDiskon = $item['harga'] - $diskon;

                if ($hargaSetelahDiskon < 0) {
                    $hargaSetelahDiskon = 0;
                }
            ?>

            <div class="col-lg-6">
                <?= form_open('keranjang') ?>

                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                <input type="hidden" name="nama" value="<?= $item['nama'] ?>">
                <input type="hidden" name="harga" value="<?= $hargaSetelahDiskon ?>">
                <input type="hidden" name="diskon" value="<?= $diskon ?>">
                <input type="hidden" name="foto" value="<?= $item['foto'] ?>">
            
                <div class="card">
                    <div class="card-body">
                        <img src="<?= base_url() . "img/" . $item['foto'] ?>" alt="..." width="50%">
                        <h5 class="card-title">
                            <?= $item['nama'] ?><br>

                            <?php if ($diskon > 0) : ?>
                                <small>
                                    <del><?= number_to_currency($item['harga'], 'IDR') ?></del>
                                </small><br>
                                <?= number_to_currency($hargaSetelahDiskon, 'IDR') ?>
                            <?php else : ?>
                                <?= number_to_currency($item['harga'], 'IDR') ?>
                            <?php endif; ?>
                        </h5>
                        <button type="submit" class="btn btn-info rounded-pill">Beli</button>
                    </div>
                </div>
                <?= form_close() ?>
            </div> 
    <?php endforeach ?> 
</div>
<!-- End Table with stripped rows -->
 <?= $this->endSection() ?>