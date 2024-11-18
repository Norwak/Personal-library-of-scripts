<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<!-- Meta tags -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="format-detection" content="telephone=no">

	<!-- WP Head -->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<header id="header">
		<!-- Section: Header -->
		<div class="header" id="header">
			<div class="container-3-steps mtb15">
				<div class="flex-csb lpt-col lpt-gap10b">
					<div class="noshrink header__logo">
						<?=get_custom_logo()?>
					</div>

					<div class="grow header__text plr20">
						<div class="contact flex-gl cols2 gap10b lpt-gap20b stb-cols1 stb-gap10b">
							<div class="contact__item">
								<div class="contact__phone">
									<i class="contact__icon fa-solid fa-phone"></i> <a href="tel:<?=PHONE1_RAW?>" class="focus"><?=PHONE1_FORMATTED?></a>
								</div>
							</div>
							<div class="contact__item">
								<div class="contact__phone">
									<i class="contact__icon fa-solid fa-phone"></i> <a href="tel:<?=PHONE2_RAW?>" class="focus"><?=PHONE2_FORMATTED?></a>
								</div>
							</div>
							<div class="contact__item">
								<div class="contact__email">
									E-mail: <a href="mailto:<?=EMAIL?>"><?=EMAIL?></a>
								</div>
							</div>
							<div class="contact__item">
								<div class="contact__worktime">
									<span class="highlight">Время работы:</span> <?=WORKTIME?>
								</div>
							</div>
						</div>
					</div>

					<div class="header__actions">
						<div class="header__action">
							<button class="btn btn--primary btn--100p spu-open-64">Заказать звонок</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>

	<div class="page-content" id="page-content">