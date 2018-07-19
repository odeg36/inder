<?php

namespace AdminBundle\ValidData;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ValidarDatos {
    
    protected $container = null;
    protected $trans = null;
    protected $em = null;
    protected $entities = [];
    protected $unico = [];
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->trans = $container->get("translator");
        $this->em = $container->get("doctrine")->getManager();
    }
    
    public function validar($datos, $validaciones) {
        $errores = [];
        
        if(count($datos) <= 0){
            array_push($errores, $this->trans->trans('error.usuario.carga_excel.vacio'));
        }
        
        foreach ($datos as $keyDato => $dato) {
            $arrayDiff = array_diff(array_keys($validaciones), array_keys($dato));
            if(count($arrayDiff) > 0){
                $diferente = "";
                foreach ($arrayDiff as $key => $diff) {
                    $diferente .= $diff . ((count($arrayDiff) > 1 && $diff != end($arrayDiff))? ', ' : '');
                }
                array_push($errores, $this->trans->trans('error.import.header.not.exist.title', ['%header%' => $diferente]));
                break;
            }
            foreach ($dato as $keyItem => $item){
                try {
                    foreach ($validaciones[$keyItem]["validaciones"] as $key => $validacion) {
                        $validacionError = $this->switcValidate($validacion, $item['valor'], $dato);
                        if($validacionError === true){
                            array_push($errores, $this->trans->trans($validacion['mensaje_error'], ["%cell%" => $item['columna'].$keyDato, "%value%" => $item['valor']]));
                        }
                    }
                } catch (\Exception $exc) {
                    array_push($errores, $this->trans->trans('error.import.header.not.exist.data', ['%header%' => $keyItem, '%column%' => $item['columna']]));
                }
            }
        }
        
        if(count($errores) > 0){
            return $errores;
        }
        
        return true;
    }
    
    public function getDatos(File $file){
        
        try {
            $objPHPExcel = $this->container->get('phpexcel')->createPHPExcelObject($file);
        } catch (\Exception $e) {
            return ["error" => $this->trans->trans("error.create.excel")];
        }

        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
        
        $highestRow = $objWorksheet->getHighestDataRow();
        $highestColumn = $objWorksheet->getHighestDataColumn();
        
        
        $headingsArray = $objWorksheet->rangeToArray('A2:' . $highestColumn . '2', null, true, true, true);
        $headingsArray = $headingsArray[2];
        $datosArray = $objWorksheet->rangeToArray('A3:' . $highestColumn . $highestRow, null, true, true, true);
        
        $cuerpo = [];
        foreach ($datosArray as $keyDato => $dato) {
            $datos = [];
            foreach ($dato as $keyItem => $item) {
                $datos[$headingsArray[$keyItem]] = ['valor' => $item, 'columna' => $keyItem];
            }
            
            $cuerpo[$keyDato] = $datos;
        }
        
        return $cuerpo;
    }
    
    public function switcValidate($validacion, $valor, $dato) {
        switch ($validacion['tipo']) {
            case 'no-null':
                return $this->esNull($valor);
                break;
            case 'buscarTexto':
                return $this->buscarTexto($validacion, $valor);
                break;
            case 'texto':
                return $this->noEsTexto($validacion, $valor);
                break;
            case 'numero':
                return $this->noEsNumero($valor);
                break;
            case 'flotante':
                return $this->noEsFlotante($valor);
                break;
            case 'fecha':
                return $this->noEsFecha($valor);
                break;
                break;
            case 'email':
                return $this->noEsEmail($valor);
                break;
            case 'entidad':
                return $this->noEsEntidad($validacion, $valor);
                break;
            case 'unico':
                return $this->noEsUnico($validacion, $valor);
                break;
            case 'personalizada': 
                return $this->getPersonalizada($validacion, $valor);
                break;
            case 'dependiente': 
                return $this->noCumpleDepencia($validacion, $dato, $valor);
                break;
            case 'dependienteCampo': 
                return $this->noCumpleDepenciaCampo($validacion, $dato, $valor);
                break;
            case 'booleano': 
                return $this->noEsBooleano($valor);
                break;
            case 'length': 
                return $this->noLength($validacion, $valor);
                break;
            case 'different': 
                return $this->noIsDiffernt($validacion, $valor);
                break;
            case 'busquedaCombinada': 
                return $this->noBusquedaCombinada($validacion, $dato);
                break;
        }
    }
    
    public function esNull($valor) {
        if(is_null($valor)){
            return true;
        }
        
        return false;
    }
    
    public function noEsTexto($tipo, $valor) {
        if(key_exists('valores', $tipo)){
            if(!in_array(mb_strtoupper($valor, 'UTF-8'), array_map('mb_strtoupper', $tipo['valores']))){
                return true;
            }
        }
        
        if(!is_string($valor) && !is_null($valor)){
            return true;
        }
        
        return false;
    }
    
    public function buscarTexto($validacion, $valor) {
        $error = true;
        if(key_exists('valores', $validacion)){
            foreach ($validacion['valores'] as $texto) {
                if (mb_strpos($valor, $texto, 0, 'UTF-8') !== false){
                    $error = false;
                }
            }
        }
        
        return $error;
    }
    
    public function noEsNumero($valor) {
        if(!is_numeric($valor) && !is_null($valor)){
            return true;
        }
        
        return false;
    }
    
    public function noEsFlotante($valor) {
        $patrón = '/^\d+\.?\d*$/';
        if(!preg_match($patrón, $valor) && !is_null($valor)){
            return true;
        }
        
        return false;
    }
    
    public function noEsFecha($valor) {
        if(!is_null($valor)){
            try {
                $fecha = $this->convertData($valor);
                if(is_bool($fecha)){
                    return true;
                }
            } catch (\Exception $e) {
                return true;
            }
        }
        
        return false;
    }
    
    public function noEsEntidad($tipo, $valor) {
        if(!is_null($valor)){
            $entity = $this->getEntidad($tipo['clase'], $valor, $tipo['campo']);
            
            if(!$entity){
                return true;
            }
        }
        
        return false;
    }
    
    public function noEsUnico($tipo, $valor) {
        if(key_exists($tipo['clase'], $this->unico)){
            if(key_exists("$valor", $this->unico[$tipo['clase']])){
                return true;
            }
        }
        
        if(!key_exists($tipo['clase'], $this->unico)){
            $this->unico[$tipo['clase']] = [];
        }

        $this->unico[$tipo['clase']]["$valor"] = $valor;
        
        $entity = false;
        if(!key_exists('no_consultar', $tipo)){
            $entity = $this->getEntidad($tipo['clase'], $valor, $tipo['campo']);
        }
        
        if($entity){
            return true;
        }
        
        return false;
    }
    
    public function noEsBooleano($valor) {
        if(gettype($valor) == "double" && gettype($valor) == "integer"){
            $valor = intval($valor);
        }else{
            $valor = strtoupper($valor);
        }
        
        if(is_null($valor)){
            return false;
        }
        
        if(in_array($valor, array_map('strtoupper', ['SI', 'NO', 1, 0]))){
            return false;
        }
        
        return true;
    }
    
    public function getPersonalizada($tipo, $valor) {
        $entity = $this->getEntidad($tipo['clase'], $valor, null, $tipo['metodo']);
        
        if(!$valor){
            return false;
        }
        
        if(!$entity){
            return true;
        }
        
        return false;
    }
    
    public function getEntidad($clase, $valor, $campo, $metodo = false) {
        if($metodo != false){
            $entity = $this->em->getRepository("LogicBundle:" . $clase)->{$metodo}(trim($valor));
        }else{
            $entity = $this->em->getRepository("LogicBundle:" . $clase)->findOneBy([$campo => trim($valor)]);
        }
        
        if($entity){
            if(!key_exists($clase, $this->entities)){
                $this->entities[$clase] = [];
            }

            $this->entities[$clase]["$valor"] = $entity;
        }
        
        return $entity;
    }
    
    public function noEsEmail($valor) {
        if (!filter_var($valor, FILTER_VALIDATE_EMAIL) && !is_null($valor)) {
            return true;
        }
        
        return false;
    }
    
    public function convertData($valor) {
        try {
            $date = str_replace('/', '-', $valor);
            $date = new \DateTime($date);
            $date->format('d-m-Y  H:i:s');
        } catch (\Exception $exc) {
            return false;
        }
        
        return $date;
    }
    
    public function getEntidades() {
        return $this->entities;
    }
    
    public function buscarEntidad($calse, $valor) {
        if(key_exists($calse, $this->entities)){
            if(key_exists("$valor", $this->entities[$calse])){
                return $this->entities[$calse][$valor];
            }
        }
        
        return null;
    }
    
    public function noCumpleDepencia($tipo, $dato, $valor) {
        if(in_array($dato[$tipo['campo']]['valor'], $tipo['valores'])){
            if(is_null($valor)){
                return true;
            }
        }
        
        return false;
    }
    public function noCumpleDepenciaCampo($tipo, $dato, $valor) {
        if(!is_null($dato[$tipo['campo']]['valor'])){
            if(is_null($valor)){
                return true;
            }
        }
        return false;
    }
    
    public function getBooleano($valor) {
        if(is_null($valor)){
            return false;
        }
        
        $valor = strtoupper($valor);
        if($valor == "SI"){
            return true;
        }else if($valor == "NO"){
            return false;
        }else{
            return $valor;
        }
    }
    
    public function noLength($tipo, $valor) {
        if(is_null($valor)){
            return false;
        }
        
        if(strlen($valor) < $tipo['range']['min'] && strlen($valor) < $tipo['range']['max']){
            return true;
        }
        
        return false;
    }
    
    function noIsDiffernt($tipo, $valor) {
        if(in_array(strtoupper($valor), array_map('strtoupper', $tipo['valores']))){
            return true;
        }
        
        return false;
    }
    
    public function noBusquedaCombinada($tipo, $dato) {
        $campo = $tipo['campo'];
        $clase = $tipo['clase'];
        $metodo = $tipo['metodo'];
        $campoCombinar = $tipo['campoCombinar'];
        $valor = '';
        
        foreach ($campoCombinar as $key => $combina) {
            $valor .= $dato[$combina]['valor'];
        }
        
        $entity = $this->em->getRepository($clase)->{$metodo}(trim($valor));
        if($entity){
            if(!key_exists($clase, $this->entities)){
                $this->entities[$clase] = [];
            }

            $this->entities[$clase]["$valor"] = $entity;
        }
        
        if(!$entity){
            return true;
        }
    }
}
