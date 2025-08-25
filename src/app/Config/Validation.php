<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    public $userCreate = [
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

    public $userCreate_errors = [
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

    public $dealCreate = [
        'type' => 'required|in_list[1,2,3]',
        'value' => 'required|decimal',
        'description' => 'required|min_length[3]|max_length[1000]',
        'trade_for' => 'permit_empty|max_length[500]',
        'location.lat' => 'required|decimal',
        'location.lng' => 'required|decimal',
        'location.address' => 'required|min_length[5]|max_length[500]',
        'location.city' => 'required|min_length[2]|max_length[100]',
        'location.state' => 'required|min_length[2]|max_length[100]',
        'location.zip_code' => 'required|integer|greater_than[0]',
        'urgency.type' => 'required|in_list[1,2,3,4]',
        'urgency.limit_date' => 'permit_empty|valid_date',
        'photos' => 'permit_empty',
        'photos.*.src' => 'required_with[photos]|min_length[1]|max_length[1000]'
    ];

    public $dealCreate_errors = [
        'type' => [
            'required' => 'O tipo é obrigatório',
            'in_list' => 'Tipo inválido'
        ],
        'value' => [
            'required' => 'O valor é obrigatório',
            'decimal' => 'O valor deve ser decimal'
        ],
        'description' => [
            'required' => 'A descrição é obrigatória',
            'min_length' => 'A descrição deve ter pelo menos 3 caracteres',
            'max_length' => 'A descrição não pode exceder 1000 caracteres'
        ],
        'trade_for' => [
            'max_length' => 'O campo trade_for não pode exceder 500 caracteres'
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
        ],
        'urgency.type' => [
            'required' => 'O tipo de urgência é obrigatório',
            'in_list' => 'Tipo de urgência inválido'
        ],
        'urgency.limit_date' => [
            'valid_date' => 'A data limite deve ser válida'
        ],
        'photos.*.src' => [
            'required_with' => 'A foto deve conter o campo src',
            'min_length' => 'O src da foto é inválido',
            'max_length' => 'O src da foto é muito longo'
        ]
    ];

    public $dealSearch = [
        'type' => 'permit_empty|integer|in_list[1,2,3]',
        'value_start' => 'permit_empty|decimal|greater_than_equal_to[0]',
        'value_end' => 'permit_empty|decimal|greater_than_equal_to[0]',
        'term' => 'permit_empty|max_length[255]',
        'lat' => 'permit_empty|decimal',
        'lng' => 'permit_empty|decimal'
    ];

    public $dealSearch_errors = [
        'type' => [
            'integer' => 'O tipo deve ser um número inteiro',
            'in_list' => 'O tipo deve ser 1 (Venda), 2 (Troca) ou 3 (Desejo)'
        ],
        'value_start' => [
            'decimal' => 'O valor inicial deve ser um número decimal',
            'greater_than_equal_to' => 'O valor inicial não pode ser negativo'
        ],
        'value_end' => [
            'decimal' => 'O valor final deve ser um número decimal',
            'greater_than_equal_to' => 'O valor final não pode ser negativo'
        ],
        'term' => [
            'max_length' => 'O termo de busca não pode exceder 255 caracteres'
        ],
        'lat' => [
            'decimal' => 'A latitude deve ser um número decimal'
        ],
                           'lng' => [
                       'decimal' => 'A longitude deve ser um número decimal'
                   ]
               ];

               public $bidCreate = [
                   'user_id' => 'required|integer|greater_than[0]',
                   'accepted' => 'permit_empty|in_list[0,1,true,false]',
                   'value' => 'required|decimal|greater_than[0]',
                   'description' => 'permit_empty|max_length[1000]'
               ];

               public $bidCreate_errors = [
                   'user_id' => [
                       'required' => 'O ID do usuário é obrigatório',
                       'integer' => 'O ID do usuário deve ser um número inteiro',
                       'greater_than' => 'O ID do usuário deve ser maior que zero'
                   ],
                   'accepted' => [
                       'in_list' => 'O status de aceitação deve ser true ou false'
                   ],
                   'value' => [
                       'required' => 'O valor do lance é obrigatório',
                       'decimal' => 'O valor do lance deve ser um número decimal',
                       'greater_than' => 'O valor do lance deve ser maior que zero'
                   ],
                   'description' => [
                       'max_length' => 'A descrição do lance não pode exceder 1000 caracteres'
                   ]
               ];
           }
