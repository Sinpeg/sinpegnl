<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

class Index {

    const DEFAULT_PAGE = 'consultautil';
    const DEFAULT_MODULO = 'utilizacao';
    const MODULO_DIR = 'modulo/';
    const LAYOUT_DIR = '';

    /**
     * System config.
     */
    public function init() {
        ob_start();
        // Diretórios
        define('MODULO_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'modulo' . DIRECTORY_SEPARATOR);
        define('AJAX_DIR', 'ajax' . DIRECTORY_SEPARATOR);
        header('Content-Type: text/html; charset=utf-8');
        error_reporting(E_ALL | E_STRICT);
        mb_internal_encoding('UTF-8');
        set_exception_handler(array($this, 'handleException'));
        spl_autoload_register(array($this, 'loadClass'));
        session_start(); // inicia a sessão
        if (!isset($_SESSION['sessao'])) {
            header("Location:../index.php");
        }
        
        include_once 'banco/include_dao.php';
    }

    /**
     * Class loader.
     */
    public function loadClass($name) {
        $classes = array(
            'Flash' => 'flash/Flash.php',
            'NotFoundException' => 'exception/NotFoundException.php',
            'PDOConnectionFactory' => 'dao/PDOConnectionFactory.php',
            'Utils' => 'util/Utils.php',
            'Sessao' => 'classes/sessao.php',
            'Arquivo' => 'modulo/uparquivo/classes/arquivo.php',
            'ArquivoDAO' => 'modulo/uparquivo/dao/arquivoDAO.php',
            'Usuario' => 'classes/usuario.php',
            'Unidade' => 'classes/unidade.php',
            'Controlador'=>'classes/Controlador.php',
            'UsuarioDAO' => 'dao/usuarioDAO.php',
            'Campus' => 'classes/campus.php',
            'CampusDAO' => 'dao/campusDAO.php',
            
            'Curso' => 'classes/curso.php',
            'CursoDAO' => 'dao/cursoDAO.php',
            'Error' => 'util/Error.php',
            'ensinoeaDAO' => 'dao/ensinoeaDAO.php',
            'Tdmensinoea' => 'classes/tdmensinoea.php',
            'Ensinoea' => 'classes/ensinoea.php',
            'EnsinoeaDAO' => 'dao/ensinoeaDAO.php',
            'UnidadeDAO' => 'dao/unidadeDAO.php',
            'psaudemensalDAO' => 'dao/psaudemensalDAO.php',
            'ServprocDAO' => 'dao/servprocDAO.php',
            'Servico' => 'classes/servico.php',
            'ServicoDAO' => 'dao/servicoDAO.php',
            'Procedimento' => 'classes/procedimento.php',
            'Servproced' => 'classes/servproced.php',
            'PsaudemensalDAO' => 'dao/psaudemensalDAO.php',
            'Psaudemensal' => 'classes/psaudemensal.php',
            'DocumentoDAO' => "modulo/documentopdi/dao/DocumentoDAO.php",
            'IndicadorDAO' => "modulo/indicadorpdi/dao/IndicadorDAO.php",
            'Documento' => "modulo/documentopdi/classe/Documento.php",
            'Indicador' => "modulo/indicadorpdi/classe/Indicador.php",
            'MapaDAO' => "modulo/mapaestrategico/dao/MapaDAO.php",
            'Mapa' => "modulo/mapaestrategico/classes/Mapa.php",
            'Calendario' => "modulo/calendarioPdi/classes/Calendario.php",
            'CalendarioDAO' => "modulo/calendarioPdi/dao/CalendarioDAO.php",
            'Meta' => "modulo/metapdi/classe/Meta.php",
            'MetaDAO' => "modulo/metapdi/dao/MetaDAO.php",
            'Resultado' => "modulo/resultadopdi/classes/Resultado.php",
            'ResultadoDAO' => "modulo/resultadopdi/dao/ResultadoDAO.php",
            'Premios' => "modulo/premios/classes/Premios.php",
            'PremiosDAO' => "modulo/premios/dao/PremiosDAO.php",
            'Tppremios' => "modulo/premios/classes/Tppremios.php",
            'TppremiosDAO' => "modulo/premios/dao/TppremiosDAO.php",
            'Lock'=>"util/Lock.php",
        	'PerspectivaDAO' => 'modulo/documentopdi/dao/PerspectivaDAO.php',
        	'Perspectiva' => 'modulo/documentopdi/classe/Perspectiva.php',
        	'ObjetivoDAO' => 'modulo/mapaestrategico/dao/ObjetivoDAO.php',
        	'Objetivo' => 'modulo/mapaestrategico/classes/Objetivo.php' ,
        	'Mapaindicador' => 'modulo/indicadorpdi/classe/Mapaindicador.php',
            'MapaIndicadorDAO' => 'modulo/indicadorpdi/dao/MapaIndicadorDAO.php',
            'IndicIniciativaDAO'=>'modulo/iniciativa/dao/IndicIniciativaDAO.php',
            'IniciativaDAO'=>'modulo/iniciativa/dao/IniciativaDAO.php',
            'Iniciativa'=>'modulo/iniciativa/classe/Iniciativa.php',
            'IndicIniciativa'=>'modulo/iniciativa/classe/IndicIniciativa.php',
            'ResultIniciativa' => "modulo/resultadopdi/classes/ResultIniciativa.php",
            'ResultIniciativaDAO' => "modulo/resultadopdi/dao/ResultIniciativaDAO.php",
            'AvaliacaofinalDAO' => 'modulo/avaliacao/dao/AvaliacaofinalDAO.php',
        	'Avaliacaofinal' => 'modulo/avaliacao/classe/Avaliacaofinal.php',
            'Modelo' => 'modulo/raa/classes/modelo.php',
            'Texto' => 'modulo/raa/classes/texto.php',
            'Topico' => 'modulo/raa/classes/topico.php',
            'ModeloDAO' => 'modulo/raa/dao/modeloDAO.php',
            'TextoDAO' => 'modulo/raa/dao/textoDAO.php',
            'TopicoDAO' => 'modulo/raa/dao/topicoDAO.php',
        
            'Solicitacao'=>"modulo/mapaestrategico/classes/Solicitacao.php",      
            
            'SolicitacaoInsercaoObjetivo'=>"modulo/mapaestrategico/classes/SolicitacaoInsercaoObjetivo.php",          
            'SolicitacaoInsercaoObjetivoDAO'=>"modulo/mapaestrategico/dao/SolicitacaoInsercaoObjetivoDAO.php", 

        	'SolicitacaoInsercaoIndicador'=>"modulo/mapaestrategico/classes/SolicitacaoInsercaoIndicador.php",
        	'SolicitacaoInsercaoIndicadorDAO'=>"modulo/mapaestrategico/dao/SolicitacaoInsercaoIndicadorDAO.php",
            
            'SolicitacaoEditIndicador' => 'modulo/indicadorpdi/classe/SolicitacaoEditIndicador.php',
            'SolicitacaoEditIndicadorDAO' => 'modulo/indicadorpdi/dao/SolicitacaoEditIndicadorDAO.php',

            'SolicitacaoVersaoIndicador' => 'modulo/indicadorpdi/classe/SolicitacaoVersaoIndicador.php',
            'SolicitacaoVersaoIndicadorDAO' => 'modulo/indicadorpdi/dao/SolicitacaoVersaoIndicadorDAO.php',

            'SolicitacaoEditObjetivo'=>"modulo/mapaestrategico/classes/SolicitacaoEditObjetivo.php",          
            'SolicitacaoEditObjetivoDAO'=>"modulo/mapaestrategico/dao/SolicitacaoEditObjetivoDAO.php", 
          
            'SolicitItensIndicadoresDeObjetivo'=>"modulo/mapaestrategico/classes/SolicitItensIndicadoresDeObjetivo.php",          
            'SolicitItensIndicadoresDeObjetivoDAO'=>"modulo/mapaestrategico/dao/SolicitItensIndicadoresDeObjetivoDAO.php",  
                
            'SolicitacaoRepactuacao'=>"modulo/metapdi/classe/SolicitacaoRepactuacao.php",          
            'SolicitacaoRepactuacaoDAO'=>"modulo/metapdi/dao/SolicitacaoRepactuacaoDAO.php" 
        );
        if (!array_key_exists($name, $classes)) {
            die('Classe "' . $name . '" não encontrada.');
        }
        require_once $classes[$name];
    }

    /**
     * Run the application!
     */
    public function run() {
        $this->runPage($this->getModulo(), $this->getAcao());
    }

    public function handleException(Exception $ex) {
        $extra = array('message' => $ex->getMessage());
        if ($ex instanceof NotFoundException) {
            header('HTTP/1.1 404 Not Found');
            $this->runPage('exception', '400');
        } else {
            header('HTTP/1.0 500 Internal Server Error');
            $this->runPage('exception', '500');
        }
    }

    public function getModulo() {
        $modulo = self::DEFAULT_MODULO;
        if (array_key_exists('modulo', $_GET)) {
            $modulo = $_GET['modulo'];
        }
        return $this->check($modulo);
    }

    public function getAcao() {
        $acao = self::DEFAULT_PAGE;
        if (array_key_exists('acao', $_GET)) {
            $acao = $_GET['acao'];
        }
        return $this->check($acao);
    }

    private function runPage($modulo, $acao, array $extra = array()) {
    	$run = false;
        $template = null;
        if ($this->hasScript($modulo, $acao)) {
            /**
             * Inicialização das variáveis
             *
             */
        	$daoun = new UnidadeDAO(); // objeto de busca das unidades
        	$sessao = $_SESSION["sessao"]; // objeto guardado na sessão
            $aplicacoes = $sessao->getAplicacoes();
            $codunidade = $sessao->getCodunidade(); // código da unidade
            $anobase = $sessao->getAnobase(); // ano base
            $nomeunidade = $sessao->getNomeunidade(); // nome da unidade
            $codestruturado = $sessao->getCodestruturado(); // código estruturado
            $cpga = $sessao->getCodUnidadeSup(); // código sigaa da unidade superior            
            $rows=$daoun->RetornaCodUnidadeSuperior($cpga); // código da unidade superior                       
            foreach ($rows as $row) {
            	$codunidadecpga = $row["CodUnidade"];
            }            
            
            if (!isset($cpga)) {
                $cpga = $codunidade; // se não houver código da unidade superior atribui CPGA
            }
            $array_codunidade = array(); // array com o código das unidades
            $array_codunidade[] = $codunidade; // recebe o código da própria unidade
            $rows = $daoun->queryByUnidadeResponsavel($sessao->getIdUnidade());
            foreach ($rows as $row) {
                $array_codunidade[] = $row["CodUnidade"];
                                
            }
            $template = $this->getScript($modulo, $acao);
            $run = true;
        }
        
        
        $flashes = null;
        $erros = null;
        if (Flash::hasFlashes()) {
            $flashes = Flash::getFlashes();
        }
        
//          if (Error::hasErros()) {
//              $erros = Error::getErros();
//          }
        
        
        require self::LAYOUT_DIR . 'index.phtml';
    }

    private function check($componente) {
        if (!preg_match('/^[a-z0-9-]+$/i', $componente)) {
            // TODO log attempt, redirect attacker, ...
            throw new NotFoundException("URL informada é inválida!");
        }
        return $componente;
    }

    private function getScript($modulo, $acao) {
        return self::MODULO_DIR . $modulo . '/' . $acao . '.php';
    }

    private function hasScript($modulo, $acao) {
        return file_exists($this->getScript($modulo, $acao));
    }

}

$index = new Index();
$index->init();
$index->run();
?>
