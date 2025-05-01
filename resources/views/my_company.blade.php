@extends('template.master')
@section('title','My Company')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="offset-md-3 col-md-6">
                <!-- Default box -->
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">My Company</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form role="form" id="form">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1">BR Number</label>
                                <input type="text" class="form-control" id="BRNo" placeholder="Enter BR Number">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Company Name</label>
                                <input type="text" class="form-control" id="CompName" placeholder="Enter Company Name">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Address</label>
                                <textarea id="Address" class="form-control" rows="1"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email</label>
                                <input type="email" class="form-control" id="Email" placeholder="Enter Email Address">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Web Site</label>
                                <input type="text" class="form-control" id="WebUrl" placeholder="Enter Web Address">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Office Telephone</label>
                                <input type="text" class="form-control" id="OfcTel" placeholder="Enter Phone Number" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==15) return false;">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Mobile</label>
                                <input type="number" class="form-control" id="Mobile" placeholder="Enter Phone Number" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==15) return false;">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Fax</label>
                                <input type="number" class="form-control" id="Fax" placeholder="Enter Fax Number" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==15) return false;">
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="button" class="btn btn-primary float-right">Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>

@endsection
