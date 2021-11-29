@extends('layouts.app')

@section('content')

    <div class="container">

    <div class="alerts">
        {{-- rekomenduojama max 3 modal langai--}}
    </div>

    {{--ISOKANTIS LANGAS SUKURIMUI SU MODAL--}}
        <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#createTypeModal">
            CREATE NEW TYPE MODAL
        </button>

    {{--alert vieta, kol ju nera- nerodo--}}
    <div class="alerts d-none">
    </div>

    <table class="types table table-striped">
        <tr>
            <th>ID</th>
            <th>TITLE</th>
            <th>DESCRIPTION</th>
            <th>TOTAL ARTICLES</th>
            <th>ACTIONS</th>

        </tr>

        @foreach ($types as $type)
            <tr class="type{{$type->id}}">
                <td>{{$type->id}}</td>
                <td>{{$type->title}}</td>
                <td>{{$type->description}}</td>
                <td>{{$type->typeArticles->count()}} </td>
                <td>
                    <button type="button" class="btn btn-info show-type" data-typeid='{{$type->id}}'>SHOW</button>
                    <button type="button" class="btn btn-dark update-type" data-typeid='{{$type->id}}'>UPDATE</button>
                </td>
                <td><input class="delete-type" type="checkbox"  name="typeDelete[]" value="{{$type->id}}" /></td>
            </tr>
        @endforeach
    </table>
    {{-- visu pazymetu istrynimas--}}
    <button class="btn btn-danger" id="delete-selected">DELETE</button>


    {{--type sukurimo Modal langas--}}
    <div class="modal fade" id="createTypeModal" tabindex="-1" role="dialog" aria-labelledby="createTypeModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">

            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">CREATE TYPE</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
                <div class="typeAjaxForm">

                    <div class="form-group row">
                        <label for="typeTitle" class="col-md-4 col-form-label text-md-right">{{ __('TYPE TITLE') }}</label>
                        <div class="col-md-6">
                            <input id="typeTitle" type="text" class="form-control" name="typeTitle">
                            <span class="invalid-feedback typeTitle" role="alert"></span>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="typeDescription" class="col-md-4 col-form-label text-md-right">{{ __('TYPE DESCRIPTION') }}</label>
                            <div class="col-md-6">
                            <textarea id="typeDescription" name="typeDescription" class="summernote form-control">
                            </textarea>
                            <span class="invalid-feedback typeDescription" role="alert"></span>
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary addTypeModal">Add</button>
                      </div>
                    </div>
                </div>
            </div>

            {{--show Modal--}}
            <div class="modal fade" id="showTypeModal" tabindex="-1" role="dialog" aria-labelledby="showTypeModal" aria-hidden="true">
                <div class="modal-dialog" role="document">

                <div class="modal-content">

                    <div class="modal-header">
                      <h5 class="modal-title show-typeTitle"></h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <p class="show-typeDescription"></p>
                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
            </div>

            {{--Edit Type Modal--}}
            <div class="modal fade" id="editTypeModal" tabindex="-1" role="dialog" aria-labelledby="editTypeModal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">

                    {{-- virsus- atvaizdavimas--}}
                    <div class="modal-header">
                      <h5 class="modal-title">EDIT TYPE</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                        <div class="typeAjaxForm">
                            <input type='hidden' id='edit-typeid'>

                            <div class="form-group row">
                                <label for="typeTitle" class="col-md-4 col-form-label text-md-right">{{ __('TYPE TITLE') }}</label>
                                <div class="col-md-6">
                                    <input id="edit-typeTitle" type="text" class="form-control" name="typeTitle">
                                    <span class="invalid-feedback typeTitle" role="alert"></span>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="typeDescription" class="col-md-4 col-form-label text-md-right">{{ __('TYPE DESCRIPTION') }}</label>
                                <div class="col-md-6">
                                    <textarea id="edit-typeDescription" name="typeDescription" class="summernote form-control">
                                    </textarea>
                                    <span class="invalid-feedback typeDescription" role="alert"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
                      <button type="button" class="btn btn-primary updateTypeModal">UPDATE</button>
                    </div>

                  </div>
                </div>
            </div>


          </div>
        </div>
    </div>


    <script>
        // formos apsauga- PRIVALOMA PRADZIOJE
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            }
        });

        // FUNKCIJA PAZYMETU LAUKELIU ISTRYNIMUI
        $(document).ready(function() {
                $("#delete-selected").click(function() {
                    var checkedTypes = [];
                    $.each( $(".delete-type:checked"), function( key, type) {
                        checkedTypes[key] = type.value;
                    });
                    console.log(checkedTypes);

                    // vykdoma ajax uzklausa
                    $.ajax({
                    type: 'POST',
                    url: '{{route("type.destroySelected")}}',
                    data: { checkedTypes: checkedTypes },
                    success: function(data) {
                            $(".alerts").toggleClass("d-none");
                            for(var i=0; i<data.messages.length; i++) {
                                $(".alerts").append("<div class='alert alert-"+data.errorsuccess[i] + "'><p>"+ data.messages[i] + "</p></div>")

                                var id = data.success[i];
                                if(data.errorsuccess[i]artic == "success") {
                                    $(".type"+id ).remove();
                                } setInterval(() => {

                                }, interval);
                            }
                        }
                    });
                })
            $(".delete-type").click(function(){
                var type_id = $(this).val();
            })
        })

    // FUNKCIJA TYPE PRIDEJUMUI SU MODAL
    $(document).ready(function() {
    $(".addTypeModal").click(function() {
        var typeTitle = $("#typeTitle").val();
        var typeDescription = $("#typeDescription").val();

        // vykdoma Ajax uzklausa
        $.ajax({
                type: 'POST',
                url: '{{route("type.storeAjax")}}',
                data: {typeTitle:typeTitle, typeDescription:typeDescription},
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        $(".invalid-feedback").css("display", 'none');
                        $("#createTypeModal").modal("hide");
                        $(".types").append("<tr><td>"+ data.typeId +"</td><td>"+ data.typeTitle +"</td><td>"+ data.typeDescription +"</td><td>Actions</td></tr>");
                        $(".alerts").append("<div class='alert alert-success'>"+ data.success +"</div");
                        $("#typeTitle").val('');
                        $("#typeDescription").val('');
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

    // FUNKCIJA TYPE PARODYMUI SU MODAL
    $(".show-type").click(function() {
       $('#showTypeModal').modal('show');
       var typeid = $(this).attr("data-typeid");

        // vykdoma ajax uzklausa
            $.ajax({
                type: 'GET',
                url: '/types/showAjax/' + typeid ,
                success: function(data) {
                    $('.show-typeTitle').html('');
                    $('.show-typeDescription').html('');

                    $('.show-typeTitle').append(data.typeId + '. ' + data.typeTitle);
                    $('.show-typeDescription').append(data.typeDescription);
                }
            });

    });

    // FUNKCIJA UPDATE- EDIT SU MODAL
    $(".update-type").click(function() {
        var typeid = $(this).attr('data-typeid');
        $("#editTypeModal").modal("show");

        // vykdoma ajax uzklausa
        $.ajax({
                type: 'GET',
                url: '/types/editAjax/' + typeid ,
                success: function(data) {
                    $("#edit-typeid").val(data.typeId);
                  $("#edit-typeTitle").val(data.typeTitle);
                  $("#edit-typeDescription").val(data.typeDescription);
                }
            });
    })

    $(".updateTypeModal").click(function() {
        var typeid = $("#edit-typeid").val();
        var typeTitle = $("#edit-typeTitle").val();
        var typeDescription = $("#edit-typeDescription").val();

        // vykdoma ajax uzklausa
        $.ajax({
                type: 'POST',
                url: '/types/updateAjax/' + typeid ,
                data: {typeTitle:typeTitle, typeDescription:typeDescription},
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        $(".invalid-feedback").css("display", 'none');
                        $("#editTypeModal").modal("hide");
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
