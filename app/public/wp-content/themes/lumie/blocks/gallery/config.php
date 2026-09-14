<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

return [
	'fields' => [

		'accordioncontent' => [
			'label' => esc_html__('Inhoud instellingen', 'mbeffect'),
			'type' => 'accordion',
		],

		'gallery' => [
			'label' => esc_html__('Galerij', 'mbeffect'),
			'type' => 'flexible_content',
			'button_label' => esc_html__('Nieuwe rij', 'mbeffect'),
			'layouts' => [
				[
					'key' => 'field_gallery_gallery_image',
					'label' => esc_html__('Eén afbeelding', 'mbeffect'),
					'name' => 'image',
					'sub_fields' => [
						[
							'key' => 'field_gallery_gallery_image_image',
							'label' => esc_html__('Afbeelding', 'mbeffect'),
							'name' => 'image',
							'type' => 'image',
							'mime_types' => 'png, jpg, jpeg, webp',
							'return_format' => 'id',
							'wrapper' => [
								'width' => '50',
							],
						],
						[
							'key' => 'field_gallery_gallery_image_video',
							'label' => esc_html__('Video', 'mbeffect'),
							'name' => 'video',
							'type' => 'oembed',
							'wrapper' => [
								'width' => '50',
							],
						],
					],
				],
				[
					'key' => 'field_gallery_gallery_images',
					'label' => esc_html__('Grote + twee kleine afbeeldingen', 'mbeffect'),
					'name' => 'images',
					'sub_fields' => [
						[
							'key' => 'field_gallery_gallery_images_layout',
							'label' => esc_html__('Weergave', 'mbeffect'),
							'name' => 'layout',
							'type' => 'button_group',
							'choices' => [
								'large' => esc_html__('Links groot', 'mbeffect'),
								'large-reverse' => esc_html__('Rechts groot', 'mbeffect'),
							],
							'default_value' => 'large',
							'allow_null' => false,
							'layout' => 'horizontal',
						],
						[
							'key' => 'field_gallery_gallery_images_large_image',
							'label' => esc_html__('Grote afbeelding', 'mbeffect'),
							'name' => 'large_image',
							'type' => 'group',
							'layout' => 'block',
							'sub_fields' => [
								[
									'key' => 'field_gallery_gallery_images_large_image_image',
									'label' => esc_html__('Afbeelding', 'mbeffect'),
									'name' => 'image',
									'type' => 'image',
									'mime_types' => 'png, jpg, jpeg, webp',
									'return_format' => 'id',
									'wrapper' => [
										'width' => '50',
									],
								],
								[
									'key' => 'field_gallery_gallery_images_large_image_video',
									'label' => esc_html__('Video', 'mbeffect'),
									'name' => 'video',
									'type' => 'oembed',
									'wrapper' => [
										'width' => '50',
									],
								],
							],
						],
						[
							'key' => 'field_gallery_gallery_images_images',
							'label' => esc_html__('Kleine afbeeldingen', 'mbeffect'),
							'name' => 'images',
							'type' => 'repeater',
							'layout' => 'block',
							'min' => 2,
							'max' => 2,
							'button_label' => esc_html__('Nieuwe afbeelding', 'mbeffect'),
							'sub_fields' => [
								[
									'key' => 'field_gallery_gallery_images_images_image',
									'label' => esc_html__('Afbeelding', 'mbeffect'),
									'name' => 'image',
									'type' => 'image',
									'mime_types' => 'png, jpg, jpeg, webp',
									'return_format' => 'id',
									'wrapper' => [
										'width' => '50',
									],
								],
								[
									'key' => 'field_gallery_gallery_images_images_video',
									'label' => esc_html__('Video', 'mbeffect'),
									'name' => 'video',
									'type' => 'oembed',
									'wrapper' => [
										'width' => '50',
									],
								],
							],
						],
					],
				],
			],
		],

	],
];
