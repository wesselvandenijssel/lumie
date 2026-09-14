<?php

defined('ABSPATH') || exit('Forbidden');

add_action('acf/init', function () {
	acf_add_options_page([
		'page_title' => esc_html__('MB instellingen', 'mbeffect'),
		'menu_title' => esc_html__('MB instellingen', 'mbeffect'),
		'menu_slug' => 'mb-settings',
		'icon_url' => 'data:image/svg+xml;base64,' . base64_encode('<svg height="12.023844mm" viewBox="0 0 25.385353 12.023844" width="25.385353mm" xmlns="http://www.w3.org/2000/svg"><g fill="#fff" stroke-width=".264583" transform="translate(-288.01821 -242.64453)"><path d="m299.60695 246.66613c-1.19063 0-2.27542.60854-2.8575 1.40229-.3175-.79375-1.37584-1.37583-2.48709-1.37583-.0265 0-.0265 0-.0529 0-.0794 0-.15875-.0265-.23813-.0265-1.19062 0-2.40771.635-2.98979 1.42875-.0265-.0794-.0794-.13229-.13229-.15875-.23813-.39688-.47625-.55563-.47625-.55563-1.05833-.97895-2.35479-.84666-2.35479-.84666v1.98437c.635-.10583.97896.29104 1.16416.68792.1323.29104.21167.60854.21167.89958v.58209 3.81h1.87854.39688v-3.91584c0-1.03187.66146-1.95791 1.77271-1.95791.92604 0 1.42875.66145 1.42875 1.98437v3.88938h2.43416v-3.91584c0-1.03187.66146-1.95791 1.77271-1.95791.92604 0 1.42875.66145 1.42875 1.98437v3.88938h2.27542v-4.20688c0-2.2225-.92604-3.62479-3.175-3.62479z"/><path d="m312.01591 247.61863c-.97896-.84667-2.27542-1.24354-3.54542-1.00542-.3175.0529-.55563.13229-.76729.21167v2.03729c1.16416-.47625 2.14312-.0529 2.14312-.0529.74084.3175 1.05834.82021 1.13771 1.29646.15875.635-.0794 1.32292-.52917 1.79917-.39687.42333-.87312.58208-1.29645.635-1.19063.13229-2.2225-.68792-2.38125-1.85208-.0529-.37042-.0265-.635-.0265-.635l-.0265.0265v-7.43479h-2.54l.0265 7.85813c-.0265 2.67229 2.51354 3.73062 2.51354 3.73062 1.905.97896 3.99521 0 3.99521 0 3.01625-1.34937 2.67229-3.86291 2.67229-3.86291-.0794-1.27-.71438-2.16959-1.37583-2.75167z"/></g></svg>'),
		'redirect' => true
	]);

	// Basic details
	acf_add_options_page([
		'page_title' => esc_html__('Basisgegevens', 'mbeffect'),
		'menu_title' => esc_html__('Basisgegevens', 'mbeffect'),
		'menu_slug' => 'basic-details',
		'capability' => 'edit_posts',
		'parent_slug' => 'mb-settings',
	]);

	acf_add_local_field_group([
		'key' => 'theme_settings_basic_details',
		'title' => esc_html__('Basisgegevens', 'mbeffect'),
		'fields' => [
			[
				'key' => 'theme_settings_logo',
				'name' => 'logo',
				'label' => esc_html__('Logo', 'mbeffect'),
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
				'label' => esc_html__('Contactgegevens', 'mbeffect'),
				'type' => 'group',
				'sub_fields' => [
					[
						'key' => 'theme_settings_contact_details_phone',
						'name' => 'phone',
						'type' => 'text',
						'label' => esc_html__('Telefoonnummer', 'mbeffect'),
						'wrapper' => [
							'width' => '25',
						],
					],

					[
						'key' => 'theme_settings_contact_details_phone_link',
						'name' => 'phone_link',
						'type' => 'link',
						'label' => esc_html__('Telefoonnummer (link)', 'mbeffect'),
						'wrapper' => [
							'width' => '25',
						],
					],

					[
						'key' => 'theme_settings_contact_details_email',
						'name' => 'email',
						'type' => 'email',
						'label' => esc_html__('E-mailadres', 'mbeffect'),
						'wrapper' => [
							'width' => '25',
						],
					],

					[
						'key' => 'theme_settings_contact_details_email_link',
						'name' => 'email_link',
						'type' => 'link',
						'label' => esc_html__('E-mailadres (link)', 'mbeffect'),
						'wrapper' => [
							'width' => '25',
						],
					],

					[
						'key' => 'theme_settings_contact_details_address_data',
						'name' => 'address_data',
						'label' => esc_html__('Adresgegevens', 'mbeffect'),
						'type' => 'group',
						'sub_fields' => [

							[
								'key' => 'theme_settings_contact_details_address_data_street',
								'name' => 'street',
								'type' => 'text',
								'label' => esc_html__('Straatnaam + huisnummer', 'mbeffect'),
								'wrapper' => [
									'width' => '20',
								],
							],

							[
								'key' => 'theme_settings_contact_details_address_data_city',
								'name' => 'city',
								'type' => 'text',
								'label' => esc_html__('Plaatsnaam', 'mbeffect'),
								'wrapper' => [
									'width' => '20',
								],
							],

							[
								'key' => 'theme_settings_contact_details_address_data_county',
								'name' => 'county',
								'type' => 'text',
								'label' => esc_html__('Provincie', 'mbeffect'),
								'wrapper' => [
									'width' => '20',
								],
							],

							[
								'key' => 'theme_settings_contact_details_address_data_zip',
								'name' => 'zip',
								'type' => 'text',
								'label' => esc_html__('Postcode', 'mbeffect'),
								'wrapper' => [
									'width' => '20',
								],
							],

							[
								'key' => 'theme_settings_contact_details_address_data_link',
								'name' => 'link',
								'type' => 'link',
								'label' => esc_html__('Link', 'mbeffect'),
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
				'label' => esc_html__('Awards', 'mbeffect'),
				'type' => 'repeater',
				'sub_fields' => [
					[
						'key' => 'theme_settings_awards_award',
						'name' => 'award',
						'type' => 'text',
						'label' => esc_html__('Award', 'mbeffect'),
					],
				],
				'wrapper' => [
					'width' => '50',
				],
			],
			[
				'key' => 'theme_settings_founder',
				'name' => 'founder',
				'label' => esc_html__('Oprichter', 'mbeffect'),
				'type' => 'user',
				'wrapper' => [
					'width' => '25',
				],
			],
			[
				'key' => 'theme_settings_founding_year',
				'name' => 'founding_year',
				'label' => esc_html__('Oprichtingsjaar', 'mbeffect'),
				'type' => 'number',
				'wrapper' => [
					'width' => '25',
				],
			],
			[
				'key' => 'theme_settings_social_media',
				'name' => 'social_media',
				'label' => esc_html__('Social media', 'mbeffect'),
				'type' => 'group',
				'sub_fields' => [
					[
						'key' => 'theme_settings_social_media_facebook',
						'name' => 'facebook',
						'type' => 'link',
						'label' => esc_html__('Facebook', 'mbeffect'),
						'wrapper' => [
							'width' => '25',
						],
					],

					[
						'key' => 'theme_settings_social_media_instagram',
						'name' => 'instagram',
						'type' => 'link',
						'label' => esc_html__('Instagram', 'mbeffect'),
						'wrapper' => [
							'width' => '25',
						],
					],

					[
						'key' => 'theme_settings_social_media_linkedin',
						'name' => 'linkedin',
						'type' => 'link',
						'label' => esc_html__('LinkedIn', 'mbeffect'),
						'wrapper' => [
							'width' => '25',
						],
					],

					[
						'key' => 'theme_settings_social_media_twitter',
						'name' => 'twitter',
						'type' => 'link',
						'label' => esc_html__('Twitter', 'mbeffect'),
						'wrapper' => [
							'width' => '25',
						],
					],

					[
						'key' => 'theme_settings_social_media_tiktok',
						'name' => 'tiktok',
						'type' => 'link',
						'label' => esc_html__('TikTok', 'mbeffect'),
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
		'page_title' => esc_html__('Footer', 'mbeffect'),
		'menu_title' => esc_html__('Footer', 'mbeffect'),
		'menu_slug' => 'footer',
		'post_id' => 'footer',
		'parent_slug' => 'mb-settings',
	]);

	acf_add_local_field_group([
		'key' => 'theme_settings_footer',
		'title' => esc_html__('Footer', 'mbeffect'),
		'fields' => [
			[
				'key' => 'theme_settings_footer_group',
				'label' => esc_html__('Footer', 'mbeffect'),
				'name' => 'footer',
				'type' => 'group',
				'layout' => 'block',
				'sub_fields' => [
					[
						'key' => 'theme_settings_footer_group_column',
						'type' => 'group',
						'label' => esc_html__('Footer groepen', 'mbeffect'),
						'name' => 'footer_column',
						'sub_fields' => [
							[
								'key' => 'theme_settings_footer_group_column_column_one',
								'label' => esc_html__('Kolom 1', 'mbeffect'),
								'name' => 'column_1',
								'type' => 'clone',
								'clone' => ['clone_footer_column'],
								'prefix_name' => true,
							],
							[
								'key' => 'theme_settings_footer_group_column_column_two',
								'label' => esc_html__('Kolom 2', 'mbeffect'),
								'name' => 'column_2',
								'type' => 'clone',
								'clone' => ['clone_footer_column'],
								'prefix_name' => true,
							],
							[
								'key' => 'theme_settings_footer_group_column_column_three',
								'label' => esc_html__('Kolom 3', 'mbeffect'),
								'name' => 'column_3',
								'type' => 'clone',
								'clone' => ['clone_footer_column'],
								'prefix_name' => true,
							],
							[
								'key' => 'theme_settings_footer_group_column_column_four',
								'label' => esc_html__('Kolom 4', 'mbeffect'),
								'name' => 'column_4',
								'type' => 'clone',
								'clone' => ['clone_footer_column'],
								'prefix_name' => true,
							],
						],
					],
					[
						'key' => 'theme_settings_footer_group_sub_footer',
						'label' => esc_html__('Subfooter', 'mbeffect'),
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
		'page_title' 	=> esc_html__('Header', 'mbeffect'),
		'menu_title'	=> esc_html__('Header', 'mbeffect'),
		'menu_slug' 	=> 'header',
		'capability'	=> 'edit_posts',
		'parent_slug'	=> 'mb-settings',
	]);

	acf_add_local_field_group([
		'key' => 'theme_settings_header',
		'title' => esc_html__('Header', 'mbeffect'),
		'fields' => [
			[
				'key' => 'theme_settings_header_group',
				'name' => 'header',
				'label' => esc_html__('Header', 'mbeffect'),
				'type' => 'group',
				'sub_fields' => [
					[
						'key' => 'theme_settings_header_group_usps',
						'label' => esc_html__('USP\'s', 'mbeffect'),
						'name' => 'usps',
						'type' => 'repeater',
						'button_label' => esc_html__('Nieuwe USP', 'mbeffect'),
						'sub_fields' => [
							[
								'key' => 'theme_settings_header_group_usps_usp',
								'label' => esc_html__('USP', 'mbeffect'),
								'name' => 'usp',
								'type' => 'text',
							],
							[
								'key' => 'theme_settings_header_group_usps_link',
								'label' => esc_html__('Link', 'mbeffect'),
								'name' => 'link',
								'type' => 'link',
							],
						],
					],
					[
						'key' => 'theme_settings_header_group_buttons',
						'label' => esc_html__('Button(s)', 'mbeffect'),
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
		'page_title' => esc_html__('Melding', 'mbeffect'),
		'menu_title' => esc_html__('Melding', 'mbeffect'),
		'menu_slug' => 'notification',
		'capability' => 'edit_posts',
		'parent_slug' => 'mb-settings',
	]);

	acf_add_local_field_group([
		'key' => 'theme_settings_notification',
		'title' => esc_html__('Melding', 'mbeffect'),
		'fields' => [
			[
				'key' => 'theme_settings_notification_group',
				'label' => esc_html__('Melding', 'mbeffect'),
				'name' => 'notification',
				'type' => 'group',
				'sub_fields' => [
					[
						'key' => 'theme_settings_notification_group_text',
						'label' => esc_html__('Tekst', 'mbeffect'),
						'name' => 'text',
						'type' => 'text',
						'wrapper' => [
							'width' => 50,
						],
					],
					[
						'key' => 'theme_settings_notification_group_show_from',
						'label' => esc_html__('Weergeven van', 'mbeffect'),
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
						'label' => esc_html__('Weergeven tot', 'mbeffect'),
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
		'page_title' => esc_html__('Niet gevonden pagina', 'mbeffect'),
		'menu_title' => esc_html__('Niet gevonden pagina', 'mbeffect'),
		'menu_slug' => 'not_found',
		'capability' => 'edit_posts',
		'parent_slug' => 'mb-settings',
	]);

	acf_add_local_field_group([
		'key' => 'theme_settings_not_found',
		'title' => esc_html__('Niet gevonden pagina', 'mbeffect'),
		'fields' => [
			[
				'key' => 'theme_settings_not_found_group',
				'label' => esc_html__('Niet gevonden pagina', 'mbeffect'),
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
		'page_title' => esc_html__('Scripts', 'mbeffect'),
		'menu_title' => esc_html__('Scripts', 'mbeffect'),
		'menu_slug' => 'scripts',
		'post_id' => 'scripts',
		'parent_slug' => 'mb-settings',
	]);

	acf_add_local_field_group([
		'key' => 'theme_settings_scripts',
		'title' => esc_html__('Scripts', 'mbeffect'),
		'fields' => [
			[
				'key' => 'theme_settings_scripts_head',
				'label' => esc_html__('&lt;head&gt;-scripts', 'mbeffect'),
				'instructions' => wp_kses_post(__('Dit veld wordt bovenaan de &lt;head&gt; geplaatst.', 'mbeffect')),
				'name' => 'head',
				'type' => 'textarea',
				'new_lines' => '',
				'wrapper' => [
					'width' => '50',
				],
			],
			[
				'key' => 'theme_settings_scripts_body',
				'label' => esc_html__('&lt;body&gt;-scripts', 'mbeffect'),
				'instructions' => wp_kses_post(__('Dit veld wordt bovenaan de &lt;body&gt; geplaatst.', 'mbeffect')),
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
		'page_title' => esc_html__('Reviews', 'mbeffect'),
		'menu_title' => esc_html__('Reviews', 'mbeffect'),
		'menu_slug' => 'review-settings',
		'capability' => 'manage_options',
		'parent_slug' => 'mb-settings',
	]);

	acf_add_local_field_group([
		'key' => 'theme_settings_review_settings',
		'title' => esc_html__('Review instellingen', 'mbeffect'),
		'fields' => [
			[
				'key' => 'theme_settings_review_settings_group',
				'label' => esc_html__('Instellingen', 'mbeffect'),
				'name' => 'review_settings',
				'type' => 'group',
				'sub_fields' => [
					[
						'key' => 'theme_settings_review_settings_group_enabled',
						'type' => 'true_false',
						'label' => esc_html__('Automatische reviews aanzetten', 'mbeffect'),
						'name' => 'enabled',
						'ui' => true,
						'default_value' => true,
					],
					[
						'key' => 'theme_settings_review_settings_group_cron_status',
						'type' => 'message',
						'label' => esc_html__('Cron status', 'mbeffect'),
						'message' => wp_next_scheduled('fetch_google_reviews_event')
							? esc_html__('Cron is actief. Volgende uitvoering: ', 'mbeffect') . date('d-m-Y H:i:s', wp_next_scheduled('fetch_google_reviews_event'))
							: esc_html__('Cron is niet actief', 'mbeffect'),
						'esc_html' => false,
					],
					[
						'key' => 'theme_settings_review_settings_group_cron_button',
						'type' => 'button_group',
						'label' => esc_html__('Cron beheer', 'mbeffect'),
						'name' => 'cron_button',
						'choices' => [
							'schedule' => esc_html__('Activeer cron', 'mbeffect'),
							'unschedule' => esc_html__('Deactiveer cron', 'mbeffect'),
						],
						'allow_null' => true,
						'return_format' => 'value',
					],
					[
						'key' => 'theme_settings_review_settings_group_client_id',
						'type' => 'text',
						'label' => esc_html__('Client ID', 'mbeffect'),
						'name' => 'client_id',
						'instructions' => wp_kses_post(__('Google OAuth Client ID. Te vinden in Google Cloud Console > APIs & Services > Credentials.', 'mbeffect')),
					],
					[
						'key' => 'theme_settings_review_settings_group_client_secret',
						'type' => 'text',
						'label' => esc_html__('Client Secret', 'mbeffect'),
						'name' => 'client_secret',
						'instructions' => wp_kses_post(__('Google OAuth Client Secret. Te vinden in Google Cloud Console > APIs & Services > Credentials.', 'mbeffect')),
					],
					[
						'key' => 'theme_settings_review_settings_group_refresh_token',
						'type' => 'text',
						'label' => esc_html__('Refresh Token', 'mbeffect'),
						'name' => 'refresh_token',
						'instructions' => wp_kses_post(__('Google OAuth Refresh Token. Te verkrijgen via OAuth Playground: https://developers.google.com/oauthplayground', 'mbeffect')),
					],
					[
						'key' => 'theme_settings_review_settings_group_api_base_url',
						'type' => 'text',
						'label' => esc_html__('API Base URL', 'mbeffect'),
						'name' => 'api_base_url',
						'instructions' => wp_kses_post(__('Standaard: https://mybusiness.googleapis.com/v4', 'mbeffect')),
						'default_value' => 'https://mybusiness.googleapis.com/v4',
						'placeholder' => 'https://mybusiness.googleapis.com/v4',
					],
					[
						'key' => 'theme_settings_review_settings_group_account_id',
						'type' => 'text',
						'label' => esc_html__('Account ID', 'mbeffect'),
						'name' => 'account_id',
						'instructions' => wp_kses_post(__('Standaard MB account= 100489270130940983275', 'mbeffect')),
					],
					[
						'key' => 'theme_settings_review_settings_group_location_id',
						'type' => 'text',
						'label' => esc_html__('Locatie ID', 'mbeffect'),
						'name' => 'location_id',
						'instructions' => wp_kses_post(__('Te verkrijgen op: https://business.google.com/locations. Voorbeeld: 0000000000000000000', 'mbeffect')),
					],
					[
						'key' => 'theme_settings_review_settings_group_reviews_link',
						'type' => 'link',
						'label' => wp_kses_post(__('Google reviews link', 'mbeffect')),
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
		'page_title' => esc_html__('Utilities', 'mbeffect'),
		'menu_title' => esc_html__('Utilities', 'mbeffect'),
		'menu_slug' => 'utilities',
		'post_id' => 'utilities',
		'parent_slug' => 'mb-settings',
	]);

	acf_add_local_field_group([
		'key' => 'theme_settings_utilities',
		'title' => esc_html__('Utilities', 'mbeffect'),
		'fields' => [
			[
				'key' => 'theme_settings_utilities_image_settings_group',
				'label' => esc_html__('Instellingen', 'mbeffect'),
				'name' => 'image_settings_group',
				'type' => 'group',
				'sub_fields' => [
					[
						'key' => 'theme_settings_utilities_image_settings_group_focuspoint',
						'type' => 'true_false',
						'label' => esc_html__('Focuspoint aanzetten', 'mbeffect'),
						'name' => 'focuspoint',
						'ui' => true,
						'default_value' => true,
					],
					[
						'key' => 'theme_settings_utilities_image_settings_group_webp',
						'type' => 'true_false',
						'label' => esc_html__('Webp optimalisatie aanzetten', 'mbeffect'),
						'name' => 'webp',
						'ui' => true,
						'default_value' => true,
						'instructions' => esc_html__('Alle verkleinde bestanden worden omgezet naar webP formaat, de originele afbeelding blijft ook nog beschikbaar', 'mbeffect'),
					],
					[
						'key' => 'theme_settings_utilities_image_settings_group_thumbnails',
						'type' => 'true_false',
						'label' => esc_html__('Thumbnails inschakelen', 'mbeffect'),
						'name' => 'thumbnails',
						'ui' => true,
						'default_value' => false,
					],
				],
			],
			[
				'key' => 'theme_settings_utilities_blocks',
				'label' => esc_html__('ACF Blocks', 'mbeffect'),
				'name' => 'blocks',
				'type' => 'group',
				'sub_fields' => [
					[
						'key' => 'theme_settings_utilities_blocks_version',
						'label' => esc_html__('Block versie', 'mbeffect'),
						'name' => 'version',
						'type' => 'button_group',
						'choices' => [
							2 => esc_html__('V2', 'mbeffect'),
							3 => esc_html__('V3', 'mbeffect'),
						],
						'default_value' => 3,
					],
				],
			],
			[
				'key' => 'theme_settings_utilities_preconnect',
				'label' => __('Preconnect hints', 'mbeffect'),
				'name' => 'preconnect',
				'type' => 'repeater',
				'layout' => 'table',
				'button_label' => __('URL toevoegen', 'mbeffect'),
				'instructions' => __('Externe domeinen waarmee de browser vroegtijdig verbinding maakt, bijv. https://consentcdn.cookiebot.eu of https://tagging.domein.nl', 'mbeffect'),
				'sub_fields' => [
					[
						'key' => 'theme_settings_utilities_preconnect_url',
						'label' => __('URL', 'mbeffect'),
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
