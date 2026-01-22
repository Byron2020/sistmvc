<?php
function validarCedulaEcuatoriana($cedula)
{
    // Debe tener 10 dígitos numéricos
    if (!preg_match('/^\d{10}$/', $cedula)) {
        return false;
    }

    // Provincia válida (01–24)
    $provincia = intval(substr($cedula, 0, 2));
    if ($provincia < 1 || $provincia > 24) {
        return false;
    }

    // Tercer dígito (0–5 para personas naturales)
    $tercerDigito = intval($cedula[2]);
    if ($tercerDigito > 5) {
        return false;
    }

    // Algoritmo de validación
    $coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
    $suma = 0;

    for ($i = 0; $i < 9; $i++) {
        $valor = intval($cedula[$i]) * $coeficientes[$i];
        if ($valor >= 10) {
            $valor -= 9;
        }
        $suma += $valor;
    }

    $digitoVerificador = (10 - ($suma % 10)) % 10;

    return intval($cedula[9]) === $digitoVerificador;
}
