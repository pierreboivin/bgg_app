<?php

namespace App\Lib;

class UserInfos
{

    /**
     * @param $arrayRawUserInfos
     * @return array
     */
    public static function getUserInformations($arrayRawUserInfos)
    {
        $arrayInfo = [
            'username' => $arrayRawUserInfos['@attributes']['name'],
            'firstname' => $arrayRawUserInfos['firstname']['@attributes']['value'],
            'lastname' => $arrayRawUserInfos['lastname']['@attributes']['value'],
            'stateorprovince' => $arrayRawUserInfos['stateorprovince']['@attributes']['value'],
            'country' => $arrayRawUserInfos['country']['@attributes']['value'],
            'yearregistered' => $arrayRawUserInfos['yearregistered']['@attributes']['value']
        ];
        return $arrayInfo;
    }

    /**
     * @param $arrayUserInfos
     * @return array
     */
    public static function formatArrayUserInfo($arrayUserInfos, $key, $subkey)
    {
        $arrayUserInfo = [];
        if (isset($arrayUserInfos[$key][$subkey])) {
            foreach ($arrayUserInfos[$key][$subkey] as $buddy) {
                $arrayUserInfo[$buddy['@attributes']['id']] = $buddy['@attributes']['name'];
            }
            return $arrayUserInfo;
        }
        return $arrayUserInfo;
    }
}