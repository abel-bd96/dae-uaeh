<?php

class clsConstanciaListaNegra
{
    private $rutaNumeroCuenta;
    private $rutaEstatus;
    private $rutaConfiguraciones;

    // Constructor de la clase
    public function __construct()
    {
        $directorioDatos = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;
        $this->rutaNumeroCuenta     = $directorioDatos . 'vta_siae_numero_cuenta.json';
        $this->rutaEstatus          = $directorioDatos . 'vta_estatus.json';
        $this->rutaConfiguraciones  = $directorioDatos . 'constancia_lista_negra.json';
    }

    // Métodos Públicos


    public function consultarNumeroCuenta($texto = '')
    {
        $numeroCuentaSiae = $this->leer($this->rutaNumeroCuenta);
        $texto = $this->normalizar($texto);
        $resultado = array();

        foreach ($numeroCuentaSiae as $numeroCuenta) {
            if ($texto === '' || strpos($this->normalizar($numeroCuenta['NumeroCuenta']), $texto) !== false) {
                $resultado[] = $numeroCuenta;
            }
        }

        return $resultado;
    }
        public function listar()
    {
        return $this->leer($this->rutaConfiguraciones);
    }

    public function guardar($datos)
    {
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
            if (isset($registro['NumeroCuenta']) && (string)$registro['NumeroCuenta'] === (string)$numeroCuenta) {
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
            if (isset($registro['NumeroCuenta']) && (string)$registro['NumeroCuenta'] === (string)$numeroCuenta) {
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

    private function escribir($ruta, $datos)
    {
        file_put_contents($ruta, json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function normalizar($texto)
    {
        return mb_strtolower(trim($texto), 'UTF-8');
    }
}
