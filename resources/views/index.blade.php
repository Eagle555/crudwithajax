<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Список статей</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->

    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

    <!--[if lt IE 9]>


    <![endif]-->

</head>

<body>

<div class="container">

    <div class='row'>

        <h1 class="ofset">Список статей</h1>

            <button type="button" class="btn btn-primary btn-lg ofset right" data-toggle="modal" data-target="#addArticle">

                Добавить статью

            </button>

    </div>

    <br />

    <div class='row @if(count($articles) != 0) show @else text-hide @endif' id='articles-wrap'>

        <table class="table table-striped ">

            <thead>

            <tr>

                <th>ID</th>

                <th>Заголовок</th>

                <th></th>

            </tr>

            </thead>

            <tbody>

            @foreach($articles as $article)

                <tr>

                    <td>{{ $article->id }}</td>

                    <td><a href="{{ route('article.show',$article->id) }}">{{ $article->title }}</a></td>

                    <td><a href="" class="delete" data-href=" {{ route('article.destroy',$article->id) }} ">Удалить</a></td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

    <div class="row">

        <div class="alert alert-warning @if(count($articles) != 0) text-hide @else show @endif" role="alert"> Записей нет</div>

    </div>

</div>

<!-- Modal -->

<div class="modal fade" id="addArticle" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title" id="addArticleLabel">Добавление статьи</h4>

            </div>

            <form id="myForm" name="myForm" class="form-horizontal" novalidate="">

                <div class="modal-body">

                    <div class="form-group">

                        <label for="title">Заголовок</label>

                        <input type="text" class="form-control" id="title">

                    </div>

                </div>

                <div class="modal-body">


                    <div class="form-group">

                        <label for="text">Текст</label>

                        <textarea class="form-control" id="text"></textarea>

                    </div>

                </div>

            </form>

            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>

                <button type="button" class="btn btn-primary" id="save">Сохранить</button>

            </div>

        </div>

    </div>

</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

<!-- Include all compiled plugins (below), or include individual files as needed -->

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

<script>

    $(function() {

        $('#save').on('click',function(){

            var title = $('#title').val();

            var text = $('#text').val();

            $.ajax({

                url: '{{ route('article.store') }}',

                type: "POST",

                data: {title:title,text:text},

                headers: {

                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')

                },

                success: function (data) {

                    $('#myForm').trigger("reset");

                    $('#addArticle').modal('hide');

                    $('#articles-wrap').removeClass('text-hide').addClass('show');

                    $('.alert').removeClass('show').addClass('text-hide');

                    var str = '<tr><td>'+data['id']+

                        '</td><td><a href="/article/'+data['id']+'">'+data['title']+'</a>'+

                        '</td><td><a href="" class="delete" data-href="/article/'+data['id']+'">Удалить</a></td></tr>';

                    $('.table > tbody:last').append(str);

                },

                error: function (msg) {

                    alert('Ошибка');

                }

            });

        });

    })

   $('body').on('click','.delete',function(e){

        e.preventDefault();

        var url = $(this).data('href');

        var el = $(this).parents('tr');

        $.ajax({

            url: url,

            type: "DELETE",

            headers: {

                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')

            },

            success: function (data) {

                el.detach();

            },

            error: function (msg) {

                alert('Ошибка');

            }

        });

    });

</script>

</body>

</html>
