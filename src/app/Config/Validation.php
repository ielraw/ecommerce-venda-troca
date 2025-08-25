<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    public array $userCreate = [
        'name' => 'required|min_length[2]|max_length[255]',
        'email' => 'required|valid_email|max_length[255]|is_unique[users.email]',
        'login' => 'required|min_length[3]|max_length[100]|is_unique[users.login]',
        'password' => 'required|min_length[6]|max_length[255]',
        'location.lat' => 'required|decimal',
        'location.lng' => 'required|decimal',
        'location.address' => 'required|min_length[5]|max_length[500]',
        'location.city' => 'required|min_length[2]|max_length[100]',
        'location.state' => 'required|min_length[2]|max_length[100]',
        'location.zip_code' => 'required|integer|greater_than[0]'
    ];

    public array $userCreate_errors = [
        'name' => [
            'required' => 'O nome é obrigatório',
            'min_length' => 'O nome deve ter pelo menos 2 caracteres',
            'max_length' => 'O nome não pode exceder 255 caracteres'
        ],
        'email' => [
            'required' => 'O email é obrigatório',
            'valid_email' => 'O email deve ser válido',
            'max_length' => 'O email não pode exceder 255 caracteres',
            'is_unique' => 'Este email já está em uso'
        ],
        'login' => [
            'required' => 'O login é obrigatório',
            'min_length' => 'O login deve ter pelo menos 3 caracteres',
            'max_length' => 'O login não pode exceder 100 caracteres',
            'is_unique' => 'Este login já está em uso'
        ],
        'password' => [
            'required' => 'A senha é obrigatória',
            'min_length' => 'A senha deve ter pelo menos 6 caracteres',
            'max_length' => 'A senha não pode exceder 255 caracteres'
        ],
        'location.lat' => [
            'required' => 'A latitude é obrigatória',
            'decimal' => 'A latitude deve ser um número decimal'
        ],
        'location.lng' => [
            'required' => 'A longitude é obrigatória',
            'decimal' => 'A longitude deve ser um número decimal'
        ],
        'location.address' => [
            'required' => 'O endereço é obrigatório',
            'min_length' => 'O endereço deve ter pelo menos 5 caracteres',
            'max_length' => 'O endereço não pode exceder 500 caracteres'
        ],
        'location.city' => [
            'required' => 'A cidade é obrigatória',
            'min_length' => 'A cidade deve ter pelo menos 2 caracteres',
            'max_length' => 'A cidade não pode exceder 100 caracteres'
        ],
        'location.state' => [
            'required' => 'O estado é obrigatório',
            'min_length' => 'O estado deve ter pelo menos 2 caracteres',
            'max_length' => 'O estado não pode exceder 100 caracteres'
        ],
        'location.zip_code' => [
            'required' => 'O CEP é obrigatório',
            'integer' => 'O CEP deve ser um número inteiro',
            'greater_than' => 'O CEP deve ser maior que zero'
        ]
    ];
}
