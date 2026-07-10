<?php foreach ($discounts as $diskon) : ?>
    <!-- Edit Modal Begin -->
    <div class="modal fade" id="editModal-<?= $diskon['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <?= form_open('diskon/edit/' . $diskon['id']) ?>
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Data Diskon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <?= form_label('Tanggal', 'tanggal') ?>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?= $diskon['tanggal'] ?>" readonly>
                    </div>
                    <div class="form-group">
                        <?= form_label('Nominal', 'nominal') ?>
                        <input type="text" name="nominal" id="nominal" class="form-control" value="<?= $diskon['nominal'] ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <?= form_submit('submit', 'Simpan', ['class' => 'btn btn-primary']) ?>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
    <!-- Edit Modal End -->
<?php endforeach ?>