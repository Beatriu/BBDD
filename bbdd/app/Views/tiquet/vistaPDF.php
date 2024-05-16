<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TIQUET PDF</title>
</head>
<body>
    <h1>
        TIQUET: <?= $tiquet['id_tiquet'] ?>
    </h1>

    <div>
        <table>
            <tr>
                <th><?= lang('general_lang.equipment_code') ?></th>
                <th><?= lang('general_lang.type') ?></th>
                <th><?= lang('registre.data_alta') ?></th>
            </tr>
            <tr>
                <td><?= $tiquet['codi_equip'] ?></td>
                <td><?= $tipus_dispositiu ?></td>
                <td><?= $tiquet['data_alta'] ?></td>
            </tr>
        </table>
    </div>
    <br>
    <div>
        <table>
            <tr>
                <th><?= lang('registre.centre') ?></th>
                <th><?= lang('registre.centre_reparador') ?></th>
                <th><?= lang('general_lang.name') ?></th>
                <th><?= lang('general_lang.contact') ?></th>
            </tr>
            <tr>
                <td><?= $nom_centre_emissor ?></td>
                <td><?= $nom_centre_reparador ?></td>
                <td><?= $tiquet['nom_persona_contacte_centre'] ?></td>
                <td><?= $tiquet['correu_persona_contacte_centre'] ?></td>
            </tr>
        </table>
    </div>
    <br>
    <div>
        <table>
            <tr>
                <th><?= lang('general_lang.problem') ?></th>
            </tr>
            <tr>
                <td><?= $tiquet['descripcio_avaria'] ?></td>
            </tr>
        </table>
    </div>


    <?php for($j = 0; $j < sizeof($intervencions); $j++): ?>
        <br>
        <div style="border: 1px solid black; border-radius: 10px; padding-left: 10px; margin-left: 30px; margin-top: 5px;">
            <table>
                <tr>
                    <th><?= lang('intervencio.id_intervencio') ?></th>
                    <th><?= lang('intervencio.tipus_intervencio') ?></th>
                    <th><?= lang('alumne.correu_alumne') ?></th>
                    <th><?= lang('intervencio.id_xtec') ?></th>
                    <th><?= lang('intervencio.data_intervencio') ?></th>
                </tr>
                <tr>
                    <td><?= $intervencions[$j]['id_intervencio'] ?></td>
                    <td><?= $intervencions[$j]['tipus_intervencio'] ?></td>
                    <td><?= $intervencions[$j]['correu_alumne'] ?></td>
                    <td><?= $intervencions[$j]['id_xtec'] ?></td>
                    <td><?= $intervencions[$j]['data_intervencio'] ?></td>
                </tr>
            </table>
            <table>
                <tr>
                    <th><?= lang('intervencio.descripcio_intervencio') ?></th>
                </tr>
                <tr>
                    <td><?= $intervencions[$j]['descripcio_intervencio'] ?></td>
                </tr>
            </table>
        </div>

        <?php for($k = 0; $k < sizeof($inventaris[$j]); $k++): ?>
            <div style="border: 1px solid black; border-radius: 10px; padding-left: 10px; margin-left: 80px; margin-top: 5px;">
                <table>
                    <tr>
                        <th><?= lang('inventari.id_inventari') ?></th>
                        <th><?= lang('inventari.tipus_inventari') ?></th>
                        <th><?= lang('inventari.descripcio_inventari_limitada') ?></th>
                        <th><?= lang('inventari.data_compra') ?></th>
                        <th><?= lang('inventari.preu') ?></th>
                    </tr>
                    <tr>
                        <td><?= $inventaris[$j][$k]['id_inventari'] ?></td>
                        <td><?= $inventaris[$j][$k]['tipus_inventari'] ?></td>
                        <td><?= $inventaris[$j][$k]['descripcio_inventari'] ?></td>
                        <td><?= $inventaris[$j][$k]['data_compra'] ?></td>
                        <td><?= $inventaris[$j][$k]['preu'] ?> €</td>
                    </tr>
                </table>
            </div>
        <?php endfor; ?>
    <?php endfor; ?>

    <div>
        <h2>
            PREU TOTAL: <?= $preu_total ?> €
        </h2>
    </div>

</body>
</html>