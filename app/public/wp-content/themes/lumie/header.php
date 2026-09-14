<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <main id="content">
 *
 * @package mbeffect
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

		<header class="header">
			<div class="header__top">
				<div class="header__top-wrapper">
					<?php
					$usps = get_field('header', 'options')['usps'] ?? [];

					if (!empty($usps)) :
					?>
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
					<?php endif; ?>

					<div class="header__top-right">
						<?php wp_nav_menu([
							'theme_location' => 'top',
							'walker' => new Walker_Primary_Menu,
							'fallback_cb' => false,
							'container_class' => 'header__top-menu',
							'depth' => 1,
						]); ?>

						<?php if (!empty($contact_details['phone_link']['url']) && !empty($contact_details['phone'])) : ?>
							<a href="<?= esc_url($contact_details['phone_link']['url']); ?>" title="<?= esc_attr($contact_details['phone_link']['title'] ?? ''); ?>" class="header__icon header__icon--phone" aria-label="<?= esc_attr($contact_details['phone_link']['title'] ?? __('Bel ons', 'mbeffect')); ?>">
								<span class="mobile-none"><?= esc_html($contact_details['phone']); ?></span>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div class="header__main">
				<div class="header__main-wrapper">
					<?= get_logo(['class' => 'header__logo']); ?>

					<nav id="site-navigation" class="main-navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
						<div class="menu-toggle" role="button" tabindex="0" aria-label="<?= esc_attr__('Open menu', 'mbeffect'); ?>" aria-expanded="false">
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
							<span class="menu-toggle__text"><?= esc_html__('Menu', 'mbeffect'); ?></span>
						</div>
						<a class="skip-link screen-reader-text" href="#content"><?= esc_html__('Skip to content', 'mbeffect'); ?></a>

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

							<div class="header__buttons">
								<?php
								$header_options = get_field('header', 'options') ?: [];
								if (!empty($header_options['buttons_group'])) :
									$content = new FlexContent();

									$content->setButtons($header_options['buttons_group']);

									echo $content->getContent();
								endif; ?>
							</div>
						</div>
					</nav>
				</div>
			</div>

			<div class="header__background"></div>
		</header>

		<main id="content" class="site-content">
