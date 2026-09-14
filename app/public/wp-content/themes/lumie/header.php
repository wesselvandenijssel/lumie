<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <main id="content">
 *
 * @package lumie
 */
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<?= get_field('head', 'scripts') ?? ''; ?>

	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php wp_title('|', true, 'right'); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="icon" type="image/png" href="<?= assets('favicon-96x96.png'); ?>" sizes="96x96" />
	<link rel="icon" type="image/svg+xml" href="<?= assets('favicon.svg'); ?>" />
	<link rel="shortcut icon" href="<?= assets('favicon.ico'); ?>" />
	<link rel="apple-touch-icon" sizes="180x180" href="<?= assets('apple-touch-icon.png'); ?>" />
	<meta name="apple-mobile-web-app-title" content="Vissers Zwembaden" />
	<link rel="manifest" href="<?= assets('site.webmanifest'); ?>" />
	<?php include('json-ld.php'); ?>
	<script type="application/ld+json">
		<?= json_encode($payload); ?>
	</script>
	<?php wp_head(); ?>
</head>

<?php
$contact_details = get_field('contact_details', 'options');
?>

<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">
	<?= get_field('body', 'scripts') ?? ''; ?>

	<div id="page" class="hfeed site">
		<?php do_action('before'); ?>

		<?php notification(); ?>

		<header class="header<?= has_block('acf/hero') ? ' header--has-hero' : ''; ?>">
			<?php
			$usps = get_field('header', 'options')['usps'] ?? [];

			if (!empty($usps)) :
			?>
				<div class="header__top">
					<div class="header__top-wrapper">
						<div class="swiper">
							<div class="header__usps swiper-wrapper">
								<?php foreach ($usps as $usp) : ?>
									<?php if (empty($usp['usp'])) continue; ?>

									<?php if (!empty($usp['link']['url'])) : ?>
										<a href="<?= esc_url($usp['link']['url']); ?>" title="<?= esc_attr($usp['link']['title'] ?? ''); ?>" target="<?= esc_attr($usp['link']['target'] ? $usp['link']['target'] : '_self'); ?>" class="header__usp swiper-slide">
											<?= wp_kses_post($usp['usp']); ?>
										</a>
									<?php else : ?>
										<div class="header__usp swiper-slide">
											<?= wp_kses_post($usp['usp']); ?>
										</div>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						</div>

						<div class="header__top-right">
							<?php wp_nav_menu([
								'theme_location' => 'top',
								'walker' => new Walker_Primary_Menu,
								'fallback_cb' => false,
								'container_class' => 'header__top-menu',
								'depth' => 1,
							]); ?>

							<?php if (!empty($contact_details['phone_link']['url']) && !empty($contact_details['phone'])) : ?>
								<a href="<?= esc_url($contact_details['phone_link']['url']); ?>" title="<?= esc_attr($contact_details['phone_link']['title'] ?? ''); ?>" class="header__icon header__icon--phone" aria-label="<?= esc_attr($contact_details['phone_link']['title'] ?? __('Bel ons', 'lumie')); ?>">
									<span class="mobile-none"><?= esc_html($contact_details['phone']); ?></span>
								</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<div class="header__main">
				<div class="header__main-wrapper">
					<?= get_logo(['class' => 'header__logo']); ?>

					<nav id="site-navigation" class="main-navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
						<div class="menu-toggle" role="button" tabindex="0" aria-label="<?= esc_attr__('Open menu', 'lumie'); ?>" aria-expanded="false">
							<div class="menu-toggle__lines">
								<div class="menu-toggle__burger">
									<span class="menu-toggle__burger-line menu-toggle__burger-line--first"></span>
									<span class="menu-toggle__burger-line menu-toggle__burger-line--second"></span>
									<span class="menu-toggle__burger-line menu-toggle__burger-line--last"></span>
								</div>
								<div class="menu-toggle__close">
									<span class="menu-toggle__close-line menu-toggle__close-line--first"></span>
									<span class="menu-toggle__close-line menu-toggle__close-line--last"></span>
								</div>
							</div>
							<span class="menu-toggle__text"><?= esc_html__('Menu', 'lumie'); ?></span>
						</div>
						<a class="skip-link screen-reader-text" href="#content"><?= esc_html__('Skip to content', 'lumie'); ?></a>

						<div class="main-navigation__content">
							<?php wp_nav_menu(
								[
									'theme_location' => 'primary',
									'walker' => new Walker_Primary_Menu,
									'fallback_cb' => false,
									'container_class' => 'main-navigation__menu main-navigation__menu--desktop',
								]
							);
							wp_nav_menu(
								[
									'theme_location' => 'primary_mobile',
									'walker' => new Walker_Primary_Menu,
									'fallback_cb' => false,
									'container_class' => 'main-navigation__menu main-navigation__menu--mobile',
								]
							); ?>

							<?php if (!empty(get_field('header', 'options')['buttons'])) : ?>
								<div class="header__buttons">
									<?php $content = new FlexContent();

									$content->setButtons(get_field('header', 'options')['buttons']);

									echo $content->getContent(); ?>
								</div>
							<?php endif; ?>
						</div>
					</nav>
				</div>
			</div>

			<div class="header__background"></div>
		</header>

		<main id="content" class="site-content">
