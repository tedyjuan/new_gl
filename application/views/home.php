<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Dashboard | General Ladger</title>
	<link rel="shortcut icon" href="<?php echo base_url('public/assets/favicon.ico'); ?>">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&amp;display=swap" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo base_url('public/assets/css/vendor.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('public/assets/css/theme.minc619.css?v=1.0'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('public/assets/vendor/datatable-bootstrap5/datatables-bootstrap5.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('public/assets/vendor/select2/css/select2.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('public/assets/vendor/sweetalert/sweetalert.css'); ?>">
	<style>
		.select2-container .select2-selection--single {
			height: 3em !important;
		}

		.select2-container--default .select2-selection--single .select2-selection__rendered {
			line-height: 2.7em !important;
		}
	</style>
	<style type="text/css">
		.overlay {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100vh;
			background: rgba(153, 202, 239, 0.748);
			z-index: 999;
			opacity: 0.2;
			transition: all 0.5s;
			text-align: center;
		}

		.img-spinner {
			width: 105px;
			height: 105px;
			margin-top: 10%;
		}
	</style>
</head>

<body class="has-navbar-vertical-aside navbar-vertical-aside-show-xl footer-offset">
	<div id="preloader" class="spinner overlay" style="display: none;">
		<img class="img-spinner" src="<?php echo base_url('public/assets/svg/loading.svg'); ?>">
	</div>
	<script src="<?php echo base_url('public/assets/js/vendor.min.js'); ?>"></script>
	<script src="<?php echo base_url('public/assets/vendor/datatable-bootstrap5/datatables-jquery.min.js'); ?>"></script>
	<script src="<?php echo base_url('public/assets/vendor/datatable-bootstrap5/datatables-bootstrap5.min.js'); ?>"></script>
	<script src="<?php echo base_url('public/assets/vendor/flatpiker/flatpickr.js'); ?>"></script>
	<script src="<?php echo base_url('public/assets/vendor/select2/js/select2.js'); ?>"></script>

	<!-- ========== HEADER ========== -->
	<header id="header" class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-container navbar-bordered bg-white">
		<div class="navbar-nav-wrap">
			<!-- Logo -->
			<a class="navbar-brand" href="index.html" aria-label="Front">
				<img class="navbar-brand-logo" src="<?php echo base_url('public/assets/svg/logos/logo.svg'); ?>" alt="Logo">
				<img class="navbar-brand-logo-mini" src="<?php echo base_url('public/assets/svg/logos/logo-short.svg'); ?>" alt="Logo">
			</a>
			<!-- End Logo -->
			<div class="navbar-nav-wrap-content-start">
				<!-- Navbar Vertical Toggle -->
				<button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-aside-toggler">
					<i class="bi-arrow-bar-left navbar-toggler-short-align" data-bs-toggle="tooltip" data-bs-placement="right" title="Collapse"></i>
					<i class="bi-arrow-bar-right navbar-toggler-full-align" data-bs-toggle="tooltip" data-bs-placement="right" title="Expand"></i>
				</button>
			</div>
			<div class="navbar-nav-wrap-content-end">
				<!-- Navbar -->
				<ul class="navbar-nav">
					<li class="nav-item">
						<div class="dropdown">
							<a class="navbar-dropdown-account-wrapper" href="javascript:;" id="accountNavbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
								<div class="avatar avatar-sm avatar-circle">
									<img class="avatar-img" loading="lazy" src="<?php echo base_url('public/assets/img/160x160/img6.jpg'); ?>" alt="Image Description">
									<span class="avatar-status avatar-sm-status avatar-status-success"></span>
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-end navbar-dropdown-menu navbar-dropdown-menu-borderless navbar-dropdown-account" aria-labelledby="accountNavbarDropdown" style="width: 16rem;">
								<div class="dropdown-item-text">
									<div class="d-flex align-items-center">
										<div class="avatar avatar-sm avatar-circle">
											<img class="avatar-img" loading="lazy" src="<?php echo base_url('public/assets/img/160x160/img6.jpg'); ?>" alt="Image Description">
										</div>
										<div class="flex-grow-1 ms-3">
											<h5 class="mb-0"><?php echo $this->session->userdata('user_nama'); ?></h5>
											<p class="card-text text-body"><?php echo $this->session->userdata('user_nik'); ?></p>
										</div>
									</div>
								</div>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="javascript:;">Lokasi : <?php echo $this->session->userdata('user_lokasi'); ?></a>
								<a class="dropdown-item" href="javascript:;">Branch : <?php echo $this->session->userdata('user_branch'); ?></a>
								<a class="dropdown-item" href="javascript:;">Kode Jabatan : <?php echo $this->session->userdata('user_kode_jabatan'); ?></a>
								<a class="dropdown-item" href="javascript:;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
									Sign out
								</a>
								<!-- Hidden logout form -->
								<form id="logout-form" action="<?php echo site_url('auth/logout'); ?>" method="POST" style="display: none;">
								</form>
							</div>
						</div>
					</li>
				</ul>
				<!-- End Navbar -->
			</div>
		</div>
	</header>
	<!-- ========== END HEADER ========== -->

	<!-- ========== Asside ========== -->
	<aside class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered bg-white">
		<div class="navbar-vertical-container">
			<div class="navbar-vertical-footer-offset">
				<!-- Logo -->
				<a class="navbar-brand" href="index.html" aria-label="Front">
					<img class="navbar-brand-logo" src="<?php echo base_url('public/assets/svg/logos/logo.svg'); ?>" alt="Logo">
					<img class="navbar-brand-logo-mini" src="<?php echo base_url('public/assets/svg/logos/logo-short.svg'); ?>" alt="Logo">
				</a>
				<button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-aside-toggler">
					<i class="bi-arrow-bar-left navbar-toggler-short-align" data-bs-toggle="tooltip" data-bs-placement="right" title="Collapse"></i>
					<i class="bi-arrow-bar-right navbar-toggler-full-align" data-bs-toggle="tooltip" data-bs-placement="right" title="Expand"></i>
				</button>
				<div class="navbar-vertical-content">
					<div id="navbarVerticalMenu" class="nav nav-pills nav-vertical card-navbar-nav">
						<div class="nav-item">
							<a class="nav-link sub_reset active" id="nav_dashboard" href="<?php echo site_url('beranda'); ?>" data-placement="left">
								<i class="bi bi-speedometer2 nav-icon"></i>
								<span class="nav-link-title">Dashboards</span>
							</a>
						</div>
						<span class="dropdown-header mt-4">Pages</span>
						<small class="bi-three-dots nav-subtitle-replacer"></small>
						<div class="nav-item ">
							<a class="nav-link sub_reset " id="nav_role" href="javascript:;" onclick="tocontroller('role', 'nav_role')" data-placement="left">
								<i class="bi bi-shield-check nav-icon"></i>
								<span class="nav-link-title">Role</span>
							</a>
						</div>
						<div id="navbarVerticalMenuPagesMenu">
							<div class="nav-item">
								<a class="nav-link dropdown-toggle sub_reset" id="nav_master" href="#li_master"
									role="button" data-bs-toggle="collapse" data-bs-target="#li_master"
									aria-expanded="false" aria-controls="li_master">
									<i class="bi bi-stack nav-icon"></i>
									<span class="nav-link-title">Master</span>
								</a>
								<div id="li_master" class="nav-collapse collapse"
									data-bs-parent="#navbarVerticalMenuPagesMenu">
									<a class="nav-link sub_reset" id="sub_comany" href="javascript:;" onclick="tocontroller('C_company', 'sub_comany','li_master', 'nav_master')">Company</a>
									<!-- <a class="nav-link sub_reset" id="sub_comany" href="javascript:;">Company</a> -->
									<a class="nav-link sub_reset" id="sub_depo" href="javascript:;">Depo</a>
									<a class="nav-link sub_reset" id="sub_department" href="javascript:;">Department</a>
									<a class="nav-link sub_reset" id="sub_divisi" href="javascript:;">Divisi</a>
									<a class="nav-link sub_reset" id="sub_segment" href="javascript:;">Segment</a>
									<a class="nav-link sub_reset" id="sub_costcenter" href="javascript:;">Cost Center</a>
									<a class="nav-link sub_reset" id="sub_journalsource" href="javascript:;">Journal Source</a>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</aside>
	<!-- End Navbar Vertical -->

	<main id="content" role="main" class="main">
		<!-- Content -->
		<div class="content container-fluid">
			<div id="contentdata">
				<h1 class="page-header-title">Dashboard</h1>
			</div>
		</div>
		<!-- End Content -->
		<!-- Footer -->
		<div class="footer">
			<div class="row justify-content-between align-items-center">
				<div class="col">
					<p class="fs-6 mb-0">&copy; <span class="d-none d-sm-inline-block"><?= date('Y') ?> ICT-Hotline.</span></p>
				</div>
				<div class="col-auto">
					<div class="d-flex justify-content-end">
						<ul class="list-inline list-separator">
							<li class="list-inline-item">
								<a class="list-separator-link" href="#">FAQ</a>
							</li>
							<li class="list-inline-item">
								<button class="btn btn-ghost-secondary btn btn-icon btn-ghost-secondary rounded-circle" type="button">
									<i class="bi-command"></i>
								</button>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- End Footer -->
	</main>

	<script src="<?php echo base_url('public/assets/js/sweetalert.js'); ?>"></script>
	<script src="<?php echo base_url('public/assets/js/loadpages.js'); ?>"></script>
	<script src="<?php echo base_url('public/assets/vendor/parsleyjs/parsley.min.js'); ?>"></script>
	<script src="<?php echo base_url('public/assets/vendor/parsleyjs/id.js'); ?>"></script>
	<script src="<?php echo base_url('public/assets/vendor/jquery-mask/jquery.mask.min.js'); ?>"></script>

	<?php if ($this->session->flashdata('flash_success')): ?>
		<script>
			swet_sukses("Login Sukses")
		</script>
	<?php endif; ?>

	<script>
		// Di file .blade.php
		const BASE_URL = "<?= rtrim(base_url(), '/'); ?>";
		(function() {
			window.onload = function() {
				new HSSideNav('.js-navbar-vertical-aside').init()
			}
		})()
	</script>
</body>

</html>
