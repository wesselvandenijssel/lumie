<?php

defined('ABSPATH') || exit('Forbidden');

add_action('acf/init', function () {
	acf_add_options_page([
		'page_title' => esc_html__('Lumie instellingen', 'lumie'),
		'menu_title' => esc_html__('Lumie instellingen', 'lumie'),
		'menu_slug' => 'lumie-settings',
		// The sparkle rather than the wordmark: WordPress draws this at 20x20,
		// where the 2.7:1 wordmark collapses into an unreadable smudge. White,
		// not currentColor, because WordPress renders it inside an <img>.
		'icon_url' => 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="#fff" d="M12 0c0 6.627 5.373 12 12 12-6.627 0-12 5.373-12 12 0-6.627-5.373-12-12-12C6.627 12 12 6.627 12 0Z"/></svg>'),
		'redirect' => true
	]);

	// Basic details
	acf_add_options_page([
		'page_title' => esc_html__('Basisgegevens', 'lumie'),
		'menu_title' => esc_html__('Basisgegevens', 'lumie'),
		'menu_slug' => 'basic-details',
		'capability' => 'edit_posts',
		'parent_slug' => 'lumie-settings',
	]);

	acf_add_local_field_group([
		'key' => 'theme_settings_basic_details',
		'title' => esc_html__('Basisgegevens', 'lumie'),
		'fields' => [
			[
				'key' => 'theme_settings_logo',
				'name' => 'logo',
				'label' => esc_html__('Logo', 'lumie'),
				'type' => 'image',
				'mime_types' => 'svg, png, jpg, jpeg, webp',
				'return_format' => 'id',
				'wrapper' => [
					'width' => '50',
				],
			],
			[
				'key' => 'theme_settings_logo_white',
				'name' => 'logo_white',
				'label' => esc_html__('Logo wit', 'lumie'),
				'type' => 'image',
				'mime_types' => 'svg, png, jpg, jpeg, webp',
				'return_format' => 'id',
				'wrapper' => [
					'width' => '50',
				],
			],
			[
				'key' => 'theme_settings_contact_details',
				'name' => 'contact_details',
				'label' => esc_html__('Contactgegevens', 'lumie'),
				'type' => 'group',
				'sub_fields' => [
					[
						'key' => 'theme_settings_contact_details_phone',
						'name' => 'phone',
						'type' => 'text',
						'label' => esc_html__('Telefoonnummer', 'lumie'),
						'wrapper' => [
							'width' => '25',
						],
					],

					[
						'key' => 'theme_settings_contact_details_phone_link',
						'name' => 'phone_link',
						'type' => 'link',
						'label' => esc_html__('Telefoonnummer (link)', 'lumie'),
						'wrapper' => [
							'width' => '25',
						],
					],

					[
						'key' => 'theme_settings_contact_details_email',
						'name' => 'email',
						'type' => 'email',
						'label' => esc_html__('E-mailadres', 'lumie'),
						'wrapper' => [
							'width' => '25',
						],
					],

					[
						'key' => 'theme_settings_contact_details_email_link',
						'name' => 'email_link',
						'type' => 'link',
						'label' => esc_html__('E-mailadres (link)', 'lumie'),
						'wrapper' => [
							'width' => '25',
						],
					],

					[
						'key' => 'theme_settings_contact_details_address_data',
						'name' => 'address_data',
						'label' => esc_html__('Adresgegevens', 'lumie'),
						'type' => 'group',
						'sub_fields' => [

							[
								'key' => 'theme_settings_contact_details_address_data_street',
								'name' => 'street',
								'type' => 'text',
								'label' => esc_html__('Straatnaam + huisnummer', 'lumie'),
								'wrapper' => [
									'width' => '20',
								],
							],

							[
								'key' => 'theme_settings_contact_details_address_data_city',
								'name' => 'city',
								'type' => 'text',
								'label' => esc_html__('Plaatsnaam', 'lumie'),
								'wrapper' => [
									'width' => '20',
								],
							],

							[
								'key' => 'theme_settings_contact_details_address_data_county',
								'name' => 'county',
								'type' => 'text',
								'label' => esc_html__('Provincie', 'lumie'),
								'wrapper' => [
									'width' => '20',
								],
							],

							[
								'key' => 'theme_settings_contact_details_address_data_zip',
								'name' => 'zip',
								'type' => 'text',
								'label' => esc_html__('Postcode', 'lumie'),
								'wrapper' => [
									'width' => '20',
								],
							],

							[
								'key' => 'theme_settings_contact_details_address_data_link',
								'name' => 'link',
								'type' => 'link',
								'label' => esc_html__('Link', 'lumie'),
								'wrapper' => [
									'width' => '20',
								],
							],
						],
					],

				],
			],
			[
				'key' => 'theme_settings_awards',
				'name' => 'awards',
				'label' => esc_html__('Awards', 'lumie'),
				'type' => 'repeater',
				'sub_fields' => [
					[
						'key' => 'theme_settings_awards_award',
						'name' => 'award',
						'type' => 'text',
						'label' => esc_html__('Award', 'lumie'),
					],
				],
				'wrapper' => [
					'width' => '50',
				],
			],
			[
				'key' => 'theme_settings_founder',
				'name' => 'founder',
				'label' => esc_html__('Oprichter', 'lumie'),
				'type' => 'user',
				'wrapper' => [
					'width' => '25',
				],
			],
			[
				'key' => 'theme_settings_founding_year',
				'name' => 'founding_year',
				'label' => esc_html__('Oprichtingsjaar', 'lumie'),
				'type' => 'number',
				'wrapper' => [
					'width' => '25',
				],
			],
			[
				'key' => 'theme_settings_social_media',
				'name' => 'social_media',
				'label' => esc_html__('Social media', 'lumie'),
				'type' => 'group',
				'sub_fields' => [
					[
						'key' => 'theme_settings_social_media_facebook',
						'name' => 'facebook',
						'type' => 'link',
						'label' => esc_html__('Facebook', 'lumie'),
						'wrapper' => [
							'width' => '25',
						],
					],

					[
						'key' => 'theme_settings_social_media_instagram',
						'name' => 'instagram',
						'type' => 'link',
						'label' => esc_html__('Instagram', 'lumie'),
						'wrapper' => [
							'width' => '25',
						],
					],

					[
						'key' => 'theme_settings_social_media_linkedin',
						'name' => 'linkedin',
						'type' => 'link',
						'label' => esc_html__('LinkedIn', 'lumie'),
						'wrapper' => [
							'width' => '25',
						],
					],

					[
						'key' => 'theme_settings_social_media_twitter',
						'name' => 'twitter',
						'type' => 'link',
						'label' => esc_html__('Twitter', 'lumie'),
						'wrapper' => [
							'width' => '25',
						],
					],

					[
						'key' => 'theme_settings_social_media_tiktok',
						'name' => 'tiktok',
						'type' => 'link',
						'label' => esc_html__('TikTok', 'lumie'),
						'wrapper' => [
							'width' => '25',
						],
					],
				]
			],
		],

		'location' => [
			[
				[
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'basic-details',
				],
			],
		],
	]);


	// Footer
	acf_add_options_page([
		'page_title' => esc_html__('Footer', 'lumie'),
		'menu_title' => esc_html__('Footer', 'lumie'),
		'menu_slug' => 'footer',
		'post_id' => 'footer',
		'parent_slug' => 'lumie-settings',
	]);

	acf_add_local_field_group([
		'key' => 'theme_settings_footer',
		'title' => esc_html__('Footer', 'lumie'),
		'fields' => [
			[
				'key' => 'theme_settings_footer_group',
				'label' => esc_html__('Footer', 'lumie'),
				'name' => 'footer',
				'type' => 'group',
				'layout' => 'block',
				'sub_fields' => [
					[
						'key' => 'theme_settings_footer_group_column',
						'type' => 'group',
						'label' => esc_html__('Footer groepen', 'lumie'),
						'name' => 'footer_column',
						'sub_fields' => [
							[
								'key' => 'theme_settings_footer_group_column_column_one',
								'label' => esc_html__('Kolom 1', 'lumie'),
								'name' => 'column_1',
								'type' => 'clone',
								'clone' => ['clone_footer_column'],
								'prefix_name' => true,
							],
							[
								'key' => 'theme_settings_footer_group_column_column_two',
								'label' => esc_html__('Kolom 2', 'lumie'),
								'name' => 'column_2',
								'type' => 'clone',
								'clone' => ['clone_footer_column'],
								'prefix_name' => true,
							],
							[
								'key' => 'theme_settings_footer_group_column_column_three',
								'label' => esc_html__('Kolom 3', 'lumie'),
								'name' => 'column_3',
								'type' => 'clone',
								'clone' => ['clone_footer_column'],
								'prefix_name' => true,
							],
							[
								'key' => 'theme_settings_footer_group_column_column_four',
								'label' => esc_html__('Kolom 4', 'lumie'),
								'name' => 'column_4',
								'type' => 'clone',
								'clone' => ['clone_footer_column'],
								'prefix_name' => true,
							],
						],
					],
					[
						'key' => 'theme_settings_footer_group_sub_footer',
						'label' => esc_html__('Subfooter', 'lumie'),
						'name' => 'sub_footer',
						'type' => 'wysiwyg',
						'delay' => true,
					],
				],
			],
		],
		'location' => [
			[
				[
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'footer',
				],
			],
		],
	]);


	// Header
	acf_add_options_page([
		'page_title' 	=> esc_html__('Header', 'lumie'),
		'menu_title'	=> esc_html__('Header', 'lumie'),
		'menu_slug' 	=> 'header',
		'capability'	=> 'edit_posts',
		'parent_slug'	=> 'lumie-settings',
	]);

	acf_add_local_field_group([
		'key' => 'theme_settings_header',
		'title' => esc_html__('Header', 'lumie'),
		'fields' => [
			[
				'key' => 'theme_settings_header_group',
				'name' => 'header',
				'label' => esc_html__('Header', 'lumie'),
				'type' => 'group',
				'sub_fields' => [
					[
						'key' => 'theme_settings_header_group_usps',
						'label' => esc_html__('USP\'s', 'lumie'),
						'name' => 'usps',
						'type' => 'repeater',
						'button_label' => esc_html__('Nieuwe USP', 'lumie'),
						'sub_fields' => [
							[
								'key' => 'theme_settings_header_group_usps_usp',
								'label' => esc_html__('USP', 'lumie'),
								'name' => 'usp',
								'type' => 'text',
							],
							[
								'key' => 'theme_settings_header_group_usps_link',
								'label' => esc_html__('Link', 'lumie'),
								'name' => 'link',
								'type' => 'link',
							],
						],
					],
					[
						'key' => 'theme_settings_header_group_buttons',
						'label' => esc_html__('Button(s)', 'lumie'),
						'name' => 'buttons_clone',
						'type' => 'clone',
						'clone' => [
							'clone_buttons_buttons_group',
						],
					],
				]
			],
		],

		'location' => [
			[
				[
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'header',
				],
			],
		],
	]);


	// Notification
	acf_add_options_page([
		'page_title' => esc_html__('Melding', 'lumie'),
		'menu_title' => esc_html__('Melding', 'lumie'),
		'menu_slug' => 'notification',
		'capability' => 'edit_posts',
		'parent_slug' => 'lumie-settings',
	]);

	acf_add_local_field_group([
		'key' => 'theme_settings_notification',
		'title' => esc_html__('Melding', 'lumie'),
		'fields' => [
			[
				'key' => 'theme_settings_notification_group',
				'label' => esc_html__('Melding', 'lumie'),
				'name' => 'notification',
				'type' => 'group',
				'sub_fields' => [
					[
						'key' => 'theme_settings_notification_group_text',
						'label' => esc_html__('Tekst', 'lumie'),
						'name' => 'text',
						'type' => 'text',
						'wrapper' => [
							'width' => 50,
						],
					],
					[
						'key' => 'theme_settings_notification_group_show_from',
						'label' => esc_html__('Weergeven van', 'lumie'),
						'name' => 'show_from',
						'type' => 'date_time_picker',
						'wrapper' => [
							'width' => '25',
						],
						'display_format' => 'd-m-Y H:i:s',
						'return_format' => 'd-m-Y H:i:s',
					],
					[
						'key' => 'theme_settings_notification_group_show_until',
						'label' => esc_html__('Weergeven tot', 'lumie'),
						'name' => 'show_until',
						'type' => 'date_time_picker',
						'wrapper' => [
							'width' => '25',
						],
						'display_format' => 'd-m-Y H:i:s',
						'return_format' => 'd-m-Y H:i:s',
					],
				],
			],
		],
		'location' => [
			[
				[
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'notification',
				],
			],
		],
	]);


	// Not found
	acf_add_options_page([
		'page_title' => esc_html__('Niet gevonden pagina', 'lumie'),
		'menu_title' => esc_html__('Niet gevonden pagina', 'lumie'),
		'menu_slug' => 'not_found',
		'capability' => 'edit_posts',
		'parent_slug' => 'lumie-settings',
	]);

	acf_add_local_field_group([
		'key' => 'theme_settings_not_found',
		'title' => esc_html__('Niet gevonden pagina', 'lumie'),
		'fields' => [
			[
				'key' => 'theme_settings_not_found_group',
				'label' => esc_html__('Niet gevonden pagina', 'lumie'),
				'name' => 'not_found',
				'type' => 'group',
				'layout' => 'block',
				'sub_fields' => [
					get_flex_content('theme_settings_not_found_group')
				],
			],
		],
		'location' => [
			[
				[
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'not_found',
				],
			],
		],
	]);


	// Scripts
	acf_add_options_page([
		'page_title' => esc_html__('Scripts', 'lumie'),
		'menu_title' => esc_html__('Scripts', 'lumie'),
		'menu_slug' => 'scripts',
		'post_id' => 'scripts',
		'parent_slug' => 'lumie-settings',
	]);

	acf_add_local_field_group([
		'key' => 'theme_settings_scripts',
		'title' => esc_html__('Scripts', 'lumie'),
		'fields' => [
			[
				'key' => 'theme_settings_scripts_head',
				'label' => esc_html__('&lt;head&gt;-scripts', 'lumie'),
				'instructions' => wp_kses_post(__('Dit veld wordt bovenaan de &lt;head&gt; geplaatst.', 'lumie')),
				'name' => 'head',
				'type' => 'textarea',
				'new_lines' => '',
				'wrapper' => [
					'width' => '50',
				],
			],
			[
				'key' => 'theme_settings_scripts_body',
				'label' => esc_html__('&lt;body&gt;-scripts', 'lumie'),
				'instructions' => wp_kses_post(__('Dit veld wordt bovenaan de &lt;body&gt; geplaatst.', 'lumie')),
				'name' => 'body',
				'type' => 'textarea',
				'new_lines' => '',
				'wrapper' => [
					'width' => '50',
				],
			],
		],
		'location' => [
			[
				[
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'scripts',
				],
			],
		],
	]);

	// Review settings
	acf_add_options_page([
		'page_title' => esc_html__('Reviews', 'lumie'),
		'menu_title' => esc_html__('Reviews', 'lumie'),
		'menu_slug' => 'review-settings',
		'capability' => 'edit_posts',
		'parent_slug' => 'lumie-settings',
	]);

	acf_add_local_field_group([
		'key' => 'theme_settings_review_settings',
		'title' => esc_html__('Review instellingen', 'lumie'),
		'fields' => [
			[
				'key' => 'theme_settings_review_settings_group',
				'label' => esc_html__('Instellingen', 'lumie'),
				'name' => 'review_settings',
				'type' => 'group',
				'sub_fields' => [
					[
						'key' => 'theme_settings_review_settings_group_enabled',
						'type' => 'true_false',
						'label' => esc_html__('Automatische reviews aanzetten', 'lumie'),
						'name' => 'enabled',
						'ui' => true,
						'default_value' => true,
					],
					[
						'key' => 'theme_settings_review_settings_group_cron_status',
						'type' => 'message',
						'label' => esc_html__('Cron status', 'lumie'),
						'message' => wp_next_scheduled('fetch_google_reviews_event')
							? esc_html__('Cron is actief. Volgende uitvoering: ', 'lumie') . date('d-m-Y H:i:s', wp_next_scheduled('fetch_google_reviews_event'))
							: esc_html__('Cron is niet actief', 'lumie'),
						'esc_html' => false,
					],
					[
						'key' => 'theme_settings_review_settings_group_cron_button',
						'type' => 'button_group',
						'label' => esc_html__('Cron beheer', 'lumie'),
						'name' => 'cron_button',
						'choices' => [
							'schedule' => esc_html__('Activeer cron', 'lumie'),
							'unschedule' => esc_html__('Deactiveer cron', 'lumie'),
						],
						'allow_null' => true,
						'return_format' => 'value',
					],
					[
						'key' => 'theme_settings_review_settings_group_client_id',
						'type' => 'text',
						'label' => esc_html__('Client ID', 'lumie'),
						'name' => 'client_id',
						'instructions' => wp_kses_post(__('Google OAuth Client ID. Te vinden in Google Cloud Console > APIs & Services > Credentials.', 'lumie')),
					],
					[
						'key' => 'theme_settings_review_settings_group_client_secret',
						'type' => 'text',
						'label' => esc_html__('Client Secret', 'lumie'),
						'name' => 'client_secret',
						'instructions' => wp_kses_post(__('Google OAuth Client Secret. Te vinden in Google Cloud Console > APIs & Services > Credentials.', 'lumie')),
					],
					[
						'key' => 'theme_settings_review_settings_group_refresh_token',
						'type' => 'text',
						'label' => esc_html__('Refresh Token', 'lumie'),
						'name' => 'refresh_token',
						'instructions' => wp_kses_post(__('Google OAuth Refresh Token. Te verkrijgen via OAuth Playground: https://developers.google.com/oauthplayground', 'lumie')),
					],
					[
						'key' => 'theme_settings_review_settings_group_api_base_url',
						'type' => 'text',
						'label' => esc_html__('API Base URL', 'lumie'),
						'name' => 'api_base_url',
						'instructions' => wp_kses_post(__('Standaard: https://mybusiness.googleapis.com/v4', 'lumie')),
						'default_value' => 'https://mybusiness.googleapis.com/v4',
						'placeholder' => 'https://mybusiness.googleapis.com/v4',
					],
					[
						'key' => 'theme_settings_review_settings_group_account_id',
						'type' => 'text',
						'label' => esc_html__('Account ID', 'lumie'),
						'name' => 'account_id',
						'instructions' => wp_kses_post(__('Standaard MB account= 100489270130940983275', 'lumie')),
					],
					[
						'key' => 'theme_settings_review_settings_group_location_id',
						'type' => 'text',
						'label' => esc_html__('Locatie ID', 'lumie'),
						'name' => 'location_id',
						'instructions' => wp_kses_post(__('Te verkrijgen op: https://business.google.com/locations. Voorbeeld: 0000000000000000000', 'lumie')),
					],
					[
						'key' => 'theme_settings_review_settings_group_reviews_link',
						'type' => 'link',
						'label' => wp_kses_post(__('Google reviews link', 'lumie')),
						'name' => 'reviews_link',
					],
				],
			],
		],
		'location' => [
			[
				[
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'review-settings',
				],
			],
		],
	]);


	// Basic details
	acf_add_options_page([
		'page_title' => esc_html__('Utilities', 'lumie'),
		'menu_title' => esc_html__('Utilities', 'lumie'),
		'menu_slug' => 'utilities',
		'post_id' => 'utilities',
		'parent_slug' => 'lumie-settings',
	]);

	acf_add_local_field_group([
		'key' => 'theme_settings_utilities',
		'title' => esc_html__('Utilities', 'lumie'),
		'fields' => [
			[
				'key' => 'theme_settings_utilities_image_settings_group',
				'label' => esc_html__('Instellingen', 'lumie'),
				'name' => 'image_settings_group',
				'type' => 'group',
				'sub_fields' => [
					[
						'key' => 'theme_settings_utilities_image_settings_group_focuspoint',
						'type' => 'true_false',
						'label' => esc_html__('Focuspoint aanzetten', 'lumie'),
						'name' => 'focuspoint',
						'ui' => true,
						'default_value' => true,
					],
					[
						'key' => 'theme_settings_utilities_image_settings_group_webp',
						'type' => 'true_false',
						'label' => esc_html__('Webp optimalisatie aanzetten', 'lumie'),
						'name' => 'webp',
						'ui' => true,
						'default_value' => true,
						'instructions' => esc_html__('Alle verkleinde bestanden worden omgezet naar webP formaat, de originele afbeelding blijft ook nog beschikbaar', 'lumie'),
					],
					[
						'key' => 'theme_settings_utilities_image_settings_group_thumbnails',
						'type' => 'true_false',
						'label' => esc_html__('Thumbnails inschakelen', 'lumie'),
						'name' => 'thumbnails',
						'ui' => true,
						'default_value' => false,
					],
				],
			],
			[
				'key' => 'theme_settings_utilities_blocks',
				'label' => esc_html__('ACF Blocks', 'lumie'),
				'name' => 'blocks',
				'type' => 'group',
				'sub_fields' => [
					[
						'key' => 'theme_settings_utilities_blocks_version',
						'label' => esc_html__('Block versie', 'lumie'),
						'name' => 'version',
						'type' => 'button_group',
						'choices' => [
							2 => esc_html__('V2', 'lumie'),
							3 => esc_html__('V3', 'lumie'),
						],
						'default_value' => 3,
					],
				],
			],
			[
				'key' => 'theme_settings_utilities_preconnect',
				'label' => __('Preconnect hints', 'lumie'),
				'name' => 'preconnect',
				'type' => 'repeater',
				'layout' => 'table',
				'button_label' => __('URL toevoegen', 'lumie'),
				'instructions' => __('Externe domeinen waarmee de browser vroegtijdig verbinding maakt, bijv. https://consentcdn.cookiebot.eu of https://tagging.domein.nl', 'lumie'),
				'sub_fields' => [
					[
						'key' => 'theme_settings_utilities_preconnect_url',
						'label' => __('URL', 'lumie'),
						'name' => 'url',
						'type' => 'url',
					],
				],
			],
		],

		'location' => [
			[
				[
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'utilities',
				],
			],
		],
	]);
});
