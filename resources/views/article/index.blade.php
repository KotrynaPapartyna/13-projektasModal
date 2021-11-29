@extends('layouts.app')

@section('content')

<div class="container">

<div class="alerts">
    {{-- rekomenduojama max 3 modal langai--}}
</div>

    {{--ISOKANTIS LANGAS SUKURIMUI SU MODAL--}}
    <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#createArticleModal">
        CREATE NEW ARTICLE MODAL
    </button>

    {{--ISOKANTIS LANGAS PARODYMUI SU MODAL
    <button type="button" class="btn btn-outline-dark" data-toggle="modal" data-target="#showArticleModal">
        SHOW ARTICLE MODAL
    </button>--}}

{{--alert vieta, kol ju nera- nerodo--}}
<div class="alerts d-none">
</div>

<table class="articles table table-striped">
    <tr>
        <th>ID</th>
        <th>TITLE</th>
        <th>DESCRIPTION</th>
        <th>TYPE</th>
        <th>ACTIONS</th>
    </tr>

    @foreach ($articles as $article)
        <tr>
            <td>{{$article->id}}</td>
            <td>{{$article->title}}</td>
            <td>{!!$article->description!!}</td>
            <td>{{$article->articleType->title}}</td>
            <td>
                <button type="button" class="btn btn-info show-article" data-articleid='{{$article->id}}'>SHOW</button>
                <button type="button" class="btn btn-dark update-article" data-articleid='{{$article->id}}'>UPDATE</button>
            </td>
            <td><input class="delete-article" type="checkbox"  name="articleDelete[]" value="{{$article->id}}" /></td>
        </tr>
    @endforeach
</table>

{{-- visu pazymetu istrynimas--}}
<button class="btn btn-danger" id="delete-selected">DELETE</button>

</div>

{{--type sukurimo Modal langas--}}
<div class="modal fade" id="createArticleModal" tabindex="-1" role="dialog" aria-labelledby="createArticleModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">CREATE ARTICLE</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
            <div class="articleAjaxForm">

                <div class="form-group row">
                    <label for="articleTitle" class="col-md-4 col-form-label text-md-right">{{ __('TITLE') }}</label>
                    <div class="col-md-6">
                        <input id="articleTitle" type="text" class="form-control" name="articleTitle">
                        <span class="invalid-feedback articleTitle" role="alert"></span>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="articleDescription" class="col-md-4 col-form-label text-md-right">{{ __('DESCRIPTION') }}</label>

                    <div class="col-md-6">
                        <textarea id="articleDescription" name="articleDescription" class="summernote form-control">
                        </textarea>
                        <span class="invalid-feedback articleDescription" role="alert"></span>
                    </div>
                </div>

                <div class="form-group row articleType">
                    <label for="articleType" class="col-md-4 col-form-label text-md-right">{{ __('ARTICLE TYPE') }}</label>

                    <div class="col-md-6">

                        <select id="articleType" class="form-control" name="articleType">
                            @foreach ($types as $type)
                                <option value="{{$type->id}}"> {{$type->title}}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback articleType" role="alert"></span>
                    </div>

                </div>
            </div>
        </div>


        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary addArticleModal">Add</button>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="showArticleModal" tabindex="-1" role="dialog" aria-labelledby="showArticleModal" aria-hidden="true">
    <div class="modal-dialog" role="document">

    <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title show-articleTitle"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <p class="show-articleDescription"></p>
          <p class="show-articleType"></p>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="editArticleModal" tabindex="-1" role="dialog" aria-labelledby="editArticleModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">EDIT ARTICLE</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
            <div class="articleAjaxForm">
                <input type='hidden' id='edit-articleid'>

                <div class="form-group row">
                    <label for="articleTitle" class="col-md-4 col-form-label text-md-right">{{ __('TITLE') }}</label>
                    <div class="col-md-6">
                        <input id="edit-articleTitle" type="text" class="form-control" name="articleTitle">
                        <span class="invalid-feedback articleTitle" role="alert"></span>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="articleDescription" class="col-md-4 col-form-label text-md-right">{{ __('DESCRIPTION') }}</label>
                    <div class="col-md-6">
                        <textarea id="edit-articleDescription" name="articleDescription" class="summernote form-control">

                        </textarea>
                        <span class="invalid-feedback articleDescription" role="alert"></span>
                    </div>
                </div>


                <div class="form-group row articleType">
                    <label for="articleType" class="col-md-4 col-form-label text-md-right">{{ __('ARTICLE TYPE') }}</label>

                    <div class="col-md-6">
                        <select id="edit-articleType" class="form-control" name="articleType">
                            @foreach ($types as $type)
                                <option value="{{$type->id}}"> {{$type->title}}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback articleType" role="alert"></span>
                    </div>
                </div>

            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary updateArticleModal">Update</button>
        </div>

      </div>
    </div>
