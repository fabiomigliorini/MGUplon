@include('layouts.includes.header_start')
<!--Morris Chart CSS -->
<link rel="stylesheet" href="{{ URL::asset('public/assets/plugins/morris/morris.css') }}">
@include('layouts.includes.header_end')


<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="content-page">
    <div class="content">
		<div class="row">
		    <div class="col-xs-12">
		        <div class="page-title-box">
		            <h4 class="page-title">{{ $bc->header }}</h4>
		            <ol class="breadcrumb p-0">
		            	@foreach($bc->breadcrumbs as $item)
		            		@if (empty($item->url))
			                <li class="active">
			                    {{ $item->label }}
			                </li>
		            		@else
			                <li>
			                    <a href="{{ $item->url }}"  >{{ $item->label }}</a>
			                </li>
		            		@endif
		            	@endforeach
		            </ol>
		            <div class="clearfix"></div>
		        </div>
		    </div>
		</div>
        <div class="container">

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
