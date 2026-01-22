<?php

require_once 'app/models/Cliente.php';
require_once 'app/helpers/validaciones.php';

class ClienteController
{
    private $model;

    public function __construct()
    {
        $this->model = new Cliente();
    }

    /* =========================
       LISTAR CLIENTES (INDEX)
    ========================== */
    public function index()
    {
        // Paginación
        $porPagina = 10;
        $pagina = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $pagina = max($pagina, 1);
        $offset = ($pagina - 1) * $porPagina;

        $total = $this->model->count();
        $totalPaginas = ceil($total / $porPagina);

        $clientes = $this->model->getAll($porPagina, $offset);

        include 'app/views/clientes/index.php';
    }

    /* =========================
       FORM NUEVO CLIENTE
    ========================== */
    public function create()
    {
        include 'app/views/clientes/form.php';
    }

    /* =========================
       GUARDAR CLIENTE
    ========================== */
    public function store()
    {
        if (!validarCedulaEcuatoriana($_POST['cedu_cliente'])) {
            $_SESSION['error'] = 'La cédula ingresada no es válida';
            header('Location: index.php?page=clientes&action=create');
            exit;
        }
        $data = [
            'nomb_cliente' => $_POST['nomb_cliente'] ?? '',
            'apel_cliente' => $_POST['apel_cliente'] ?? '',
            'cedu_cliente' => $_POST['cedu_cliente'] ?? '',
            'celu_cliente' => $_POST['celu_cliente'] ?? '',
            'ciud_cliente' => $_POST['ciud_cliente'] ?? '',
            'dire_cliente' => $_POST['dire_cliente'] ?? '',
            'esta_cliente' => $_POST['esta_cliente'] ?? '1'
        ];

        $this->model->insert($data);

        header('Location: index.php?page=clientes');
        exit;
    }

    /* =========================
       FORM EDITAR CLIENTE
    ========================== */
    public function edit()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: index.php?page=clientes');
            exit;
        }

        $data = $this->model->find($id);

        if (!$data) {
            header('Location: index.php?page=clientes');
            exit;
        }

        include 'app/views/clientes/form.php';
    }

    /* =========================
       ACTUALIZAR CLIENTE
    ========================== */
    public function update()
    {
        $id = $_POST['id_cliente'] ?? null;

        if (!$id) {
            header('Location: index.php?page=clientes');
            exit;
        }

        $data = [
            'nomb_cliente' => $_POST['nomb_cliente'] ?? '',
            'apel_cliente' => $_POST['apel_cliente'] ?? '',
            'cedu_cliente' => $_POST['cedu_cliente'] ?? '',
            'celu_cliente' => $_POST['celu_cliente'] ?? '',
            'ciud_cliente' => $_POST['ciud_cliente'] ?? '',
            'dire_cliente' => $_POST['dire_cliente'] ?? ''
        ];

        $this->model->update($id, $data);

        header('Location: index.php?page=clientes');
        exit;
    }

    /* =========================
   ELIMINAR CLIENTE
========================== */
    public function destroy()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: index.php?page=clientes');
            exit;
        }

        $this->model->delete($id);

        header('Location: index.php?page=clientes');
        exit;
    }
}
