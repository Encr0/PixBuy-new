<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar si hay productos en el carrito
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

// Incluir lógica de productos
include_once 'includes/products_logic.php';

$cart_items = array();
$subtotal = 0;
$total_items = 0;
$products = $games ?? $products;

foreach ($_SESSION['cart'] as $product_id => $quantity) {
    if (isset($products[$product_id])) {
        $product = $products[$product_id];
        $item_total = $product['price'] * $quantity;
        $subtotal += $item_total;
        $total_items += $quantity;
        $cart_items[] = array(
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'total' => $item_total
        );
    }
}

$total = $subtotal;

$invoice_number = 'INV-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
$user_info = array(
    'name' => $_SESSION['fullname'] ?? $_SESSION['username'],
    'username' => $_SESSION['username'],
    'date' => date('d/m/Y H:i:s')
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura <?php echo $invoice_number; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Orbitron', 'Roboto', Arial, sans-serif;
            background: #0a0a0a;
            color: #fff;
            max-width: 900px;
            margin: 0 auto;
            padding: 36px 12px 24px 12px;
            line-height: 1.7;
        }
        .invoice-header {
            text-align: center;
            border-bottom: 2px solid #39ff14;
            padding-bottom: 20px;
            margin-bottom: 32px;
        }
        .invoice-header h1 {
            color: #39ff14;
            margin: 0;
            font-size: 2.4rem;
            font-family: 'Orbitron', sans-serif;
            text-shadow: 0 0 16px #39ff14, 0 0 32px #39ff14;
            letter-spacing: 2px;
        }
        .invoice-header p {
            color: #b6ffb3;
            margin: 10px 0 0 0;
            font-family: 'Roboto', sans-serif;
        }
        .invoice-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
            margin-bottom: 32px;
        }
        .company-info, .customer-info {
            background: #181a1b;
            padding: 20px;
            border-radius: 10px;
            border: 1.5px solid #39ff14;
            box-shadow: 0 0 16px #39ff1422;
        }
        .company-info h3, .customer-info h3 {
            margin-top: 0;
            color: #39ff14;
            font-family: 'Orbitron', sans-serif;
            letter-spacing: 1px;
            font-size: 1.1rem;
            text-shadow: 0 0 8px #39ff14;
        }
        .invoice-details {
            background: #181a1b;
            padding: 16px 20px;
            border-radius: 10px;
            margin-bottom: 32px;
            border: 1.5px solid #39ff14;
            color: #b6ffb3;
            font-family: 'Roboto', sans-serif;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 32px;
            background: #111;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 12px #39ff1422;
        }
        .items-table th,
        .items-table td {
            padding: 14px 10px;
            text-align: left;
            border-bottom: 1px solid #222;
        }
        .items-table th {
            background: #39ff14;
            color: #0a0a0a;
            font-weight: bold;
            font-family: 'Orbitron', sans-serif;
            font-size: 1.03rem;
            letter-spacing: 1px;
        }
        .items-table tr:nth-child(even) {
            background: #181a1b;
        }
        .items-table td {
            color: #fff;
            font-size: 1rem;
        }
        .text-right {
            text-align: right;
        }
        .totals-section {
            float: right;
            width: 320px;
            background: #181a1b;
            border: 2px solid #39ff14;
            border-radius: 10px;
            box-shadow: 0 0 12px #39ff1422;
            overflow: hidden;
            margin-bottom: 32px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 18px;
            border-bottom: 1px solid #222;
            font-size: 1.05rem;
            color: #b6ffb3;
        }
        .totals-row:last-child {
            border-bottom: none;
            background: #39ff14;
            color: #0a0a0a;
            font-weight: bold;
            font-size: 1.18rem;
            letter-spacing: 1px;
            text-shadow: 0 0 8px #39ff14;
        }
        .invoice-footer {
            clear: both;
            margin-top: 48px;
            padding-top: 24px;
            border-top: 1.5px solid #39ff14;
            text-align: center;
            color: #b6ffb3;
            font-size: 1.05rem;
            font-family: 'Roboto', sans-serif;
        }
        @media (max-width: 900px) {
            .invoice-info {
                grid-template-columns: 1fr;
                gap: 18px;
            }
            .totals-section {
                width: 100%;
                float: none;
                margin-bottom: 32px;
            }
        }
        @media (max-width: 600px) {
            body {
                padding: 0.7rem;
            }
            .invoice-header h1 {
                font-size: 1.3rem;
            }
            .totals-section {
                width: 100%;
                padding: 0;
            }
            .items-table th, .items-table td {
                padding: 8px 4px;
                font-size: 0.96rem;
            }
        }
    </style>
