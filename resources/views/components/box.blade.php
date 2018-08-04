<div class="box box-default" data-widget="box-widget">
    <div class="box-header">
        <h3 class="box-title">@yield('box-title')</h3>
        <div class="box-tools">
            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
        @yield('box-content')
    </div>
</div>