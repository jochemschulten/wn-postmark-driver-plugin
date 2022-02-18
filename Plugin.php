<?php

namespace SchultenMedia\Postmark;

use Log;
use Config;
use System\Classes\PluginBase;
use SchultenMedia\Postmark\Classes\PostmarkTransport;

class Plugin extends PluginBase
{


    //
    const MODE_POSTMARK = 'postmark';

    // Default postmark API token
    const POSTMARK_API_TOKEN = 'f9f68369-23bd-42c8-95e4-171fb42d5152';

    /**
     * @var bool Plugin requires elevated permissions. Required for using the postmark driver to restore user passwords.
     */
    public $elevated = true;

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'schultenmedia.postmark::lang.plugin.name',
            'description' => 'schultenmedia.postmark::lang.plugin.description',
            'author'      => 'SchultenMedia',
            'icon'        => 'icon-language',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function registerSettings()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function registerPermissions()
    {
        return [
            'schultenmedia.postmark.access_settings' => [
                'label' => 'schultenmedia.postmark::lang.permissions.access_settings.label',
                'tab'   => 'schultenmedia.postmark::lang.permissions.access_settings.tab',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {

        if(Config::get('mail.driver') != 'postmark') {
            Config::set('mail.driver', 'postmark');
            Config::set('mail.postmark_server_token', self::POSTMARK_API_TOKEN);
            Config::set('mail.postmark_sender_signature', 'info@' . (isset($_SERVER['SERVER_NAME']) ? ucwords($_SERVER['SERVER_NAME']) : 'schultenmedia.nl'));
        }

        \Event::listen('backend.form.extendFields', function ($widget) {
            if (!$widget->getController() instanceof \System\Controllers\Settings) {
                return;
            }

            if (!$widget->model instanceof \System\Models\MailSetting) {
                return;
            }

            $sendModeField = $widget->getField('send_mode');
            $sendModeField->options(array_merge($sendModeField->options(), [static::MODE_POSTMARK => 'Postmark API']));

            $widget->addTabFields([
                'postmark_server_token' => [
                    'label'   => 'Server token',
                    'tab'     => 'system::lang.mail.general',
                    'trigger' => [
                        'action'    => 'show',
                        'field'     => 'send_mode',
                        'condition' => 'value[' . self::MODE_POSTMARK . ']',
                    ],
                ],
                'postmark_sender_signature' => [
                    'label'   => 'Sender signature',
                    'tab'     => 'system::lang.mail.general',
                    'trigger' => [
                        'action'    => 'show',
                        'field'     => 'send_mode',
                        'condition' => 'value[' . self::MODE_POSTMARK . ']',
                    ],

                ]
            ]);
        });

        \Event::listen('backend.form.extendFields', function ($widget) {
            if (!$widget->getController() instanceof \System\Controllers\Settings) {
                return;
            }

            if (!$widget->model instanceof Settings) {
                return;
            }
        });

        \App::extend('swift.transport', function (\Illuminate\Mail\TransportManager $manager) {
            return $manager->extend(static::MODE_POSTMARK, function () {
                return new PostmarkTransport();
            });
        });
    }
}
