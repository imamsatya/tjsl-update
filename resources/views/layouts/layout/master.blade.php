		
		<!--begin::Main-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Page-->
			<div class="page d-flex flex-row flex-column-fluid">

<!--layout-partial:layout/aside/_base.html-->
@include('layouts.layout.aside._base')

				<!--begin::Wrapper-->
				<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">

<!--layout-partial:layout/header/_base.html-->
@include('layouts.layout.header._base')

					<!--begin::Content-->
					<div class="content d-flex flex-column flex-column-fluid" id="kt_content">

<!--layout-partial:layout/_toolbar.html-->
@include('layouts.layout._toolbar')

						<!--begin::Post-->

<!--layout-partial:layout/_content.html-->
@include('layouts.layout._content')
						<!--end::Post-->
					</div>
					<!--end::Content-->

<!--layout-partial:layout/_footer.html-->
@include('layouts.layout._footer')

				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::Root-->
		<!--begin::Drawers-->

<!--layout-partial:layout/topbar/partials/_activity-drawer.html-->

<!--layout-partial:layout/explore/_main.html-->

		<!--end::Drawers-->
		<!--begin::Modals-->

<!--layout-partial:partials/modals/_invite-friends.html-->


<!--layout-partial:partials/modals/create-app/_main.html-->


<!--layout-partial:partials/modals/_upgrade-plan.html-->

		<!--end::Modals-->

<!--layout-partial:layout/_scrolltop.html-->
@include('layouts.layout._scrolltop')

		<!--end::Main-->
		