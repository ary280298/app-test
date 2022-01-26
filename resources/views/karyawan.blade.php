<!DOCTYPE html>
<html>

<head>
    <title>Laravel 8 Server Side Datatables Tutorial</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12  mb-2 mt-2">
                <h3>Data Karyawan</h3>
            </div>
            <div class="col-md-12  mb-2 mt-2">
                <button class="btn btn-primary col-md-1" id="add_data">Add Data</button>
            </div>
        </div>
        <div class="row">
            <div class="table-responsive col-md-12">
                <table class="table table-bordered dataKaryawan">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Jenis Kelamin</th>
                            <th>Salary</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah data karyawan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group" id="fotoWrapper" style="display: none;">
                            <img id="foto" width="20%" class="rounded mx-auto d-block" alt="...">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Nama:</label>
                            <input type="text" class="form-control" id="nama_karyawan">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Email:</label>
                            <input type="text" class="form-control" id="email_aktif">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Telepon:</label>
                            <input type="number" class="form-control" id="no_hp">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Jenis Kelamin</label><br>
                            <input type="radio" class="jenis_kelamin1" name="jenis_kelamin" value="Pria">
                            <label for="age1">Pria</label><br>
                            <input type="radio" class="jenis_kelamin2" name="jenis_kelamin" value="Wanita">
                            <label for="age1">Wanita</label><br>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Salary:</label>
                            <input type="number" class="form-control" id="salary">
                            <input type="hidden" class="form-control" id="karyawan_id">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Foto:</label>
                            <input type="file" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveData">Save</button>
                    <button type="button" class="btn btn-warning" id="updateData">Update</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    getData();
    $("#add_data").click(function (e) {
        $("#fotoWrapper").css('display', 'none');
        $("#updateData").css('display', 'none');
        $("#saveData").css('display', 'block');
        $("#modal_add").modal('show');
        $("#email_aktif").prop('disabled', false);
        reset();
    })
    $("#saveData").click(function (e) {
        addDataKaryawan();
    })
    $('body').on('click', '.editData', function () {
        $("#saveData").css('display', 'none');
        $("#updateData").css('display', 'block');
        $("#nama_karyawan").val($(this).data('nama_karyawan'));
        $("#email_aktif").val($(this).data('email_aktif')).prop('disabled', true);
        $("#no_hp").val($(this).data('nomor_hp'));
        $("#salary").val($(this).data('salary'));
        $("#karyawan_id").val($(this).data('karyawan_id'));
        $("#modal_add").modal('show');
        $("#fotoWrapper").css('display', 'block');
        $("#foto").attr("src", `assets/karyawan-img/${$(this).data('foto_profil')}`);

        if ($(this).data('jenis_kelamin') == 'Pria') {
            $(".jenis_kelamin1").prop('checked', true);
        } else {
            $(".jenis_kelamin2").prop('checked', true);
        }
    });
    

    $("#updateData").click(function (e) {
        updateDataKaryawan();
    })
   

    function getData() {
        var table = $('.dataKaryawan').DataTable({
            processing: true,
            serverSide: true,
            ajax: "karyawan",
            columns: [{
                    data: 'nama_karyawan',
                    name: 'nama_karyawan'
                },
                {
                    data: 'email_aktif',
                    name: 'email_aktif'
                },
                {
                    data: 'nomor_hp',
                    name: 'nomor_hp'
                },
                {
                    data: 'jenis_kelamin',
                    name: 'jenis_kelamin'
                },
                {
                    data: 'salary',
                    name: 'salary'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    }

    function addDataKaryawan() {
        var formData = new FormData();
        formData.append('nama_karyawan', $("#nama_karyawan").val());
        formData.append('email_aktif', $("#email_aktif").val());
        formData.append('salary', $("#salary").val());
        formData.append('nomor_hp', $("#no_hp").val());
        formData.append('jenis_kelamin', $('input[name="jenis_kelamin"]:checked').val());
        formData.append('foto_profil', $('input[type=file]')[0].files[0]);
        for (var pair of formData.entries()) {
            console.log(pair[0] + ', ' + pair[1]);
        }
        $.ajax({
            url: 'api/karyawan/add',
            type: "POST",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (response, xhr) {
                // console.log(xhr.status)
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data berhasil disimpan',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('.dataKaryawan').DataTable().ajax.reload();
                $("#modal_add").modal('toggle');
                reset();

            },
            error: function (xhr, status, errorThrown) {
                console.log(xhr.status);
                if (xhr.status) {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Pastikan seluruh field diisi dengan benar, Email tidak boleh sama',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal menambahkan data',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }

                xhr.responseText;
            }
        });
    }

    function updateDataKaryawan() {
        var formData = new FormData();
        formData.append('nama_karyawan', $("#nama_karyawan").val());
        formData.append('karyawan_id', $("#karyawan_id").val());
        formData.append('email_aktif', $("#email_aktif").val());
        formData.append('salary', $("#salary").val());
        formData.append('nomor_hp', $("#no_hp").val());
        formData.append('jenis_kelamin', $('input[name="jenis_kelamin"]:checked').val());
        formData.append('foto_profil', $('input[type=file]')[0].files[0]);
        for (var pair of formData.entries()) {
            console.log(pair[0] + ', ' + pair[1]);
        }
        $.ajax({
            url: 'api/karyawan/update',
            type: "POST",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (response, xhr) {
                // console.log(xhr.status)
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data berhasil disimpan',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('.dataKaryawan').DataTable().ajax.reload();
                $("#modal_add").modal('toggle');
                reset();

            },
            error: function (xhr, status, errorThrown) {
                console.log(xhr.status);
                if (xhr.status) {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Pastikan seluruh field diisi dengan benar, Email tidak boleh sama',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal mengubah data',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }

                xhr.responseText;
            }
        });
    }
    $('body').on('click', '.deleteButton', function () {
        var karyawan_id = $(this).data('karyawan_id');          
        $.ajax({
            url: 'api/karyawan/delete',
            type: "POST",
            data: {"karyawan_id" : karyawan_id},
            dataType: "json",
            success: function (response, xhr) {
                // console.log(xhr.status)
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data berhasil disimpan',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('.dataKaryawan').DataTable().ajax.reload();

            },
            error: function (xhr, status, errorThrown) {
                console.log(xhr.errorThrown);
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal menghapus data',
                    showConfirmButton: false,
                    timer: 1500
                });

                xhr.responseText;
            }
        });
    });
    
    $('body').on('click', '.generateWord', function () {      
        var id = $(this).data('karyawan_id');
        $.ajax({
            url: 'api/karyawan/generate/' + id,
            type: "GET",            
            dataType: "json",
            success: function (response, xhr) {
               return response;

            }
        });
    });

    function reset(){
        $("#nama_karyawan").val('');
        $("#email_aktif").val('');
        $("#no_hp").val('');
        $("#salary").val('');
    }

</script>

</html>
