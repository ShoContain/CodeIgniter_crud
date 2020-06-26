<div class="container">
    <h2>社員リスト</h2>
    <div class="alert alert-success" style="display: none">

    </div>
    <button class="btn btn-success" id="btnAdd">Add New</button>
    <table class="table table-bordered" style='margin-top: 20px'>
        <thead>
            <tr>
                <th>ID</th>
                <th>名前</th>
                <th>住所</th>
                <th>作成日</th>
                <th>編集・削除</th>
            </tr>
        </thead>
        <!--データベースから取得-->
        <tbody id="showData">
        </tbody>
    </table>
</div>

<!--モダルウィンドウ-->
<div id="myModel" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body">
                <form id="formAdd" method="post" class="form-horizontal">
                    <input type="hidden" name="txtId" value="0">
                    <div class="form-group row">
                        <label for='name' class="label-control col-md-2">名前</label>
                        <div class="col-md-10">
                            <input type="text" name="employeeName" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for='address' class="label-control col-md-2">住所</label>
                        <div class="col-md-10">
                            <textarea name="address" class="form-control"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                <button type="button" class="btn btn-primary" id="btnSave">追加</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--モダルウィンドウ(削除)-->
<div id="deleteModel" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">削除</h4>
            </div>
            <div class="modal-body">
                <div class="form-group row delete-group" style="margin-left: 20px"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
                <button type="button" class="btn btn-danger" id="btnDelete">削除</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script>
    $(function () {
        //load-data
        showAll();

        $('#btnAdd').click(function () {
           $('#myModel').modal('show');
           $('#myModel').find('.modal-title').text('追加');
           $('#formAdd').attr('action','<?=base_url()?>employee/add');
        });

        //click btnSave(Modal画面の追加)
        $('#btnSave').click(function () {
            var url =$('#formAdd').attr('action');
            var data = $('#formAdd').serialize(); //employeeName=&address=

            //validation
            var name = $('input[name=employeeName]');
            var address = $('textarea[name=address]');
            var result = '';

            if(name.val()==''){
                name.parent().parent().addClass('name-error');
                $('.name-error').after('<p class="error-name-msg">名前は必須です</p>');
                $('.error-name-msg').css({'color':'red'});
            }else{
                name.parent().parent().removeClass('name-error');
                $('.error-name-msg').remove();
                result +='1';
            }
            if(address.val()==''){
                address.parent().parent().addClass('address-error');
                $('.address-error').after('<p class="error-address-msg">住所は必須です</p>');
                $('.error-address-msg').css({'color':'red'});
            }else{
                address.parent().parent().removeClass('address-error');
                $('.error-address-msg').remove();
                result +='2';
            }

            //resultが12(どちらのテキストも記入済み)だったらajaxで送信
            if(result == '12'){
                $.ajax({
                    type:'POST',
                    url: url,
                    async:false,
                    data:data,
                    dataType: 'json',
                    success:function (response) {
                        if(response.success){
                            $('#myModel').modal('hide');
                            $('#formAdd')[0].reset();
                            if(response.type=='add') {
                                var type = '追加';
                            }else if(response.type='update'){
                                var type = '更新';
                            }
                            $('.alert-success').html('社員リストを'+type+'しました').fadeIn().delay(4000).fadeOut('slow');
                            showAll();
                        }else {
                            alert('データの追加に失敗しました');
                        }
                    },
                    error:function () {
                        alert('データを送信できませんでした');
                    }
                })
            }
        }); // end of click btnSave

        // ハマったー (；□；)
        // function()内でclick()を設定してしまうと非同期で持ってきたHTMLにはイベント登録がされていないのでクリックイベントが発火しない
        //click btnSave(Modal画面の更新)
            $('#showData').on('click','.item-edit', function () {
                var id = $(this).attr('data');
                $('#myModel').modal('show');
                $('#myModel').find('.modal-title').text('編集');
                $('#formAdd').attr('action', '<?=base_url()?>employee/update');
                $.ajax({
                    type:'ajax',
                    url: '<?= base_url()?>employee/edit/',
                    async:false,
                    data: {id:id},
                    dataType:'json',
                    method:'get',
                    success:function (data) {
                        $('input[name=employeeName]').val(data.name);
                        $('textarea[name=address]').val(data.address);
                        $('input[name=txtId]').val(data.id);
                    },
                    error:function(){
                        alert('データを取得できませんでした')
                    }
                })
            });
        //end of click btnSav

        //click btnSave(削除モダル画面描画)
        $('#showData').on('click','.delete_button', function () {
            var id = $(this).attr('data');
            var name = $(this).attr('data_name');
            $('#deleteModel').modal('show');
            $('.delete-group').text(name+'を本当に削除しますか?');
            $('#btnDelete').click(function () {
                $.ajax({
                    type:'ajax',
                    url:'<?= base_url()?>employee/delete',
                    async:false,
                    data:{id:id},
                    dataType:'json',
                    method:'get',
                    success:function(response){
                        $('#deleteModel').modal('hide');
                        $('.alert-success').html('削除しました').fadeIn().delay(4000).fadeOut('slow');
                        showAll();
                    },
                    error:function(){
                        alert('削除できませんでした')
                    }
                });
            });
        });


        //load-data
        function showAll() {
            $.ajax({
                type:'ajax',
                url: '<?= base_url()?>employee/showAllEmployee',
                async:false,
                dataType:'json',
                success: function(data){
                    var table_data = '';
                    var i;
                    for (i=0;i<data.length;i++){
                    table_data +='<tr>'+
                                    '<td>'+data[i].id+'</td>'+
                                    '<td>'+data[i].name+'</td>'+
                                    '<td>'+data[i].address+'</td>'+
                                    '<td>'+data[i].created_at+'</td>'+
                                    '<td>'+
                                    '<a id="edit_button" data="'+data[i].id+'" class="btn btn-info item-edit" style="margin-right:20px">編集</a>'+
                                    '<a id="delete_button" data="'+data[i].id+'" data_name="'+data[i].name+'" class="btn btn-danger delete_button">削除</a>'+
                                    '</td>'+
                                '</tr>';
                    }
                    $('#showData').html(table_data);
                    $('#formAdd')[0].reset();
                },
                error:function () {
                    alert('データを取得できませんでした');
                }
            })
        }
        //end of load-data
    });
</script>