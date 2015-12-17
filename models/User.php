<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends ApiModel implements UserInterface, RemindableInterface, ApiQueryableInterface {

    use UserTrait, RemindableTrait, SoftDeletingTrait;

    const TYPE_ADMIN = 'admin';
    const TYPE_PUBLISHER_ADMIN = 'publisher_admin';
    const TYPE_PUBLISHER = 'publisher';
    const TYPE_PAYEE = 'payee';

    protected $table = 'user';
    protected $hidden = ['password', 'remember_token'];
    protected $guarded = ['id', 'remember_token', 'created_at', 'updated_at'];

    public static function boot()
    {
        parent::boot();
        User::created(function ($user) {
            if ($user->type == User::TYPE_PAYEE)
                AuthorizationToken::createAuthorizationToken($user);
        });
    }

    protected function accessors() {
        return [
            "company_name" => $this->company()->first()->name,
            "company_authorization_token" => $this->company()->first()->authorizationToken()->first()->token,
            "authorization_token" => $this->getAuthorizationToken(),
            "service_ids" => array_flatten($this->companyServicesIds()),
        ];
    }

    public function getAuthorizationToken()
    {
        $authorization = $this->authorizationToken()->first();
        if ($authorization != null)
            return $authorization->token;
        return null;
    }

    public function authorizationToken()
    {
        return $this->hasOne('AuthorizationToken','model_id','id')->where('model', '=', "User");
    }

//    public function paymentDealId()
//    {
//        $deal = $this->paymentDeal()->first();
//        if ($deal != null)
//            return $deal->id;
//        return 0;
//    }

//    public function paymentDeal() {
//        return $this->hasOne('Deal','payee_code','code')->where("payment_analysis", "=", 1);
//    }

    public function companyServicesIds()
    {
        if (Auth::user()["type"] == self::TYPE_PAYEE)
            return [];
        else
            return CompanyService::select("service_id")->where('company_id', '=', Auth::user()["company_id"])->get()->toArray();
    }

    public static function apiTypes()
    {
        if (Auth::user()["type"] == self::TYPE_ADMIN)
            return [self::TYPE_ADMIN, self::TYPE_PUBLISHER_ADMIN, self::TYPE_PUBLISHER/*, self::TYPE_PAYEE*/];
        if (Auth::user()["type"] == self::TYPE_PUBLISHER_ADMIN)
            return [self::TYPE_PUBLISHER_ADMIN, self::TYPE_PUBLISHER/*, self::TYPE_PAYEE*/];
        if (Auth::user()["type"] == self::TYPE_PUBLISHER)
            return [self::TYPE_PUBLISHER];
        if (Auth::user()["type"] == self::TYPE_PAYEE)
            return [self::TYPE_PAYEE];
    }

    public static function upload()
    {
        $uploadPath = storage_path('temp')."/".basename($_FILES['file']['name']);
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath))
            return null;

        $csvUsers = CsvFileService::csvToArray($uploadPath);
        foreach ($csvUsers as $csvUser)
            self::updateEmailAndPassword($csvUser[0], $csvUser[3], $csvUser[4]);
        unlink($uploadPath);
        return "success";
    }

    public static function isUserValid($fields, $rules)
    {
        $validator = Validator::make($fields, $rules);
        return $validator->fails();
    }

    public static function userAlreadyExists($email)
    {
        return User::where("email", "=", $email)->first() != null;
    }

    public static function updateEmailAndPassword($id, $email, $password)
    {
        if (empty($password)) {
            return self::updateCredentialsIfValid($id,
                $email,
                ['email' => $email],
                ['email' => 'required|email'],
                ["email" => $email]);
        }else{
            return self::updateCredentialsIfValid($id,
                $email,
                ['email' => $email],
                ['email' => 'required|email'],
                ["email" => $email, "password" => self::hash($password)]);
        }
    }

    public static function updateEmail($id, $email)
    {
        return self::updateCredentialsIfValid($id, $email, ['email' => $email], ['email' => 'required|email'], ["email" => $email]);
    }

    public static function updateCredentialsIfValid($id, $email, $validate, $rules, $update)
    {
        if (self::isUserValid($validate, $rules) ||
            self::userAlreadyExists($email))
            return false;

        $updated = User::where("id", "=", $id)->whereNull("email")->update($update);
        return $updated == 1 ? true : false;
    }

    public static function apiQuery() {
        $query = self::query();
        if (!Auth::user()->isAdmin()) {
            $query->where(User::table().'.company_id', '=', Auth::user()["company_id"]);
        }
        return $query;
    }

    public static function apiBatchUpdateEmails()
    {
        $usersInput = Input::all();
        foreach ($usersInput as $userInput) {
            self::updateEmail($userInput["id"], $userInput["email"]);
        }
        return true;
    }

    public static function apiNoEmailUsers() {
        $results = PayeeCompany::payeesWithoutEmail()->select(["user.id", "user.name", "user.code", "user.email"])->get()->toArray();
        return ["content" => CsvFileService::toCsv($results, "id,name,code,email,password"),
                "content_type" => "text/csv",
                "filename" => "noEmailUsers.csv"];
    }

    public static function apiUserByPayeeCode($payeeCode)
    {
        return User::where("code", "=", $payeeCode)->first();
    }

    public static function publisherAdmins($companyId) {
        return User::where("company_id", "=", $companyId)
            ->where("type", "=", self::TYPE_PUBLISHER_ADMIN)
            ->whereNotNull("email")
            ->get();
    }

    public function company() {
        return $this->hasOne('Company','id','company_id');
    }

    public static function payees()
    {
        $payees = User::where('type', '=', User::TYPE_PAYEE);
        if (!Auth::user()->isAdmin())
            $payees->where('company_id', '=', Auth::user()["company_id"]);
        return $payees;
    }

//    public static function payeesWithoutEmail()
//    {
//        return User::payees()->whereNull("email");
//    }
//
//    public static function payeesWithEmail()
//    {
//        return User::payees()->whereNotNull("email");
//    }

    public static function apiPayeesCounts()
    {
        return [
            "new_payees" => PayeeCompany::payeesWithoutEmail()->count(),
            "all_payees" => PayeeCompany::payeesWithEmail()->count(),
            "unpaid_statements" => PayeePayment::unpaid()->count(),
            "paid_statements" => PayeePayment::paid()->count()
        ];
    }

    public static function apiPaymentsTotals()
    {
        return [
            "paid_payments_amount" => RoyaltyPayment::sumAmountReceivedPaidPerPayee(Auth::user()->code),
            "unpaid_payments_amount" => RoyaltyPayment::sumAmountReceivedUnpaidPerPayee(Auth::user()->code),
            "current_advance" => Auth::user()->currentAdvance(),
        ];
    }

    public function advances()
    {
        return $this->hasMany('Advance','payee_code','code')->where("status", "=", "incomplete")->orderBy('start_date');
    }

    public function currentAdvance()
    {
        return $this->advances()->first();
    }

    public function setPasswordAttribute($value) {
        $this->attributes['password'] = self::hash($value);
    }

    public static function hash($value)
    {
        return Hash::make($value);
    }

    public function isAdmin() {
        return $this->type === self::TYPE_ADMIN;
    }

    public function isPayee() {
        return $this->type === self::TYPE_PAYEE;
    }

    public function isPublisherAdmin() {
        return $this->type === self::TYPE_PUBLISHER_ADMIN;
    }

    public function isPublisher() {
        return $this->type === self::TYPE_PUBLISHER;
    }
}