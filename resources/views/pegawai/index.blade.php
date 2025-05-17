@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">            
                    <div class="card-header">Import file Excel kedalam database</div>
                        <div class="card-body"> 
                            <div id="alert_msg"></div>
                            <button class="btn btn-primary btn-sm tambah_petugas mb-3" id="add_file">Import</button>
                            <button class="btn btn-danger btn-sm tambah_petugas mb-3" id="clear_table">Clear</button>
                            <div>

                                <table class="table table-sm table-striped tb_pegawai" id="tb_pegawai">
                                    <thead>
                                        <tr>
                                            <th width='180px'>Nama</th>
                                            <th width='280px'>Alamat</th>
                                            <th>Email</th>
                                            <th width='180px'>Nomor telpon</th>
                                            <th width='180px'>Jenis kelamin</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="err_msg"></div>
                <form id="form_import" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file" class="font-weight-bold">File</label>
                        <input type="file" class="form-control-file" name="file" id="file">
                        <small class="form-text text-muted">Jenis file csv, xls, xlsx</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="save">Import</button>
                    </div>
                </form>
            </div>
            
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/JavaScript">
    $(document).ready(function(){

        $('body').on('click', '#add_file', function(){
            $('#modal_import').modal('show');
        });

        $('#save').click(function(e){
            e.preventDefault();
            var formData = new FormData(document.getElementById('form_import'));
            $.ajax({
                type:'POST',
                url:"{{route('api.v1.import')}}",
                data:formData,
                processData:false,
                contentType:false,
                dataType:'JSON',
                success:function(data){
                    console.log(data)
                    $('#modal_import').modal('hide');
                    $('#tb_pegawai').DataTable().ajax.reload();
                },
                error:function(err){
                    if(err.status == 422){
                        $.each(err.responseJSON.errors, function (i, error) {
                            document.getElementById('err_msg').innerHTML = '<div class="alert alert-danger" role="alert">'+error[0]+'</div>'
                        });
                    }
                }
            });
        });

        $(".tb_pegawai").DataTable({
            ajax:{
                url:"{{route('api.v1.pegawai')}}",
                dataSrc:'pegawai'
            },
            processing:false,
            serverside:false,
            columns:[
                {data:'nama'},
                {data:'alamat'},
                {data:'email', 
                    mRender:function(data, type, full){
                        return`<span class="badge badge-warning">${data}</span>`;
                    }
                },
                {data:'no_telpon'},
                {data:'jenis_kelamin'},
            ]
        });

        $('body').on('click', '#clear_table', function(){
            if(confirm('Seluruh data akan dihapus. Anda yakin?')){
                $.ajax({
                    url:"{{route('api.v1.pegawai.clear')}}",
                    type:'DELETE',
                    data:{
                        '_token':'{{csrf_token()}}'
                    },
                    dataType:'json',
                    success:function(data){
                        if(data.count > 0){
                            $('#tb_pegawai').DataTable().ajax.reload();

                            document.getElementById('alert_msg').innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert">Data sebanyak '+data.count+' berhasil dihapus.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        }else{
                            document.getElementById('alert_msg').innerHTML = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong>Holy guacamole!</strong> Data sudah kosong.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        }

                    },error:function(err){
                        console.log(err.responseText);
                    }
                });
            }
        });
    });
</script>
@endpush
