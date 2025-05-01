@extends('template.master')
@section('title','User Type View')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="offset-md-3 col-md-6">
                <!-- Default box -->
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Permissions of <b>{{$role->name}}</b> Role</h3>
                        <div class="card-tools">
                            <a class="btn btn-sm btn-secondary no-shadow" href="{{ route('role.index') }}">  <i class="fas fa-hand-o-left"></i>Go Back</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <div class="card-body">
                        <table class="table">
                            <strong>Permissions:</strong>
                            @foreach($permission_head AS $ph)
                                <tr>
                                    <td>{{$ph->permission_title}}</td>
                                    @if(!empty($rolePermissions))
                                        <td>
                                            @foreach($rolePermissions as $v)
                                                @if($ph->permission_title == ucfirst(explode('-',$v->name)[0]))
                                                    <label class="label label-success">{{ $v->name }},</label>
                                                @endif
                                            @endforeach
                                        </td>
                                    @endif
                                </tr>
                            @endforeach

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
