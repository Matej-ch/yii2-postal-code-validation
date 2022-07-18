<?php

class BuildController extends \yii\console\Controller
{
    private $baseUrl = 'https://chromium-i18n.appspot.com/ssl-address/data';

    /**
     * Initialize files examples and patterns in directory resources
     * @return int
     */
    public function actionInit(): int
    {
        //@TODO
        //
        // 1. load $this->baseUrl
        $countries = $this->fileGetContentsCurl($this->baseUrl);
        // 2. load patterns for countries $this->baseUrl/{country code}

        // 3. save patterns and examples

        return \yii\console\ExitCode::OK;
    }

    private function fileGetContentsCurl($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
}