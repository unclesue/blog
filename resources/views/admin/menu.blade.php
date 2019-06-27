@extends('admin.index')

@section('content')
    <!-- Content Header (Page header) -->
    @include('admin.partials.breadcrumb')

    <section class="content">
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
                                @each('admin.partials.tree', $tree, 'node')
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
                    <form class="form-horizontal" action="{{ route('menu.save') }}" method="POST">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label for="parent_id" class="col-sm-2 control-label">Parent</label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="parent_id">
                                    <select class="form-control" id="parent_id">
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
                            <div class="form-group @error('title') has-error @enderror">
                                <label for="title" class="col-sm-2 asterisk control-label">Title</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" placeholder="Input Title">
                                    </div>
                                    @error('title')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group @error('icon') has-error @enderror">
                                <label for="icon" class="col-sm-2 asterisk control-label">Icon</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon"></span>
                                        <input type="text" id="icon" name="icon" value="fa-bars" class="form-control" placeholder="Selcet Icon">
                                    </div>
                                    @error('icon')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="uri" class="col-sm-2 control-label">URI</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        <input type="text" id="uri" name="uri" class="form-control" placeholder="Input URI">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="roles" class="col-sm-2 control-label">Roles</label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="roles">
                                    <select class="form-control" id="roles" multiple="multiple" data-placeholder="Roles">
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
                            <div class="form-group">
                                <label for="permission" class="col-sm-2  control-label">Permission</label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="permission"/>
                                    <select class="form-control" id="permission" name="permission" data-value="" >
                                        <option value=""></option>
                                        <option value="*" >All permission</option>
                                        <option value="dashboard" >Dashboard</option>
                                        <option value="auth.login" >Login</option>
                                        <option value="auth.setting" >User setting</option>
                                        <option value="auth.management" >Auth management</option>
                                    </select>
                                </div>
                            </div>
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
    </section><!-- /.content -->
@stop
