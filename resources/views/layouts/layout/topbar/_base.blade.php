									
									<!--begin::Toolbar wrapper-->
									<div class="topbar d-flex align-items-stretch flex-shrink-0">

										<!--begin::Helpdesk-->
										<div class="d-flex align-items-stretch" id="kt_header_user_menu_toggle">
											<!--begin::Menu wrapper-->
											<div class="topbar-item cursor-pointer symbol px-3 px-lg-5 me-n3 me-lg-n5 symbol-30px symbol-md-35px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end" data-kt-menu-flip="bottom" title="Helpdesk Portal TJSL">
												<i class="bi bi-headset" style="font-size:20px;color: aliceblue"></i>
											</div>
<!--layout-partial:layout/topbar/partials/_user-menu.html-->
@include('layouts.layout.topbar.partials._helpdesk')
											<!--end::Menu wrapper-->
										</div>
										<!--end::Helpdesk-->

										<!--begin::User-->
										<div class="d-flex align-items-stretch" id="kt_header_user_menu_toggle">
											<!--begin::Menu wrapper-->
											<div class="topbar-item cursor-pointer symbol px-15 px-lg-15 me-n3 me-lg-n5 symbol-30px symbol-md-35px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end" data-kt-menu-flip="bottom">
												<img src="{{ asset('/assets/media/avatars/blank2.png') }}" alt="metronic" style="border-radius: 50%;margin-right:20px;"/>
												<a style="color: aliceblue;">{{ auth()->user()->name? ucWords(auth()->user()->name) : 'Anonymous' }}<br>
												<small>{{ auth()->user()->getRoleNames()[0] }}</small></a>
											</div>
<!--layout-partial:layout/topbar/partials/_user-menu.html-->
@include('layouts.layout.topbar.partials._user-menu')

											<!--end::Menu wrapper-->
										</div>
										<!--end::User -->
										<!--begin::Heaeder menu toggle-->
										{{-- <div class="d-flex align-items-stretch d-lg-none px-3 me-n3" title="Show header menu">
											<div class="topbar-item" id="kt_header_menu_mobile_toggle">
												<i class="bi bi-text-left fs-1"></i>
											</div>
										</div> --}}
										<!--end::Heaeder menu toggle-->
									</div>
									<!--end::Toolbar wrapper-->
									