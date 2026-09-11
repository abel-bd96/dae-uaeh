<?php

class clsConstanciaCiclo
{
    private $rutaCiclos;
    private $rutaPlanes;
    private $rutaConfiguraciones;
    private $rutaEspecificas;

    /**
     * Constructor de la clase
    */
    public function __construct()
    {
        $directorioDatos = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;
        $this->rutaCiclos = $directorioDatos . 'vta_siae_ciclo.json';
        $this->rutaPlanes = $directorioDatos . 'vta_siae_plan.json';
        $this->rutaConfiguraciones = $directorioDatos . 'ae_ciclo.json';
        $this->rutaEspecificas = $directorioDatos . 'ae_Ciclo_Fecha_Plan.json';
    }

    public function consultarCiclos($texto = '')
    {
        $ciclosSiae = $this->leer($this->rutaCiclos);
        $texto = $this->normalizar($texto);
        $resultado = array();

        foreach ($ciclosSiae as $ciclo) {
            if ($texto === '' || strpos($this->normalizar($ciclo['nombre']), $texto) !== false) {
                $resultado[] = $ciclo;
            }
        }

        return $resultado;
    }

    public function consultarPlanes($texto = '')
    {
        $planes = $this->leer($this->rutaPlanes);
        $texto = $this->normalizar($texto);
        $resultado = array();

        foreach ($planes as $plan) {
            if ($texto === '' || strpos($this->normalizar($plan['nombre']), $texto) !== false) {
                $resultado[] = $plan;
            }
        }

        return $resultado;
    }

    public function listarConfiguraciones()
    {
        $ciclos = $this->leer($this->rutaConfiguraciones);
        $especificas = $this->leer($this->rutaEspecificas);
        $planes = $this->indexar($this->leer($this->rutaPlanes), 'id_plan');
        $nombresCiclo = array();
        $resultado = array();

        foreach ($ciclos as $ciclo) {
            $nombresCiclo[(string) $ciclo['idCiclo']] = $ciclo['nombre'];
            $ciclo['tipo'] = 'GENERAL';
            $ciclo['planes'] = array();
            $resultado[] = $ciclo;
        }

        foreach ($especificas as $configuracion) {
            $configuracion['tipo'] = 'ESPECIFICO';
            $configuracion['nombre'] = isset($nombresCiclo[(string) $configuracion['idCiclo']]) ? $nombresCiclo[(string) $configuracion['idCiclo']] : '';
            $configuracion['planes'] = array();
            $idPlan = (string) $configuracion['idPlan'];
            if (isset($planes[$idPlan])) {
                $configuracion['planes'][] = $planes[$idPlan]['nombre'];
            }
            $resultado[] = $configuracion;
        }

        return $resultado;
    }

    public function obtenerConfiguracion($tipo, $id)
    {
        $archivo = $tipo === 'GENERAL' ? $this->rutaConfiguraciones : $this->rutaEspecificas;
        $clave = $tipo === 'GENERAL' ? 'idCiclo' : 'idCicloFechaPlan';

        $generales = $this->leer($this->rutaConfiguraciones);
        foreach ($this->leer($archivo) as $registro) {
            if ((string) $registro[$clave] === (string) $id) {
                if ($tipo !== 'GENERAL') {
                    $registro['nombre'] = '';
                    foreach ($generales as $general) {
                        if ((string) $general['idCiclo'] === (string) $registro['idCiclo']) {
                            $registro['nombre'] = $general['nombre'];
                            break;
                        }
                    }
                }
                return $registro;
            }
        }

        return null;
    }

    public function guardar($datos)
    {
        $this->validarDatos($datos);
        $nombreCiclo = $this->nombreCicloSiae($datos['nombre']);
        if ($nombreCiclo === null) {
            throw new Exception('El ciclo seleccionado no existe en SIAE.');
        }

        $generales = $this->leer($this->rutaConfiguraciones);
        $especificas = $this->leer($this->rutaEspecificas);
        $planesSeleccionados = isset($datos['planes']) && is_array($datos['planes']) ? $datos['planes'] : array();
        $esGeneral = count($this->configuracionesDelCiclo($generales, $especificas, $nombreCiclo)) === 0;

        if ($esGeneral) {
            if (count($planesSeleccionados) > 0) {
                throw new Exception('La primera configuración del ciclo debe ser GENERAL.');
            }
            $nuevo = $this->registroFechas($datos);
            $nuevo['idCiclo'] = $this->siguienteId($generales, 'idCiclo');
            $nuevo['nombre'] = $nombreCiclo;
            $generales[] = $nuevo;
            $this->escribir($this->rutaConfiguraciones, $generales);
            return array('tipo' => 'GENERAL', 'id' => $nuevo['idCiclo']);
        }

        if (count($planesSeleccionados) === 0) {
            throw new Exception('Una configuración ESPECÍFICA debe tener al menos un programa educativo.');
        }

        $this->validarPlanesSiae($planesSeleccionados);
        $this->validarDuplicados($especificas, $nombreCiclo, $datos, $planesSeleccionados);
        $idCiclo = $this->idCicloGeneral($generales, $nombreCiclo);
        $id = $this->siguienteId($especificas, 'idCicloFechaPlan');

        foreach ($planesSeleccionados as $idPlan) {
            $registro = $this->registroFechas($datos);
            $registro['idCicloFechaPlan'] = $id++;
            $registro['idPlan'] = (string) $idPlan;
            $registro['idCiclo'] = (string) $idCiclo;
            $especificas[] = $registro;
        }

        $this->escribir($this->rutaEspecificas, $especificas);
        return array('tipo' => 'ESPECIFICO', 'id' => $id - count($planesSeleccionados));
    }

