<?php
    //=============================================
    // Ficheiro das permissoes do sistema
    //=============================================

    return[
        //==========================================
        // Administracao do sistema
        //==========================================
        [
            'permissao'         => 'Grupo de utilizadores',
            'funcionalidade'    => 'Visualizar e cadastrar grupo de utilizadores'
        ], 
        [
            'permissao'         => 'Gestao de utilizadores',
            'funcionalidade'    => 'Visualizar e gerir utilizadores'
        ],
        [
            'permissao'         => 'Gestao de Funcionarios',
            'funcionalidade'    => 'Visualizar e cadastrar funcionarios'
        ],
        [
            'permissao'         => 'Gestao de Especialidade',
            'funcionalidade'    => 'Visualizar e cadastrar especialidades'
        ],
        [
            'permissao'         => 'Gestao de Configuracoes',
            'funcionalidade'    => 'Gestao de configuracoes do sistema'
        ],

        //==========================================
        // Permissoes da clinica
        //==========================================
        [
            'permissao'         => 'Gestao de pacientes',
            'funcionalidade'    => 'Visualizar e cadastrar Pacientes'
        ], 
        [
            'permissao'         => 'Gestao de Diagnosticos',
            'funcionalidade'    => 'Visualizar e cadastrar Diagnosticos'
        ],
        [
            'permissao'         => 'Gestao de consultas medicas',
            'funcionalidade'    => 'Gestao de consultas medicas'
        ],
        [
            'permissao'         => 'Agendamentos',
            'funcionalidade'    => 'Gestao e realizacao de Agendamentos'
        ],

        //==========================================
        // Permissoes da Farmacia
        //==========================================
        [
            'permissao'         => 'Gestao de Medicamentos',
            'funcionalidade'    => 'Visualizar e cadastrar Medicamentos'
        ], 
        [
            'permissao'         => 'Entrada de Medicamentos',
            'funcionalidade'    => 'Gestao de entrada de medicamentos'
        ],


    ]

?>