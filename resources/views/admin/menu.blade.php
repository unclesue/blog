@extends('layouts.admin')

@section('content')
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Quick Example</h3>
                </div><!-- /.box-header -->

                <div class="box-body table-responsive no-padding">
                    <div class="dd" id="{{ $id }}">
                        <ol class="dd-list">
                            @each('admin.tree', $tree, 'node')
                        </ol>
                    </div>
                </div><!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!--/.col (left) -->

        <!-- right column -->
        <div class="col-md-6">
            <!-- Horizontal Form -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">New</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="parent_id" class="col-sm-2 control-label">Parent</label>
                            <div class="col-sm-10">
                                <input type="hidden" name="parent_id">
                                <select class="form-control parent_id">
                                    <option selected="selected">Root</option>
                                    <option>Alaska</option>
                                    <option disabled="disabled">California (disabled)</option>
                                    <option>Delaware</option>
                                    <option>Tennessee</option>
                                    <option>Texas</option>
                                    <option>Washington</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 asterisk control-label">Title</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fas fa-pencil-alt"></i></span>
                                    <input type="text" id="title" name="title" class="form-control" placeholder="Input Title">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="icon" class="col-sm-2 asterisk control-label">Icon</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon"></span>
                                    <input type="text" id="icon" name="icon" class="form-control icon" placeholder="Input Title" value="fas fa-bars">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="uri" class="col-sm-2 control-label">URI</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fas fa-pencil-alt"></i></span>
                                    <input type="text" id="uri" name="uri" class="form-control" placeholder="Input URI">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="roles" class="col-sm-2 control-label">Roles</label>
                            <div class="col-sm-10">
                                <input type="hidden" name="roles">
                                <select class="form-control roles" multiple="multiple" data-placeholder="Roles">
                                    <option>Alabama</option>
                                    <option>Alaska</option>
                                    <option>California</option>
                                    <option>Delaware</option>
                                    <option>Tennessee</option>
                                    <option>Texas</option>
                                    <option>Washington</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group  ">
                            <label for="permission" class="col-sm-2  control-label">Permission</label>
                            <div class="col-sm-10">
                                <input type="hidden" name="permission"/>
                                <select class="form-control permission" name="permission" data-value="" >
                                    <option value=""></option>
                                    <option value="*" >All permission</option>
                                    <option value="dashboard" >Dashboard</option>
                                    <option value="auth.login" >Login</option>
                                    <option value="auth.setting" >User setting</option>
                                    <option value="auth.management" >Auth management</option>
                                </select>
                            </div>
                        </div>
                        @csrf
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="reset" class="btn btn-warning">Reset</button>
                        <button type="submit" class="btn btn-info pull-right">Submit</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!--/.col (right) -->
    </div>
    <!-- /.row -->
@stop

@section('header')
    <link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css') }}">
@stop
@section('footer')
    <script src="{{ asset('plugins/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.min.js') }}"></script>
    <script>
        $(function () {
            $(".parent_id").select2({"allowClear": true, "placeholder": {"id": "", "text": "Parent"}});
            $('.icon').iconpicker({placement: 'bottomLeft'});
            $(".roles").select2({"allowClear": true, "placeholder": {"id": "", "text": "Roles"}});
            $(".permission").select2({"allowClear": true, "placeholder": {"id": "", "text": "Permission"}});
        })
    </script>
@stop
