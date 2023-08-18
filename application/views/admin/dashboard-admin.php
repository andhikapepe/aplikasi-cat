<div class="container-fluid">
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1 class="display-4">Hai, <?= ucwords($this->session->userdata('nama')) ?>...</h1>
            <p class="lead">Selamat Datang di <?= $_app_name ?>, <?= $_app_slogan ?></p>
        </div>
    </div>

    <div class="container">
        <!-- Example row of columns -->
        <div class="row">
            <div class="col">
                <h2>Apa Itu CAT / Computer Assisted Test?</h2>
                <p>
                    Computer Assisted Test (CAT) adalah suatu sistem yang dipakai untuk membantu proses seleksi dengan alat bantu komputer. Dengan menggunakan Computer Assisted Test ini bisa mendapatkan standar minimal kompetensi dasar untuk para pelamar.
                </p>
                <h2>Kelebihan CAT</h2>
                <ol>
                    <li>Waktu yang Fleksibel</li>
                    <p>
                        Waktu penyelenggaraan ujian online lebih fleksibel. Ujian dapat dilakukan kapanpun dan dimanapun 24 jam dan 7 hari dalam seminggu.
                    </p>
                    <li>Durasi Pengerjaan dapat Dibatasi</li>
                    <p>
                        Dalam ujian online, durasi pengerjaan dapat dibatasi. Penyelenggara ujian online dapat memberikan batas waktu agar proses mengerjakan soal peserta tidak melebihi waktu yang ditentukan.
                    </p>
                    <li>Hasil yang Langsung Diketahui</li>
                    <p>
                        Hasil ujian yang dilaksanakan secara online dapat diketahui langsung setelah ujian selesai. Hal ini tentu akan sangat meringankan tugas guru dan peserta ujian pun tidak perlu menunggu lama untuk hasil ujiannya.
                    </p>
                    <li>Input Data Otomatis</li>
                    <p>
                        Data yang terkait dengan pelaksanaan ujian seperti data peserta ujian, mata uji, dan hasil ujian dapat disimpan secara langsung pada database tanpa harus diinput secara manual. Hal ini tentu akan sangat menghemat waktu dan tenaga panitia ujian untuk mengoreksi jawaban para peserta ujian.
                    </p>
                    <li>Menggunakan Media yang Canggih</li>
                    <p>
                        Ujian online dapat menggunakan media khusus seperti audio, video, atau gambar sehingga dapat membantu pemahaman peserta ujian dalam menginterpretasikan dan menjawab menjawab soal ujian.
                    </p>
                    <li>Acak Soal</li>
                    <p>
                        Ujian online memungkinkan untuk dapat mengacak soal, sehingga memperkecil peluang peserta ujian mendapatkan soal dengan urutan yang sama.
                    </p>
                    <li>Hemat</li>
                    <p>
                        Pelaksanaan ujian online dapat menghemat penggunaan sumber daya, seperti penggunaan ruang ujian jika ujian dapat dilakukan dari rumah. Selain itu ujian online juga membantu kita menjaga lingkungan, sebab kita bisa meminimalisir penggunaan kertas.
                    </p>
                </ol>
            </div>
        </div>
    </div>
</div>