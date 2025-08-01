<?php 

namespace Project\Installer\Helpers;

use Project\Installer\Helpers\Helper;

class ValidationHelper {

    public function validate(array $data) {
        
        $helper = new Helper();

        $data['client']     = $helper->client();
        $data['product_id'] = env('AD_PRODUCT_ID');

        $helper->connection($data);

        $helper->cache($data);

        $this->setStepSession();
    }

    public function setStepSession() {
        session()->put('validation',"PASSED");
    }

    public static function step() {
        return session('validation');
    }

    public function isLocalInstallation() {
        $url = request()->url();
        $url_path = parse_url($url);
        $host = $url_path['host'];
        if($host == "localhost" || $host == "127.0.0.1") return true;
        return false;
    }
}