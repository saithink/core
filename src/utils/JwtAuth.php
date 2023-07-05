<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * JWT认证类
 * Class JwtAuth
 * @package saithink\core\utils
 */
class JwtAuth
{
    /**
     * token
     * @var string
     */
    protected $token;

    public $key = 'saithink';

    /**
     * 获取token
     * @param int $id
     * @param string $type
     * @param array $params
     * @return array
     */
    public function getToken(int $id, string $type, array $params = []): array
    {
        $host = request()->host();
        $time = time();
        $params += [
            'iss' => $host,
            'aud' => $host,
            'iat' => $time,
            'nbf' => $time,
            'exp' => strtotime('+ 3hour'),
        ];
        $params['jti'] = compact('id', 'type');
        $token = JWT::encode($params, $this->key, 'HS256');
        return compact('token', 'params');
    }

    /**
     * 解析token
     * @param string $jwt
     * @return array
     */
    public function parseToken(string $jwt): array
    {
        $this->token = $jwt;
        $decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));
        $json = json_decode(json_encode($decoded), true);
        return [$json['jti']['id'], $json['jti']['type']];
    }

    /**
     * 创建token
     * @param int $id
     * @param string $type
     * @param array $params
     * @return array
     */
    public function createToken(int $id, string $type, array $params = [])
    {
        $tokenInfo = $this->getToken($id, $type, $params);
        $exp = $tokenInfo['params']['exp'] - $tokenInfo['params']['iat'] + 60;
        return $tokenInfo;
    }
}