    public function actualizar($datos)
    {
        $this->validarDatos($datos);
        $tipo = strtoupper($datos['tipo']);
        $id = $datos['id'];
        if ($tipo === 'ESPECIFICO') {
            return $this->actualizarEspecifica($datos);
        }
        $archivo = $tipo === 'GENERAL' ? $this->rutaConfiguraciones : $this->rutaEspecificas;
        $clave = $tipo === 'GENERAL' ? 'idCiclo' : 'idCicloFechaPlan';
        $registros = $this->leer($archivo);
        $encontrado = false;

        foreach ($registros as &$registro) {
            if ((string) $registro[$clave] === (string) $id) {
                $actual = $this->registroFechas($datos);
                foreach ($actual as $campo => $valor) {
                    $registro[$campo] = $valor;
                }
                $encontrado = true;
                break;
            }
        }
        unset($registro);

        if (!$encontrado) {
            throw new Exception('La configuración solicitada no existe.');
        }

        $this->escribir($archivo, $registros);
        return true;
    }

    private function actualizarEspecifica($datos)
    {
        $planes = isset($datos['planes']) && is_array($datos['planes']) ? array_values(array_unique($datos['planes'])) : array();
        if (count($planes) === 0) {
            throw new Exception('Una configuración ESPECÍFICA debe tener al menos un programa educativo.');
        }
        $this->validarPlanesSiae($planes);
        $registros = $this->leer($this->rutaEspecificas);
        $actual = null;
        $restantes = array();
        foreach ($registros as $registro) {
            if ((string) $registro['idCicloFechaPlan'] === (string) $datos['id']) {
                $actual = $registro;
            } else {
                $restantes[] = $registro;
            }
        }
        if ($actual === null) {
            throw new Exception('La configuración solicitada no existe.');
        }
        foreach ($restantes as $registro) {
            if ((string) $registro['idCiclo'] === (string) $actual['idCiclo'] && $this->mismasFechas($registro, $datos) && in_array((string) $registro['idPlan'], array_map('strval', $planes), true)) {
                throw new Exception('El programa educativo ya tiene una configuración específica con esas fechas.');
            }
        }
        $siguiente = $this->siguienteId($registros, 'idCicloFechaPlan');
        foreach ($planes as $indice => $idPlan) {
            $registro = $this->registroFechas($datos);
            $registro['idCicloFechaPlan'] = $indice === 0 ? $actual['idCicloFechaPlan'] : $siguiente++;
            $registro['idPlan'] = (string) $idPlan;
            $registro['idCiclo'] = (string) $actual['idCiclo'];
            $restantes[] = $registro;
        }
        $this->escribir($this->rutaEspecificas, $restantes);
        return true;
    }

    private function validarDatos($datos)
    {
        $campos = array(
            'nombre',
            'fechaPeriodoEstudiosInicio',
            'fechaPeriodoEstudiosTermino',
            'fechaPeriodoVacacionalInicio',
            'fechaPeriodoVacacionalTermino',
            'fechaSolicitudConstanciaInicio',
            'fechaSolicitudConstanciaTermino',
            'estado'
        );

        foreach ($campos as $campo) {
            if (!isset($datos[$campo]) || trim($datos[$campo]) === '') {
                throw new Exception('El campo ' . $campo . ' es obligatorio.');
            }
        }

        if (!in_array(strtoupper($datos['estado']), array('ACTIVO', 'INACTIVO'), true)) {
            throw new Exception('El estado seleccionado no es válido.');
        }

        $periodos = array(
            array('fechaPeriodoEstudiosInicio', 'fechaPeriodoEstudiosTermino'),
            array('fechaPeriodoVacacionalInicio', 'fechaPeriodoVacacionalTermino'),
            array('fechaSolicitudConstanciaInicio', 'fechaSolicitudConstanciaTermino')
        );

        foreach ($periodos as $periodo) {
            $inicio = $this->fecha($datos[$periodo[0]]);
            $termino = $this->fecha($datos[$periodo[1]]);
            if ($inicio === false || $termino === false || $inicio > $termino) {
                throw new Exception('Las fechas deben ser válidas y el inicio no puede ser posterior al término.');
            }
        }
    }

