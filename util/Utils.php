<?php
/*
 * Esta classe é estática
 */
final class Utils {

    private static $icon_view = 'webroot/img/busca.png';
    private static $icon_edit = 'webroot/img/edita.png';
    private static $icon_delete = 'webroot/img/delete.png';
    private static $icon_add = 'webroot/img/adiciona.png';

    public static function getIconView() {
        return self::$icon_view;
    }

    public static function getIconEdit() {
        return self::$icon_edit;
    }

    public static function getIconDelete() {
        return self::$icon_delete;
    }

    public static function getIconAdd() {
        return self::$icon_add;
    }

    private function __construct() {
        
    }

    /**
     * Generate link.
     * @param string $page target page
     * @param array $params page parameters
     * Retorna a parte final do link
     */
    public static function createLink($modulo, $acao, array $params = array()) {
        if (isset($params)) {
            $params = array_merge(array('modulo' => $modulo), array('acao' => $acao), $params);
        } else {
            $params = array_merge(array('modulo' => $modulo), array('acao' => $acao));
        }
        // TODO add support for Apache's module rewrite
        return '?' . http_build_query($params);
    }

  //  public static function redirect($modulo = null, $acao = null, array $params = array()) {
    public static function redirect($modulo , $acao , array $params = array()) {
        if (is_null($modulo) && is_null($acao)) {
            header("Location:index.php");
        } else {
            header('Location: ' . self::createLink($modulo, $acao, $params));
        }
        exit;
    }
   

    public static function validaLogin($login) {
        if (preg_match('/^[a-z0-9]{8,}$/i', $login)) {
            return true;
        } else {
            return false;
        }
    }

    public static function validaSenha($senha) {
        if (preg_match('/^[a-zA-Z0-9]{6,}$/i', $senha)) {
            return true;
        } else {
            return false;
        }
    }

    public static function deleteModal($title, $message) {
        $string = '<div id=delete-dialog title='.$title.' style="display:none;">';
        $string .= '<p><span class="ui-icon ui-icon-circle-check" style="float: left; margin: 0 7px 50px 0;"></span></p>';
        $string .= '<p>';
        $string .= $message;
        $string .= '</p>';
        $string .= '</div>';

        return $string;
    }

    /**
     * 
     * @param type $codapp
     * @param type $cpga
     * @param type $anobase
     */
    public static function isApproved($codapp, $cpga, $sub, $anobase) {
        // Verificar se para a aplicação, cpga e ano base
    	
        $arr = DAOFactory::getHomologacaoDAO()->queryByCodUnidade($cpga);
        
       
        $cont = 0;
       // echo count($arr);die;
        for ($i = 0; $i < count($arr); $i++) {
            $row = $arr[$i];
            if ($row->codUnidade == $cpga && $row->ano == $anobase && 
                $row->codAplicacao == $codapp && $row->codSub == $sub && $row->situacao != "A") {
                $cont++;
            }
        }
        return ($cont == 0) ? false : true;
    }

    public static function approvedInYear($codapp, $cpga, $sub, $anobase) {
        // Verificar se para a aplicação, cpga e ano base
        $arr = DAOFactory::getHomologacaoDAO()->queryByCodUnidade($cpga);
        $cont = 0;
        for ($i = 0; $i < count($arr); $i++) {
            $row = $arr[$i];
            if ($anobase == $row->ano && $row->codAplicacao == $codapp && $row->codSub == $sub && $row->situacao != "A")
                $cont++;
        }
        if ($cont > 0) {
            return true;
        } else {
            return false;
        }
    }

//    public static function disabledButton($codapp, $cpga, $sub, $anobase) {
//        // Verificar se para a aplicação, cpga e ano base
//        $arr = DAOFactory::getHomologacaoDAO()->queryByCodUnidade($cpga);
//        $cont = 0; // contador de resultados
//        for ($i = 0; $i < count($arr); $i++) {
//            $row = $arr[$i];
//            // se existe homologação: para aplicação, subunidade, unidade e situação
//            // naquele ano base
//            if ($row->codAplicacao == $codapp && $row->codSub == $sub && $row->situacao!="A" && $row->ano == $anobase) {
//                $cont++;
//            }
//        }
//        
//        if ($cont==0) {
//            return false;
//        }
//        else {
//            return true;
//        }
//    }
}
