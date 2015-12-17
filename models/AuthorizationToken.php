<?php

class AuthorizationToken extends ApiModel {

    protected $table = 'authorization_token';
    protected $guarded = ['created_at', 'updated_at'];

    public static function createAuthorizationToken($model, $expires = false, $days=7){
        $authorizationToken = new AuthorizationToken();
        $authorizationToken->token = md5(uniqid(mt_rand(), true));
        $authorizationToken->model = get_class($model);
        $authorizationToken->model_id = $model->id;
        if ($expires){
            $expirationDate = time();
            $expirationDate = date('Y-m-d H:i:s', strtotime("+$days day", $expirationDate));
            $authorizationToken->expire_at = $expirationDate;
        }
        $authorizationToken->save();
    }

    public static function findByToken($token, $model)
    {
        return AuthorizationToken::where('token', '=', $token)->where("model", "=", $model)->first();
    }
}