    private function validarDuplicados($especificas, $nombreCiclo, $datos, $planes)
    {
        $idCiclo = $this->idCicloGeneral($this->leer($this->rutaConfiguraciones), $nombreCiclo);
        foreach ($especificas as $registro) {
            if ((string) $registro['idCiclo'] !== (string) $idCiclo) {
                continue;
            }
            $mismasFechas = $this->mismasFechas($registro, $datos);
            if ($mismasFechas && in_array((string) $registro['idPlan'], array_map('strval', $planes), true)) {
                throw new Exception('El programa educativo ya tiene una configuración específica con esas fechas.');
            }
        }
    }

    private function configuracionesDelCiclo($generales, $especificas, $nombre)
    {
        $idCiclo = $this->idCicloGeneral($generales, $nombre);
        $resultado = array();
        foreach ($generales as $registro) {
            if ($registro['nombre'] === $nombre) {
                $resultado[] = $registro;
            }
        }
        foreach ($especificas as $registro) {
            if ($idCiclo !== null && (string) $registro['idCiclo'] === (string) $idCiclo) {
                $resultado[] = $registro;
            }
        }
        return $resultado;
    }

    private function registroFechas($datos)
    {
        return array(
            'fechaPeriodoEstudiosInicio' => $datos['fechaPeriodoEstudiosInicio'],
            'fechaPeriodoEstudiosTermino' => $datos['fechaPeriodoEstudiosTermino'],
            'fechaPeriodoVacacionalInicio' => $datos['fechaPeriodoVacacionalInicio'],
            'fechaPeriodoVacacionalTermino' => $datos['fechaPeriodoVacacionalTermino'],
            'fechaSolicitudConstanciaInicio' => $datos['fechaSolicitudConstanciaInicio'],
            'fechaSolicitudConstanciaTermino' => $datos['fechaSolicitudConstanciaTermino'],
            'estado' => strtoupper($datos['estado'])
        );
    }

    private function mismasFechas($registro, $datos)
    {
        $campos = array('fechaPeriodoEstudiosInicio', 'fechaPeriodoEstudiosTermino', 'fechaPeriodoVacacionalInicio', 'fechaPeriodoVacacionalTermino', 'fechaSolicitudConstanciaInicio', 'fechaSolicitudConstanciaTermino');
        foreach ($campos as $campo) {
            if ($registro[$campo] !== $datos[$campo]) {
                return false;
            }
        }
        return true;
    }

    private function validarPlanesSiae($planes)
    {
        $validos = $this->indexar($this->leer($this->rutaPlanes), 'id_plan');
        foreach ($planes as $idPlan) {
            if (!isset($validos[(string) $idPlan])) {
                throw new Exception('Uno de los programas educativos no existe en SIAE.');
            }
        }
    }

    private function nombreCicloSiae($nombre)
    {
        foreach ($this->leer($this->rutaCiclos) as $ciclo) {
            if ($ciclo['nombre'] === $nombre) {
                return $ciclo['nombre'];
            }
        }
        return null;
    }

    private function idCicloGeneral($generales, $nombre)
    {
        foreach ($generales as $registro) {
            if ($registro['nombre'] === $nombre) {
                return $registro['idCiclo'];
            }
        }
        return null;
    }

    private function siguienteId($registros, $campo)
    {
        $ultimo = 0;
        foreach ($registros as $registro) {
            $ultimo = max($ultimo, (int) $registro[$campo]);
        }
        return $ultimo + 1;
    }

    private function leer($ruta)
    {
        if (!file_exists($ruta)) {
            return array();
        }
        $contenido = file_get_contents($ruta);
        $datos = json_decode($contenido, true);
        return is_array($datos) ? $datos : array();
    }

    private function escribir($ruta, $datos)
    {
        $json = json_encode(array_values($datos), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false || file_put_contents($ruta, $json . PHP_EOL, LOCK_EX) === false) {
            throw new Exception('No fue posible guardar la configuración.');
        }
    }

    private function indexar($registros, $campo)
    {
        $resultado = array();
        foreach ($registros as $registro) {
            $resultado[(string) $registro[$campo]] = $registro;
        }
        return $resultado;
    }

    private function normalizar($texto)
    {
        return function_exists('mb_strtoupper') ? mb_strtoupper(trim($texto), 'UTF-8') : strtoupper(trim($texto));
    }

    private function fecha($valor)
    {
        $fecha = DateTime::createFromFormat('Y-m-d', $valor);
        return $fecha && $fecha->format('Y-m-d') === $valor ? $fecha : false;
    }
}
