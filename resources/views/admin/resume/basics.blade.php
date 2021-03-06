@extends('layouts.backend')
@section('Title','Users List')

@section('pageHead')
    <h1>
        Resume
        <small>Basics</small>
    </h1>
@endsection

@section('styles')
<link rel="stylesheet" href="{{url('assets/admin/plugins/select2/select2.min.css')}}">
@endsection

@section('content')
    <section class="notifications">
        @include('flash::message')
    </section>
    <section class="content">
    <div class="box box-default">
        <form id="basicsInformationForm" action="{{url('admin/resume/basics/update')}}" method="POST" enctype="multipart/form-data">
            {{csrf_field()}}
            <input type="hidden" name="userID" value="{{$data['basics']->id}}">
        <div class="box-header with-border">
            <h3 class="box-title">Resume Basics Information</h3>
            <span><button  type="submit" class="btn btn-success pull-right" id="updateBasicsInformationBtn">Update</button></span>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" class="form-control" name="name" value="{{$data['basics']->name}}">
                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                        <label>Position</label>
                        <input type="text" class="form-control" name="position" value="{{$data['basics']->resume->position}}">
                    </div>
                    <div class="form-group">
                        <label>Freelance</label>
                        <select name="freelance" class="form-control select2">
                            <option value="1" selected="selected">Available</option>
                            <option value="2">Not Available</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Upload CV/Resume</label>
                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                            <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
                            <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change</span><input type="file" name="cv_path"></span>
                            <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control" value="{{$data['basics']->address}}">
                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" value="{{$data['basics']->email}}">
                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" class="form-control" name="phone" value="{{$data['basics']->phone}}">
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            Please Fill all the Required Fields.
        </div>
        </form>
    </div>
    <!-- /.box -->
<div class="row">
    @include('admin.resume.partials.social')
    @include('admin.resume.partials.config')
</div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(function () {
            $('#basicsInformationForm').on('submit',function(e){
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url:$(this).attr('action'),
                    type:$(this).attr('method'),
                    data: formData,
                    dataType:'json',
                    async:false,
                    success:function(data){
                        if(data.type){
                            Notification(data.box,data.message)
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
                return false;
            });


            //On Update of Basics Configuration.
            $('#resumeConfigForm').on('change','input',function () {

                var updatedSelector = $(this);
                var form = $(this).parents('form');
                var updatedSelectorName = $(this).attr('name');
                var updatedSelectorValue = $(this).val();
                var token = form.find('input[name="_token"]').val();
                var postData = {
                    _token: token
                };
                if(updatedSelector.is(':checkbox')){
                    postData._type = 'checkbox';

                    //If its a checkbox, then now check if its been checked or not.
                    if(updatedSelector.is(':checked')){
                        postData[updatedSelectorName] = 'true';
                    }else{
                        postData[updatedSelectorName] = 'false';
                    }

                }else{
                    postData._type = 'text';
                    postData[updatedSelectorName] = updatedSelectorValue;
                }

                //Send the Request.
                $.ajax({
                    url:form.attr('action'),
                    type:form.attr('method'),
                    data: postData,
                    dataType:"json",
                    success:function(data){
                        if(data.type){
                            Notification(data.box,data.message)
                        }

                    }
                });
            }); //End of Basics Configuration Function.
        });
    </script>
@endsection
