<?php 

namespace App\DTOs;
use App\Http\Requests\CompanyCreateRequest;

final readonly class CreateCompanyDTO
{
    public function __construct(
        public string $name,
        public string $address,
        public string $industry,
        public string $website,
    ) {}

    public static function fromRequest(CompanyCreateRequest $request) : self {
        return self::fromArray($request->validated());
    }

    public static function fromArray(array $data) : self {
        return new self(
            name: $data['name'],
            address: $data['address'],
            industry: $data['industry'],
            website: $data['website'],
        );
    }

}