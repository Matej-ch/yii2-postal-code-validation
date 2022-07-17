<?php

class BuildController extends \yii\console\Controller
{
    public function actionInit()
    {
        //@TODO
        //
        // 1. load https://chromium-i18n.appspot.com/ssl-address/data

        // 2. load patterns for countries https://chromium-i18n.appspot.com/ssl-address/data/{country code}

        // 3. save patterns and examples

        return \yii\console\ExitCode::OK;
    }
}