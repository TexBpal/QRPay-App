<?php

namespace Database\Seeders\Admin;

use App\Constants\ExtensionConst;
use App\Models\Admin\Extension;
use Illuminate\Database\Seeder;

class ExtensionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name'              => "Tawk",
                'slug'              => ExtensionConst::TAWK_TO_SLUG,
                'description'       => "Go to your tawk to dashbaord. Click [setting icon] on top bar. Then click [Chat Widget] link from sidebar and follow the screenshot bellow. Copy property ID and paste it in Property ID field. Then copy widget ID and paste it in Widget ID field. Finally click on [Update] button and you are ready to go.",
                'script'            => null,
                'shortcode'         => json_encode([ExtensionConst::TAWK_TO_PROPERTY_ID => ['title' => 'Property ID', 'value' => '6263cb787b967b11798c1faf'],ExtensionConst::TAWK_TO_WIDGET_ID => ['title' => 'Widget ID', 'value' => '1g1at5k98']]),
                'support_image'     => "instruction-tawk-to.png",
                'image'             => "logo-tawk-to.png",
                'status'            => true,
                'created_at'        => now(),
            ],
            [
                'name'              => "Google Recaptcha",
                'slug'              => ExtensionConst::GOOGLE_RECAPTCHA_SLUG,
                'description'       => "Google Recaptcha",
                'script'            => null,
                'shortcode'         =>  json_encode([
                                            "site_key" => [
                                                'title' => 'Site key',
                                                'value' => ''
                                            ],
                                            "secret_key" => [
                                                'title' => 'Secret Key',
                                                'value' => ''
                                            ]
                                        ]),

                'support_image'     => "recaptcha.png",
                'image'             => "recaptcha3.png",
                'status'            => false,
                'created_at'        => now(),
            ],
        ];
        Extension::insert($data);
    }
}
