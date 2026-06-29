<?php 

namespace App\DTOs;
use App\Http\Requests\CompanyCreateRequest;

final readonly class CreateUserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $role = 'company-owner',
    ){}

    public static function fromRequest(CompanyCreateRequest $request) : self {
        return self::fromArray($request->validated());
    }

    public static function fromArray(array $data) : self {
        return new self (
            name : $data['name'],
            email : $data['email'],
            password : $data['password'],
        );
    }

}