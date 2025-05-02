@extends('template.master')
@section('title','New Users')

@section('content')
    <div class="container-fluid" id="">
        <div class="row">

            <div class="col-md-12">
                @include('errors.errors-forms')
            </div>

            <div class="offset-md-2 col-md-7">
                <form action="{{url('users/store')}}" method="post">
                    @csrf
                    <!-- Default box -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Create User</h3>
                            <div class="card-tools">
                                <a class="btn btn-sm btn-secondary no-shadow" href="{{ route('user.index') }}">  <i class="fas fa-hand-o-left"></i>Go Back</a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <strong>Employee: <span class="text-danger">*</span></strong>
                                            <select class="form-control" name="emp_id" required>
                                                <option value="">Select an Employee</option>
                                                @foreach($emp_list AS $e_list)
                                                    <option value="{{ $e_list->id }}">{{ $e_list->full_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Email: <span class="text-danger">*</span></strong>
                                            {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control', 'required')) !!}
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Username: <span class="text-danger">*</span></strong>
                                            {!! Form::text('username', null, array('placeholder' => 'Username','class' => 'form-control', 'required')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-xs-6 col-md-6 ">
                                        <div class="form-group">
                                            <strong>Password: <span class="text-danger">*</span></strong>
                                            {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control', 'required')) !!}
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-xs-6 col-md-6 ">
                                        <div class="form-group">
                                            <strong>Confirm Password: <span class="text-danger">*</span></strong>
                                            {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control', 'required')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Work Order Role: <span class="text-danger">*</span></strong>
                                            {!! Form::select('roles[]', $roles,[], array('class' => 'form-control','multiple', 'required')) !!}
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>POS Role: <span class="text-danger">*</span></strong>
                                            <select class="form-control" name="pos_role" required>
                                                <option value="">Select a Role</option>
                                                @foreach($pos_roles AS $el_list)
                                                    <option value="{{ $el_list->id }}">{{ $el_list->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <a href="{{ route('user.index') }}"
                                   class="btn btn-default">Cancel</a>
                                <button type="submit" class="btn btn-success float-right">Save</button>
                            </div>

                    </div>
                    <!-- /.card -->
                </form>
            </div>
        </div>
    </div>
@endsection
