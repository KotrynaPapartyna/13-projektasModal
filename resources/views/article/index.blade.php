@extends('layouts.app')

@section('content')


{{-- Paieska laukas --}}
<div class="container">

    <div class="search-form row">
        <div class="col-md-8">
            {{--sukurimo mygtukas--}}
            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#createArticleModal">
                Create New Article Modal
            </button>
        </div>

        {{--paieskos laukas--}}
        <div class="col-md-4">
            <input type="text" class="form-control" id="search-field" name="search-field"/>
            <button type="button" class="btn btn-primary" id="search-button" >Search</button>
            <span class="search-feedback">
            </span>
        </div>
    </div>

    {{--filtravimas ir rikiavimas--}}
    <div class="sort-form row">

        {{--pasirinkimas pagal ka rikiuojam--}}
        <select id="sortCol" name="sortCol">
            <option value='id' selected="true">ID</option>
            <option value='title'>Title</option>
            <option value='description'>Description</option>
            <option value='type_id'>Type</option>
        </select>

        {{--didejimo/mazejimo tvarka--}}
        <select id="sortOrder" name="sortOrder">
            <option value='ASC' selected="true">ASC</option>
            <option value='DESC'>DESC</option>
        </select>

        <select id="type_id" name="type_id">
            <option value="all" selected="true"> Show All </option>

            @foreach ($types as $type)
                <option value='{{$type->id}}'>{{$type->title}}</option>
            @endforeach
        </select>

    <button type="button" id="filterArticles" class="btn btn-primary">Filter articles</button>

    </div>

        <div class="alerts">
        </div>

        <div class="search-alert">
        </div>

{{-- atvaizdavimo lentele--}}
<table class="articles table table-striped">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Description</th>
        <th>Type</th>
        <th>Actions</th>
    </tr>

    @foreach ($articles as $article)
        <tr class="rowArticle{{$article->id}}">
            <td class="colArticleId">{{$article->id}}</td>
            <td class="colArticleTitle">{{$article->title}}</td>
            <td class="colArticleDescription">{{$article->description}}</td>
            <td class="colArticleTypeTitle">{{$article->articleType->title}}</td>
            <td>
                <button type="button" class="btn btn-success show-article" data-articleid='{{$article->id}}'>Show</button>
                <button type="button" class="btn btn-secondary update-article" data-articleid='{{$article->id}}'>Update</button>

            </td>
        </tr>
    @endforeach
</table>

</div>

