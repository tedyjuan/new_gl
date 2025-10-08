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
											<h5 class="mb-0"><?php echo $this->session->userdata('sess_name'); ?></h5>
											<p class="card-text text-body"><?php echo $this->session->userdata('sess_username'); ?></p>
										</div>
									</div>
								</div>
								<div class="dropdown-divider"></div>

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
							<a class="nav-link sub_reset active" id="nav_dashboard" href="<?php echo site_url('Dashboard'); ?>" data-placement="left">
								<i class="bi bi-speedometer2 nav-icon"></i>
								<span class="nav-link-title">Dashboards</span>
							</a>
						</div>
						<?php
						$menus = $this->session->userdata('sess_menus');

						if ($menus) :

							$parents = array_filter($menus, function ($m) {
								return empty($m['parent_id']);
							});
							$children = array_filter($menus, function ($m) {
								return !empty($m['parent_id']);
							});
						?>

							<?php foreach ($parents as $p) : ?>
								<?php
								$subMenus = array_filter($children, function ($c) use ($p) {
									return $c['parent_id'] == $p['id'];
								});


								$slugParts = array_map('trim', explode(',', str_replace("'", '', $p['slug'])));
								$controller = $slugParts[0] ?? '';
								$active     = $slugParts[1] ?? '';
								$dropdown   = $slugParts[2] ?? '';
								$head_nav   = $slugParts[3] ?? '';

								?>

								<?php if (count($subMenus) > 0) : ?>
									<div class="nav-item">
										<a class="nav-link dropdown-toggle sub_reset" id="<?php echo $head_nav; ?>" href="#menu_<?php echo $p['id']; ?>" data-bs-toggle="collapse" aria-expanded="false">
											<i class="<?php echo $p['icon']; ?> nav-icon"></i>
											<span class="nav-link-title"><?php echo $p['name']; ?></span>
										</a>

										<div id="menu_<?php echo $p['id']; ?>" class="nav-collapse collapse">
											<?php foreach ($subMenus as $s) : ?>
												<?php

												$slugPartsChild = array_map('trim', explode(',', str_replace("'", '', $s['slug'])));
												$controllerC = $slugPartsChild[0] ?? '';
												$activeC     = $slugPartsChild[1] ?? '';
												$dropdownC   = $slugPartsChild[2] ?? '';
												$head_navC   = $slugPartsChild[3] ?? '';
												?>
												<a class="nav-link sub_reset" id="<?php echo $activeC; ?>" href="javascript:;" onclick="tocontroller('<?php echo $controllerC; ?>', '<?php echo $activeC; ?>', '<?php echo $dropdownC; ?>', '<?php echo $head_navC; ?>')">
													<?php echo $s['name']; ?>
												</a>
											<?php endforeach; ?>
										</div>
									</div>

								<?php else : ?>
									<div class="nav-item">
										<a class="nav-link sub_reset" id="<?php echo $active; ?>" href="javascript:;" onclick="tocontroller('<?php echo $controller; ?>', '<?php echo $active; ?>', '<?php echo $dropdown; ?>', '<?php echo $head_nav; ?>')">
											<i class="<?php echo $p['icon']; ?> nav-icon"></i>
											<span class="nav-link-title"><?php echo $p['name']; ?></span>
										</a>
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>
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
					<strong>ASIATRI</strong> &copy; <?= date('Y'); ?>
				</div>
				<div class="col-auto">
					<div class="d-flex justify-content-end">
						<!-- List Separator -->
						<ul class="list-inline list-separator">
							<li class="list-inline-item">
								<a class="list-separator-link" href="#">General Ledger System</a>
							</li>

							<li class="list-inline-item">
								<a class="list-separator-link" href="#">Version 1.0</a>
							</li>

							<li class="list-inline-item">
								<a class="list-separator-link" href="#">Lingga Project</a>
							</li>


						</ul>
						<!-- End List Separator -->
					</div>
				</div>
				<!-- End Col -->
			</div>
		</div>
		<!-- End Footer -->
	</main>

	<script src="<?php echo base_url('public/assets/js/sweetalert.js'); ?>"></script>
	<script src="<?php echo base_url('public/assets/js/loadpages.js'); ?>"></script>
	<script src="<?php echo base_url('public/assets/vendor/parsleyjs/parsley.min.js'); ?>"></script>
	<script src="<?php echo base_url('public/assets/vendor/parsleyjs/id.js'); ?>"></script>
	<script src="<?php echo base_url('public/assets/vendor/jquery-mask/jquery.mask.min.js'); ?>"></script>



	<script>
		<?php
		$flashTypes = ['success', 'info', 'error', 'warning'];
		foreach ($flashTypes as $type) {
			if ($this->session->flashdata($type)) {
				$message = $this->session->flashdata($type);
				echo "Swal.fire({ icon: '$type', title: '" . addslashes($message) . "' });";
			}
		}
		?>

		const BASE_URL = "<?= rtrim(base_url(), '/'); ?>";
		(function() {
			window.onload = function() {
				new HSSideNav('.js-navbar-vertical-aside').init()
			}
		})()
	</script>
</body>

</html>
