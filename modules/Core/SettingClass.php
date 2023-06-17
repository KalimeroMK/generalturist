<?php

	namespace Modules\Core;

	use Modules\Core\Abstracts\BaseSettingsClass;

	class SettingClass extends BaseSettingsClass
	{
		const UPLOAD_DRIVER=['uploads','s3'];
		const BROADCAST_DRIVER=["null","log","pusher"];
		public static function getSettingPages()
		{
            $configs = [
//				[
//					'id'        => 'uploads',
//					'title'     => __("Upload Settings"),
//					'position'  => 110,
//					'view'      => "Core::admin.settings.groups.uploads",
//					"keys"      => [
//						'upload_max_width',
//						'upload_max_height',
//						'uploads_max_size',
//					],
//					'html_keys' => [
//					]
//				]
                'general'=>[
                    'id'   => 'general',
                    'title' => __("General Settings"),
                    'position'=>0,
					'view'      => "Core::admin.settings.groups.general",
					"keys"      => [
                        'site_title',
                        'site_desc',
                        'site_favicon',
                        'home_page_id',
                        'topbar_left_text',
                        'logo_id',
                        'footer_text_left',
                        'footer_text_right',
                        'list_widget_footer',
                        'date_format',
                        'site_timezone',
                        'site_locale',
                        'site_first_day_of_the_weekin_calendar',
                        'site_enable_multi_lang',
                        'enable_rtl',
                        'page_contact_title',
                        'page_contact_sub_title',
                        'page_contact_desc',
                        'page_contact_image',
                        'home_hotel_id'
					],
                    'filter_demo_mode'=>[
                        'home_page_id',
                        'admin_email',
                        'email_from_name',
                        'email_from_address',
                        'footer_text_left',
                        'footer_text_right',
                        'topbar_left_text',
                        'site_title',
                        'site_desc',
                        'logo_id',
                        'page_contact_title',
                        'page_contact_sub_title',
                        'page_contact_desc',
                    ]
                ],
                'advance'=>[
                    'id'   => 'advance',
                    'title' => __("Advanced Settings"),
                    'position'=>80,
					'view'      => "Core::admin.settings.groups.advance",
					"keys"      => [
                        'map_provider',
                        'map_gmap_key',
                        'google_client_secret',
                        'google_client_id',
                        'google_enable',
                        'facebook_client_secret',
                        'facebook_client_id',
                        'facebook_enable',
                        'twitter_enable',
                        'twitter_client_id',
                        'twitter_client_secret',
                        'recaptcha_enable',
                        'recaptcha_version',
                        'recaptcha_api_key',
                        'recaptcha_api_secret',
                        'head_scripts',
                        'body_scripts',
                        'footer_scripts',
                        'size_unit',

                        'cookie_agreement_enable',
                        'cookie_agreement_button_text',
                        'cookie_agreement_content',

                        'broadcast_driver',
                        'pusher_api_key',
                        'pusher_api_secret',
                        'pusher_app_id',
                        'pusher_cluster',

                        'search_open_tab',

                        'map_lat_default',
                        'map_lng_default',
                        'map_clustering',
                        'map_fit_bounds',
					],
                    'filter_demo_mode'=>[
                        'head_scripts',
                        'body_scripts',
                        'footer_scripts',
                        'cookie_agreement_content',
                        'cookie_agreement_button_text',
                    ]
                ],
			];
            return apply_filters(Hook::CORE_SETTING_CONFIG,$configs);
		}
	}