{{--Modal langas sukurimui--}}
<div class="modal fade" id="createArticleModal" tabindex="-1" role="dialog" aria-labelledby="createArticleModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">CREATE NEW ARTICLE</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
            <div class="articleAjaxForm">

                <div class="form-group row">
                    <label for="articleTitle" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>
                    <div class="col-md-6">
                        <input id="articleTitle" type="text" class="form-control" name="articleTitle">
                        <span class="invalid-feedback articleTitle" role="alert"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="articleDescription" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>
                    <div class="col-md-6">
                        <textarea id="articleDescription" name="articleDescription" class="summernote form-control">
                        </textarea>
                        <span class="invalid-feedback articleDescription" role="alert"></span>
                    </div>

                </div>
                <div class="form-group row articleType">
                    <label for="articleType" class="col-md-4 col-form-label text-md-right">{{ __('Type') }}</label>

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
          <h5 class="modal-title">Edit article</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
            <div class="articleAjaxForm">
                <input type='hidden' id='edit-articleId'>
                <div class="form-group row">
                    <label for="articleTitle" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>
                    <div class="col-md-6">
                        <input id="edit-articleTitle" type="text" class="form-control" name="articleTitle">
                        <span class="invalid-feedback articleTitle" role="alert"></span>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="articleDescription" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                    <div class="col-md-6">
                        <textarea id="edit-articleDescription" name="articleDescription" class="summernote form-control">

                        </textarea>
                        <span class="invalid-feedback articleDescription" role="alert"></span>
                    </div>
                </div>

                <div class="form-group row articleType">
                    <label for="articleType" class="col-md-4 col-form-label text-md-right">{{ __('type') }}</label>

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
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
        }
    });

    function createTable(articles) {
        $(".articles tbody").html("");
        $(".articles tbody").append("<tr><th>ID</th><th>Title</th><th>Description</th><th>Type</th><th>Actions</th></tr>");
        $.each(articles, function(key, article){
                var articleRow = "<tr class='rowArticle"+ article.id +"'>";

                articleRow += "<td class='colArticleId'>"+ article.id +"</td>";
                articleRow += "<td class='colArticleTitle'>"+ article.title +"</td>";
                articleRow += "<td class='colArticleDescription'>"+ article.description +"</td>";
                articleRow += "<td class='colArticleTypeTitle'>"+ article.typeTitle +"</td>";
                articleRow += "<td>";
                articleRow += "<button type='button' class='btn btn-success show-article' data-articleid='"+ article.id +"'>Show</button>";
                articleRow += "<button type='button' class='btn btn-secondary update-article' data-articleid='"+ article.id +"'>Update</button>";
                articleRow += "</td>";
                articleRow += "</tr>";
                $(".articles tbody").append(articleRow);
        });
    }
 $(document).ready(function() {
    $(".addArticleModal").click(function() {
        var articleTitle = $("#articleTitle").val();
        var articleDescription = $("#articleDescription").val();
        var articleType = $("#articleType").val();

        $.ajax({
                type: 'POST',
                url: '{{route("article.storeAjax")}}',
                data: {articleTitle:articleTitle,articleDescription:articleDescription, articleType:articleType },
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        $(".invalid-feedback").css("display", 'none');
                        $("#createArticleModal").modal("hide");
                        var articleRow = "<tr class='rowArticle"+ data.articleId +"'>";
                            articleRow += "<td class='colArticleId'>"+ data.articleId +"</td>";
                            articleRow += "<td class='colArticleTitle'>"+ data.articleTitle +"</td>";
                            articleRow += "<td class='colArticleDescription'>"+ data.articleDescription +"</td>";
                            articleRow += "<td class='colArticleTypeTitle'>"+ data.articleType +"</td>";
                            articleRow += "<td>";
                            articleRow += "<button type='button' class='btn btn-success show-article' data-articleid='"+ data.articleid +"'>Show</button>";
                            articleRow += "<button type='button' class='btn btn-secondary update-article' data-articleid='"+ data.articleid +"'>Update</button>";
                            articleRow += "</td>";
                            articleRow += "</tr>";
                        $(".articles").append(articleRow);
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

       $(document).on('click', '.show-article', function() {
       $('#showArticleModal').modal('show');
       var articleid = $(this).attr("data-articleid");
       $.ajax({
                type: 'GET',
                url: '/articles/showAjax/' + articleid ,// action
                success: function(data) {

                    $('.show-articleDescription').html('');
                    $('.show-articleType').html('');

                    $('.show-articleDescription').append(data.articleDescription);
                    $('.show-articleType').append(data.articleType);
                }
            });
       console.log(articleid);
    });

        $(document).on('click', '.update-article', function() {
        var articleid = $(this).attr('data-articleid');
        $("#editArticleModal").modal("show");
        $.ajax({
                type: 'GET',
                url: '/articles/editAjax/' + articleid ,// action
                success: function(data) {
                    $("#edit-articleId").val(data.articleId);
                  $("#edit-articleTitle").val(data.articleTitle);
                  $("#edit-articleDescription").val(data.articleDescription);
                  $("#edit-articleType").val(data.articleType);
                }
            });
    })
    $(".updateArticleModal").click(function() {
        var articleId = $("#edit-articleId").val();
        var articleTitle = $("#edit-articleTitle").val();
        var articleDescription = $("#edit-articleDescription").val();
        var articleType = $("#edit-articleType").val();
        $.ajax({
                type: 'POST',
                url: '/articles/updateAjax/' + articleid ,
                data: {articleTitle:articleTitle, articleDescription:articleDescription, articleType:articleType },
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        $(".invalid-feedback").css("display", 'none');
                        $("#editArticleModal").modal("hide");
                        $(".alerts").append("<div class='alert alert-success'>"+ data.success +"</div");
                        $(".rowArticle"+ articleid + " .colArticleTitle").html(data.articleTitle);
                        $(".rowArticle"+ articleid + " .colArticleDescription").html(data.articleDescription);
                        $(".rowArticle"+ articleid + " .colArticleTypeTitle").html(data.articleType);
                    } else {
                        $(".invalid-feedback").css("display", 'none');
                        $.each(data.error, function(key, error){
                            //key = laukelio pavadinimas prie kurio ivyko klaida
                            var errorSpan = '.' + key;
                            $(errorSpan).css('display', 'block');
                            $(errorSpan).html('');
                            $(errorSpan).append('<strong>'+ error + "</strong>");
                        });
                    }
                }
            });
    })

    $(".test-delete").click(function() {

        $(".rowArticle3 .colArticleTitle").html("pakeistas per javascript");

    })

      $(document).on('input', '#search-field', function() {

        var searchField = $("#search-field").val();
        var searchFieldCount = searchField.length;
        if(searchFieldCount != 0 && searchFieldCount < 3) {
            $(".search-feedback").css('display', 'block');
            $(".search-feedback").html("Min 3 symbols"); // minimalus ivedamu simboliu kiekis
        } else {
            $(".search-feedback").css('display', 'none');

        $.ajax({
                type: 'GET',
                url: '/articles/searchAjax/',
                data: {searchField: searchField },
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        console.log(data.success);
                        $(".articles").css("display", "block");
                        $(".search-alert").html("");
                        $(".search-alert").html(data.success);
                        createTable(data.articles);
                    } else {
                        $(".articles").css("display", "none");
                        $(".articles tbody").html("");
                        $(".search-alert").html("");
                        $(".search-alert").append(data.error);

                    }
                }
            });
        }
    })
    $(document).on('click', '#filterArticles', function() {
        var sortCol = $("#sortCol").val();
        var sortOrder = $("#sortOrder").val();
        var type_id = $("#type_id").val();
        $.ajax({
                type: 'GET',
                url: '/articles/indexAjax/',
                data: {sortCol: sortCol, sortOrder: sortOrder, type_id: type_id },
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        createTable(data.articles);
                    } else {
                        console.log(data.error)
                    }
                }
            });
    });

 });
</script>

{{--SUMMERNOTE SCRIPTAS--}}
    <script>
        $(document).ready(function() {
            $('.summernote').summernote();
        });
    </script>

@endsection