</head>
<body>
    <!-- Botón oculto para descarga automática -->
    <button id="downloadPDF" class="print-btn" style="display:none;">
        <i class="fas fa-file-pdf"></i> Descargar en PDF
    </button>
    <div id="factura-content">
        <div class="invoice-header">
            <h1>🛒 FACTURA DE VENTA</h1>
            <p style="margin: 5px 0; color: #666;">PixBuy, tu tienda de videojuegos</p>
        </div>
        <div class="invoice-info">
            <div class="company-info">
                <h3>📍 INFORMACIÓN DE LA EMPRESA</h3>
                <p><strong>PixBuy</strong></p>
                <p>Santiago, Chile</p>
                <p>📧 Email: Pixbuy@business.cl</p>
            </div>
            <div class="customer-info">
                <h3>👤 INFORMACIÓN DEL CLIENTE</h3>
                <p><strong><?php echo htmlspecialchars($user_info['name']); ?></strong></p>
                <p>Usuario: <?php echo htmlspecialchars($user_info['username']); ?></p>
                <p>📅 Fecha de compra: <strong><?php echo $user_info['date']; ?></strong></p>
                <p>🛒 Total de items: <strong><?php echo $total_items; ?></strong></p>
                <p>💵 Valor de compra: <strong>$<?php echo number_format($subtotal, 0, ',', '.'); ?></strong></p>
            </div>
        </div>
        <div class="invoice-details">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <p><strong>📄 Número de Factura:</strong> <?php echo $invoice_number; ?></p>
                    <p><strong>📅 Fecha de Emisión:</strong> <?php echo $user_info['date']; ?></p>
                </div>
                <div>
                    <p><strong>👤 Usuario ID:</strong> <?php echo $_SESSION['user_id']; ?></p>
                    <p><strong>🛒 Total de Items:</strong> <?php echo $total_items; ?></p>
                </div>
            </div>
        </div>
        <h3 style="color: #39ff14; margin-bottom: 15px; font-family: 'Orbitron', sans-serif;">📦 DETALLE DE PRODUCTOS</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th class="text-right">Precio Unit.</th>
                    <th class="text-right">Cantidad</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                        </td>
                        <td class="text-right">$<?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                        <td class="text-right"><?php echo $item['quantity']; ?></td>
                        <td class="text-right"><strong>$<?php echo number_format($item['total'], 0, ',', '.'); ?></strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="totals-section">
            <div class="totals-row">
                <span>Subtotal:</span>
                <span>$<?php echo number_format($subtotal, 0, ',', '.'); ?></span>
            </div>
            <div class="totals-row">
                <span>TOTAL A PAGAR:</span>
                <span>$<?php echo number_format($total, 0, ',', '.'); ?></span>
            </div>
        </div>
        <div class="invoice-footer">
            <p><strong>¡Gracias por tu compra!</strong></p>
            <p>Esta factura fue generada automáticamente por nuestro sistema.</p>
            <p>Para cualquier consulta, contacta nuestro servicio al cliente.</p>
            <hr style="margin: 20px 0;">
            <p style="font-size: 0.9rem; color: #888;">
                Factura generada el <?php echo $user_info['date']; ?> | 
                Sistema PixBuy | 
                Procesado de forma segura
            </p>
        </div>
    </div>
    <!-- Librerías para PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
    window.onload = function () {
        // Espera a que todo esté renderizado
        setTimeout(function() {
            const factura = document.getElementById('factura-content');
            html2canvas(factura, { scale: 2 }).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const pdf = new window.jspdf.jsPDF('p', 'mm', 'a4');
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const imgProps = pdf.getImageProperties(imgData);
                const pdfImgHeight = (imgProps.height * pdfWidth) / imgProps.width;
                pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfImgHeight);
                pdf.save('Factura_<?php echo $invoice_number; ?>.pdf');
            });
        }, 700); // Da tiempo a cargar fuentes y estilos
    };
    </script>
</body>
</html>
<?php
// Vaciar el carrito después de generar la factura
$_SESSION['cart'] = array();
?>
