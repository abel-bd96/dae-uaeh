<?php

class clsConstanciaListaNegra
{
    private  $rutaNumeroCuenta;
    private  $rutaEstatus;
    private  $rutaConfiguraciones;

    // Constructor de la clase
    public function __construct()
    {
        $directorioDatos = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;
        $this->rutaNumeroCuenta     = $directorioDatos . 'vta_siae_numero_cuenta.json';
        $this->rutaEstatus          = $directorioDatos . 'vta_estatus.json';
        $this->rutaConfiguraciones  = $directorioDatos . 'constancia_lista_negra.json';
    }

    // Métodos Públicos


    public function consultarNumeroCuenta($texto = '')    {
		$registrosLocales = $this->leer($this->rutaConfiguraciones);
        $numeroCuentaSiae = $this->leer($this->rutaNumeroCuenta);
        $texto = $this->normalizar($texto);
        $resultado = array();
		foreach ($registrosLocales as $numeroCuenta) {
            $cuenta = (is_array($numeroCuenta) && isset($numeroCuenta['NumeroCuenta']))? $numeroCuenta['NumeroCuenta'] : '';
            if ($texto === '' || strpos($this->normalizar($cuenta), $texto) !== false) {
                $resultado[] = $numeroCuenta;
            }
        }
        foreach ($numeroCuentaSiae as $numeroCuenta) {
            $cuenta = (is_array($numeroCuenta) && isset($numeroCuenta['NumeroCuenta']))? $numeroCuenta['NumeroCuenta'] : '';
            if ($texto === '' || strpos($this->normalizar($cuenta), $texto) !== false) {
                $resultado[] = $numeroCuenta;
            }
        }

        return $resultado;
    }
    public function listar() {
            return $this->leer($this->rutaConfiguraciones);
        }

    public function validarCuenta($numeroCuenta){
        $numeroCuenta = trim($numeroCuenta);
        if ($numeroCuenta === ''){
            throw new Exception('El numero de cuenta es obligatorio');
        }
        $registrosLocales = $this->leer($this->rutaConfiguraciones);
        foreach ($registrosLocales as $registro){
            if(isset($registro['NumeroCuenta']) && $this->normalizar(
                $registro['NumeroCuenta']) === $this->normalizar($numeroCuenta)){
                
                return ['enLocal' => true, 'enSIAE'=> true, 'registro' => $registro];
                }
        }
        $registroSIAE = $this->leer($this->rutaNumeroCuenta);
        foreach ($registroSIAE as $registroSIAE){
            $cuentaSIAE = isset($registroSIAE['NumeroCuenta']) ? $registroSIAE['NumeroCuenta'] : '';
            if(
                $this->normalizar($cuentaSIAE) === $this->normalizar($numeroCuenta)){
                return['enLocal' => false, 'enSIAE'=> true, 'registro' => null];

                }}
            
        return [
        'enLocal' => false,
        'enSIAE' => false,
        'registro' => null
        ];

    }

            public function consultarEstatus($texto) {
                $estatusLista = $this->leer($this->rutaEstatus);
                $textoNormalizado = $this->normalizar($texto);
                $resultado = [];

                foreach ($estatusLista as $datoEstatus) {
                    $nombre = is_array($datoEstatus) ? ($datoEstatus['nombre'] ?? '') : $datoEstatus;
                    if (strpos($this->normalizar($nombre), $textoNormalizado) !== false) {
                        $resultado[] = $datoEstatus;
                    }
                }

                return $resultado;
            }

            public function guardar($datos) {
                $numeroCuenta = isset($datos['NumeroCuenta']) ? trim($datos['NumeroCuenta']) : '';
                $estatus = isset($datos['Estatus']) ? strtoupper(trim($datos['Estatus'])) : '';

                // Validar número de cuenta
                if (empty($numeroCuenta)) {
                    throw new Exception('El número de cuenta es obligatorio.');
                }

                // Validar que el estatus sea S o N
                if ($estatus !== 'S' && $estatus !== 'N') {
                    throw new Exception('El estatus solamente puede ser S o N.');
                }

                $registros = $this->leer($this->rutaConfiguraciones);

                // Verificar que el número de cuenta no exista
                foreach ($registros as $registro) {
                    if ( isset($registro['NumeroCuenta']) && 
                        $this->normalizar($registro['NumeroCuenta']) === $this->normalizar($numeroCuenta) ) {
                        throw new Exception('El número de cuenta ya se encuentra registrado.');
                    }
                }

                $nuevo = [
                    'NumeroCuenta' => $numeroCuenta,
                    'Estatus'      => $estatus
                ];

                $registros[] = $nuevo;
                $this->escribir($this->rutaConfiguraciones, $registros);

                return $nuevo;
            }

            public function actualizar($datos) 
            {
                $numeroCuenta = isset($datos['NumeroCuenta']) ? trim($datos['NumeroCuenta']) : '';
                $estatus = isset($datos['Estatus']) ? strtoupper(trim($datos['Estatus'])) : '';

                if (empty($numeroCuenta)) {
                    throw new Exception('El número de cuenta es obligatorio.');
                }

                // Validar estatus
                if ($estatus !== 'S' && $estatus !== 'N') {
                    throw new Exception('El estatus solamente puede ser S o N.');
                }

                $registros = $this->leer($this->rutaConfiguraciones);
                
                $encontrado = false;

                foreach ($registros as &$registro) {
                    if (isset($registro['NumeroCuenta']) &&
                    $this->normalizar($registro['NumeroCuenta']) === $this->normalizar($numeroCuenta)) {
                        $registro['Estatus'] = $estatus;
                        $encontrado = true;
                        break;
                    }
                }
                unset($registro);

                if (!$encontrado) {
                    throw new Exception('El número de cuenta no se encuentra registrado.');
                }

                $this->escribir($this->rutaConfiguraciones, $registros);

                return true;
            }

            // Métodos Privados (Utilidades)

            private function leer($ruta) 
            {
                if (!file_exists($ruta)) {
                    return [];
                }
                $contenido = file_get_contents($ruta);
                $datos = json_decode($contenido, true);

                return is_array($datos) ? $datos : [];
            }

            private function escribir($ruta, $datos) {
                file_put_contents($ruta, json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }

            private function normalizar($texto) {
                return mb_strtolower(trim($texto), 'UTF-8');
            }
        }