</div>

<script>
    $.ajaxSetup({   // formos apsauga- turi buti visada
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
        }
    });



 $(document).ready(function() {
    $(".addArticleModal").click(function() {
        var articleTitle = $("#articleTitle").val();
        var articleDescription = $("#articleDescription").val();
        var articleType = $("#articleType").val();

        $.ajax({
                type: 'POST',
                url: '{{route("article.storeAjax")}}',
                data: {articleTitle:articleTitle, articleDescription:articleDescription, articleType:articleType},
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        $(".invalid-feedback").css("display", 'none');
                        $("#createArticleModal").modal("hide");
                        $(".articles").append("<tr><td>"+ data.articleId +"</td><td>"+ data.articleTitle +"</td><td>"+ data.articleDescription +"</td><td>"+ data.articleType +"</td><td>Actions</td></tr>");
                        $(".alerts").append("<div class='alert alert-success'>"+ data.success +"</div");
                        $("#articleTitle").val('');
                        $("#articleDescription").val('');
                    } else {
                        $(".invalid-feedback").css("display", 'none');
                        $.each(data.error, function(key, error){
                            var errorSpan = '.' + key;
                            $(errorSpan).css('display', 'block');
                            $(errorSpan).html('');
                            $(errorSpan).append('<strong>'+ error + "</strong>");
                        });
                    }
                }
            });
    });


    $(".show-article").click(function() {
       $('#showArticleModal').modal('show');
       var articleid = $(this).attr("data-articleid");
        // vykdoma ajax uzklausa
            $.ajax({
                type: 'GET',
                url: '/articles/showAjax/' + articleid ,
                success: function(data) {
                    $('.show-articleTitle').html('');
                    $('.show-articleDescription').html('');
                    $('.show-articleType').html('');

                    $('.show-articleTitle').append(data.articleId + '. ' + data.articleTitle);
                    $('.show-articleDescription').append(data.articleDescription);
                    $('.show-articleType').append(data.articleType);
                }
            });
       //console.log(articleid);
    });

    $(".update-article").click(function() {
        var articleid = $(this).attr('data-articleid');
        $("#editArticleModal").modal("show");

        // vykdoma ajax uzklausa
        $.ajax({
                type: 'GET',
                url: '/articles/editAjax/' + articleid ,
                success: function(data) {
                    $("#edit-articleid").val(data.articleId);
                  $("#edit-articleTitle").val(data.articleTitle);
                  $("#edit-articleDescription").val(data.articleDescription);
                  $("#edit-articleType").val(data.articleType);
                }
            });
    })


    $(".updateArticleModal").click(function() {
        var articleid = $("#edit-articleid").val();
        var articleTitle = $("#edit-articleTitle").val();
        var articleDescription = $("#edit-articleDescription").val();
        var articleType = $("#edit-articleType").val();

        // vykdoma ajax uzklausa
        $.ajax({
                type: 'POST',
                url: '/articles/updateAjax/' + articleid ,
                data: {articleTitle:articleTitle, articleDescription:articleDescription, articleType:articleType},
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        $(".invalid-feedback").css("display", 'none');
                        $("#editArticleModal").modal("hide");
                        $(".alerts").append("<div class='alert alert-success'>"+ data.success +"</div");
                    } else {
                        $(".invalid-feedback").css("display", 'none');
                        $.each(data.error, function(key, error){
                            var errorSpan = '.' + key;
                            $(errorSpan).css('display', 'block');
                            $(errorSpan).html('');
                            $(errorSpan).append('<strong>'+ error + "</strong>");
                        });
                    }
                }
            });
    })
 });
</script>

{{--SUMMERNOTE SCRIPTAS--}}
<script>
    $(document).ready(function() {
        $('.summernote').summernote();
    });
</script>

@endsection
