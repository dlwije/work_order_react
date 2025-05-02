@extends('template.master')
@section('title','New Users Type')
@section('content')
    <link href="{{ asset('assets/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css"/>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @include('errors.errors-forms')
            </div>

            <div class="col-md-12">
                <!-- Default box -->
                <form action="{{route('role.store')}}" method="post">
                    @csrf
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">New User Type</h3>
                            <div class="card-tools">
                                <a class="btn btn-sm btn-secondary no-shadow" href="{{ route('role.index') }}">  <i class="fas fa-hand-o-left"></i>Go Back</a>
                            </div>
                        </div>
                        <!-- /.card-header -->

                        <div class="card-body">

                                <div class="">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <strong>Name:</strong>
                                            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-12 table-responsive no-padding">

                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>Modules</th>
                                                <th>
                                                    <label>{{ Form::checkbox('permission_all', 1, false, array('class' => 'name','id' => 'permission_all')) }}</label>
                                                    &nbsp;
                                                    Options
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($permission_head as $value_head)
                                                @foreach($permission as $value)
                                                    @if($value_head->id === $value->header_id)
                                                        @php $child_count = $loop->count; @endphp
                                                    @endif
                                                @endforeach
                                                <tr>
                                                    <td>
                                                        {{ Form::checkbox('permission_all'.$loop->index, 1, false, array('class' => 'name','id' => 'permission_all'.$loop->index, 'data-chk-type' =>'row_chk', 'data-chk-parent' =>$loop->index, 'data-chk-row-count' =>$child_count)) }}
                                                        &nbsp;
                                                        {{$value_head->permission_title}}
                                                    </td>
                                                    @foreach($permission as $value)
                                                        @if($value_head->id === $value->header_id)
                                                            <td>
                                                                <label>{{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name','id' => 'permission'.$loop->parent->index.$loop->index)) }} {{ $value->name }}</label>
                                                            </td>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                </div>

                        </div>
                        <div class="card-footer">
                            <a href="{{ route('role.index') }}"
                               class="btn btn-default">Cancel</a>
                            <button type="submit" class="btn btn-success float-right">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{asset('assets/plugins/iCheck/icheck.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            // Input checkbox iCheck
            $('input[type="checkbox"]').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%'
            });

            $('#permission_all').on('ifChecked', function (event){
                setAllPerm();
            });
            $('#permission_all').on('ifUnchecked', function (event) {
                getAllPerm();
            });
            $(':checkbox').on('ifChecked', function (v) {

                var parentID = $(this).attr("data-chk-parent");

                if($(this).attr("data-chk-type") === "row_chk"){
                    var rowCount = $(this).attr("data-chk-row-count");
                    for (var k=0; k<rowCount; k++){ $('#permission'+parentID+k).iCheck('check'); }
                }
            });
            $(':checkbox').on('ifUnchecked', function (v) {

                var parentID = $(this).attr("data-chk-parent");

                if($(this).attr("data-chk-type") === "row_chk"){
                    var rowCount = $(this).attr("data-chk-row-count");
                    for (var k=0; k<rowCount; k++){ $('#permission'+parentID+k).iCheck('uncheck'); }
                }
            });
        });

        function setAllPerm() {

            var x = document.getElementsByTagName("input");

            for (i = 0; i < x.length; i++) {
                if (x[i].type == "checkbox") {
                    var chkID = '#'+x[i].id;
                    if(chkID != "#")
                    $(chkID).iCheck('check');
                }
            }
        }
        function getAllPerm() {

            var x = document.getElementsByTagName("input");

            for (i = 0; i < x.length; i++) {
                if (x[i].type == "checkbox") {
                    var chkID = '#'+x[i].id;
                    if(chkID != "#")
                    $(chkID).iCheck('uncheck');
                }
            }
        }
    </script>
@endpush


