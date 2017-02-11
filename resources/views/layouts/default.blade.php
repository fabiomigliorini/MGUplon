@include('layouts.includes.header_start')
<!--Morris Chart CSS -->
<link rel="stylesheet" href="{{ URL::asset('public/assets/plugins/morris/morris.css') }}">
@include('layouts.includes.header_end')

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="content-page">
    <div class="content">
        <div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="page-title-box">
                <h4 class="page-title">Dashboard</h4>
                <ol class="breadcrumb p-0">
                    <li>
                        <a href="#">Uplon</a>
                    </li>
                    <li>
                        <a href="#">Dashboard</a>
                    </li>
                    <li class="active">
                        Dashboard
                    </li>
                </ol>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <!-- end row -->
    @include('errors.flash')
    @yield('content')
</div>

        </div> <!-- container -->

    </div> <!-- content -->
<!-- End content-page -->


<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->


@include('layouts.includes.footer_start')

<!--Morris Chart-->
<script src="{{ URL::asset('public/assets/plugins/morris/morris.min.js') }}"></script>
<script src="{{ URL::asset('public/assets/plugins/raphael/raphael-min.js') }}"></script>


<!-- Page specific js -->
<script src="{{ URL::asset('public/assets/pages/jquery.dashboard.js') }}"></script>

@include('layouts.includes.footer_end')
