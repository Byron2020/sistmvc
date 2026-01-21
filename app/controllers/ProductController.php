<?php
require_once 'app/models/Product.php';

class ProductController
{

    public function index()
    {
        role_required(['Administrador']);

        $product = new Product();
        $porPagina = 10;
        $pagina = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $pagina = max($pagina, 1);

        $totalRegistros = $product->countAll();
        $totalPaginas = ceil($totalRegistros / $porPagina);
        $offset = ($pagina - 1) * $porPagina;

        $products = $product->getPaginated($porPagina, $offset);
        include 'app/views/products/index.php';
    }

    public function create()
    {
        role_required(['Administrador']);

        if ($_POST) {
            $product = new Product();
            $product->create([
                $_POST['nombre'],
                $_POST['descripcion'],
                $_POST['preciovent'],
                $_POST['stock']
            ]);
            header('Location: index.php?page=productos');
            $_SESSION['swal'] = [
                'icon' => 'success',
                'title' => 'Operación exitosa',
                'text' => 'Datos creados correctamente',
                'timer' => 3500
            ];
            exit;
        }

        include 'app/views/products/create_product.php';
    }

    public function edit()
    {
        role_required(['Administrador']);

        $product = new Product();

        if ($_POST) {
            $product->update([
                $_POST['nombre'],
                $_POST['descripcion'],
                $_POST['preciovent'],
                $_POST['stock'],
                $_POST['id_producto']
            ]);
            header('Location: index.php?page=productos');
            $_SESSION['swal'] = [
                'icon' => 'success',
                'title' => 'Operación exitosa',
                'text' => 'Datos modificados correctamente',
                'timer' => 3500
            ];
            exit;
        }

        $data = $product->getById($_GET['id']);
        include 'app/views/products/create_product.php';
    }

    public function destroy()
    {
        $product = new Product();
        $product->delete($_GET['id']);

        $_SESSION['swal'] = [
            'icon' => 'success',
            'title' => 'Operación exitosa',
            'text' => 'Datos eliminados correctamente',
            'timer' => 3500
        ];

        header('Location: index.php?page=productos');
        exit;
    }
}
