<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <?= $judul ?>
        </div>
        <div class="card-body">
            <div class="alert alert-primary" role="alert">
                <h1 class="display-3">Soal</h1>
            </div>
            <?php if (!empty($dt_detail['file_soal'])) : ?>
                <div class="w-50 mb-2">
                    <?= tampil_media('uploads/bank-soal/' . $dt_detail['file_soal']); ?>
                </div>
            <?php endif; ?>
            <br>
            <h4><?= $dt_detail['soal'] ?></h4>
            <div class="alert alert-primary" role="alert">
                <h1 class="display-4">Jawaban</h1>
            </div>
            <hr>
            <?php
            $abjad = ['a', 'b', 'c', 'd', 'e'];
            $benar = '<span class="badge badge-pill badge-success"><i class="fa fa-check-circle text-purple"></i> Jawaban Benar</span>';

            foreach ($abjad as $abj) :

                $ABJ = strtoupper($abj);
                $opsi = 'opsi_' . $abj;
                $file = 'file_' . $abj;
            ?>

                <h4>Pilihan <?= $ABJ ?> <?= $dt_detail['jawaban'] === $ABJ ? $benar : "" ?></h4>
                <?php if (!empty($dt_detail[$file])) : ?>
                    <div class="w-50 mb-2">
                        <?= tampil_media('uploads/bank-soal/' . $dt_detail[$file]); ?>
                    </div>
                <?php endif; ?>
                <?= $dt_detail[$opsi] ?>
            <?php endforeach; ?>
            <hr>
            <p><strong>Dibuat Pada:</strong> <?= strftime("%A, %d %B %Y", date($dt_detail['created_on'])) ?></p>
            <p><strong>Terkahir diupdate :</strong> <?= strftime("%A, %d %B %Y", date($dt_detail['updated_on'])) ?></p>
        </div>
    </div>
</div>