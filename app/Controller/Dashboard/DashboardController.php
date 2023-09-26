<?php
    namespace App\Controller\Dashboard;
    use App\Utils\ViewManager;
    use App\Model\Entity\Utilizador as EntityUtilizador;
    use App\Utils\Funcoes;

    class DashboardController extends DashboardPageController{
        //Verificacacao das permissoes
        
            public static function getDashboard($request){
                if(Funcoes::Permition(0)){
                    $quantidadeTotal = EntityUtilizador::getUtilizadores(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
                    
                    $content = ViewManager::render('dashboard/modules/home/painelInicial',[
                        'navbar'        => parent::getNavbar(),
                        'users'         => $quantidadeTotal,
                        'designation'   => 'Utilizadores Activos' 
                    ]);
        
                    return parent::getPainel('Centro-medico - Painel Inicial', $content);
                }elseif(Funcoes::Permition(5)){
                    $quantidadeTotal = 109999;
                    $content = ViewManager::render('dashboard/modules/home/painelInicial',[
                        'navbar'        => parent::getNavbar(),
                        'users'         => $quantidadeTotal,
                        'designation'   => 'Pacientes Activos' 
                    ]);
        
                 
                    return parent::getPainel('Centro-medico - Painel Inicial', $content);
                }elseif(Funcoes::Permition(9)){
                    $quantidadeTotal = 188888;
                    $content = ViewManager::render('dashboard/modules/home/painelInicial',[
                        'navbar'        => parent::getNavbar(),
                        'users'         => $quantidadeTotal,
                        'designation'   => 'Medicamentos Cadastrados'
                    ]);
        
                    return parent::getPainel('Centro-medico - Painel Inicial', $content);
                }else{
                    ErrorController::getError($request);
                }
            }
        
        
    }
?>